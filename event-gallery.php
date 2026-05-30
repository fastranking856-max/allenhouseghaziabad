<?php
require_once __DIR__ . '/includes/api-adapters.php';
$galleryId = $_GET['id'] ?? null;
$gallery = cmsFindGalleryById($galleryId);

if (!$gallery) {
    echo "Event not found.";
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
    $galleryTitle = 'Event Gallery';
}

$galleryDesc = trim((string) (
    $gallery['description']
    ?? $gallery['achivementdescription']
    ?? $gallery['content']
    ?? ''
));

// Media extraction logic
$medias = [];

if (!empty($gallery['medias']) && is_array($gallery['medias'])) {
    $medias = $gallery['medias'];
} elseif (!empty($gallery['media'])) {
    $medias = [$gallery['media']];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($galleryTitle, ENT_QUOTES, 'UTF-8') ?> | AllenHouse Ghaziabad</title>

    <?php include "includes/head.php" ?>

    <style>
        .pdf-preview {
            height: 300px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            text-align: center;
            padding: 1.25rem;
        }
        .pdf-preview:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 20px -4px rgba(220,38,38,0.15);
            border-color: #f87171;
        }
        .pdf-icon {
            font-size: 4rem;
            color: #dc2626;
            margin-bottom: 0.75rem;
        }
        .pdf-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #991b1b;
        }
        .pdf-hint {
            margin-top: 0.75rem;
            font-size: 0.875rem;
            color: #6b7280;
        }
        .gallery-item-wrapper {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .gallery-item-wrapper:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<?php include "includes/header.php" ?>

<div class="main relative sm:top-[20px] mb-[40px] sm:mb-[120px]">

    <!-- Banner -->
    <div class="bg-center flex items-center text-center h-[300px] job-opening-bg common-banner">
        <div class="w-full">
            <h1 class="text-[28px] sm:text-[32px] font-[700] text-white text-left pl-4 sm:ml-[7rem] hr-line relative leading-tight uppercase">
                <?= htmlspecialchars($galleryTitle, ENT_QUOTES, 'UTF-8') ?>
            </h1>
        </div>
    </div>

    <div class="sm:w-full sm:max-w-screen-xl sm:mx-auto mx-3 mt-10 mb-10">

        <!-- Back -->
        <nav class="mb-8">
            <a href="event.php" class="text-blue-main font-bold inline-flex items-center gap-2 group hover:text-blue-800">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Jhansi Events
            </a>
        </nav>

        <!-- Description -->
        <?php if ($galleryDesc !== ''): ?>
        <div class="bg-white p-6 sm:p-10 rounded-2xl shadow-sm border border-gray-100 mb-12">
            <h2 class="text-2xl font-bold mb-4 text-blue-main border-b pb-2">Event Highlights</h2>
            <div class="text-gray-600 leading-relaxed text-lg prose max-w-none">
                <?= $galleryDesc ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- MEDIA GRID -->
        <div class="mt-10">

        <?php if (!empty($medias)): ?>

        <div id="photoGallerys" class="grid gap-5 sm:gap-6 grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">

        <?php foreach ($medias as $media):

            $url = htmlspecialchars($media['urls'] ?? $media['media_url'] ?? '');
            if (empty($url)) continue;

            $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
            $is_pdf = $ext === 'pdf';
        ?>

        <div class="gallery-item-wrapper rounded-xl overflow-hidden shadow-sm bg-white border border-gray-200">

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
                         alt="Event Media"
                         class="w-full h-full object-cover"
                         loading="lazy">
                </a>

            <?php endif; ?>

        </div>

        <?php endforeach; ?>

        </div>

        <?php else: ?>

        <div class="text-center py-20 text-gray-600 text-xl bg-white rounded-2xl shadow-sm border border-gray-200">
            No media available for this event.
        </div>

        <?php endif; ?>

        </div>

    </div>
</div>

<?php include "includes/footer.php" ?>
<?php include "includes/foot.php" ?>

</body>
</html>