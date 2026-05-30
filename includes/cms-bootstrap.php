<?php

if (!function_exists('cmsSharedBasePath')) {
    function cmsSharedBasePath(): string
    {
        $siteShared = dirname(__DIR__) . '/shared/cms';
        if (is_file($siteShared . '/cms-core.php')) {
            return $siteShared;
        }

        $monorepoShared = dirname(__DIR__, 2) . '/shared/cms';
        if (is_file($monorepoShared . '/cms-core.php')) {
            return $monorepoShared;
        }

        throw new RuntimeException('shared/cms not found (expected site/shared/cms or monorepo shared/cms)');
    }
}

$cmsShared = cmsSharedBasePath();
require_once $cmsShared . '/cms-core.php';
require_once $cmsShared . '/cms-section-renderer.php';
require_once $cmsShared . '/cms-gallery-dynamic.php';

cmsCoreConfigure([
    'branch_suffix' => 'ghaziabad',
    'branch_hosts' => ['allenhouseghaziabad.com', 'www.allenhouseghaziabad.com'],
    'header_layout_slug' => 'header-aps-ghaziabad',
    'footer_layout_slug' => 'footer-aps-ghaziabad',
]);

if (!function_exists('cmsFetchGhaziabadPage')) {
    function cmsFetchGhaziabadPage(string $slug): array
    {
        return cmsFetchPageBySlug($slug);
    }
}

if (!function_exists('cmsGhaziabadHeaderMeta')) {
    /** @return array<string, mixed> */
    function cmsGhaziabadHeaderMeta(): array
    {
        static $meta = null;
        if ($meta !== null) {
            return $meta;
        }

        $meta = [];
        if (!defined('BRANCH_ID')) {
            return $meta;
        }

        $slug = (string) cmsCoreGet('header_layout_slug', 'header-aps-ghaziabad');
        $layoutResponse = cmsLoadFooterBootstrap()['layout'] ?? [];
        $items = [];
        if (isset($layoutResponse['data']) && is_array($layoutResponse['data'])) {
            $items = $layoutResponse['data'];
        } elseif (is_array($layoutResponse)) {
            $items = $layoutResponse;
        }

        $headerPart = null;
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            if (($item['slug'] ?? null) === $slug || ($item['layout_slug'] ?? null) === $slug) {
                $headerPart = $item;
                break;
            }
        }
        if ($headerPart === null) {
            foreach ($items as $item) {
                if (!is_array($item)) {
                    continue;
                }
                if (($item['type'] ?? null) === 'header' || ($item['layout_type'] ?? null) === 'header') {
                    $headerPart = $item;
                    break;
                }
            }
        }

        $meta = is_array($headerPart['meta_data'] ?? null) ? $headerPart['meta_data'] : [];
        return $meta;
    }
}

if (function_exists('cmsAutoGuardCurrentScriptPage')) {
    cmsAutoGuardCurrentScriptPage();
}
