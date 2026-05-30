<?php

declare(strict_types=1);

if (!function_exists('cmsCoreConfig')) {
    /** @return array<string, mixed> */
    function cmsCoreConfig(): array
    {
        static $config = null;
        if ($config === null) {
            $config = [];
        }
        return $config;
    }

    /** @param array<string, mixed> $config */
    function cmsCoreConfigure(array $config): void
    {
        $GLOBALS['cms_core_config'] = array_merge(cmsCoreConfig(), $config);
    }

    function cmsCoreGet(string $key, mixed $default = null): mixed
    {
        $config = $GLOBALS['cms_core_config'] ?? cmsCoreConfig();
        return $config[$key] ?? $default;
    }

    function cmsBranchSuffix(): string
    {
        return (string) cmsCoreGet('branch_suffix', 'agra');
    }

    function cmsApiBase(): string
    {
        if (defined('CMS_API_URL')) {
            return rtrim(CMS_API_URL, '/');
        }
        if (defined('API_BASE_URL')) {
            return rtrim(API_BASE_URL, '/');
        }
        return 'https://apscmsnew.fastranking.cloud/api';
    }

    function cmsCmsGetJson(string $endpoint, int $timeout = 20): ?array
    {
        $url = cmsApiBase() . '/' . ltrim($endpoint, '/');
        if (function_exists('cms_curl_get_json')) {
            $decoded = cms_curl_get_json($url, $timeout);
            return is_array($decoded) ? $decoded : null;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);
        if (function_exists('apply_curl_ssl_options')) {
            apply_curl_ssl_options($ch);
        }
        $body = curl_exec($ch);
        curl_close($ch);
        if ($body === false) {
            return null;
        }
        $decoded = json_decode((string) $body, true);
        return is_array($decoded) ? $decoded : null;
    }

    function cmsNormalizeMenuTree(array $item): array
    {
        $children = $item['children'] ?? [];
        $descendants = $item['descendants'] ?? [];
        if ((!is_array($children) || $children === []) && is_array($descendants) && $descendants !== []) {
            $item['children'] = $descendants;
        }
        unset($item['descendants']);

        $normalized = [];
        foreach ($item['children'] ?? [] as $child) {
            if (is_array($child)) {
                $normalized[] = cmsNormalizeMenuTree($child);
            }
        }
        if ($normalized !== []) {
            $item['children'] = $normalized;
        }

        $item['name'] = preg_replace('/\s*Page$/i', '', (string) ($item['name'] ?? ''));
        return $item;
    }

    /** @return list<array<string, mixed>> */
    function cmsNavItems(): array
    {
        static $items = null;
        if ($items !== null) {
            return $items;
        }

        $items = [];
        if (!defined('BRANCH_ID')) {
            return $items;
        }

        $branchResponse = cmsCmsGetJson('menus/branch/' . BRANCH_ID);
        $menus = is_array($branchResponse['data'] ?? null) ? $branchResponse['data'] : [];
        $branchMenu = is_array($menus[0] ?? null) ? $menus[0] : [];
        $rawItems = is_array($branchMenu['menu_items'] ?? null) ? $branchMenu['menu_items'] : [];

        $menuId = $branchMenu['id'] ?? null;
        if ($menuId !== null && $menuId !== '') {
            $menuResponse = cmsCmsGetJson('menus/' . rawurlencode((string) $menuId));
            $fullMenu = is_array($menuResponse['data'] ?? null) ? $menuResponse['data'] : [];
            $fullItems = is_array($fullMenu['menu_items'] ?? null) ? $fullMenu['menu_items'] : [];
            if ($fullItems !== []) {
                $rawItems = $fullItems;
            }
        }

        foreach ($rawItems as $item) {
            if (is_array($item)) {
                $items[] = cmsNormalizeMenuTree($item);
            }
        }

        return $items;
    }

    /** @return list<array<string, mixed>> */
    function cmsPagesIndex(): array
    {
        static $pages = null;
        if ($pages !== null) {
            return $pages;
        }

        $pages = [];
        if (!defined('BRANCH_ID')) {
            return $pages;
        }

        $response = cmsCmsGetJson('pages?branch_id=' . BRANCH_ID, 45);
        $list = is_array($response['data'] ?? null) ? $response['data'] : [];
        foreach ($list as $page) {
            if (is_array($page) && !empty($page['slug'])) {
                $pages[] = $page;
            }
        }

        return $pages;
    }

    function cmsFetchPageBySlug(string $slug): array
    {
        $slug = trim($slug);
        if ($slug === '') {
            return ['data' => []];
        }

        static $cache = [];
        if (isset($cache[$slug])) {
            return $cache[$slug];
        }

        $page = cmsCmsGetJson('pages/' . rawurlencode($slug));
        if (!is_array($page)) {
            $page = ['data' => []];
        }

        $cache[$slug] = $page;
        $GLOBALS['cms_page_slug'] = $slug;

        return $page;
    }

    function cmsFetchPageById(int|string $id): array
    {
        $page = cmsCmsGetJson('pages/' . rawurlencode((string) $id));
        return is_array($page) ? $page : ['data' => []];
    }

    function cmsPathFromSlug(string $slug): string
    {
        $suffix = '-page-' . cmsBranchSuffix();
        if (str_ends_with($slug, $suffix)) {
            return substr($slug, 0, -strlen($suffix));
        }
        return $slug;
    }

    function cmsScriptAliases(): array
    {
        return [
            'faq' => 'faqs',
            'club' => 'clubs',
            'event' => 'events',
            'apply-jobs' => 'careers',
            'job-apply' => 'careers',
        ];
    }

    function cmsGuessSlugFromScript(): ?string
    {
        $script = basename($_SERVER['SCRIPT_FILENAME'] ?? '', '.php');
        if ($script === '' || in_array($script, ['index', 'cms-page', 'header', 'footer', 'foot'], true)) {
            return null;
        }

        $base = cmsScriptAliases()[$script] ?? $script;
        $suffix = cmsBranchSuffix();
        $candidates = [
            $base . '-page-' . $suffix,
            $script . '-page-' . $suffix,
            str_replace('-', '', $base) . '-page-' . $suffix,
        ];

        $index = cmsPagesIndex();
        $known = [];
        foreach ($index as $page) {
            $known[(string) $page['slug']] = true;
        }

        foreach ($candidates as $candidate) {
            if (isset($known[$candidate])) {
                return $candidate;
            }
        }

        foreach ($index as $page) {
            $slug = (string) ($page['slug'] ?? '');
            if ($slug !== '' && str_contains($slug, $base . '-page-')) {
                return $slug;
            }
        }

        return null;
    }

    function cmsResolveSlugFromPath(string $path): ?string
    {
        $path = trim(strtolower($path), '/');
        if ($path === '' || $path === 'home' || $path === 'index') {
            return 'home-page-' . cmsBranchSuffix();
        }

        static $map = null;
        if ($map === null) {
            $map = [];
            foreach (cmsPagesIndex() as $page) {
                $slug = (string) ($page['slug'] ?? '');
                if ($slug === '') {
                    continue;
                }
                $derived = cmsPathFromSlug($slug);
                $map[$derived] = $slug;
            }

            $walk = static function (array $items) use (&$walk, &$map): void {
                foreach ($items as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $url = trim((string) ($item['url'] ?? ''), '/');
                    if ($url !== '' && $url !== '#') {
                        $linkableType = (string) ($item['linkable_type'] ?? '');
                        $linkableId = $item['linkable_id'] ?? null;
                        if ($linkableId && str_contains($linkableType, 'Page')) {
                            foreach (cmsPagesIndex() as $page) {
                                if ((string) ($page['id'] ?? '') === (string) $linkableId) {
                                    $map[$url] = (string) $page['slug'];
                                    break;
                                }
                            }
                        }
                    }
                    $children = $item['children'] ?? [];
                    if (is_array($children) && $children !== []) {
                        $walk($children);
                    }
                }
            };
            $walk(cmsNavItems());
        }

        if (isset($map[$path])) {
            return $map[$path];
        }

        $suffix = cmsBranchSuffix();
        $guess = $path . '-page-' . $suffix;
        foreach (cmsPagesIndex() as $page) {
            if ((string) ($page['slug'] ?? '') === $guess) {
                return $guess;
            }
        }

        return null;
    }

    /**
     * Gallery listing pages store items via the galleries API, not page sections.
     *
     * @return array<string, mixed>|null
     */
    function cmsPageGalleryMeta(string $slug): ?array
    {
        $path = strtolower(cmsPathFromSlug($slug));
        if ($path === '') {
            return null;
        }

        $rules = [
            ['paths' => ['events', 'media-events'], 'mode' => 'events', 'detail' => 'event-gallery'],
            ['paths' => ['photo-gallery'], 'mode' => 'subtype', 'subType' => 'photo', 'detail' => 'photo-gallery'],
            ['paths' => ['achievements', 'our-students-achievements', 'awards-achievements', 'awards'], 'mode' => 'subtype', 'subType' => 'achievements', 'detail' => 'achievements'],
            ['paths' => ['video-gallery'], 'mode' => 'subtype', 'subType' => 'video', 'detail' => 'video-gallery'],
            ['paths' => ['print-media'], 'mode' => 'subtype', 'subType' => 'print', 'detail' => 'print-media'],
        ];

        foreach ($rules as $rule) {
            foreach ($rule['paths'] as $prefix) {
                if ($path === $prefix || str_starts_with($path, $prefix . '-')) {
                    return [
                        'mode' => $rule['mode'],
                        'subType' => $rule['subType'] ?? null,
                        'detailBase' => $rule['detail'],
                    ];
                }
            }
        }

        if (preg_match('/(gallery|event|achievement|award|photo|video|print)/', $path)) {
            return ['mode' => 'events', 'subType' => null, 'detailBase' => 'event-gallery'];
        }

        return null;
    }

    function cmsPageTitle(array $page, string $fallback = ''): string
    {
        $data = is_array($page['data'] ?? null) ? $page['data'] : [];
        $title = trim((string) ($data['page_heading'] ?? $data['title'] ?? ''));
        return $title !== '' ? $title : $fallback;
    }

    function cmsNavMenuUrl(?string $url): string
    {
        if (function_exists('cmsMenuUrl')) {
            return cmsMenuUrl($url);
        }
        $url = trim((string) $url);
        return $url !== '' ? $url : '#';
    }

    function renderCmsNavMenu(array $items, bool $isSub = false, string $variant = 'agra'): void
    {
        if ($items === []) {
            return;
        }

        if ($variant === 'ghaziabad') {
            renderGhaziabadCmsMenu($items, $isSub);
            return;
        }

        if (function_exists('renderAgraMenu')) {
            renderAgraMenu($items, $isSub);
            return;
        }

        renderGhaziabadCmsMenu($items, $isSub);
    }

    function renderGhaziabadCmsMenu(array $items, bool $isSub = false): void
    {
        $dropdownSvg = '<svg width="8" height="5" viewBox="0 0 8 5" xmlns="http://www.w3.org/2000/svg" class="transition fill-white sm:fill-black group-hover:fill-white"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.699203 0.281451C0.825931 0.154761 0.997788 0.0835904 1.17698 0.0835904C1.35617 0.0835904 1.52803 0.154761 1.65476 0.281451L3.88011 2.5068L6.10546 0.281451C6.1678 0.216906 6.24237 0.165424 6.32482 0.130007C6.40727 0.0945895 6.49594 0.0759472 6.58567 0.0751674C6.6754 0.0743877 6.76439 0.091486 6.84744 0.125465C6.93049 0.159444 7.00595 0.209623 7.0694 0.273074C7.13285 0.336525 7.18303 0.411978 7.21701 0.495029C7.25099 0.57808 7.26808 0.667067 7.2673 0.756797C7.26652 0.846527 7.24788 0.935203 7.21246 1.01765C7.17705 1.1001 7.12557 1.17467 7.06102 1.23701L4.35789 3.94014C4.23116 4.06683 4.0593 4.138 3.88011 4.138C3.70092 4.138 3.52906 4.06683 3.40233 3.94014L0.699203 1.23701C0.572513 1.11028 0.501343 0.938422 0.501343 0.759229C0.501343 0.580035 0.572513 0.408179 0.699203 0.281451Z"/></svg>';

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $children = $item['children'] ?? [];
            $hasChildren = is_array($children) && $children !== [];
            $name = htmlspecialchars((string) ($item['name'] ?? ''), ENT_QUOTES, 'UTF-8');
            $href = cmsNavMenuUrl($item['url'] ?? '#');
            $target = htmlspecialchars((string) ($item['target'] ?? '_self'), ENT_QUOTES, 'UTF-8');
            $linkClass = 'hover:text-white hover:bg-[#002a5b] p-2 transition-all';

            if ($isSub) {
                echo '<li><a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="' . $target . '" class="' . $linkClass . '">' . $name . '</a></li>';
                continue;
            }

            if ($hasChildren) {
                echo '<li><a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="' . $target . '" class="group ' . $linkClass . ' flex items-center gap-1">' . $name . $dropdownSvg . '</a><ul>';
                renderGhaziabadCmsMenu($children, true);
                echo '</ul></li>';
            } else {
                echo '<li><a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="' . $target . '" class="' . $linkClass . '">' . $name . '</a></li>';
            }
        }
    }

    function renderGhaziabadMobileCmsMenu(array $items): void
    {
        $arrowSvg = '<svg width="13" height="8" viewBox="0 0 8 5" xmlns="http://www.w3.org/2000/svg" class="transition fill-white arrow group-hover:fill-white"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.699203 0.281451C0.825931 0.154761 0.997788 0.0835904 1.17698 0.0835904C1.35617 0.0835904 1.52803 0.154761 1.65476 0.281451L3.88011 2.5068L6.10546 0.281451C6.1678 0.216906 6.24237 0.165424 6.32482 0.130007C6.40727 0.0945895 6.49594 0.0759472 6.58567 0.0751674C6.6754 0.0743877 6.76439 0.091486 6.84744 0.125465C6.93049 0.159444 7.00595 0.209623 7.0694 0.273074C7.13285 0.336525 7.18303 0.411978 7.21701 0.495029C7.25099 0.57808 7.26808 0.667067 7.2673 0.756797C7.26652 0.846527 7.24788 0.935203 7.21246 1.01765C7.17705 1.1001 7.12557 1.17467 7.06102 1.23701L4.35789 3.94014C4.23116 4.06683 4.0593 4.138 3.88011 4.138C3.70092 4.138 3.52906 4.06683 3.40233 3.94014L0.699203 1.23701C0.572513 1.11028 0.501343 0.938422 0.501343 0.759229C0.501343 0.580035 0.572513 0.408179 0.699203 0.281451Z"/></svg>';

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $children = $item['children'] ?? [];
            $hasChildren = is_array($children) && $children !== [];
            $name = htmlspecialchars((string) ($item['name'] ?? ''), ENT_QUOTES, 'UTF-8');
            $href = cmsNavMenuUrl($item['url'] ?? '#');
            $target = htmlspecialchars((string) ($item['target'] ?? '_self'), ENT_QUOTES, 'UTF-8');
            $linkClass = 'hover:text-white hover:bg-[#002a5b] p-2 transition-all';

            if ($hasChildren) {
                echo '<li class="mobile-nav-item has-submenu">';
                echo '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="' . $target . '" class="group ' . $linkClass . ' flex items-center gap-1">' . $name . $arrowSvg . '</a>';
                echo '<ul class="mobile-submenu">';
                renderGhaziabadMobileCmsMenu($children);
                echo '</ul></li>';
            } else {
                echo '<li><a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="' . $target . '" class="' . $linkClass . '">' . $name . '</a></li>';
            }
        }
    }

    function cmsLoadFooterBootstrap(): array
    {
        static $loaded = false;
        static $layout = [];
        static $links = [];

        if ($loaded) {
            return ['layout' => $layout, 'links' => $links];
        }
        $loaded = true;

        if (!defined('BRANCH_ID')) {
            return ['layout' => [], 'links' => []];
        }

        $layout = cmsCmsGetJson('public/branches/' . BRANCH_ID . '/layout-parts/') ?? [];
        $links = cmsCmsGetJson('public/link-groups/position/footer/hierarchical?branch_id=' . BRANCH_ID) ?? [];

        return ['layout' => $layout, 'links' => $links];
    }
}
