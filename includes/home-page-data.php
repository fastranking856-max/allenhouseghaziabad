<?php

declare(strict_types=1);

require_once __DIR__ . '/cms-page-helpers.php';
require_once __DIR__ . '/api-adapters.php';

function cmsGhaziabadHomePage(): array
{
    static $page = null;
    if ($page === null) {
        $page = cmsFetchGhaziabadPage('home-page-ghaziabad');
    }

    return $page;
}

function cmsHomeSections(): array
{
    $page = cmsGhaziabadHomePage();
    $sections = $page['data']['sections'] ?? [];

    return is_array($sections) ? $sections : [];
}

function cmsHomeSectionByOrder(int $order): ?array
{
    foreach (cmsHomeSections() as $section) {
        if ((int) ($section['order'] ?? 0) === $order) {
            return $section;
        }
    }

    return null;
}

function cmsHomeSectionItems(?array $section): array
{
    if (!is_array($section)) {
        return [];
    }
    $items = $section['resolved_content']['items'] ?? [];

    return is_array($items) ? $items : [];
}

/**
 * Legacy hero shape: [ ['device'=>'desktop','medias'=>[]], ['device'=>'mobile','medias'=>[]] ]
 *
 * @return list<array<string, mixed>>
 */
function cmsHomeHeroLegacyDevices(): array
{
    $items = cmsHomeSectionItems(cmsHomeSectionByOrder(1));
    $desktop = [];
    $mobile = [];

    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }
        $desktopUrl = cmsAssetUrl($item['image_url'] ?? '');
        $mobileUrl = cmsAssetUrl($item['mobile_image_url'] ?? '') ?: $desktopUrl;
        if ($desktopUrl === '') {
            continue;
        }
        $desktop[] = ['urls' => $desktopUrl];
        $mobile[] = ['urls' => $mobileUrl];
    }

    return [
        ['device' => 'desktop', 'medias' => $desktop],
        ['device' => 'mobile', 'medias' => $mobile],
    ];
}

/**
 * @return list<array<string, mixed>>
 */
function cmsHomeCtaItems(): array
{
    $section = cmsHomeSectionByOrder(2);
    $media = $section['resolved_content']['media'] ?? [];
    if (!is_array($media)) {
        return [];
    }

    $rows = [];
    foreach ($media as $card) {
        if (!is_array($card)) {
            continue;
        }
        $image = cmsAssetUrl($card['media_url'] ?? $card['media_file'] ?? '');
        if ($image === '') {
            continue;
        }
        $name = trim(strip_tags((string) ($card['heading'] ?? $card['title'] ?? 'Learn More')));
        $rows[] = [
            'name' => $name !== '' ? $name : 'Learn More',
            'url' => (string) ($card['redirect_url'] ?? $card['page_link'] ?? $card['heading_link'] ?? '#'),
            'media' => ['urls' => $image],
        ];
    }

    return $rows;
}

/**
 * Plain text for fields shown inside a single <p> (no visible HTML tags).
 */
function cmsPlainTextFromHtml(string $html): string
{
    if ($html === '') {
        return '';
    }

    $text = preg_replace('/<\/p>\s*<p[^>]*>/i', ' ', $html);
    $text = strip_tags((string) $text);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return trim(preg_replace('/\s+/u', ' ', $text));
}

/**
 * Parse excellence card copy from CMS HTML (matches Allenhouse Agra index).
 *
 * @return array{firstLine: string, secondLine: string, cleanDesc: string, fullTitle: string}
 */
function cmsParseExcellenceDescription(string $description): array
{
    preg_match('/<h2[^>]*>(.*?)<\/h2>/is', $description, $titleMatch);
    $fullTitle = strip_tags($titleMatch[1] ?? '');
    $titleParts = preg_split('/<br\s*\/?>/i', $fullTitle, 2);
    $firstLine = trim($titleParts[0] ?? '');
    $secondLine = trim(strip_tags($titleParts[1] ?? ''));
    $cleanDesc = preg_replace('/<h2[^>]*>.*?<\/h2>/is', '', $description);
    $cleanDesc = strip_tags($cleanDesc, '<p><br><strong><em>');
    $cleanDesc = trim($cleanDesc);

    return [
        'firstLine' => $firstLine,
        'secondLine' => $secondLine,
        'cleanDesc' => $cleanDesc,
        'fullTitle' => $fullTitle,
    ];
}

/**
 * @return array{firstLine: string, secondLine: string, cleanDesc: string, fullTitle: string}
 */
function cmsParseExcellenceItem(array $item): array
{
    $description = (string) ($item['description'] ?? '');
    if (stripos($description, '<h2') !== false) {
        return cmsParseExcellenceDescription($description);
    }

    $fullTitle = trim(strip_tags((string) ($item['title'] ?? '')));
    if (preg_match('/^(.+?)\s+In\s+(.+)$/iu', $fullTitle, $match)) {
        $firstLine = trim($match[1]);
        $secondLine = 'In ' . trim($match[2]);
    } else {
        $parts = preg_split('/\s+/', $fullTitle, 2);
        $firstLine = trim($parts[0] ?? '');
        $secondLine = trim($parts[1] ?? '');
    }

    $cleanDesc = strip_tags($description, '<p><br><strong><em>');
    $cleanDesc = trim($cleanDesc);

    return [
        'firstLine' => $firstLine,
        'secondLine' => $secondLine,
        'cleanDesc' => $cleanDesc,
        'fullTitle' => $fullTitle,
    ];
}

/**
 * @return list<array<string, mixed>>
 */
function cmsHomeExcellenceItems(): array
{
    $rows = [];
    foreach (cmsHomeSectionItems(cmsHomeSectionByOrder(3)) as $item) {
        if (!is_array($item)) {
            continue;
        }
        $parsed = cmsParseExcellenceItem($item);
        $rows[] = array_merge($parsed, [
            'image_url' => cmsAssetUrl($item['image_url'] ?? ''),
            'link_url' => (string) ($item['link_url'] ?? '#'),
            'media' => ['urls' => cmsAssetUrl($item['image_url'] ?? '')],
        ]);
    }

    return $rows;
}

function cmsHomeApproachDescription(): string
{
    $section = cmsHomeSectionByOrder(4);
    $content = (string) ($section['content'] ?? '');
    if ($content === '' && is_array($section)) {
        $content = (string) ($section['resolved_content']['content'] ?? '');
    }

    return cmsPlainTextFromHtml($content);
}

/**
 * @return array{years: string, campus: string, student: string, staff: string, alumni: string}
 */
function cmsHomeLegacyRow(): array
{
    $row = [
        'years' => '',
        'campus' => '',
        'student' => '',
        'staff' => '',
        'alumni' => '',
    ];

    if (!defined('CMS_API_URL') || !defined('BRANCH_ID')) {
        return $row;
    }

    $response = cms_curl_get_json(rtrim(CMS_API_URL, '/') . '/statistics/branch/' . BRANCH_ID, 20);
    $items = [];
    if (is_array($response['data'][0]['items'] ?? null)) {
        $items = $response['data'][0]['items'];
    }

    foreach ($items as $stat) {
        if (!is_array($stat)) {
            continue;
        }
        $heading = strtolower(trim((string) ($stat['heading'] ?? '')));
        $value = trim((string) ($stat['count'] ?? '')) . trim((string) ($stat['unit'] ?? ''));
        if ($heading === 'years') {
            $row['years'] = $value;
        } elseif ($heading === 'campus') {
            $row['campus'] = $value;
        } elseif ($heading === 'students') {
            $row['student'] = $value;
        } elseif ($heading === 'staff') {
            $row['staff'] = $value;
        } elseif ($heading === 'alumni') {
            $row['alumni'] = $value;
        }
    }

    return $row;
}

function cmsPhilosophyItemIsPlaceholder(array $item): bool
{
    $description = trim(strip_tags((string) ($item['description'] ?? '')));
    if ($description !== '') {
        return false;
    }

    $title = trim(strip_tags((string) ($item['title'] ?? '')));

    return $title === '' || preg_match('/^\d+$/', $title) === 1;
}

/**
 * Shared Allenhouse philosophy copy when branch CMS items only have placeholder titles.
 *
 * @return list<array{title: string, description: string}>
 */
function cmsNetworkPhilosophyFallbackItems(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $cache = [];
    if (!defined('CMS_API_URL')) {
        return $cache;
    }

    $page = cms_curl_get_json(rtrim(CMS_API_URL, '/') . '/pages/home-page-agra', 20);
    $items = $page['data']['sections'][5]['resolved_content']['items'] ?? [];
    if (!is_array($items)) {
        return $cache;
    }

    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }
        $cache[] = [
            'title' => cmsPlainText((string) ($item['title'] ?? '')),
            'description' => cmsPlainText((string) ($item['description'] ?? '')),
        ];
    }

    return $cache;
}

/**
 * @return list<array<string, mixed>>
 */
function cmsHomePhilosophyItems(): array
{
    $items = cmsHomeSectionItems(cmsHomeSectionByOrder(6));
    $fallbackItems = [];
    foreach ($items as $item) {
        if (is_array($item) && cmsPhilosophyItemIsPlaceholder($item)) {
            $fallbackItems = cmsNetworkPhilosophyFallbackItems();
            break;
        }
    }

    $rows = [];
    foreach ($items as $idx => $item) {
        if (!is_array($item)) {
            continue;
        }
        $image = cmsAssetUrl($item['image_url'] ?? '');
        if ($image === '') {
            continue;
        }

        $title = cmsPlainText((string) ($item['title'] ?? ''));
        $description = cmsPlainText((string) ($item['description'] ?? ''));
        if (cmsPhilosophyItemIsPlaceholder($item) && isset($fallbackItems[$idx])) {
            $title = $fallbackItems[$idx]['title'];
            $description = $fallbackItems[$idx]['description'];
        }

        if ($title === '' && $description === '') {
            continue;
        }

        $rows[] = [
            'title' => $title,
            'description' => $description,
            'media' => ['urls' => $image],
        ];
    }

    return $rows;
}

function cmsHomeYoutubeEmbedUrl(string $videoUrl): string
{
    $videoUrl = trim($videoUrl);
    if ($videoUrl === '') {
        return '';
    }

    $videoId = '';
    if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([A-Za-z0-9_-]{6,})#i', $videoUrl, $m)) {
        $videoId = $m[1];
    } else {
        $path = (string) parse_url($videoUrl, PHP_URL_PATH);
        $videoId = trim($path, '/');
    }

    if ($videoId === '') {
        return '';
    }

    return 'https://www.youtube.com/embed/' . rawurlencode($videoId) . '?autoplay=1&mute=1&loop=1&playlist=' . rawurlencode($videoId);
}

function cmsHomeVideoUrl(): string
{
    $section = cmsHomeSectionByOrder(8);
    $url = (string) ($section['resolved_content']['video_url'] ?? '');
    if ($url !== '') {
        return $url;
    }
    foreach (($section['resolved_content']['media'] ?? []) as $media) {
        if (!is_array($media)) {
            continue;
        }
        $candidate = (string) ($media['media_url'] ?? '');
        if ($candidate !== '') {
            return $candidate;
        }
    }

    return '';
}

/**
 * @return array{address: string, contact: string}
 */
function cmsCampusAddressPhone(string $description): array
{
    $address = '';
    $contact = '';

    if ($description === '') {
        return ['address' => $address, 'contact' => $contact];
    }

    if (preg_match('/href=["\']tel:([^"\']+)/i', $description, $phoneMatch)) {
        $contact = trim(html_entity_decode($phoneMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    if (preg_match('/<p[^>]*class="[^"]*text-gray-500[^"]*"[^>]*>(.*?)<\/p>/is', $description, $addressMatch)) {
        $address = trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $addressMatch[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    } elseif (preg_match('/<p[^>]*>(.*?)<\/p>/is', $description, $addressMatch)) {
        $address = trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $addressMatch[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }

    return ['address' => $address, 'contact' => $contact];
}

/**
 * @return list<array<string, mixed>>
 */
function cmsHomeCampusItems(): array
{
    $rows = [];
    foreach (cmsHomeSectionItems(cmsHomeSectionByOrder(9)) as $item) {
        if (!is_array($item)) {
            continue;
        }
        $name = trim(strip_tags((string) ($item['title'] ?? '')));
        if ($name === '' || $name === '1') {
            $name = 'Allenhouse Campus';
        }
        $meta = cmsCampusAddressPhone((string) ($item['description'] ?? ''));
        $rows[] = [
            'id' => $item['id'] ?? null,
            'name' => $name,
            'addressline1' => $meta['address'],
            'contact' => $meta['contact'],
            'media' => ['urls' => cmsAssetUrl($item['image_url'] ?? '')],
            'weburl' => (string) ($item['link_url'] ?? '#'),
        ];
    }

    return $rows;
}

/**
 * @return list<array<string, mixed>>
 */
function cmsHomeGalleryImages(): array
{
    $rows = [];
    foreach (cmsHomeSectionItems(cmsHomeSectionByOrder(12)) as $item) {
        if (!is_array($item)) {
            continue;
        }
        $url = cmsAssetUrl($item['image_url'] ?? '');
        if ($url === '') {
            continue;
        }
        $rows[] = [
            'media' => ['urls' => $url],
            'title' => (string) ($item['title'] ?? ''),
        ];
    }

    return $rows;
}

/**
 * @return list<array<string, mixed>>
 */
function cmsHomeTestimonialItems(): array
{
    $rows = [];
    foreach (cmsHomeSectionItems(cmsHomeSectionByOrder(11)) as $item) {
        if (!is_array($item)) {
            continue;
        }
        $rows[] = [
            'heading' => trim(strip_tags((string) ($item['title'] ?? ''))),
            'description' => (string) ($item['description'] ?? ''),
            'media' => ['urls' => cmsAssetUrl($item['image_url'] ?? '')],
        ];
    }

    if ($rows !== []) {
        return $rows;
    }

    $testimonialsPage = cmsFetchGhaziabadPage('testimonials-page-ghaziabad');
    $legacy = cmsTestimonialItemsLegacy(cmsPageSection($testimonialsPage, 1));

    return $legacy['data'] ?? [];
}

/**
 * @return array{data: list<array<string, mixed>>}
 */
function cmsHomePopupLegacy(): array
{
    if (!defined('CMS_API_URL') || !defined('BRANCH_ID')) {
        return ['data' => []];
    }

    $response = cms_curl_get_json(rtrim(CMS_API_URL, '/') . '/flyers/branch/' . BRANCH_ID, 15);
    $flyer = is_array($response['data'][0] ?? null) ? $response['data'][0] : null;
    if ($flyer === null) {
        return ['data' => []];
    }

    $image = cmsAssetUrl($flyer['image_url'] ?? $flyer['image'] ?? '');

    return [
        'data' => [[
            'image' => $image,
            'url' => (string) ($flyer['link_url'] ?? '#'),
            'text' => (string) ($flyer['title'] ?? ''),
        ]],
    ];
}
