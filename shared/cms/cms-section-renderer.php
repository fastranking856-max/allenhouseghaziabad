<?php

declare(strict_types=1);

require_once __DIR__ . '/cms-core.php';

if (!function_exists('cmsFixMediaUrl')) {
    function cmsFixMediaUrl(?string $url): string
    {
        if ($url === null || $url === '') {
            return '';
        }
        $url = trim($url);
        if (function_exists('fixCmsAssetUrl')) {
            return fixCmsAssetUrl($url);
        }
        if (function_exists('cmsAssetUrl')) {
            return cmsAssetUrl($url);
        }
        if (str_starts_with($url, 'https://apscmsnew.fastranking.cloud/https://')) {
            return str_replace('https://apscmsnew.fastranking.cloud/', '', $url);
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }
        return cmsApiBase() . '/' . ltrim($url, '/');
    }
}

if (!function_exists('cmsSectionIsHero')) {
    function cmsSectionIsHero(array $section): bool
    {
        $title = strtolower((string) ($section['title'] ?? ''));
        $layout = strtolower((string) ($section['layout'] ?? ''));
        return str_contains($title, 'hero') || str_contains($title, 'banner') || $layout === 'hero';
    }
}

if (!function_exists('cmsSectionInnerHtml')) {
    function cmsSectionInnerHtml(array $section): string
    {
        if (function_exists('cmsSectionHtml')) {
            $html = cmsSectionHtml($section);
            if ($html !== '') {
                return $html;
            }
        }

        foreach (['content', 'content_heading', 'description'] as $key) {
            if (!empty($section[$key]) && is_string($section[$key])) {
                return $section[$key];
            }
        }

        $resolved = $section['resolved_content'] ?? null;
        if (is_array($resolved)) {
            foreach (['content', 'heading', 'description', 'content_text'] as $key) {
                if (!empty($resolved[$key]) && is_string($resolved[$key])) {
                    return $resolved[$key];
                }
            }
        }

        return '';
    }
}

if (!function_exists('cmsRenderSectionHero')) {
    function cmsRenderSectionHero(array $section): void
    {
        $html = cmsSectionInnerHtml($section);
        if ($html === '') {
            return;
        }
        echo '<div class="cms-section cms-section-hero common-banner bg-center flex items-center text-center h-[300px]">';
        echo '<div class="w-full cms-hero-inner">' . $html . '</div>';
        echo '</div>';
    }
}

if (!function_exists('cmsRenderSectionCarousel')) {
    function cmsRenderSectionCarousel(array $section): void
    {
        $items = $section['resolved_content']['items'] ?? [];
        if (!is_array($items) || $items === []) {
            return;
        }

        $heading = cmsSectionInnerHtml($section);
        echo '<section class="cms-section cms-section-carousel mt-10">';
        if ($heading !== '') {
            echo '<div class="ql-snow mb-6"><div class="ql-editor">' . $heading . '</div></div>';
        }
        echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">';
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $img = cmsFixMediaUrl($item['image_url'] ?? $item['media_url'] ?? '');
            if ($img === '') {
                continue;
            }
            $alt = htmlspecialchars((string) ($item['image_alt'] ?? $item['title'] ?? ''), ENT_QUOTES, 'UTF-8');
            echo '<div class="overflow-hidden rounded-xl border border-gray-100 shadow-sm">';
            echo '<img src="' . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') . '" alt="' . $alt . '" class="w-full h-56 object-cover" loading="lazy" />';
            echo '</div>';
        }
        echo '</div></section>';
    }
}

if (!function_exists('cmsRenderSectionColumns')) {
    function cmsRenderSectionColumns(array $section): void
    {
        $columns = $section['columns'] ?? [];
        if (!is_array($columns) || $columns === []) {
            return;
        }

        echo '<section class="cms-section cms-section-columns mt-10">';
        echo '<div class="md:flex flex-wrap gap-8">';
        foreach ($columns as $column) {
            if (!is_array($column)) {
                continue;
            }
            $type = strtolower((string) ($column['content_type'] ?? ''));
            echo '<div class="cms-column flex-1 min-w-[280px] mb-6">';
            if ($type === 'image') {
                $img = cmsFixMediaUrl($column['image_url'] ?? $column['media_url'] ?? '');
                if ($img !== '') {
                    echo '<img src="' . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') . '" alt="" class="w-full rounded-lg" loading="lazy" />';
                }
            } else {
                $content = (string) ($column['content'] ?? '');
                if ($content !== '') {
                    echo '<div class="ql-snow"><div class="ql-editor">' . $content . '</div></div>';
                }
            }
            echo '</div>';
        }
        echo '</div></section>';
    }
}

if (!function_exists('cmsRenderSectionCards')) {
    function cmsRenderSectionCards(array $section): void
    {
        $items = $section['resolved_content']['items'] ?? $section['gallery_json_data'] ?? [];
        if (is_string($items)) {
            $decoded = json_decode($items, true);
            $items = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($items) || $items === []) {
            $html = cmsSectionInnerHtml($section);
            if ($html !== '') {
                echo '<section class="cms-section cms-section-card mt-10"><div class="ql-snow"><div class="ql-editor">' . $html . '</div></div></section>';
            }
            return;
        }

        echo '<section class="cms-section cms-section-cards mt-10"><div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">';
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $title = htmlspecialchars((string) ($item['title'] ?? $item['heading'] ?? ''), ENT_QUOTES, 'UTF-8');
            $img = cmsFixMediaUrl($item['image_url'] ?? $item['media_url'] ?? '');
            $body = (string) ($item['description'] ?? $item['content'] ?? '');
            echo '<article class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">';
            if ($img !== '') {
                echo '<img src="' . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') . '" alt="' . $title . '" class="w-full h-48 object-cover" loading="lazy" />';
            }
            echo '<div class="p-5">';
            if ($title !== '') {
                echo '<h3 class="text-blue-main font-bold text-lg mb-2">' . $title . '</h3>';
            }
            if ($body !== '') {
                echo '<div class="ql-snow text-sm"><div class="ql-editor">' . $body . '</div></div>';
            }
            echo '</div></article>';
        }
        echo '</div></section>';
    }
}

if (!function_exists('cmsRenderSectionContent')) {
    function cmsRenderSectionContent(array $section): void
    {
        if (!empty($section['columns']) && is_array($section['columns']) && count($section['columns']) >= 2) {
            cmsRenderSectionColumns($section);
            return;
        }

        $html = cmsSectionInnerHtml($section);
        if ($html === '') {
            return;
        }

        $title = trim((string) ($section['title'] ?? ''));
        echo '<section class="cms-section cms-section-content mt-10">';
        if ($title !== '' && !cmsSectionIsHero($section)) {
            echo '<h2 class="text-[28px] font-bold text-blue-main mb-4">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h2>';
        }
        echo '<div class="ql-snow"><div class="ql-editor">' . $html . '</div></div>';
        echo '</section>';
    }
}

if (!function_exists('cmsRenderPageSection')) {
    function cmsRenderPageSection(array $section, array $options = []): void
    {
        if (empty($section['is_active']) && array_key_exists('is_active', $section) && !$section['is_active']) {
            return;
        }

        $layout = strtolower((string) ($section['layout'] ?? $section['section_type'] ?? 'content'));
        $skipHero = !empty($options['skip_hero']);

        if ($skipHero && cmsSectionIsHero($section)) {
            return;
        }

        if (!$skipHero && cmsSectionIsHero($section)) {
            cmsRenderSectionHero($section);
            return;
        }

        if ($layout === 'carousel' || str_contains($layout, 'carousel')) {
            cmsRenderSectionCarousel($section);
            return;
        }

        if ($layout === 'card' || str_contains($layout, 'card')) {
            cmsRenderSectionCards($section);
            return;
        }

        cmsRenderSectionContent($section);
    }
}

if (!function_exists('cmsRenderPageSections')) {
  /**
   * @param array<string, mixed> $options skip_hero, start_index, end_index
   */
    function cmsRenderPageSections(?array $page, array $options = []): void
    {
        if (!is_array($page)) {
            return;
        }

        $sections = $page['data']['sections'] ?? [];
        if (!is_array($sections) || $sections === []) {
            return;
        }

        $start = max(0, (int) ($options['start_index'] ?? 0));
        $end = isset($options['end_index']) ? (int) $options['end_index'] : count($sections) - 1;

        echo '<div class="cms-dynamic-sections mx-auto max-w-[1280px] px-3 sm:px-5 py-4">';
        foreach ($sections as $index => $section) {
            if (!is_array($section) || $index < $start || $index > $end) {
                continue;
            }
            cmsRenderPageSection($section, $options);
        }
        echo '</div>';
    }
}

if (!function_exists('cmsMaybeRenderExtraSections')) {
    function cmsMaybeRenderExtraSections(): void
    {
        if (!empty($GLOBALS['cms_skip_auto_sections'])) {
            return;
        }

        $slug = $GLOBALS['cms_page_slug'] ?? cmsGuessSlugFromScript();
        if ($slug === null || $slug === '') {
            return;
        }

        $start = (int) ($GLOBALS['cms_rendered_section_count'] ?? 0);
        if ($start < 0) {
            return;
        }

        $page = cmsFetchPageBySlug($slug);
        $sections = $page['data']['sections'] ?? [];
        if (!is_array($sections) || count($sections) <= $start) {
            return;
        }

        cmsRenderPageSections($page, ['start_index' => $start, 'skip_hero' => true]);
    }
}
