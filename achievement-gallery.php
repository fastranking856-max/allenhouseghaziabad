<?php
$page = "achievement-gallery";
require_once __DIR__ . '/includes/api-adapters.php';

$galleryId = $_GET['id'] ?? null;
$gallery = null;
$data = cmsFetchAchievementGalleries();

if ($galleryId !== null) {
    foreach ($data['data'] as $item) {
        if ((string) $item['id'] === (string) $galleryId) {
            $gallery = $item;
            break;
        }
    }
}

// Redirect if not found
if (!$gallery) {
    header("Location: awards.php");
    exit;
}

// Prepare media array (handles both 'medias' array and single 'media')
$medias = $gallery['medias'] ?? [];
if (empty($medias) && !empty($gallery['media'])) {
    $medias = [$gallery['media']];
}

// Fallback values
$page_title = htmlspecialchars($gallery['achievementtitle'] ?? 'Achievement Gallery');
$desc = trim((string) (
    $gallery['achivementdescription']
    ?? $gallery['description']
    ?? $gallery['content']
    ?? ''
));
$date_raw   = $gallery['achevementdate'] ?? $gallery['date'] ?? '';
$date       = $date_raw ? date("d M, Y", strtotime($date_raw)) : 'Date not available';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/achievement-gallery?id=<?= urlencode($galleryId) ?>" />
    <title><?= $page_title ?> | AllenHouse Ghaziabad</title>
    <?php include "includes/head.php"; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

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

<body class="bg-slate-50">

    <?php include "includes/header.php"; ?>

    <div class="main relative">

        <!-- Banner -->
        <div class="bg-center flex items-center text-left h-[300px] common-banner">
            <div class="w-full px-4 sm:px-8">
                <h1 class="text-[28px] sm:text-[36px] font-[800] text-white pl-4 sm:ml-[7rem] hr-line relative uppercase leading-tight">
                    <?= $page_title ?>
                </h1>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

            <!-- Breadcrumb & Back -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="/" class="text-xs font-medium text-blue-main uppercase tracking-tighter">Home</a>
                        </li>
                        <svg class="w-3 h-3 text-blue-main mx-1" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                        <li class="text-xs font-medium text-blue-main uppercase tracking-tighter">Achievements</li>
                        <svg class="w-3 h-3 text-blue-main mx-1" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                        <li class="text-xs font-medium text-blue-main uppercase tracking-tighter truncate max-w-[150px] sm:max-w-full">
                            <?= htmlspecialchars($page_title) ?>
                        </li>
                    </ol>
                </nav>

                <a href="javascript:history.back()" class="back-btn inline-flex items-center gap-2 text-blue-main font-bold text-xs uppercase tracking-widest transition-all hover:text-blue-900">
                    <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Achievements
                </a>
            </div>

            <!-- Description -->
            <?php if ($desc !== ''): ?>
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mb-12">
                <h2 class="text-blue-main text-lg font-black uppercase tracking-tight mb-5 flex items-center gap-3">
                    <span class="w-10 h-[3px] bg-orange-500"></span>
                    Details
                </h2>
                <div class="text-gray-700 leading-relaxed text-base prose max-w-none italic">
                    <?php
                        $clean = $desc;

                        // 1. If string starts/ends with literal double quotes from API, remove them
                        $clean = trim($clean, '"');

                        // 2. Remove the extra <span style=...> wrapper if it exists
                        $clean = preg_replace('/<span style="[^"]*">(.*?)<\/span>/s', '$1', $clean);

                        // 3. Output as HTML
                        echo $clean;
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Media Grid -->
            <?php if (!empty($medias)): ?>
            <div id="photoGallerys" class="grid gap-5 sm:gap-6 grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 mb-20">
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
                                     alt="<?= htmlspecialchars($page_title) ?> Image" 
                                     class="w-full h-full object-cover" 
                                     loading="lazy">
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-20 text-gray-600 text-xl bg-white rounded-2xl shadow-sm border border-gray-200">
                No media available for this achievement.
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>
    <?php include "includes/foot.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox]", {
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: ["zoomIn", "zoomOut", "toggle1to1", "rotateCCW", "rotateCW"],
                    right: ["slideshow", "thumbs", "close"],
                },
            },
            Images: {
                Panzoom: {
                    maxScale: 3
                },
            },
            Thumbs: {
                autoStart: true,
            },
            protect: true
        });
    </script>
</body>
</html>