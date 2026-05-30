<?php
require_once __DIR__ . '/includes/cms-page-helpers.php';
require_once __DIR__ . '/includes/api-adapters.php';

$pedagogy_page = cmsFetchGhaziabadPage('pedagogy-page-ghaziabad');
$phc_data = cmsLegacyCarouselBlock(cmsPageSection($pedagogy_page, 2), 'Highlights of the Curriculum');
$ptl_data = cmsLegacyTwoColumnBlock(cmsPageSection($pedagogy_page, 3));
$pol_data = cmsLegacyTwoColumnBlock(cmsPageSection($pedagogy_page, 4));
$pbc_data = cmsLegacyCarouselBlock(cmsPageSection($pedagogy_page, 5), 'Brilliance Curriculum Model');
$pac_data = cmsLegacyCarouselBlock(cmsPageSection($pedagogy_page, 6), 'Achiever Curriculum Model');

$phc_row = cmsLegacyDataRow($phc_data);
$ptl_row = cmsLegacyDataRow($ptl_data);
$pol_row = cmsLegacyDataRow($pol_data);
$pbc_row = cmsLegacyDataRow($pbc_data);
$pac_row = cmsLegacyDataRow($pac_data);
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/head.php" ?>
    <title>Teaching Methodology | Allenhouse | Vasundhara, Ghaziabad</title>
    <meta name="description" content="Allenhouse Ghaziabad transforms teaching into an experience. Our teaching methodology turns lessons into life skills.">
</head>

<body>
    <style>
        .ql-editor {
            height: auto !important;
            padding: 0 !important;
            white-space: normal !important;
            line-height: 26px;
            overflow: hidden;
        }
    </style>

    <?php include "includes/header.php" ?>

    <div class="main relative  mb-[40px]">
        <!-- Start -->
        <div class="bg-center flex items-center text-left h-[300px] comman-banner">
            <div>
                <h2
                    class="text-[32px] sm:hidden block font-[700] text-white text-left mb-5 sm:mb-8 hr-line relative leading-9 pl-4 ">
                    Pedagogy
                </h2>
            </div>

            <div class="md:w-[100%]">
                <h2
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    Pedagogy
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
                        <a href="pedagogy" class="ms-1 text-xs sm:text-sm font-medium text-blue-main">Pedagogy</a>
                    </div>
                </li>
            </ol>
        </div>
        <!-- End -->
        <div class="text-center">
            <div
                class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3 mx-3 sm:pb-9 pb-5">
                <div class="p-3 pt-7 sm:pt-10 text-center">
                </div>
                <div class="text-left sm:text-center sm:mx-3">
                    <p class="text-gray-500 text-[16px]">Academic Programmes Offered:</p>
                    <h2 class="font-[700] text-blue-main text-[16px] leading-5">Pre-Primary, Primary, <span
                            class="sm:hidden"> <br></span> Secondary,
                        Sr. Secondary <span class="sm:hidden"> <br></span> School</h2>
                    <p class="mt-3 text-gray-500 text-[16px]">At Allenhouse Schools, we provide a thorough academic
                        journey, starting with our Pre-Primary programme and reaching out to our Sr. Secondary School.
                    </p>
                </div>
            </div>
        </div>

        <div class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3 mx-3 mt-3  mb-10">
            <div class="bg-center mt-5">
                <div class="relative w-full bg-white rounded glide-01 opne-hide-circle">
                    <!-- Slides -->
                    <div class="overflow-hidden" data-glide-el="track">
                        <ul
                            class="relative w-full overflow-hidden p-0 whitespace-no-wrap flex flex-no-wrap [backface-visibility: hidden] [transform-style: preserve-3d] [touch-action: pan-Y] [will-change: transform]">
                            <li class="relative">
                                <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/63oDHlLeviBXuBdlSWZYnL3p8XH5hONnNm2tErRm.png"
                                    class="w-[100%]">
                            </li>
                            <li class="relative">
                                <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/qyzuHDfqnwt2RkNyG7WMg6OGPkyyrjgYvTGORCRZ.png"
                                    class="w-[100%]">
                            </li>
                            <li class="relative">
                                <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/PPr9NqoKe28LEEqH7w9t7zgkbAEKIcUmR0I03Rip.png"
                                    class="w-[100%]">
                            </li>
                            <li class="relative">
                                <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/ySuFGKByoyrdFB4XT8MEAs8TJlNbYdzf0JHMh3MF.png"
                                    class="w-[100%]">
                            </li>
                            <li class="relative">
                                <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/kSOTGo4kFWv2HXeVhzcgBLB2sBkyYwfCT4fVAwul.png"
                                    class="w-[100%]">
                            </li>
                        </ul>
                    </div>
                    <!-- Controls -->
                    <div class="absolute left-0  xl:flex hidden items-center justify-between w-full h-0 px-4 top-1/2 hide-circle"
                        data-glide-el="controls">
                        <button
                            class="inline-flex items-center relative sm:left-[-66px]  justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir="<" aria-label="prev slide">
                            <i class="fa-solid fa-angle-left"></i>
                        </button>
                        <button
                            class="inline-flex items-center relative sm:right-[-66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir=">" aria-label="next slide">
                            <i class="fa-solid fa-angle-right"></i>
                        </button>
                    </div>

                </div>
                <div class="mt-4">
                    <p class="text-gray-600 text-[18px]">
                        Our Pre-Primary programme centres around childhood improvement, encouraging interest and central
                        abilities through intelligent and play-based learning.
                    </p>
                    <div id="moreContent" class="toggle-item hidden mt-4">

                        <p class="text-gray-600 text-[18px] mt-1">For the primary years, our educational programme
                            supports
                            academic calibre and all-encompassing development. </p>
                        <p class="text-gray-600 text-[18px] mt-1">Our Secondary programme is intended to extend subject
                            information, decisive reasoning and practical application, planning students for advanced
                            education and equipping them with crucial life skills.  </p>
                        <p class="text-gray-600 text-[18px] mt-1">Our Sr. Secondary School offers specialised streams in
                            Science, Commerce and Humanities, supported by experienced staff and advanced resources,
                            guaranteeing students are prepared for college and do well in life. </p>
                        <p class="text-gray-600 text-[18px] mt-1">Each phase of training at Allenhouse is created to
                            advance
                            intellectual, emotional, and social skills development, equipping students with the
                            abilities
                            and information expected to flourish in a rapidly changing world.</p>
                    </div>
                    <a id="readMoreBtne"
                        class="text-[15px] font-[600] text-blue-main cursor-pointer mt-2 inline-block">Read
                        More</a>
                </div>

                <div class="bg-center mt-8 relative">

                    <div class="text-center">
                        <h2 class="text-[28px] font-[700] leading-7 text-blue-main">Highlights of <span
                                class="sm:hidden"> <br></span>Curriculum
                        </h2>
                        <?php if ($phc_row['details'] !== ''): ?>
                        <div class="text-gray-500 text-[16px] mt-3"><?php echo $phc_row['details']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="relative w-full  bg-white rounded  glide-02 mt-5 opne-hide-circle">
                        <!-- Slides -->
                        <div class="overflow-hidden" data-glide-el="track">
                            <ul
                                class="relative w-full overflow-hidden p-0 whitespace-no-wrap flex flex-no-wrap [backface-visibility: hidden] [transform-style: preserve-3d] [touch-action: pan-Y] [will-change: transform]">
                                <?php
                                foreach ($phc_row['medias'] as $img_data) { ?>
                                    <li class="mb-4">
                                        <div class="text-center">
                                            <img src="<?php echo $img_data['urls']; ?>" alt=""
                                                width="100%" alt="" class="mb-2 mx-auto">
                                        </div>
                                    </li>
                                <?php } ?>

                            </ul>
                        </div>
                        <!-- Controls -->
                        <div class="absolute left-0  xl:flex hidden items-center justify-between w-full h-0 px-4 top-1/2 hide-circle"
                            data-glide-el="controls">
                            <button
                                class="inline-flex items-center justify-center relative sm:left-[-66px] hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                                data-glide-dir="<" aria-label="prev slide">
                                <i class="fa-solid fa-angle-left"></i>
                            </button>
                            <button
                                class="inline-flex items-center justify-center relative sm:right-[-66px] hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                                data-glide-dir=">" aria-label="next slide">
                                <i class="fa-solid fa-angle-right"></i>
                            </button>
                        </div>
                    </div>
                    <style>
                        ::marker {
                            color: rgb(156 163 175);
                            font-size: 18px;
                        }
                    </style>
                    <div class="mt-3 sm:mt-1">
                        <div class="ql-snow">
                            <div class="ql-editor">
                                <?php echo $phc_row['description']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TEACHING Methods -->
        <div class="mt-8 sm:mt-10">

            <div class="text-center mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
                <h2 class="text-[28px] font-[700] leading-7 text-blue-main">Teaching and <span class="sm:hidden">
                        <br></span>Learning</h2>
                <?php if ($ptl_row['details'] !== ''): ?>
                <div class="text-gray-500 text-[16px] mt-3"><?php echo $ptl_row['details']; ?></div>
                <?php endif; ?>
            </div>
            <div class="mt-5 relative ab-cr-bg sm:py-10">
                <div
                    class="relative  2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3 md:flex gap-4">
                    <?php if (!empty($ptl_row['media']['urls'])): ?>
                    <div class="mx-3 md:w-[50%]">
                        <img src="<?php echo htmlspecialchars($ptl_row['media']['urls']); ?>" alt="Teaching and Learning">
                    </div>
                    <?php endif; ?>
                    <div class="bg-blue-main sm:mt-0 mt-[-100px]  md:w-[50%]">
                        <div class="2xl:pt-[120px] lg:pt-1 md:pt-1 pt-[120px] mx-3 pb-5">
                            <div class="ql-snow">
                                <div class="ql-editor">
                                    <?php echo $ptl_row['description']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END -->

        <div class="sm:mt-10 mt-8 relative">

            <div class="text-center mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
                <h2 class="text-[28px] font-[700] leading-7 text-blue-main">Online Library</h2>
                <?php if ($pol_row['details'] !== ''): ?>
                <div class="text-gray-500 text-[16px] mt-3"><?php echo $pol_row['details']; ?></div>
                <?php endif; ?>
            </div>
            <div class="mt-5 ab-cr-bg sm:py-10">
                <div
                    class="relative 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3 sm:flex gap-5 sm:flex-row-reverse">
                    <?php if (!empty($pol_row['media']['urls'])): ?>
                    <div class="mx-3 sm:w-[50%]">
                        <img src="<?php echo htmlspecialchars($pol_row['media']['urls']); ?>" alt="Online Library">
                    </div>
                    <?php endif; ?>
                    <div class="bg-blue-main sm:mt-0 mt-[-100px] sm:w-[50%]">
                        <div class="2xl:pt-[120px] lg:pt-1 md:pt-1 pt-[120px] mx-3 pb-5">
                            <div class="ql-snow">
                                <div class="ql-editor">
                                    <?php echo $pol_row['description']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3 ">

            <div class="mt-8 sm:mt-10 relative sm:mx-0 mx-3">

                <div class="text-center">
                    <h2 class="text-[28px] font-[700] leading-7 text-blue-main uppercase">Brilliance <span
                            class="sm:hidden">
                            <br></span> Curriculum
                        Model</h2>

                    <?php if ($pbc_row['details'] !== ''): ?>
                    <div class="ql-snow">
                        <div class="ql-editor">
                            <?php echo $pbc_row['details']; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="relative w-full  bg-white rounded glide-03 mt-4 opne-hide-circle">
                    <!-- Slides -->
                    <div class="overflow-hidden" data-glide-el="track">
                        <ul
                            class="relative w-full p-0 whitespace-no-wrap flex flex-no-wrap [backface-visibility: hidden] [transform-style: preserve-3d] [touch-action: pan-Y] [will-change: transform]">
                            <?php
                            foreach ($pbc_row['medias'] as $img_data) { ?>
                                <li class="relative">
                                    <img src="<?php echo $img_data['urls'] ?>" alt="Brilliance Curriculum Model"
                                        class="w-[100%]">
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                    <!-- Controls -->
                    <div class="absolute left-0 xl:flex hidden items-center justify-between w-full h-0 px-4 top-1/2 hide-circle"
                        data-glide-el="controls">
                        <button
                            class="inline-flex items-center  relative sm:left-[-66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir="<" aria-label="prev slide">
                            <i class="fa-solid fa-angle-left"></i>
                        </button>
                        <button
                            class="inline-flex items-center justify-center relative sm:right-[-66px] hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir=">" aria-label="next slide">
                            <i class="fa-solid fa-angle-right"></i>
                        </button>
                    </div>
                </div>
                <div class="  mt-4 pb-5">
                    <p class=" text-[16px]" id="first-paragraph">
                        <?php echo substr($pbc_row['description'], 0, 320) ?>
                    </p>

                    <!-- "Read More" / "Read Less" Button -->
                    <a href="javascript:void(0);" id="read-more" onclick="toggleContent()"
                        class="text-[15px] font-[600] text-blue-main cursor-pointer mt-2 inline-block">Read More</a>

                    <!-- Hidden content -->
                    <div id="extra-content-container" style="display: none;">

                        <?php echo substr($pbc_row['description'], 320) ?>

                        <!-- Read Less Button (Initially hidden, will be shown when content is displayed) -->
                        <a href="javascript:void(0);" id="read-less" onclick="toggleContent()"
                            class="text-[15px] font-[600] text-blue-main cursor-pointer mt-2 inline-block"
                            style="display: none;">Read Less</a>
                    </div>
                    <script>
                        function toggleContent() {
                            var extraContentContainer = document.getElementById("extra-content-container");
                            var readMoreButton = document.getElementById("read-more");
                            var readLessButton = document.getElementById("read-less");

                            // Check if the content is currently hidden or shown
                            var isHidden = extraContentContainer.style.display === "none";

                            // Toggle the content visibility
                            extraContentContainer.style.display = isHidden ? "block" : "none";

                            // Toggle visibility of "Read More" and "Read Less" buttons
                            readMoreButton.style.display = isHidden ? "none" : "inline-block";
                            readLessButton.style.display = isHidden ? "inline-block" : "none";
                        }
                    </script>


                </div>
            </div>


            <div class="bg-center mt-8 mb-5 relative sm:mx-0 mx-3">
                <div class="text-center">
                    <h2 class="text-[28px] font-[700] leading-7 text-blue-main uppercase">
                        Achiever <span class="sm:hidden"><br></span> Curriculum Model
                    </h2>
                    <?php if ($pac_row['details'] !== ''): ?>
                      <div class="ql-snow">
                        <div class="ql-editor">
                               <?php echo $pac_row['details']; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Slider -->
                <div class="relative w-full bg-white rounded glide-04 mt-4 opne-hide-circle">
                    <div class="overflow-hidden" data-glide-el="track">
                        <ul
                            class="relative w-full p-0 whitespace-no-wrap flex flex-no-wrap [backface-visibility: hidden] [transform-style: preserve-3d] [touch-action: pan-Y] [will-change: transform]">
                            <?php foreach ($pac_row['medias'] as $img_data) { ?>
                                <li class="relative">
                                    <img src="<?php echo $img_data['urls']; ?>" alt="Achiever Curriculum Model" class="w-[100%]">
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                    <!-- Controls -->
                    <div class="absolute left-0 xl:flex items-center justify-between w-full h-0 px-4 top-1/2 hide-circle"
                        data-glide-el="controls">
                        <button
                            class="inline-flex items-center relative sm:left-[-66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir="<" aria-label="prev slide">
                            <i class="fa-solid fa-angle-left"></i>
                        </button>
                        <button
                            class="inline-flex items-center justify-center relative sm:right-[-66px] hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir=">" aria-label="next slide">
                            <i class="fa-solid fa-angle-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Description with Read More -->
                <div class="mt-4">
                    <div id="achiever-description" class="text-[16px]">
                        <?php echo $pac_row['description']; ?>
                    </div>

                    <button id="achiever-toggleBtn" class="mt-4 text-blue-main font-semibold">
                        Read More
                    </button>

                    <script>
                        const achieverDesc = document.getElementById('achiever-description');
                        const toggleBtn = document.getElementById('achiever-toggleBtn');

                        // show only first 250 chars at first
                        let fullText = achieverDesc.innerHTML.trim();
                        let shortText = fullText.substring(0, 250) + "...";

                        let expanded = false;
                        achieverDesc.innerHTML = shortText;

                        toggleBtn.addEventListener("click", () => {
                            expanded = !expanded;
                            achieverDesc.innerHTML = expanded ? fullText : shortText;
                            toggleBtn.textContent = expanded ? "Read Less" : "Read More";
                        });
                    </script>
                </div>


                <script>
                    const toggleBtn2 = document.getElementById('achiever-toggleBtn');
                    const content2 = document.getElementById('achiever-content');
                    let isExpanded2 = false;

                    toggleBtn2.addEventListener('click', function() {
                        isExpanded2 = !isExpanded2;
                        content2.classList.toggle('hidden', !isExpanded2);
                        toggleBtn2.textContent = isExpanded2 ? 'Read Less' : 'Read More';
                    });
                </script>
            </div>
        </div>
    </div>

    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>
    <script>
        const btn = document.getElementById('readMoreBtne');
        const btn2 = document.getElementById("readMoreBtne2");
        const btn3 = document.getElementById("readMoreBtne3");
        const btn4 = document.getElementById("readMoreBtne4");

        const items = document.querySelectorAll('.toggle-item');
        const items2 = document.querySelectorAll('.toggle-item2');
        const items3 = document.querySelectorAll('.toggle-item3');
        const items4 = document.querySelectorAll('.toggle-item4');

        btn.addEventListener('click', () => {
            const isHidden = items[0].classList.contains('hidden');
            items.forEach(item => item.classList.toggle('hidden'));
            btn.textContent = isHidden ? 'Read Less' : 'Read More';
        });

        btn2.addEventListener('click', () => {
            const isHidden = items2[0].classList.contains('hidden');
            items2.forEach(item => item.classList.toggle('hidden'));
            btn2.textContent = isHidden ? 'Read Less' : 'Read More';
        });

        btn3.addEventListener('click', () => {
            const isHidden = items3[0].classList.contains('hidden');
            items3.forEach(item => item.classList.toggle('hidden'));
            btn3.textContent = isHidden ? 'Read Less' : 'Read More';
        });

        btn4.addEventListener('click', () => {
            const isHidden = items4[0].classList.contains('hidden');
            items4.forEach(item => item.classList.toggle('hidden'));
            btn4.textContent = isHidden ? 'Read Less' : 'Read More';
        });
    </script>
    <script>
        var glide01 = new Glide('.glide-01', {
            type: 'carousel',
            focusAt: 'center',
            perView: 3,
            autoplay: 3500,
            animationDuration: 700,
            gap: 2,
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

        var glide02 = new Glide('.glide-02', {
            type: 'carousel',
            focusAt: 'center',
            perView: 3.5,
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
        glide02.mount();

        var glide03 = new Glide('.glide-03', {
            type: 'carousel',
            focusAt: 1,
            perView: 4,
            autoplay: 3500,
            animationDuration: 700,
            gap: 24,
            classes: {
                activeNav: '[&>*]:bg-slate-700',
            },
            breakpoints: {
                1024: {
                    perView: 4
                },
                640: {
                    perView: 1
                }
            },
        });
        glide03.mount();

        var glide04 = new Glide('.glide-04', {
            type: 'carousel',
            focusAt: 1,
            perView: 4,
            autoplay: 3500,
            animationDuration: 700,
            gap: 24,
            classes: {
                activeNav: '[&>*]:bg-slate-700',
            },
            breakpoints: {
                1024: {
                    perView: 4
                },
                640: {
                    perView: 1
                }
            },
        });
        glide04.mount();
    </script>

</body>

</html>