<?php
require_once __DIR__ . '/includes/cms-page-helpers.php';
require_once __DIR__ . '/includes/api-adapters.php';

$facilities_page = cmsFetchGhaziabadPage('facilities-page-ghaziabad');
$top_data = cmsFacilitiesTopBlock(cmsPageSection($facilities_page, 1));
$plc_data = cmsLegacyTwoColumnBlock(cmsPageSection($facilities_page, 2));
$wcf_data = cmsFacilitiesCardsLegacy(cmsPageSection($facilities_page, 3));
$wcf_two_data = cmsFacilitiesCardsLegacy(cmsPageSection($facilities_page, 4));
$three_data = cmsFacilitiesCardsLegacy(cmsPageSection($facilities_page, 5));

$top_row = cmsLegacyDataRow($top_data);
$plc_row = cmsLegacyDataRow($plc_data);
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/head.php" ?>
    <link rel="canonical" href="https://allenhouseghaziabad.com/facilities" />
    <title>Top Educational Facilities | Allenhouse School, Ghaziabad</title>
    <meta name="description" content="Explore top educational facilities at Allenhouse School, Ghaziabad - smart classrooms, modern labs, and more.">
</head>
<style>
    .ql-editor {
        height: auto !important;
        padding: 0 !important;
        white-space: normal !important;
        line-height: 26px;
        overflow: hidden;
    }
</style>

<body>

    <?php include "includes/header.php" ?>

    <div class="main relative  sm:mb-[40px] ">
        <!-- Start -->
        <div class="bg-center flex items-center text-left h-[300px] comman-banner">
            <div>
                <h2
                    class="text-[32px] sm:hidden block font-[700] text-white text-left mb-5 sm:mb-8 hr-line relative leading-9 pl-4 ">
                    Facilities
                </h2>
            </div>

            <div class="md:w-[100%]">
                <h2
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    Facilities
                    <span class="sm:hidden"></span>
                </h2>
            </div>
        </div>
        <div class="flex m-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center text-xs sm:text-sm font-medium text-blue-main">
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
                        <p class="ms-1 text-xs sm:text-sm font-medium text-blue-main">Academics
                        </p>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-blue-main mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4"></path>
                        </svg>
                        <a href="facilities" class="ms-1 text-xs sm:text-sm font-medium text-blue-main">Facilities</a>
                    </div>
                </li>
            </ol>
        </div>
        <!-- End -->
        <div class="text-center">
            <div class=" mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
                <div class=" mt-0 relative">
                    <div class="absolute top-[-100px] -z-50">
                    </div>
                    <div class="p-3 pt-7 sm:pt-10 text-center sm:block hidden">
                    </div>
                </div>
                <div>
                    <div class="ql-snow mt-3">
                        <div class="ql-editor">
                            <?= $top_row['detail'] ?? '' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3 mx-3 mt-3 mb-10">
            <div class="bg-center mt-8">
                <div class="relative w-full  bg-white rounded glide-01 opne-hide-circle">
                    <!-- Slides -->
                    <div class="overflow-hidden" data-glide-el="track">
                        <ul
                            class="relative w-full overflow-hidden p-0 whitespace-no-wrap flex flex-no-wrap [backface-visibility: hidden] [transform-style: preserve-3d] [touch-action: pan-Y] [will-change: transform]">
                            <?php
                            foreach ($top_row['medias'] as $media) {
                            ?>
                                <li class="relative">
                                    <img src="<?php echo $media['urls'] ?>" class="w-[100%]" alt="">
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- Controls -->
                    <div class="absolute left-0 xl:flex hidden items-center justify-between w-full h-0 px-4 top-1/2 hide-circle"
                        data-glide-el="controls">
                        <button
                            class="inline-flex items-center justify-center relative left-[-66px] hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir="<" aria-label="prev slide">
                            <i class="fa-solid fa-angle-left"></i>
                        </button>
                        <button
                            class="inline-flex items-center justify-center relative right-[-66px] hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir=">" aria-label="next slide">
                            <i class="fa-solid fa-angle-right"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="ql-snow">
                        <div class="ql-editor">
                            <?php echo $top_row['description'] ?? '' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8  relative">
            <div class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">

                <div class="text-center mx-3">
                    <h2 class="text-[28px] font-[700] leading-7  text-blue-main">Personalised<span class="sm:hidden">
                            <br></span> Learning Centre
                    </h2>
                    <div class="ql-snow mt-3">
                        <div class="ql-editor">
                            <?php echo $plc_row['details'] ?? '' ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 ab-cr-bg sm:py-10">
                <div
                    class="relative sm:flex 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
                    <?php if (!empty($plc_row['media']['urls'])): ?>
                    <div class="mx-3 sm:w-[50%]">
                        <img src="<?php echo htmlspecialchars($plc_row['media']['urls']); ?>" alt="Personalised Learning Centre">
                    </div>
                    <?php endif; ?>
                    <div class="bg-blue-main sm:mt-0 mt-[-100px] sm:w-[50%] ">
                        <style>
                            .whitee p {
                                color: #fff !important;
                            }
                        </style>
                        <div class="2xl:pt-[120px] lg:pt-0 md:pt-1 pt-[120px] mx-3 pb-5 whitee">
                            <div class="ql-snow">
                                <div class="ql-editor">
                                    <?php echo $plc_row['description'] ?? '' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8  relative ">
            <div class=" text-center mx-3">
                <h2 class="text-[28px] font-[700] leading-7  text-blue-main text-center " id="facilities">Our
                    World-Class Facilities </h2>
                <p class="text-gray-500 text-[16px] mt-2 ">Allenhouse believes that holistic education, when complemented
                    with the right resources,<br>
                    gives students the wings to fly. We provide students with the best resources to explore, learn, and
                    excel.
                </p>
            </div>

            <div class="mt-5 mx-3 sm:mt-10 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
                <div class="grid sm:grid-cols-4 grid-cols-1 gap-3 relative">
                     <?php
                    foreach ($wcf_data['data'] ?? [] as $data) {
                        $title = $data['titles'];
                        $description = $data['description'];
                        $imageUrl = $data['media']['urls'];
                    ?>
                        <div class="flex sm:mt-0 mt-3 justify-center items-stretch">
                            <div class="sm:w-auto sm:mx-auto">
                                <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($title) ?>" class="w-[100%]">

                                <div class="facility-text text-gray-600 text-[16px] mt-2 readmore-text" data-readmore-color="text-blue-800">
                                     <strong><?php echo  htmlspecialchars($title) ?? "" ?></strong> : <?= htmlspecialchars($description) ?>
                                </div>
                                <!-- <button class="readmore-btn text-blue-800 mt-2 font-medium focus:outline-none">Read More</button> -->
                            </div>
                        </div>
                    <?php } ?>
                    <!-- <div class="flex">
                        <div class="sm:w-[100%]   sm:mx-auto ">
                            <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/wQ4nWPuRDPVQzYqMoGh8syV2quguJdyT25fUQwRP.png"
                                alt="" class="w-[100%]">
                            <p class="text-gray-500 text-[16px] mt-2 facility-text" data-readmore-color="text-blue-800"><strong>Computer Lab:</strong> In a world where
                                computers have found a place in every setting, we prepare our students with the right
                                skills. Our fully equipped computer lab helps develop essential IT skills. It covers it
                                all– be it basic digital literacy or advanced programming. </p>
                        </div>
                    </div>
                    <div class=" sm:mt-0 mt-3">
                        <div class="sm:w-auto  mx-auto ">
                            <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/x17pM1tiaCIzkjUpKP62ek9lfVIoXsZ3AtrKkKRl.png"
                                alt="" class="w-[100%]">
                            <p class="text-gray-500 text-[16px] mt-2 facility-text" data-readmore-color="text-blue-800"><strong>Science Laboratory:</strong> Science
                                without hands-on experiments is a lost opportunity. Our laboratories for Biology,
                                Chemistry, and Physics provide students with freedom and opportunities to experiment,
                                explore, and innovate..</p>
                        </div>

                    </div>
                    <div class=" sm:mt-0 mt-3">

                        <div class="sm:w-auto   sm:mx-auto ">
                            <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/uDO7pL24ybgdHVOFHEhqxEJHE8VHqDlvByzIceCq.png"
                                alt="" class="w-[100%]">
                            <p class="text-gray-500 text-[16px] mt-2 facility-text" data-readmore-color="text-blue-800"><strong>Library:</strong> For book lovers, our
                                library is a haven. It has the most comfortable seating, perfect lighting, and a
                                welcoming ambience. We ensure that the setting encourages every student to immerse
                                themselves in literature, research, and self-learning.</p>
                        </div>
                    </div>
                    <div class=" sm:mt-0 mt-3">
                        <div class="sm:w-auto  sm:mx-auto ">
                            <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/IWeoSIur3iJxHR6EsFAjNwdWK3R2t9kG0QxyWVCq.png"
                                alt="" class="w-[100%]">
                            <p class="text-gray-500 text-[16px] mt-2 facility-text" data-readmore-color="text-blue-800"><strong>Auditorium:</strong> To face an audience
                                is a confidence-boosting experience, both for kids and adults. Our auditorium is where
                                all assemblies, performances, and community events are held. It brings everyone
                                together. It is airy, properly ventilated and well-designed. </p>
                        </div>

                    </div> -->
                </div>
            </div>

            <div class="mt-5 bg-blue-main sm:py-12 sm:pt-[65px]">
                <div
                    class="grid sm:grid-cols-4 grid-cols-1 gap-3 relative mx-3  sm:top-0 top-[-80px] sm:mt-0 mt-[120px] 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
                    <?php
                    foreach ($wcf_two_data['data'] ?? [] as $data) {
                        $title = $data['titles'];
                        $description = $data['description'];
                        $imageUrl = $data['media']['urls'];
                    ?>
                        <div class="flex sm:mt-0 mt-3 justify-center items-stretch">
                            <div class="sm:w-auto sm:mx-auto">
                                <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= strip_tags($title) ?>" class="w-[100%] h-[180px] object-cover">

                                <div class="facility-text text-white text-[16px] mt-2 readmore-text" data-readmore-color="text-blue-800">
                                     <?= htmlspecialchars($description) ?>
                                </div>
                                <!-- <button class="readmore-btn text-blue-800 mt-2 font-medium focus:outline-none">Read More</button> -->
                            </div>
                        </div>
                    <?php } ?>
                    <!-- <div class="flex ">
                         <div class="sm:w-auto   sm:mx-auto ">
                            <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/Ynbh4YhbDJbwA6rly2l4eZ9QxjHBa0s81TxLIkiA.png"
                                alt="" class="w-[100%]">
                            <p class="text-white text-[16px] mt-2 facility-text" data-readmore-color="text-white"><strong>Playground: </strong>Our playground is a
                                place to unwind, rejuvenate, and recognise new skills. It is spacious and secure to
                                encourage outdoor games and physical exercises. It is a place where students find team
                                spirit and become stronger, both physically and psychologically. </p>
                        </div>
                    </div>
                    
                     </div> -->
                </div>
            </div>

            <div class="mt-5 sm:mt-12 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto mx-3  px-3 ">
                <div class="grid sm:grid-cols-4 grid-cols-1 gap-3 relative  sm:top-0 top-[-80px] sm:mt-0 mt-[120px]">
                    <?php
                    foreach ($three_data['data'] ?? [] as $data) {
                        $title = $data['titles'];
                        $description = $data['description'];
                        $imageUrl = $data['media']['urls'];
                    ?>
                        <div class="flex sm:mt-0 mt-3 justify-center items-stretch">
                            <div class="sm:w-auto"></div>
                            <div class="sm:w-auto sm:mx-auto ">
                                <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($title) ?>" class="w-[100%]">
                                <div class="facility-text text-gray-600 text-[16px] mt-2" data-readmore-color="text-blue-800">
                                    <?= htmlspecialchars($description) ?></div>
                            </div>
                        </div>
                    <?php } ?>
                 </div>
            </div>

        </div>
    </div>

    </div>
    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

    <script>
        var glide01 = new Glide('.glide-01', {
            type: 'carousel',
            focusAt: 'center',
            perView: 3,
            autoplay: 3500,
            animationDuration: 700,
            gap: 24,
            classes: {
                activeNav: '[&>*]:bg-slate-700',
            },
            breakpoints: {
                1024: {
                    perView: 2
                },
                640: {
                    perView: 1
                }
            },
        });
        glide01.mount();
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const facilityTexts = document.querySelectorAll(".facility-text");

            facilityTexts.forEach(p => {
                const strongTag = p.querySelector("strong");
                const strongText = strongTag?.outerHTML || "";
                const fullTextOnly = p.innerText.replace(strongTag?.innerText, "").trim();
                const fullTextWords = fullTextOnly.split(" ");
                const readMoreColor = p.getAttribute("data-readmore-color") || "text-blue-600";

                if (fullTextWords.length > 12) {
                    const shortText = fullTextWords.slice(0, 12).join(" ") + "...";

                    const toggleContainer = document.createElement("div");
                    toggleContainer.className = "text-left text-[15px] mt-2 font-[600]";

                    const toggleBtn = document.createElement("span");
                    toggleBtn.textContent = "Read More...";
                    toggleBtn.className = `${readMoreColor}  cursor-pointer block`;

                    let expanded = false;

                    toggleBtn.addEventListener("click", () => {
                        expanded = !expanded;
                        if (expanded) {
                            p.innerHTML = `${strongText} ${fullTextOnly}`;
                            toggleBtn.textContent = "Read Less";
                        } else {
                            p.innerHTML = `${strongText} ${shortText}`;
                            toggleBtn.textContent = "Read More...";
                        }
                        p.appendChild(toggleContainer);
                    });

                    p.innerHTML = `${strongText} ${shortText}`;
                    toggleContainer.appendChild(toggleBtn);
                    p.appendChild(toggleContainer);
                }
            });
        });
    </script>
</body>

</html>