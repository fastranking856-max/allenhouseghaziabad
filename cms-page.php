<?php

require_once __DIR__ . '/includes/environment.php';
require_once __DIR__ . '/proxy/config.php';
require_once __DIR__ . '/includes/cms-bootstrap.php';
require_once __DIR__ . '/includes/cms-page-helpers.php';

$path = trim((string) ($_GET['cms_path'] ?? $_GET['slug'] ?? ''), '/');
$slug = cmsResolveSlugFromPath($path);

if ($slug === null) {
    http_response_code(404);
    include __DIR__ . '/index.php';
    return;
}

$page = cmsFetchPageBySlug($slug);
if (($page['data'] ?? []) === []) {
    http_response_code(404);
    include __DIR__ . '/index.php';
    return;
}

$GLOBALS['cms_page_slug'] = $slug;
$GLOBALS['cms_skip_auto_sections'] = true;

$pageTitle = cmsPageTitle($page, 'Allenhouse Ghaziabad');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> | Allenhouse Ghaziabad</title>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="main relative mb-[40px] sm:mb-[120px]">
    <?php
    $pageData = $page['data'] ?? [];
    $sections = $pageData['sections'] ?? [];
    $galleryMeta = cmsPageGalleryMeta($slug);
    if ($galleryMeta !== null && (!is_array($sections) || $sections === [])) {
        cmsRenderDynamicGalleryPage($page, $galleryMeta);
    } else {
        cmsRenderPageSections($page, ['skip_hero' => false]);
    }
    ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
<?php include __DIR__ . '/includes/foot.php'; ?>
</body>
</html>
