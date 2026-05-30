<?php
require_once __DIR__ . '/api.php';
require_once __DIR__ . '/environment.php';
require_once __DIR__ . '/cms-bootstrap.php';
cmsMaybeRenderExtraSections();

if (!function_exists('cmsLayoutPart')) {
    function cmsLayoutItems($layoutData): array
    {
        if (!is_array($layoutData)) {
            return [];
        }

        // Common API shape: ['data' => [...]]
        if (isset($layoutData['data']) && is_array($layoutData['data'])) {
            return $layoutData['data'];
        }

        // Already a list of layout items.
        if (isset($layoutData[0]) && is_array($layoutData[0])) {
            return $layoutData;
        }

        return [];
    }

    function cmsLayoutPart($layoutData, string $slug)
    {
        if ($slug === '') {
            return null;
        }

        foreach (cmsLayoutItems($layoutData) as $item) {
            if (!is_array($item)) {
                continue;
            }

            if (($item['slug'] ?? null) === $slug || ($item['layout_slug'] ?? null) === $slug) {
                return $item;
            }
        }

        return null;
    }
}

if (!function_exists('cmsLayoutPartByType')) {
    function cmsLayoutPartByType($layoutData, string $type)
    {
        if ($type === '') {
            return null;
        }

        foreach (cmsLayoutItems($layoutData) as $item) {
            if (!is_array($item)) {
                continue;
            }

            if (($item['type'] ?? null) === $type || ($item['layout_type'] ?? null) === $type) {
                return $item;
            }
        }

        return null;
    }
}

if (!function_exists('cmsLayoutMeta')) {
    function cmsLayoutMeta($layoutData, string $slug): array
    {
        $part = cmsLayoutPart($layoutData, $slug);
        return is_array($part['meta_data'] ?? null) ? $part['meta_data'] : [];
    }
}

if (!function_exists('cmsAssetUrl')) {
    function cmsAssetUrl($url): string
    {
        if (!is_string($url)) {
            return '';
        }

        $url = trim($url);
        if ($url === '') {
            return '';
        }

        // Keep absolute URLs untouched.
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        // Convert CMS relative media paths to full URL.
        if ($url[0] === '/') {
            return 'https://apscmsnew.fastranking.cloud' . $url;
        }

        return $url;
    }
}

if (!function_exists('cmsFooterLinkGroup')) {
    function cmsFooterLinkGroup($linkData, string $groupSlug): array
    {
        if ($groupSlug === '') {
            return [];
        }

        $groups = [];
        if (is_array($linkData)) {
            $groups = isset($linkData['data']) && is_array($linkData['data']) ? $linkData['data'] : $linkData;
        }

        foreach ($groups as $group) {
            if (!is_array($group)) {
                continue;
            }

            if (($group['slug'] ?? null) === $groupSlug || ($group['group_slug'] ?? null) === $groupSlug) {
                return $group;
            }
        }

        return [];
    }
}

if (!function_exists('cmsLoadGhaziabadFooterData')) {
    function cmsLoadGhaziabadFooterData(): array
    {
        static $loaded = false;
        static $layoutData = [];
        static $linkData = [];

        if ($loaded) {
            return ['layout' => $layoutData, 'links' => $linkData];
        }

        $loaded = true;
        if (!defined('BRANCH_ID') || !function_exists('cms_curl_get_json') || !defined('CMS_API_URL')) {
            return ['layout' => [], 'links' => []];
        }

        $layout = cms_curl_get_json(rtrim(CMS_API_URL, '/') . '/public/branches/' . BRANCH_ID . '/layout-parts/');
        $links = cms_curl_get_json(
            rtrim(CMS_API_URL, '/') . '/public/link-groups/position/footer/hierarchical?branch_id=' . BRANCH_ID
        );

        $layoutData = is_array($layout) ? $layout : [];
        $linkData = is_array($links) ? $links : [];

        return ['layout' => $layoutData, 'links' => $linkData];
    }
}

$header_footer_data = isset($header_footer_data) && is_array($header_footer_data) ? $header_footer_data : [];
$footer_link_data = isset($footer_link_data) && is_array($footer_link_data) ? $footer_link_data : [];

if (cmsLayoutItems($header_footer_data) === []) {
    $footerBootstrap = cmsLoadGhaziabadFooterData();
    $header_footer_data = $footerBootstrap['layout'];
}

if (
    cmsFooterLinkGroup($footer_link_data, 'important-links') === []
    && cmsFooterLinkGroup($footer_link_data, 'quick-links') === []
) {
    $footerBootstrap = cmsLoadGhaziabadFooterData();
    $footer_link_data = $footerBootstrap['links'];
}

$layoutItems = cmsLayoutItems($header_footer_data);
$importantGroup = cmsFooterLinkGroup($footer_link_data, 'important-links');
$quickGroup = cmsFooterLinkGroup($footer_link_data, 'quick-links');

// Extract header and footer parts
$headerPart = cmsLayoutPart($header_footer_data, 'header-aps-ghaziabad')
    ?? cmsLayoutPartByType($header_footer_data, 'header')
    ?? ($layoutItems[0] ?? []);
$headerMeta = is_array($headerPart['meta_data'] ?? null) ? $headerPart['meta_data'] : cmsLayoutMeta($header_footer_data, 'header-aps-ghaziabad');

$footerPart = cmsLayoutPart($header_footer_data, 'footer-aps-ghaziabad')
    ?? cmsLayoutPartByType($header_footer_data, 'footer')
    ?? ($layoutItems[1] ?? []);
$footerMeta = is_array($footerPart['meta_data'] ?? null) ? $footerPart['meta_data'] : cmsLayoutMeta($header_footer_data, 'footer-aps-ghaziabad');

// Footer logo (fallback to header logo if footer logo missing)
$footerLogo = cmsAssetUrl($footerMeta['footer_logo_url'] ?? ($headerMeta['logo_url'] ?? ''));

// Address (from footer_about)
$footerAbout = trim((string) ($footerMeta['footer_about'] ?? ''));
$footerPhone = trim((string) ($footerMeta['footer_phones'] ?? ($headerMeta['phones'] ?? '')));
$footerEmail = trim((string) ($footerMeta['footer_email'] ?? ''));
$copyrightText = trim((string) ($footerMeta['copyright_text'] ?? ''));

// App badges
$androidAppLink = $footerMeta['android_app_link'] ?? '';
$iosAppLink     = $footerMeta['ios_app_link'] ?? '';
$androidBadge   = cmsAssetUrl($footerMeta['android_badge_url'] ?? '');
$iosBadge       = cmsAssetUrl($footerMeta['ios_badge_url'] ?? '');

// Social media links (from footer meta)
$socialLinks = [
    ['name' => 'Youtube',   'url' => $footerMeta['footer_youtube'] ?? ''],
    ['name' => 'Facebook',  'url' => $footerMeta['footer_facebook'] ?? ''],
    ['name' => 'Instagram', 'url' => $footerMeta['footer_instagram'] ?? ''],
    ['name' => 'LinkedIn',  'url' => $footerMeta['footer_linkedin'] ?? ''],
];
$socialLinks = array_filter($socialLinks, fn($item) => !empty($item['url']));

// WhatsApp number
$whatsappNumber = trim((string) ($footerMeta['whatsapp_number'] ?? ''));

// Important Links & Quick Links (from CMS link groups – if available)
$importantLinks = $importantGroup['links'] ?? [];
$quickLinks     = $quickGroup['links'] ?? [];

// Fallback hardcoded links (remove once CMS groups are configured)
if (empty($importantLinks)) {
    $importantLinks = [
        ['url' => 'our-curriculum', 'title' => 'Our Curriculum'],
        ['url' => 'our-story', 'title' => 'Our Story'],
        ['url' => 'facilities', 'title' => 'Facilities'],
        ['url' => '#', 'title' => 'Media & Events', 'children' => [
            ['url' => 'photo-gallery', 'title' => 'Photo Gallery'],
            ['url' => 'video-gallery', 'title' => 'Video Gallery'],
            ['url' => 'print-media', 'title' => 'Print Media'],
            ['url' => 'event', 'title' => 'Events'],
        ]],
        ['url' => '#', 'title' => 'Information', 'children' => [
            ['url' => 'management-committee', 'title' => 'Management Committee'],
            ['url' => 'other-information', 'title' => 'Other Information'],
            ['url' => 'parent-teacher-association', 'title' => 'Parent Teacher Association'],
            ['url' => 'sexual-harassment-committee', 'title' => 'Sexual Harassment Committee'],
            ['url' => 'pocso-committee', 'title' => 'POCSO Committee'],
            ['url' => 'teachers-details', 'title' => 'Teachers Details'],
        ]],
        ['url' => 'mandatory-public-disclosure', 'title' => 'Mandatory Public Disclosure'],
    ];
}
if (empty($quickLinks)) {
    $quickLinks = [
        ['url' => '#', 'title' => 'Alumni Portal'],
        ['url' => '#', 'title' => 'ERP Login'],
        ['url' => 'faq', 'title' => 'FAQs'],
        ['url' => 'blog', 'title' => 'Blogs'],
        ['url' => 'testimonial', 'title' => 'Testimonials'],
        ['url' => 'cbse-information', 'title' => 'CBSE Information'],
        ['url' => 'apply-jobs', 'title' => 'Apply Now'],
    ];
}

$linkClass = 'text-white transition hover:text-red-500 2xl:text-[17px] xl:text-[14px] lg:text-[13px] md:text-[12px] text-[12px]';
$newsletterPlaceholder = $footerMeta['newsletter_placeholder'] ?? 'Type Email';
$newsletterButton = $footerMeta['newsletter_button'] ?? 'Subscribe';
?>
<style>
    .real-is-hide { display: none; }
    .real-should-display:hover .real-is-hide { display: block; }
    .real-should-display:hover .not-real-hide { display: none; }
    .whatsapp-button { position: fixed; right: 15px; bottom: 15px; z-index: 999; transition: transform 0.3s ease; }
    .whatsapp-button img { width: 60px; height: 60px; border-radius: 50%; }
    .whatsapp-button:hover img { box-shadow: 0 0 20px #25D366, 0 0 40px #25D366; transform: scale(1.1); }
</style>

<div id="NewsPopup" class="fixed bg-green-500 text-white px-4 py-2 rounded mb-5 hidden" style="z-index:9999; right: 0; bottom: 20%;">
    Newsletter Subscribed.
</div>

<footer class="py-5 px-4 md:px-4 pmt-20 bg-blue-main flex flex-col md:flex-row justify-around">

    <!-- Left column: Logo & contact info -->
    <div class="md:mt-3 md:order-1 order-1 mt-8">
        <div>
            <a href="index">
                <img src="<?= htmlspecialchars($footerLogo) ?>" alt="School logo" class="w-[236px] md:w-[200px]">
            </a>
        </div>
        <div class="mt-5">
            <div class="mt-4 md:mt-6">
                <div class="flex gap-2 mb-3">
                    <p class="text-white font-[400] 2xl:text-[17px] xl:text-[14px] lg:text-[13px] md:text-[12px]">
                        <?= nl2br(htmlspecialchars($footerAbout)) ?>
                    </p>
                </div>
                <div class="flex gap-2 mb-3 items-center">
                    <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_105_2550)">
                            <path d="M11.388 12.4369C9.20445 11.5124 7.46809 9.772 6.54862 7.58633L9.09841 5.03204L4.4038 0.33368L2.02575 2.71098C1.61328 3.12581 1.28717 3.6183 1.06628 4.15998C0.845385 4.70166 0.734098 5.28176 0.738855 5.86672C0.738855 11.3023 7.67728 18.2407 13.1128 18.2407C13.6977 18.2458 14.2778 18.1347 14.8194 17.9138C15.361 17.6929 15.8534 17.3666 16.2678 16.9538L18.6459 14.5758L13.9475 9.87739L11.388 12.4369ZM15.2067 15.8934C14.9313 16.1664 14.6043 16.3819 14.2449 16.5274C13.8854 16.6729 13.5006 16.7454 13.1128 16.7408C8.43847 16.7408 2.23873 10.5411 2.23873 5.86672C2.23431 5.47888 2.30695 5.09402 2.45242 4.73446C2.59789 4.3749 2.8133 4.04781 3.08616 3.77215L4.4038 2.4545L6.98134 5.03204L4.78027 7.23311L4.964 7.69357C5.5049 9.14043 6.35046 10.4541 7.44341 11.5457C8.53636 12.6372 9.85116 13.481 11.2987 14.02L11.7532 14.1933L13.9475 11.9982L16.525 14.5758L15.2067 15.8934ZM11.238 1.74206V0.242188C13.2263 0.244371 15.1325 1.03518 16.5384 2.44112C17.9444 3.84705 18.7352 5.75328 18.7374 7.74157H17.2375C17.2357 6.15095 16.603 4.62599 15.4783 3.50125C14.3536 2.37651 12.8286 1.74385 11.238 1.74206ZM11.238 4.74182V3.24194C12.431 3.24313 13.5748 3.71758 14.4184 4.56117C15.262 5.40475 15.7364 6.54856 15.7376 7.74157H14.2377C14.2377 6.94598 13.9217 6.18298 13.3591 5.62042C12.7966 5.05786 12.0336 4.74182 11.238 4.74182Z" fill="#FBFBFB" />
                        </g>
                        <defs><clipPath id="clip0_105_2550"><rect width="17.9985" height="17.9985" fill="white" transform="translate(0.738953 0.242188)"/></clipPath></defs>
                    </svg>
                    <p class="text-white font-[400] 2xl:text-[17px] xl:text-[14px] lg:text-[13px] md:text-[12px]">
                        <a href="tel:<?= htmlspecialchars(preg_replace('/\s+/', '', $footerPhone)) ?>"><?= htmlspecialchars($footerPhone) ?></a>
                    </p>
                </div>
                <div class="flex gap-2 mb-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 32 32"><path fill="#fff" d="M28.516 7.167H3.482L16 14.275zM16.74 17.303a1.5 1.5 0 0 1-1.48 0L2.5 10.06v14.773h27V10.06z"/></svg>
                    <a class="text-white font-[400] 2xl:text-[17px] xl:text-[14px] lg:text-[13px] md:text-[12px]" href="mailto:<?= htmlspecialchars($footerEmail) ?>"><?= htmlspecialchars($footerEmail) ?></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle columns: Important Links & Quick Links -->
    <div class="flex justify-between md:w-[30%] md:order-2 order-2">
        <!-- Important Links -->
        <div class="mt-3">
            <h2 class="md:text-[22px] text-[18px] font-[600]" style="color:#B4D7FF">Important Links</h2>
            <ul class="grid grid-cols-1 gap-2 mt-2">
                <?php foreach ($importantLinks as $link):
                    $url = cmsMenuUrl($link['url'] ?? '#');
                    $title = $link['title'] ?? $link['name'] ?? 'Link';
                    $children = $link['children'] ?? [];
                ?>
                <li>
                    <?php if (empty($children)): ?>
                        <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" class="<?= $linkClass ?>"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></a>
                    <?php else: ?>
                        <button type="button" class="toggle-submenu <?= $linkClass ?> transition text-left inline-flex items-center relative my-1 gap-1 group" aria-expanded="false">
                            <?= htmlspecialchars($title) ?>
                            <span class="footer-submenu-caret text-[10px] leading-none" aria-hidden="true">▼</span>
                        </button>
                        <div class="submenu hidden z-10 mt-1 bg-white w-44 rounded shadow-md">
                            <ul class="text-sm">
                                <?php foreach ($children as $child): ?>
                                <li class="border-b border-b-[#e5e7eb] last:border-b-0 hover:border-l-[#002A5B] hover:border-l-2">
                                    <a href="<?= htmlspecialchars(cmsMenuUrl($child['url'] ?? '#'), ENT_QUOTES, 'UTF-8') ?>" class="block transition py-2 px-2 text-blue-main hover:text-red-500"><?= htmlspecialchars($child['title'] ?? $child['name'] ?? 'Link', ENT_QUOTES, 'UTF-8') ?></a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Quick Links -->
        <div class="mt-2 md:mt-4">
            <h2 class="md:text-[22px] text-[18px] font-[600]" style="color:#B4D7FF">Quick links</h2>
            <ul class="grid grid-cols-1 md:gap-4 gap-2 mt-2">
                <?php foreach ($quickLinks as $link):
                    $url = cmsMenuUrl($link['url'] ?? '#');
                    $title = $link['title'] ?? $link['name'] ?? 'Link';
                ?>
                <li><a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" class="<?= $linkClass ?>"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Right column: Social Media, App badges, Newsletter -->
    <div class="mt-5 order-3 md:w-[25%] md:mt-8">
        <!-- Social Media -->
        <div>
            <h2 class="md:text-[22px] text-[18px] font-[600]" style="color:#B4D7FF">Social Media</h2>
            <ul class="flex flex-wrap md:gap-4 gap-2 mt-2 md:mt-4">
                <?php foreach ($socialLinks as $social):
                    $name = $social['name'];
                    $url = $social['url'];
                ?>
                <li>
                    <a href="<?= htmlspecialchars($url) ?>" target="_blank" class="real-should-display transition-all">
                        <?php if ($name === "Youtube"): ?>
                            <svg class="not-real-hide sm:w-10 sm:h-10 w-8 h-8" viewBox="0 0 24 24"><path fill="#fff" d="m10 15l5.19-3L10 9zm11.56-7.83c.13.47.22 1.1.28 1.9c.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83c-.25.9-.83 1.48-1.73 1.73c-.47.13-1.33.22-2.65.28c-1.3.07-2.49.1-3.59.1L12 19c-4.19 0-6.8-.16-7.83-.44c-.9-.25-1.48-.83-1.73-1.73c-.13-.47-.22-1.1-.28-1.9c-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83c.25-.9.83-1.48 1.73-1.73c.47-.13 1.33-.22 2.65-.28c1.3-.07 2.49-.1 3.59-.1L12 5c4.19 0 6.8.16 7.83.44c.9.25 1.48.83 1.73 1.73"/></svg>
                            <svg class="real-is-hide sm:w-10 sm:h-10 w-8 h-8" viewBox="0 0 256 180"><path fill="#f00" d="M250.346 28.075A32.18 32.18 0 0 0 227.69 5.418C207.824 0 127.87 0 127.87 0S47.912.164 28.046 5.582A32.18 32.18 0 0 0 5.39 28.24c-6.009 35.298-8.34 89.084.165 122.97a32.18 32.18 0 0 0 22.656 22.657c19.866 5.418 99.822 5.418 99.822 5.418s79.955 0 99.82-5.418a32.18 32.18 0 0 0 22.657-22.657c6.338-35.348 8.291-89.1-.164-123.134"/><path fill="#fff" d="m102.421 128.06l66.328-38.418-66.328-38.418z"/></svg>
                        <?php elseif ($name === "Facebook"): ?>
                            <svg class="not-real-hide sm:w-10 sm:h-10 w-8 h-8" viewBox="0 0 24 24"><path fill="#fff" d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95"/></svg>
                            <svg class="real-is-hide sm:w-10 sm:h-10 w-8 h-8" viewBox="0 0 256 256"><path fill="#1877f2" d="M256 128C256 57.308 198.692 0 128 0S0 57.308 0 128c0 63.888 46.808 116.843 108 126.445V165H75.5v-37H108V99.8c0-32.08 19.11-49.8 48.348-49.8C170.352 50 185 52.5 185 52.5V84h-16.14C152.959 84 148 93.867 148 103.99V128h35.5l-5.675 37H148v89.445c61.192-9.602 108-62.556 108-126.445"/><path fill="#fff" d="m177.825 165l5.675-37H148v-24.01C148 93.866 152.959 84 168.86 84H185V52.5S170.352 50 156.347 50C127.11 50 108 67.72 108 99.8V128H75.5v37H108v89.445A129 129 0 0 0 128 256a129 129 0 0 0 20-1.555V165z"/></svg>
                        <?php elseif ($name === "Instagram"): ?>
                            <svg class="not-real-hide sm:w-10 sm:h-10 w-8 h-8" viewBox="0 0 16 16"><path fill="#fff" d="M8 1c-1.35 0-2.33.016-2.92.047a6 6 0 0 0-1.55.266a3.66 3.66 0 0 0-2.22 2.22a6 6 0 0 0-.266 1.55c-.031.594-.047 1.57-.047 2.92s.016 2.33.047 2.92c.019.525.108 1.05.266 1.55c.182.511.475.976.86 1.36c.38.382.846.666 1.36.828c.497.178 1.02.278 1.55.296c.594.031 1.57.047 2.92.047s2.33-.016 2.92-.047a6 6 0 0 0 1.55-.266a3.67 3.67 0 0 0 2.22-2.22q.236-.754.266-1.55c.031-.594.047-1.57.047-2.92s-.01-2.32-.031-2.91a6.3 6.3 0 0 0-.282-1.56a3.66 3.66 0 0 0-2.219-2.22a6 6 0 0 0-1.55-.266q-.893-.047-2.92-.047zm-.5 12.8q-1.25 0-1.94-.031a6.6 6.6 0 0 1-1.69-.25a2.38 2.38 0 0 1-1.344-1.344a6.6 6.6 0 0 1-.25-1.69c-.021-.458-.031-1.1-.031-1.94v-1q0-1.25.031-1.94c.006-.571.09-1.14.25-1.69a2.26 2.26 0 0 1 1.343-1.343a6.6 6.6 0 0 1 1.69-.25c.458-.021 1.1-.031 1.94-.031h1q1.25 0 1.94.031c.571.006 1.14.09 1.69.25a2.26 2.26 0 0 1 1.344 1.343c.16.549.244 1.12.25 1.69c.02.438.031 1.08.031 1.94v1q0 1.25-.031 1.94a6.6 6.6 0 0 1-.25 1.69a2.38 2.38 0 0 1-1.344 1.344c-.548.16-1.12.244-1.69.25c-.437.02-1.08.031-1.94.031zm4.25-10.4a.87.87 0 0 0-.616 1.49a.85.85 0 0 0 .944.195a.8.8 0 0 0 .272-.195c.16-.168.256-.385.275-.616a.89.89 0 0 0-.875-.875zM8 4.52a3.4 3.4 0 0 0-1.75.472c-.53.307-.971.748-1.28 1.28a3.5 3.5 0 0 0 0 3.5A3.52 3.52 0 0 0 8 11.524a3.515 3.515 0 0 0 3.502-3.502c0-.61-.163-1.22-.472-1.75a3.5 3.5 0 0 0-1.28-1.28A3.4 3.4 0 0 0 8 4.52m0 5.75a2.25 2.25 0 0 1 0-4.5a2.25 2.25 0 0 1 0 4.5"/></svg>
                            <svg class="real-is-hide sm:w-10 sm:h-10 w-8 h-8" viewBox="0 0 256 256"><g fill="none"><rect width="256" height="256" fill="url(#skillIconsInstagram0)" rx="60"/><rect width="256" height="256" fill="url(#skillIconsInstagram1)" rx="60"/><path fill="#fff" d="M128.009 28c-27.158 0-30.567.119-41.233.604c-10.646.488-17.913 2.173-24.271 4.646c-6.578 2.554-12.157 5.971-17.715 11.531c-5.563 5.559-8.98 11.138-11.542 17.713c-2.48 6.36-4.167 13.63-4.646 24.271c-.477 10.667-.602 14.077-.602 41.236s.12 30.557.604 41.223c.49 10.646 2.175 17.913 4.646 24.271c2.556 6.578 5.973 12.157 11.533 17.715c5.557 5.563 11.136 8.988 17.709 11.542c6.363 2.473 13.631 4.158 24.275 4.646c10.667.485 14.073.604 41.23.604c27.161 0 30.559-.119 41.225-.604c10.646-.488 17.921-2.173 24.284-4.646c6.575-2.554 12.146-5.979 17.702-11.542c5.563-5.558 8.979-11.137 11.542-17.712c2.458-6.361 4.146-13.63 4.646-24.272c.479-10.666.604-14.066.604-41.225s-.125-30.567-.604-41.234c-.5-10.646-2.188-17.912-4.646-24.27c-2.563-6.578-5.979-12.157-11.542-17.716c-5.562-5.562-11.125-8.979-17.708-11.53c-6.375-2.474-13.646-4.16-24.292-4.647c-10.667-.485-14.063-.604-41.23-.604zm-8.971 18.021c2.663-.004 5.634 0 8.971 0c26.701 0 29.865.096 40.409.575c9.75.446 15.042 2.075 18.567 3.444c4.667 1.812 7.994 3.979 11.492 7.48c3.5 3.5 5.666 6.833 7.483 11.5c1.369 3.52 3 8.812 3.444 18.562c.479 10.542.583 13.708.583 40.396s-.104 29.855-.583 40.396c-.446 9.75-2.075 15.042-3.444 18.563c-1.812 4.667-3.983 7.99-7.483 11.488c-3.5 3.5-6.823 5.666-11.492 7.479c-3.521 1.375-8.817 3-18.567 3.446c-10.542.479-13.708.583-40.409.583c-26.702 0-29.867-.104-40.408-.583c-9.75-.45-15.042-2.079-18.57-3.448c-4.666-1.813-8-3.979-11.5-7.479s-5.666-6.825-7.483-11.494c-1.369-3.521-3-8.813-3.444-18.563c-.479-10.542-.575-13.708-.575-40.413s.096-29.854.575-40.396c.446-9.75 2.075-15.042 3.444-18.567c1.813-4.667 3.983-8 7.484-11.5s6.833-5.667 11.5-7.483c3.525-1.375 8.819-3 18.569-3.448c9.225-.417 12.8-.542 31.437-.563zm62.351 16.604c-6.625 0-12 5.37-12 11.996c0 6.625 5.375 12 12 12s12-5.375 12-12s-5.375-12-12-12zm-53.38 14.021c-28.36 0-51.354 22.994-51.354 51.355s22.994 51.344 51.354 51.344c28.361 0 51.347-22.983 51.347-51.344c0-28.36-22.988-51.355-51.349-51.355zm0 18.021c18.409 0 33.334 14.923 33.334 33.334c0 18.409-14.925 33.334-33.334 33.334s-33.333-14.925-33.333-33.334c0-18.411 14.923-33.334 33.333-33.334"/><defs><radialGradient id="skillIconsInstagram0" cx="0" cy="0" r="1" gradientTransform="matrix(0 -253.715 235.975 0 68 275.717)" gradientUnits="userSpaceOnUse"><stop stop-color="#fd5"/><stop offset=".1" stop-color="#fd5"/><stop offset=".5" stop-color="#ff543e"/><stop offset="1" stop-color="#c837ab"/></radialGradient><radialGradient id="skillIconsInstagram1" cx="0" cy="0" r="1" gradientTransform="matrix(22.25952 111.2061 -458.39518 91.75449 -42.881 18.441)" gradientUnits="userSpaceOnUse"><stop stop-color="#3771c8"/><stop offset=".128" stop-color="#3771c8"/><stop offset="1" stop-color="#60f" stop-opacity="0"/></radialGradient></defs></g></svg>
                        <?php elseif ($name === "LinkedIn"): ?>
                            <svg class="not-real-hide sm:w-10 sm:h-10 w-8 h-8" viewBox="0 0 128 128"><path fill="#fff" d="M116 3H12a8.91 8.91 0 0 0-9 8.8v104.42a8.91 8.91 0 0 0 9 8.78h104a8.93 8.93 0 0 0 9-8.81V11.77A8.93 8.93 0 0 0 116 3M39.17 107H21.06V48.73h18.11zm-9-66.21a10.5 10.5 0 1 1 10.49-10.5a10.5 10.5 0 0 1-10.54 10.48zM107 107H88.89V78.65c0-6.75-.12-15.44-9.41-15.44s-10.87 7.36-10.87 15V107H50.53V48.73h17.36v8h.24c2.42-4.58 8.32-9.41 17.13-9.41C103.6 47.28 107 59.35 107 75z"/></svg>
                            <svg class="real-is-hide sm:w-10 sm:h-10 w-8 h-8" viewBox="0 0 128 128"><path fill="#0076b2" d="M116 3H12a8.91 8.91 0 0 0-9 8.8v104.42a8.91 8.91 0 0 0 9 8.78h104a8.93 8.93 0 0 0 9-8.81V11.77A8.93 8.93 0 0 0 116 3"/><path fill="#fff" d="M21.06 48.73h18.11V107H21.06zm9.06-29a10.5 10.5 0 1 1-10.5 10.49a10.5 10.5 0 0 1 10.5-10.49m20.41 29h17.36v8h.24c2.42-4.58 8.32-9.41 17.13-9.41C103.6 47.28 107 59.35 107 75v32H88.89V78.65c0-6.75-.12-15.44-9.41-15.44s-10.87 7.36-10.87 15V107H50.53z"/></svg>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Allen Care App badges -->
        <div>
            <h2 class="sm:text-[22px] text-[18px] font-[600] mt-3 sm:mt-5" style="color:#B4D7FF">Allen Care App</h2>
            <div class="flex items-center mt-3 gap-1">
                <?php if ($androidAppLink && $androidBadge): ?>
                <a href="<?= htmlspecialchars($androidAppLink) ?>" download><img class="w-[140px]" src="<?= htmlspecialchars($androidBadge) ?>" alt="Download Android app"></a>
                <?php endif; ?>
                <?php if ($iosAppLink && $iosBadge): ?>
                <a href="<?= htmlspecialchars($iosAppLink) ?>" target="_blank"><img class="w-[140px]" src="<?= htmlspecialchars($iosBadge) ?>" alt="Download iOS app"></a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="md:mt-5 mt-3">
            <h2 class="md:text-[22px] text-[18px] font-[600]" style="color:#B4D7FF">Newsletter</h2>
            <div class="md:mt-5 mt-2">
                <form id="newsForm" action="">
                    <div>
                        <div class="flex gap-2">
                            <input type="text" class="sm:p-3 p-2 px-3 border-[1px] outline-none rounded-[10px] w-[60%] sm:w-[65%] text-[12px] sm:text-[16px]" placeholder="<?= htmlspecialchars($newsletterPlaceholder) ?>" id="news-email" required />
                            <button type="submit" id="newsSubmit" class="bg-red-500 text-white sm:p-3 p-2 sm:px-4 rounded-[10px] transition-all hover:bg-red-600 text-[12px] sm:text-[16px]"><?= htmlspecialchars($newsletterButton) ?></button>
                        </div>
                        <span id="news-email-error" class="text-red-500 text-sm mt-1 hidden">Please enter a valid email address.</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</footer>

<!-- Copyright -->
<?php if (!empty($copyrightText)): ?>
<div class="bg-gray-200 p-3 text-center">
    <p class="text-gray-500 sm:text-[14px] text-[11px]"><?= htmlspecialchars($copyrightText) ?></p>
</div>
<?php endif; ?>

<!-- WhatsApp floating button -->
<?php if (!empty($whatsappNumber)): ?>
<div>
    <a href="https://api.whatsapp.com/send?phone=<?= htmlspecialchars(preg_replace('/\D+/', '', $whatsappNumber)) ?>" class="whatsapp-button" target="_blank" rel="noopener">
        <img src="https://i.ibb.co/VgSspjY/whatsapp-button.png" alt="WhatsApp">
    </a>
</div>
<?php endif; ?>

<!-- Newsletter subscription -->
<?php include __DIR__ . '/newsletter-script.php'; ?>

<!-- Footer submenu toggle (Important Links: Media & Events, Information) -->
<script>
    document.querySelectorAll('footer .toggle-submenu').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var parentLi = button.closest('li');
            var submenu = parentLi ? parentLi.querySelector('.submenu') : null;
            if (!submenu) {
                return;
            }
            var isHidden = submenu.classList.toggle('hidden');
            button.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
            var caret = button.querySelector('.footer-submenu-caret');
            if (caret) {
                caret.textContent = isHidden ? '▼' : '▲';
            }
        });
    });
</script>