<?php

declare(strict_types=1);

function cmsEmptyResponse(): array
{
    return ['status' => 'success', 'data' => []];
}

function cmsWrapData(array $rows): array
{
    return ['status' => 'success', 'data' => $rows];
}

if (!function_exists('cmsJobsApiSucceeded')) {
    function cmsJobsApiSucceeded(?array $response): bool
    {
        if (!is_array($response)) {
            return false;
        }
        if (($response['status'] ?? '') === 'success') {
            return true;
        }
        if (!empty($response['success'])) {
            return true;
        }

        return array_key_exists('data', $response);
    }
}

if (!function_exists('cmsJobsListFromResponse')) {
    /**
     * @return list<array<string, mixed>>
     */
    function cmsJobsListFromResponse(?array $response): array
    {
        if (!is_array($response)) {
            return [];
        }
        $data = $response['data'] ?? null;
        if (!is_array($data)) {
            return [];
        }
        if (array_key_exists('id', $data) || array_key_exists('job_title', $data)) {
            return [$data];
        }

        return array_values(array_filter($data, 'is_array'));
    }
}

if (!function_exists('cmsFindJobInResponse')) {
    /**
     * @return array<string, mixed>|null
     */
    function cmsFindJobInResponse(?array $response, int|string|null $jobId): ?array
    {
        if ($jobId === null || $jobId === '') {
            return null;
        }
        foreach (cmsJobsListFromResponse($response) as $job) {
            if ((string) ($job['id'] ?? '') === (string) $jobId) {
                return $job;
            }
        }

        return null;
    }
}

if (!function_exists('cmsJobDisplayTitle')) {
    function cmsJobDisplayTitle(?array $job): string
    {
        if (!is_array($job)) {
            return '';
        }

        return trim((string) ($job['job_title'] ?? $job['title'] ?? ''));
    }
}

function cmsAssetUrl(?string $path): string
{
    if ($path === null || $path === '') {
        return '';
    }
    $path = trim($path);
    $prefix = rtrim(API_BASE_URL, '/') . '/';
    if (strpos($path, $prefix) === 0) {
        $rest = substr($path, strlen($prefix));
        if (preg_match('#^https?://#i', $rest)) {
            return $rest;
        }
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }

    return rtrim(API_BASE_URL, '/') . '/' . ltrim($path, '/');
}

function cmsPlainText(?string $html): string
{
    if ($html === null || trim($html) === '') {
        return '';
    }

    $text = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = strip_tags($text);
    $text = preg_replace('/\s+/u', ' ', $text);

    return trim((string) $text);
}

function cmsPageSection(?array $page, int $index): ?array
{
    if (!is_array($page) || !isset($page['data']['sections'][$index])) {
        return null;
    }

    $next = $index + 1;
    $rendered = (int) ($GLOBALS['cms_rendered_section_count'] ?? 0);
    if ($next > $rendered) {
        $GLOBALS['cms_rendered_section_count'] = $next;
    }

    $section = $page['data']['sections'][$index];
    return is_array($section) ? $section : null;
}

function cmsSectionHtml(?array $section): string
{
    if (!is_array($section)) {
        return '';
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

function cmsCarouselMedias(?array $section): array
{
    $items = $section['resolved_content']['items'] ?? [];
    if (!is_array($items)) {
        return [];
    }

    $medias = [];
    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }
        $url = cmsAssetUrl($item['image_url'] ?? $item['media_url'] ?? '');
        if ($url === '') {
            continue;
        }
        $medias[] = [
            'urls' => $url,
            'image_url' => $url,
            'title' => $item['title'] ?? '',
            'image_alt' => $item['image_alt'] ?? null,
        ];
    }

    return $medias;
}

function cmsLegacyCarouselBlock(?array $section, string $title = ''): array
{
    if (!is_array($section)) {
        return cmsWrapData([[
            'title' => $title,
            'details' => '',
            'description' => '',
            'medias' => [],
            'media' => ['urls' => ''],
        ]]);
    }

    $resolved = $section['resolved_content'] ?? [];
    $heading = is_array($resolved) ? ($resolved['heading'] ?? '') : '';
    $intro = (string) ($section['content'] ?? '');
    if ($intro === '') {
        $intro = is_array($resolved) ? (string) ($resolved['heading'] ?? '') : '';
    }

    return cmsWrapData([[
        'title' => $title !== '' ? $title : ($section['title'] ?? ''),
        'details' => $intro !== '' ? $intro : cmsPlainText(cmsSectionHtml($section)),
        'description' => cmsSectionHtml($section),
        'medias' => cmsCarouselMedias($section),
        'media' => ['urls' => cmsCarouselMedias($section)[0]['urls'] ?? ''],
    ]]);
}

function cmsLegacyTwoColumnBlock(?array $section): array
{
    if (!is_array($section)) {
        return cmsWrapData([[
            'details' => '',
            'description' => '',
            'media' => ['urls' => ''],
        ]]);
    }

    $imageUrl = '';
    $textHtml = '';
    foreach ($section['columns'] ?? [] as $col) {
        if (!is_array($col)) {
            continue;
        }
        if (($col['content_type'] ?? '') === 'image') {
            $imageUrl = cmsAssetUrl($col['image_url'] ?? $col['image_path'] ?? '');
        }
        if (($col['content_type'] ?? '') === 'text') {
            $textHtml = (string) ($col['content'] ?? '');
            if ($textHtml === '') {
                $textHtml = (string) ($col['content_heading'] ?? '');
            }
        }
    }

    $intro = (string) ($section['content'] ?? '');

    return cmsWrapData([[
        'details' => $intro,
        'description' => $textHtml,
        'media' => ['urls' => $imageUrl],
    ]]);
}

function cmsLegacyDataRow(array $block): array
{
    return $block['data'][0] ?? [
        'details' => '',
        'description' => '',
        'medias' => [],
        'media' => ['urls' => ''],
    ];
}

function cmsFacilitiesTopBlock(?array $section): array
{
    if (!is_array($section)) {
        return cmsWrapData([[
            'detail' => '',
            'description' => '',
            'medias' => [],
        ]]);
    }

    $content = (string) ($section['content'] ?? '');
    $heading = (string) ($section['content_heading'] ?? '');

    return cmsWrapData([[
        'detail' => $content !== '' ? $content : $heading,
        'description' => $heading !== '' ? $heading : $content,
        'medias' => cmsCarouselMedias($section),
    ]]);
}

function cmsGalleryCollectionLegacy(?array $galleryResponse): array
{
    $items = $galleryResponse['data'] ?? [];
    if (!is_array($items)) {
        return cmsWrapData([]);
    }

    $rows = [];
    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $medias = [];
        foreach ($item['media'] ?? [] as $index => $media) {
            if (!is_array($media)) {
                continue;
            }
            $url = cmsAssetUrl($media['media_url'] ?? $media['media_file'] ?? '');
            $medias[] = [
                'urls' => $url,
                'pivot' => ['is_cover' => $index === 0 ? '1' : '0'],
            ];
        }

        $subType = $item['gallery_sub_type']['sub_type_name'] ?? $item['gallery_type'] ?? '';

        $rows[] = [
            'id' => $item['id'] ?? null,
            'title' => $item['heading'] ?? $item['gallery_title'] ?? '',
            'achievementtitle' => $item['heading'] ?? $item['gallery_title'] ?? '',
            'description' => $item['content'] ?? $item['description'] ?? '',
            'achivementdescription' => $item['content'] ?? $item['description'] ?? '',
            'achevementdate' => $item['date'] ?? null,
            'date' => $item['date'] ?? null,
            'achivementtype' => $subType,
            'type' => $subType,
            'gallery_slug' => $item['gallery_slug'] ?? '',
            'medias' => $medias,
            'media' => $medias[0] ?? ['urls' => ''],
        ];
    }

    return cmsWrapData($rows);
}

function cmsFetchGalleriesRaw(): ?array
{
    require_once __DIR__ . '/api.php';
    $raw = null;
    if (function_exists('cms_curl_get_json') && defined('CMS_API_URL')) {
        $raw = cms_curl_get_json(rtrim(CMS_API_URL, '/') . '/galleries/type/gallery/branch/' . BRANCH_ID);
    }
    if (!is_array($raw)) {
        $raw = fetchApiData('galleries/type/gallery/branch/' . BRANCH_ID);
    }

    return is_array($raw) ? $raw : null;
}

function cmsFetchGalleriesBySubType(string $subType): array
{
    $legacy = cmsGalleryCollectionLegacy(cmsFetchGalleriesRaw());
    $want = strtolower($subType);
    $rows = array_values(array_filter($legacy['data'], static function ($row) use ($want) {
        return strtolower((string) ($row['achivementtype'] ?? '')) === $want;
    }));

    return cmsWrapData($rows);
}

function cmsFindGalleryById(int|string|null $id): ?array
{
    if ($id === null || $id === '') {
        return null;
    }
    $legacy = cmsGalleryCollectionLegacy(cmsFetchGalleriesRaw());
    foreach ($legacy['data'] as $row) {
        if ((string) ($row['id'] ?? '') === (string) $id) {
            return $row;
        }
    }

    return null;
}

function cmsFetchAchievementGalleries(): array
{
    require_once __DIR__ . '/api.php';
    $raw = null;
    if (function_exists('cms_curl_get_json') && defined('CMS_API_URL')) {
        $raw = cms_curl_get_json(rtrim(CMS_API_URL, '/') . '/galleries/type/achievements/branch/' . BRANCH_ID);
    }
    if (!is_array($raw)) {
        $raw = fetchApiData('galleries/type/achievements/branch/' . BRANCH_ID);
    }

    return cmsGalleryCollectionLegacy(is_array($raw) ? $raw : null);
}

function cmsFacilitiesCardsLegacy(?array $section): array
{
    if (!is_array($section)) {
        return cmsWrapData([]);
    }

    $rows = [];
    $media = $section['resolved_content']['media'] ?? [];
    if (!is_array($media)) {
        return cmsWrapData([]);
    }

    foreach ($media as $item) {
        if (!is_array($item)) {
            continue;
        }
        $headingHtml = (string) ($item['heading'] ?? '');
        if (preg_match('/<strong>(.*?)<\/strong>/s', $headingHtml, $matches)) {
            $title = trim(strip_tags($matches[1]));
        } else {
            $title = cmsPlainText($headingHtml);
        }
        $rows[] = [
            'titles' => $title,
            'description' => cmsPlainText($item['content'] ?? ''),
            'media' => ['urls' => cmsAssetUrl($item['media_url'] ?? $item['media_file'] ?? '')],
        ];
    }

    return cmsWrapData($rows);
}

function cmsLegacyContentBlock(?array $section): array
{
    if (!is_array($section)) {
        return cmsEmptyResponse();
    }

    $mediaUrl = '';
    $media = $section['resolved_content']['media'][0] ?? null;
    if (is_array($media)) {
        $mediaUrl = $media['media_url'] ?? $media['media_file'] ?? '';
    }

    $html = cmsSectionHtml($section);

    return cmsWrapData([[
        'title' => cmsPlainText((string) ($section['title'] ?? '')),
        'details' => $html,
        'description' => $html,
        'content' => $html,
        'media' => ['urls' => cmsAssetUrl($mediaUrl)],
    ]]);
}

function cmsLegacyHtmlPageBlock(?array $page, int $sectionIndex = 1): array
{
    return cmsLegacyContentBlock(cmsPageSection($page, $sectionIndex));
}

function cmsTestimonialItemsLegacy(?array $section): array
{
    if (!is_array($section)) {
        return cmsEmptyResponse();
    }

    $items = $section['resolved_content']['items'] ?? [];
    if (!is_array($items)) {
        return cmsEmptyResponse();
    }

    $rows = [];
    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }
        $description = (string) ($item['description'] ?? '');
        $rows[] = [
            'heading' => cmsPlainText($item['title'] ?? ''),
            'name' => cmsPlainText($item['title'] ?? ''),
            'description' => $description !== '' ? $description : cmsPlainText($description),
            'media' => ['urls' => cmsAssetUrl($item['image_url'] ?? '')],
            'image' => cmsAssetUrl($item['image_url'] ?? ''),
        ];
    }

    return cmsWrapData($rows);
}

function cmsExtractBlogItems(?array $response): array
{
    if (!is_array($response)) {
        return [];
    }

    if (isset($response['data']['data']) && is_array($response['data']['data'])) {
        return $response['data']['data'];
    }

    if (isset($response['data']) && is_array($response['data']) && array_is_list($response['data'])) {
        return $response['data'];
    }

    return [];
}

function cmsNormalizeBlogItem(array $blog): array
{
    $title = (string) ($blog['title'] ?? $blog['main_title'] ?? $blog['post_title'] ?? '');
    $description = (string) ($blog['description'] ?? $blog['main_description'] ?? $blog['excerpt'] ?? '');
    $image = (string) ($blog['image_url'] ?? ($blog['media']['urls'] ?? ''));

    $details = [];
    foreach ($blog['blogdetails'] ?? [] as $detail) {
        if (!is_array($detail)) {
            continue;
        }
        $details[] = [
            'title' => (string) ($detail['title'] ?? ''),
            'description' => (string) ($detail['description'] ?? ''),
            'media' => ['urls' => cmsAssetUrl($detail['image_url'] ?? ($detail['media']['urls'] ?? ''))],
        ];
    }

    $date = (string) ($blog['date'] ?? $blog['created_at'] ?? '');
    $timestamp = $date !== '' ? strtotime($date) : false;
    $dateFormatted = $timestamp ? date('d F Y', $timestamp) : '';

    return array_merge($blog, [
        'title' => $title,
        'slug' => (string) ($blog['slug'] ?? $blog['blog_slug'] ?? ''),
        'description' => $description,
        'main_title' => $title,
        'main_description' => $description,
        'media' => ['urls' => cmsAssetUrl($image)],
        'date' => $date,
        'date_formatted' => $dateFormatted,
        'blogdetails' => $details !== [] ? $details : ($blog['blogdetails'] ?? []),
    ]);
}

/**
 * @return array{matched: ?array, others: list<array>}
 */
function cmsBlogDetailContext(string $slug): array
{
    require_once __DIR__ . '/cms-page-helpers.php';

    $slug = trim(rawurldecode($slug));
    $allBlogs = cmsBlogListForDisplay();
    $matched = null;

    foreach ($allBlogs as $blog) {
        if (($blog['slug'] ?? '') === $slug) {
            $matched = $blog;
            break;
        }
    }

    if (!$matched) {
        $matched = cmsFetchBlogBySlug($slug);
    } elseif (empty($matched['blogdetails'])) {
        $full = cmsFetchBlogBySlug($slug);
        if (is_array($full)) {
            $matched = array_merge($matched, $full);
        }
    }

    $others = [];
    foreach ($allBlogs as $blog) {
        if (($blog['slug'] ?? '') === $slug) {
            continue;
        }
        if (($blog['title'] ?? '') === '') {
            continue;
        }
        $others[] = $blog;
    }

    return [
        'matched' => $matched,
        'others' => $others,
    ];
}

function cmsBlogListForDisplay(): array
{
    require_once __DIR__ . '/cms-page-helpers.php';
    $items = [];
    foreach (cmsFetchGhaziabadBlogItems() as $blog) {
        if (!is_array($blog)) {
            continue;
        }
        $normalized = cmsNormalizeBlogItem($blog);
        if (($normalized['title'] ?? '') === '') {
            continue;
        }
        $items[] = $normalized;
    }

    return $items;
}

function cmsFindBlogBySlug(string $slug): ?array
{
    require_once __DIR__ . '/cms-page-helpers.php';
    $slug = trim(rawurldecode($slug));
    if ($slug === '') {
        return null;
    }

    $blog = cmsFetchBlogBySlug($slug);
    if (is_array($blog)) {
        return $blog;
    }

    foreach (cmsBlogListForDisplay() as $item) {
        if (($item['slug'] ?? '') === $slug) {
            return $item;
        }
    }

    return null;
}
