<?php
require_once __DIR__ . '/includes/cms-page-helpers.php';
require_once __DIR__ . '/includes/api-adapters.php';
require_once __DIR__ . '/includes/testimonial-card-ui.php';
$testimonials_page = cmsFetchGhaziabadPage('testimonials-page-ghaziabad');
$i_data = cmsTestimonialItemsLegacy(cmsPageSection($testimonials_page, 1));
$v_data = cmsEmptyResponse();
testimonialCardStyles();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/testimonial" />
    <title>AllenHouse Ghaziabad| Testimonials</title>
    <?php include "includes/head.php" ?>

</head>

<body>
    <style>
    ul.glide__slides {
        display: flex;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    ul.tabs {
        padding: 0px;
        list-style: none;
    }

    ul.tabs li {
        background: none;
        color: #053B7A;
        display: inline-block;
        padding: 10px 15px;
        cursor: pointer;

        background: #ededed;
    }

    @media (max-width: 640px) {
        ul.tabs li {
            font-size: 14px;
            padding: 8px;
            background: #ededed;
        }

    }

    ul.tabs li.current {
        background: #053B7A;
        color: #fff;
    }

    .tab-content {
        display: none;
        padding-top: 15px;
    }

    .tab-content.current {
        display: block !important;
    }

    .img-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: transparent;
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .popup-content {
        background: rgba(0, 0, 0, 0.85);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        animation: animatepopup 0.3s ease-in-out forwards;

        /* Desktop default */
        width: 50vw;
        height: 90vh;
    }

    .popup-content img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        opacity: 0;
        transform: translateY(-100px);
        animation: animatepopup 0.3s ease-in-out forwards;
        border-radius: 10px;
    }

    /* Responsive for mobile screens */
    @media (max-width: 640px) {
        .popup-content {
            width: 100vw;
            height: 50vh;
        }
    }

    .close-btn {
        width: 35px;
        height: 30px;
        display: flex;
        justify-content: center;
        flex-direction: column;
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
    }

    .close-btn .bar {
        height: 4px;
        background: #fff;
        border-radius: 2px;
    }

    .close-btn .bar:nth-child(1) {
        transform: rotate(45deg);
    }

    .close-btn .bar:nth-child(2) {
        transform: translateY(-4px) rotate(-45deg);
    }

    .img-popup.opened {
        display: flex;
    }

    @keyframes animatepopup {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .mySwiper .swiper-pagination-bullet {
        background-color: #053B7A !important;
    }

    .mySwiper .swiper-pagination-bullet-active {
        background-color: #002A5B !important;
    }

    @media (max-width: 640px) {
        .swiper {
            box-shadow: none !important;
        }

        .swiper-slide {
            box-shadow: none !important;
        }

        .swiper-wrapper {
            margin: 0 !important;
        }
    }

    </style>

    <?php include "includes/header.php" ?>

    <div class="main relative mb-[40px] sm:mb-[120px]">
        <!-- Start -->
        <div class="bg-center flex items-center text-left h-[300px] comman-banner">
            <div>
                <h2
                    class="text-[32px] sm:hidden block font-[700] text-white text-left mb-5 sm:mb-8 hr-line relative leading-9 pl-4 ">
                    Testimonials
                </h2>
            </div>

            <div class="md:w-[100%]">
                <h2
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    Testimonials
                </h2>
            </div>

        </div>

        <div class="flex m-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center sm:text-sm text-xs font-medium text-blue-main">
                        Home
                    </a>
                </li>

                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-blue-main mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4"></path>
                        </svg>
                        <a href="testimonial" class="ms-1 sm:text-sm text-xs font-medium text-blue-main"> Testimonials
                        </a>
                    </div>
                </li>
            </ol>
        </div>
        <!-- End -->

        <div class="relative sm:mt-20 mt-10" id="testimonials">

            <div class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 mx-3 px-3">
                
                <div class="relative  sm:pt-5 pt-2 ">

                    <ul class="tabs 2xl:ml-[432px] md:ml-[298px]  ml-[6px]">
                        <li class="tab-link current" data-tab="tab-1">Image Testimonials</li>
                        <li class="tab-link" data-tab="tab-2">Video Testimonials</li>
                    </ul>

                    <div id="tab-1" class="tab-content current Testimonials">
                        <div class=" overflow-hidden mt-1" data-glide-el="track">
                            <ul class="glide__slides">
                                <?php foreach (($i_data['data'] ?? []) as $row) {
                                    renderTestimonialCard(is_array($row) ? $row : []);
                                } ?>
                            </ul>
                        </div>
                        <div class="glide__arrows" data-glide-el="controls">
                            <button
                                class="glide__arrow glide__arrow--left hover:bg-red-500 absolute left-2 top-[63%] -translate-y-1/2 bg-white border border-black text-black w-10 h-10 flex items-center justify-center rounded-full shadow-md"
                                data-glide-dir="<">
                                &#10094;
                            </button>
                            <button
                                class="glide__arrow glide__arrow--right absolute hover:bg-red-500 right-2 top-[63%] -translate-y-1/2 bg-white border border-black text-black w-10 h-10 flex items-center justify-center rounded-full shadow-md"
                                data-glide-dir=">">
                                &#10095;
                            </button>
                        </div>
                    </div>

                    <div id="tab-2" class="tab-content Testimonials2">
                        <?php if (!empty($v_data['data'])) { ?>
                        <div class="overflow-hidden mt-1" data-glide-el="track">
                            <ul
                                class="mt-1 relative w-full overflow-hidden p-0 pb-5 whitespace-no-wrap flex gap-3 flex-no-wrap">
                                <?php foreach ($v_data['data'] as $row) { 
                $url = $row["url"];
                // Check if YouTube link
                if (strpos($url, "youtube.com") !== false || strpos($url, "youtu.be") !== false) {
                    // Convert shorts/watch to embeddable format
                    $embedUrl = preg_replace(
                        ['/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/', '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/'],
                        ['youtube.com/embed/$1', 'youtube.com/embed/$1'],
                        $url
                    );
            ?>
                                <li class="w-[300px]">
                                    <iframe class="w-full h-64 rounded-2xl shadow-lg bg-blue-main sm:p-5 p-2"
                                        src="<?php echo $embedUrl; ?>" frameborder="0" allowfullscreen>
                                    </iframe>
                                </li>
                                <?php } else { ?>
                                <li class="w-[300px]">
                                    <video class="w-full rounded-2xl shadow-lg bg-blue-main sm:p-5 p-2" controls>
                                        <source src="<?php echo $url ?>" type="video/mp4">
                                    </video>
                                </li>
                                <?php } } ?>
                            </ul>
                        </div>
                        <div class="glide__arrows" data-glide-el="controls">
                            <button
                                class="glide__arrow glide__arrow--left hover:bg-red-500 absolute left-2 top-[63%] -translate-y-1/2 bg-white border border-black text-black w-10 h-10 flex items-center justify-center rounded-full shadow-md"
                                data-glide-dir="<">
                                &#10094;
                            </button>
                            <button
                                class="glide__arrow glide__arrow--right absolute hover:bg-red-500 right-2 top-[63%] -translate-y-1/2 bg-white border border-black text-black w-10 h-10 flex items-center justify-center rounded-full shadow-md"
                                data-glide-dir=">">
                                &#10095;
                            </button>
                        </div>
                        <?php } else { ?>
                        <p class="text-center text-gray-500 py-10">No video testimonials available.</p>
                        <?php } ?>
</div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

    <script>
    // Tab
    let glide1Initialized = false;
    let glide2Initialized = false;

    function initGlide(selector, perViewCount) {
        return new Glide(selector, {
            type: 'carousel',
            focusAt: 1,
            perView: perViewCount,
            autoplay: 3500,
            animationDuration: 700,
            gap: 10,
            breakpoints: {
                1680: {
                    perView: perViewCount >= 4 ? 3 : 2
                },
                1024: {
                    perView: perViewCount >= 4 ? 2 : 2
                },
                820: {
                    perView: 1
                },
                640: {
                    perView: 1
                }
            }
        });
    }

    // Initialize Glide for the first tab (Testimonials - 2 items)
    const glide1 = initGlide('.Testimonials', 2);
    glide1.mount();
    glide1Initialized = true;

    // Tab switch logic
    document.querySelectorAll('.tab-link').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');

            // Update active tab
            document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('current'));
            this.classList.add('current');

            // Toggle tab content
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('current'));
            document.getElementById(tabId).classList.add('current');

            // Mount Glide2 only if there are video slides
            if (tabId === 'tab-2' && !glide2Initialized) {
                const videoSlides = document.querySelectorAll('.Testimonials2 li');
                if (videoSlides.length > 0) {
                    const glide2 = initGlide('.Testimonials2', 4); // Show 4 slides
                    glide2.mount();
                    glide2Initialized = true;
                }
            }
        });
    });
    </script>

    <?php testimonialCardScripts(); ?>

</body>

</html>