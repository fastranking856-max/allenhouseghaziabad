<?php

require_once __DIR__ . '/api.php';
require_once __DIR__ . '/cms-bootstrap.php';

function cmsPrefetchIndexPage(): void
{
    static $done = false;
    if ($done || !defined('BRANCH_ID')) {
        return;
    }
    $done = true;

    if (!function_exists('cms_prefetch_endpoints')) {
        return;
    }

    $branch = (string) BRANCH_ID;
    cms_prefetch_endpoints([
        'pages/home-page-ghaziabad',
        'pages/testimonials-page-ghaziabad',
        'home-page-pop-up-links/' . $branch,
        'home-page-hero-banner/' . $branch,
        'cta-details/' . $branch,
        'our-excellence/' . $branch,
        'allenhouse-approach/' . $branch,
        'legacy-of-excellence/' . $branch,
        'our-philosophy/' . $branch,
        'home-page-video/' . $branch,
        'our-campus',
        'all-address-details/' . $branch,
        'header-contact/' . $branch,
        'erp-login/' . $branch,
        'top-header-marquee-text/' . $branch,
        'alumni-portal/' . $branch,
    ]);

    if (function_exists('cms_prefetch_json_urls') && defined('CMS_API_URL')) {
        cms_prefetch_json_urls([
            rtrim(CMS_API_URL, '/') . '/public/branches/' . $branch . '/layout-parts/',
            rtrim(CMS_API_URL, '/') . '/public/link-groups/position/footer/hierarchical?branch_id=' . $branch,
            rtrim(CMS_API_URL, '/') . '/statistics/branch/' . $branch,
            rtrim(CMS_API_URL, '/') . '/flyers/branch/' . $branch,
        ]);
    }
}

/**
 * @return array{banner_html: string, table_html: string, content_html: string}
 */
function cmsExtractPageParts(array $raw_page): array
{
    $sections = is_array($raw_page['data']['sections'] ?? null) ? $raw_page['data']['sections'] : [];
    $banner_html = '';
    $table_html = '';
    $content_html = '';

    foreach ($sections as $section) {
        $title = trim((string) ($section['title'] ?? ''));
        if (stripos($title, 'hero') !== false || stripos($title, 'banner') !== false) {
            $banner_html = (string) ($section['content_heading'] ?? $banner_html);
        }
        if (stripos($title, 'table') !== false) {
            $table_html = (string) ($section['content'] ?? $table_html);
        }
        if (stripos($title, 'policy') !== false || stripos($title, 'content') !== false) {
            $content_html = (string) ($section['content'] ?? $content_html);
        }
    }

    if ($table_html === '' && $content_html === '') {
        foreach ($sections as $section) {
            $title = trim((string) ($section['title'] ?? ''));
            if (stripos($title, 'hero') !== false || stripos($title, 'banner') !== false) {
                continue;
            }
            $content = (string) ($section['content'] ?? '');
            if ($content === '') {
                continue;
            }
            if (stripos($content, '<table') !== false) {
                $table_html = $content;
            } else {
                $content_html = $content;
            }
            break;
        }
    }

    if ($table_html === '' && $content_html === '' && !empty($raw_page['data']['content'])) {
        $main = (string) $raw_page['data']['content'];
        if (stripos($main, '<table') !== false) {
            $table_html = $main;
        } else {
            $content_html = $main;
        }
    }

    return [
        'banner_html' => $banner_html,
        'table_html' => $table_html,
        'content_html' => $content_html,
    ];
}

function cmsBeyondAcademicsRow(string $pageSlug, int $sectionIndex = 1): array
{
    require_once __DIR__ . '/api-adapters.php';
    $page = cmsFetchGhaziabadPage($pageSlug);

    return cmsLegacyDataRow(cmsLegacyTwoColumnBlock(cmsPageSection($page, $sectionIndex)));
}

function cmsBeyondAcademicsHtml(string $pageSlug, int $sectionIndex = 1): string
{
    require_once __DIR__ . '/api-adapters.php';
    $section = cmsPageSection(cmsFetchGhaziabadPage($pageSlug), $sectionIndex);
    if (!is_array($section)) {
        return '';
    }

    $html = (string) ($section['content'] ?? '');
    if ($html === '') {
        $html = cmsSectionHtml($section);
    }

    return $html;
}

function cmsGhaziabadSectionTableHtml(string $pageSlug, int $sectionIndex = 1): string
{
    $parts = cmsExtractPageParts(cmsFetchGhaziabadPage($pageSlug));
    if ($parts['table_html'] !== '') {
        return $parts['table_html'];
    }
    if ($parts['content_html'] !== '') {
        return $parts['content_html'];
    }

    return cmsBeyondAcademicsHtml($pageSlug, $sectionIndex);
}

function cmsFetchBlogBySlug(string $slug): ?array
{
    $slug = trim($slug);
    if ($slug === '') {
        return null;
    }

    static $cache = [];
    if (array_key_exists($slug, $cache)) {
        return $cache[$slug];
    }

    if (!defined('CMS_API_URL')) {
        require_once dirname(__DIR__) . '/proxy/config.php';
    }

    $url = rtrim(CMS_API_URL, '/') . '/blogs/slug/' . rawurlencode($slug);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $httpCode !== 200) {
        $cache[$slug] = null;
        return null;
    }

    $decoded = json_decode((string) $response, true);
    if (!is_array($decoded)) {
        $cache[$slug] = null;
        return null;
    }

    $item = $decoded['data'] ?? null;
    if (!is_array($item)) {
        $cache[$slug] = null;
        return null;
    }

    require_once __DIR__ . '/api-adapters.php';
    $cache[$slug] = cmsNormalizeBlogItem($item);

    return $cache[$slug];
}

function cmsGhaziabadBlogListUrl(): string
{
    if (!defined('CMS_API_URL') || !defined('BRANCH_ID')) {
        require_once dirname(__DIR__) . '/proxy/config.php';
    }

    return rtrim(CMS_API_URL, '/') . '/blogs/branch/' . BRANCH_ID;
}

function cmsPrefetchBlogPage(): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    if (function_exists('cms_prefetch_json_urls')) {
        cms_prefetch_json_urls([cmsGhaziabadBlogListUrl()]);
    }
}

function cmsFetchGhaziabadBlogItems(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    if (!defined('CMS_API_URL') || !defined('BRANCH_ID')) {
        require_once dirname(__DIR__) . '/proxy/config.php';
    }

    require_once __DIR__ . '/api-adapters.php';

    $url = cmsGhaziabadBlogListUrl();
    $decoded = function_exists('cms_curl_get_json')
        ? cms_curl_get_json($url, 25)
        : null;

    if (!is_array($decoded)) {
        $cache = [];
        return $cache;
    }

    $cache = cmsExtractBlogItems($decoded);

    return $cache;
}

function cmsFetchBusRoutes(): array
{
    $endpoint = 'get-bus-routes/' . BRANCH_ID;
    $data = null;
    if (function_exists('cms_curl_get_json') && defined('CMS_API_URL')) {
        $data = cms_curl_get_json(rtrim(CMS_API_URL, '/') . '/' . $endpoint);
    }
    if (!is_array($data)) {
        $data = fetchApiData($endpoint);
    }

    if (!empty($data['success']) && !empty($data['data']) && is_array($data['data'])) {
        return $data['data'];
    }

    return is_array($data['data'] ?? null) ? $data['data'] : [];
}
