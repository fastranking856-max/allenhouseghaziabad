<?php
require_once __DIR__ . '/proxy/config.php';
require_once __DIR__ . '/includes/environment.php';
require_once __DIR__ . '/includes/cms-page-helpers.php';
cmsPrefetchBlogPage();
require_once __DIR__ . '/includes/api-adapters.php';

$blogSlug = isset($_GET['slug']) ? trim(rawurldecode((string) $_GET['slug'])) : '';

if ($blogSlug === '') {
    http_response_code(404);
    header('Location: ' . site_base_url() . 'blog');
    exit;
}

$blogContext = cmsBlogDetailContext($blogSlug);
$matchedBlog = $blogContext['matched'];
$otherBlogs = $blogContext['others'];

if (!$matchedBlog) {
    http_response_code(404);
    header('Location: ' . site_base_url() . 'blog');
    exit;
}

$blogTitle = $matchedBlog['title'] ?? '';
$blogImage = $matchedBlog['media']['urls'] ?? '';
$blogContent = $matchedBlog['main_description'] ?? $matchedBlog['description'] ?? '';
$blogDate = $matchedBlog['date_formatted'] ?? '';
if ($blogDate === '' && !empty($matchedBlog['date'])) {
    $blogDate = date('d F Y', strtotime((string) $matchedBlog['date']));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/blog/<?= htmlspecialchars(rawurlencode($blogSlug), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title><?= htmlspecialchars($matchedBlog['meta_title'] ?? $blogTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($matchedBlog['meta_description'] ?? strip_tags($blogContent), ENT_QUOTES, 'UTF-8') ?>">
    <?php include 'includes/head.php' ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.0.2/css/glide.core.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.0.2/css/glide.theme.min.css">
    <style>
        .ql-editor {
            height: auto !important;
            padding: 0 !important;
            white-space: normal !important;
        }

        .ql-editor img {
            max-width: 100%;
            height: auto;
        }

        .capitalize {
            text-transform: capitalize;
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php' ?>

    <div class="main relative sm:top-[20px] mb:[40px] sm:mb-[120px] mx-0 sm:mx-2">
        <div class="mt-8 mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-4">

            <div class="text-blue-main text-[12px] tracking-[2px]">
                CATEGORY:
                <?php if (!empty($matchedBlog['categories']) && is_array($matchedBlog['categories'])): ?>
                    <?php $total = count($matchedBlog['categories']); ?>
                    <?php foreach ($matchedBlog['categories'] as $index => $category): ?>
                        <strong class="capitalize"><?= htmlspecialchars($category['categoryname'] ?? '', ENT_QUOTES, 'UTF-8') ?><?= $index < $total - 1 ? ' ,' : '' ?></strong>
                    <?php endforeach; ?>
                <?php else: ?>
                    <strong>General</strong>
                <?php endif; ?>
            </div>

            <h1 class="sm:text-[52px] md:leading-[55px] leading-[33px] mb-[10px] text-[28px] font-[600] sm:font-[700] text-blue-main">
                <?= htmlspecialchars($blogTitle, ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <div class="text-[#172B4D] mb-1">
                Published on <strong><?= htmlspecialchars($blogDate, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="text-[#172B4D] mb-1">
                Tags:
                <strong>
                    <?php if (!empty($matchedBlog['tag']) && is_array($matchedBlog['tag'])): ?>
                        <?= htmlspecialchars(implode(', ', array_column($matchedBlog['tag'], 'tagname')), ENT_QUOTES, 'UTF-8') ?>
                    <?php else: ?>
                        No tags
                    <?php endif; ?>
                </strong>
            </div>

            <?php if ($blogImage !== ''): ?>
                <img src="<?= htmlspecialchars($blogImage, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($blogTitle, ENT_QUOTES, 'UTF-8') ?>" class="w-full rounded-lg mb-4 object-cover" />
            <?php endif; ?>

            <div class="sm:flex sm:mt-10 mt-5 gap-5">
                <div class="sm:w-full">
                    <div class="ql-snow">
                        <div class="ql-editor">
                            <?= $blogContent ?>
                        </div>
                    </div>

                    <?php if (!empty($matchedBlog['blogdetails']) && is_array($matchedBlog['blogdetails'])): ?>
                        <?php foreach ($matchedBlog['blogdetails'] as $detail): ?>
                            <?php if (!is_array($detail)) {
                                continue;
                            } ?>
                            <h2 class="text-xl font-semibold mt-6 mb-2 text-blue-main"><?= htmlspecialchars($detail['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></h2>

                            <?php if (!empty($detail['media']['urls'])): ?>
                                <img src="<?= htmlspecialchars($detail['media']['urls'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($detail['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full rounded mb-4" />
                            <?php endif; ?>

                            <div class="ql-snow">
                                <div class="ql-editor">
                                    <?= $detail['description'] ?? '' ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-[#F9F9F9] mt-5 sm:mt-10">
        <h1 class="sm:text-[36px] text-[24px] block font-[700] text-[#053B7A] text-center hr-line relative leading-9 p-5 sm:p-10">
            Other Blogs
        </h1>
        <div class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-4">
            <?php if (empty($otherBlogs)): ?>
                <p class="text-center text-gray-500 pb-10">No other blog posts available at the moment.</p>
            <?php else: ?>
            <div class="relative w-full glide-11">
                <div class="overflow-hidden" data-glide-el="track">
                    <ul class="glide__slides">
                        <?php foreach ($otherBlogs as $data):
                            $title = $data['title'] ?? '';
                            $slug = $data['slug'] ?? '';
                            $dateFormatted = $data['date_formatted'] !== '' ? $data['date_formatted'] : date('d F Y', strtotime($data['date'] ?? ''));
                            $image = $data['media']['urls'] ?? '';
                            ?>
                        <li class="glide__slide p-4 pb-8">
                            <div class="max-w-sm bg-white border border-gray-200 rounded-[20px] shadow hover:shadow-lg transition-shadow duration-300">
                                <?php if ($image !== ''): ?>
                                    <img class="rounded-t-[20px] w-full h-48 object-cover" src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>" />
                                <?php endif; ?>
                                <div class="p-4">
                                    <div class="text-[14px] text-gray-600"><?= htmlspecialchars($dateFormatted, ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="font-[700] text-[18px] text-blue-main mt-2"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></div>
                                    <a href="<?= site_base_url() ?>blog/<?= htmlspecialchars(rawurlencode($slug), ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="button" class="text-blue-main text-[16px] flex items-center my-3 font-[600] gap-2 group">
                                            <span>View More</span>
                                            <svg width="16" height="16" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.65008 3.91156C9.9831 3.91156 10.2531 4.18153 10.2531 4.51456L10.2531 9.63112C10.2531 9.96414 9.9831 10.2341 9.65008 10.2341C9.31705 10.2341 9.04708 9.96414 9.04708 9.63112L9.04708 5.97031L4.10714 10.9103C3.87165 11.1457 3.48986 11.1457 3.25438 10.9103C3.01889 10.6748 3.01889 10.293 3.25438 10.0575L8.19432 5.11755L4.53352 5.11755C4.20049 5.11755 3.93052 4.84758 3.93052 4.51456C3.93052 4.18153 4.20049 3.91156 4.53352 3.91156L9.65008 3.91156Z" fill="#223B71" />
                                            </svg>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="absolute left-0 hidden sm:flex items-center justify-between w-full h-0 px-4 top-1/2 transform -translate-y-1/2" data-glide-el="controls">
                    <button type="button" class="inline-flex items-center relative sm:right-[66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 bg-white/20" data-glide-dir="<" aria-label="Previous">
                        <i class="fa-solid fa-angle-left"></i>
                    </button>
                    <button type="button" class="inline-flex items-center relative sm:left-[66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 bg-white/20" data-glide-dir=">" aria-label="Next">
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php' ?>
    <?php include 'includes/foot.php' ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Glide === 'undefined') {
                return;
            }
            var slides = document.querySelectorAll('.glide-11 .glide__slide, .glide-11 [data-glide-el="track"] li');
            if (slides.length === 0) {
                return;
            }
            new Glide('.glide-11', {
                type: 'carousel',
                focusAt: 'center',
                perView: 3,
                gap: 24,
                autoplay: 3500,
                animationDuration: 700,
                breakpoints: {
                    1024: { perView: 2 },
                    640: { perView: 1 }
                }
            }).mount();
        });
    </script>
</body>

</html>
