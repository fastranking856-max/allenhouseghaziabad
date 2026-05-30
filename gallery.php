<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AllenHouse Ghaziabad | Photo Gallery</title>

    <?php include "includes/head.php" ?>

    <style>
        .pdf-preview {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 280px;
            background: linear-gradient(135deg, #f9fafb, #eef2f7);
            text-align: center;
            padding: 20px;
            transition: 0.3s ease;
        }

        .pdf-preview:hover {
            background: linear-gradient(135deg, #eef2f7, #e5e7eb);
        }

        .pdf-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .pdf-label {
            font-weight: 700;
            color: #1f2937;
        }

        .pdf-hint {
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>

<body>

<?php include "includes/header.php" ?>

<?php
require_once __DIR__ . '/includes/api-adapters.php';
$galleryId = $_GET['id'] ?? null;
$gallery = cmsFindGalleryById($galleryId);

if (!$gallery) {
    echo "<div class='py-20 text-center'>Album not found.</div>";
    exit;
}

$galleryTitle = trim((string) (
    $gallery['gallery_title']
    ?? $gallery['title']
    ?? $gallery['achievementtitle']
    ?? $gallery['heading']
    ?? ''
));
if ($galleryTitle === '') {
    $galleryTitle = 'Photo Gallery';
}

$galleryDesc = trim((string) (
    $gallery['description']
    ?? $gallery['achivementdescription']
    ?? $gallery['content']
    ?? ''
));

// Process Media
$medias = [];

if (!empty($gallery['medias']) && is_array($gallery['medias'])) {
    $medias = $gallery['medias'];
} elseif (!empty($gallery['media'])) {
    $medias = [$gallery['media']];
}
?>

<div class="main relative mb-[40px] sm:mb-[120px]">

    <!-- Banner -->
    <div class="bg-center flex items-center text-center h-[300px] job-opening-bg common-banner">
        <div class="w-full">
            <h1 class="text-[28px] sm:text-[32px] font-[700] text-white text-left pl-4 sm:ml-[7rem] hr-line relative uppercase leading-tight">
                <?= htmlspecialchars($galleryTitle, ENT_QUOTES, 'UTF-8') ?>
            </h1>
        </div>
    </div>

    <div class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto mx-3 mt-10">

        <!-- Back -->
        <div class="mb-8">
            <a href="photo-gallery" class="text-blue-main font-bold flex items-center gap-2 hover:translate-x-[-5px] transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                </svg>
                Back to Albums
            </a>
        </div>

        <!-- Description -->
        <?php if ($galleryDesc !== ''): ?>
        <div class="mb-12 text-center">
            <div class="text-gray-500 max-w-3xl mx-auto italic prose max-w-none">
                <?= $galleryDesc ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Media Grid -->
        <div class="pb-20">

        <?php if (!empty($medias)): ?>

        <div id="photoGrid" class="grid gap-5 sm:gap-6 grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">

        <?php foreach ($medias as $media):

            $url = htmlspecialchars($media['urls'] ?? $media['media_url'] ?? '');
            if (empty($url)) continue;

            $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
            $is_pdf = $ext === 'pdf';
        ?>

        <div class="rounded-xl overflow-hidden shadow-sm bg-white border border-gray-200 hover:shadow-md transition">

            <?php if ($is_pdf): ?>

                <a href="<?= $url ?>" target="_blank" rel="noopener noreferrer" class="block h-full">
                    <div class="pdf-preview">
                        <div class="pdf-icon">📄</div>
                        <div class="pdf-label">PDF Document</div>
                        <div class="pdf-hint">Click to view / download</div>
                    </div>
                </a>

            <?php else: ?>

                <a href="<?= $url ?>" data-fancybox="gallery" class="block h-full">
                    <img src="<?= $url ?>"
                         alt="Gallery Image"
                         class="w-full h-[280px] object-cover"
                         loading="lazy">
                </a>

            <?php endif; ?>

        </div>

        <?php endforeach; ?>

        </div>

        <?php else: ?>

        <div class="text-center py-20 text-gray-600 text-xl bg-white rounded-2xl shadow-sm border border-gray-200">
            No media available for this album.
        </div>

        <?php endif; ?>

        </div>
    </div>
</div>

<?php include "includes/footer.php" ?>
<?php include "includes/foot.php" ?>

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