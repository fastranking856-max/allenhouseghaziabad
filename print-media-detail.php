<?php
require_once __DIR__ . '/includes/api-adapters.php';
$galleryId = $_GET['id'] ?? null;
$gallery = cmsFindGalleryById($galleryId);

if (!$gallery) {
    echo "Article not found.";
    exit;
}

$galleryTitle = $gallery['title'] ?? 'Print Media';
$galleryDescription = $gallery['description'] ?? '';

// Media extraction logic
$medias = [];
if (!empty($gallery['medias']) && is_array($gallery['medias'])) {
    $medias = $gallery['medias'];
} elseif (!empty($gallery['media'])) {
    $medias = [$gallery['media']];
}

// Check if first media is a PDF
$firstUrl = $medias[0]['urls'] ?? '';
$isPdf = strtolower(pathinfo($firstUrl, PATHINFO_EXTENSION)) === 'pdf';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($galleryTitle) ?> | AllenHouse Ghaziabad</title>
    <?php include "includes/head.php" ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <style>
        .pdf-container {
            width: 100%;
            height: 850px;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        .prose img { border-radius: 8px; margin: 1rem 0; }
    </style>
</head>

<body>
    <?php include "includes/header.php" ?>

    <div class="main relative sm:top-[20px] mb-[40px] sm:mb-[120px]">
        <div class="bg-center flex items-center text-center h-[300px] job-opening-bg common-banner">
            <div class="w-full">
                <h1 class="text-[28px] sm:text-[32px] font-[700] text-white text-left pl-4 sm:ml-[7rem] hr-line relative leading-tight uppercase">
                    <?= htmlspecialchars($galleryTitle) ?>
                </h1>
            </div>
        </div>

        <div class="sm:w-full sm:max-w-screen-xl sm:mx-auto mx-3 mt-10 mb-10">
            <nav class="mb-8">
                <a href="print-media" class="text-blue-main font-bold inline-flex items-center gap-2 group">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Press Room
                </a>
            </nav>

            <?php if (!empty($galleryDescription)): ?>
            <div class="bg-white p-6 sm:p-10 rounded-2xl border border-gray-100 mb-10">
                <h2 class="text-xl font-bold mb-4 text-blue-900 border-b pb-2">Article Highlights</h2>
                <div class="text-gray-600 prose max-w-none leading-relaxed">
                    <?= $galleryDescription ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="media-container">
                <?php if ($isPdf): ?>
                    <div class="flex flex-col items-center">
                        <div class="pdf-container mb-6">
                            <iframe src="<?= htmlspecialchars($firstUrl) ?>" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                        <a href="<?= htmlspecialchars($firstUrl) ?>" download class="bg-blue-main text-white px-10 py-4 rounded-xl font-bold hover:bg-blue-800 transition-all flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download Full Press Clip
                        </a>
                    </div>
                <?php else: ?>
                    <div id="photoGallery" class="grid gap-4 grid-flow-row-dense xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-3 grid-cols-2">
                        <?php foreach ($medias as $media): 
                            $url = $media['urls'] ?? $media['media_url'] ?? '';
                            if(!$url) continue;
                        ?>
                            <a href="<?= htmlspecialchars($url) ?>" data-fancybox="gallery" class="block overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <img src="<?= htmlspecialchars($url) ?>" 
                                     alt="Press Clipping" 
                                     class="h-[300px] w-full object-cover hover:scale-105 transition-transform duration-500">
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox]", {
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: ["zoomIn", "zoomOut", "rotateCCW", "rotateCW"],
                    right: ["slideshow", "thumbs", "close"],
                },
            },
            loop: false,
            protect: true
        });
    </script>
</body>
</html>