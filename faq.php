<?php
 $page = "faq";
 ?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/faq" />
     <title>APS School, Ghaziabad FAQs | Everything You Need to Know</title>
     <meta name="description" content="APS School, Ghaziabad FAQs: Unravel all you need to know about our school to make an informed choice.">
    <?php include "includes/head.php" ?>
</head>
<style>
    .ql-editor {
        height: auto !important;
        padding: 8px !important;
        white-space: normal !important;
    }
</style>
<body>

    <?php include "includes/header.php" ?>
    <?php
    require_once __DIR__ . '/includes/cms-page-helpers.php';
    require_once __DIR__ . '/includes/api-adapters.php';
    $faq_page = cmsFetchGhaziabadPage('faqs-page-ghaziabad');
    $faqHtml = cmsSectionHtml(cmsPageSection($faq_page, 1));
    ?>

    <div class="main relative  mb-[40px] sm:mb-[120px] ">
        <div class="bg-center flex items-center text-center h-[300px] job-opening-bg common-banner">
            <div>
                <h1
                    class="text-[32px] sm:hidden block font-[700] text-white text-center pl-4 mb-5 sm:mb-8 hr-line relative leading-9">
                    FAQ's
                </h1>
            </div>

            <div class="md:w-[100%]">
                <h1
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left ml-[7rem] sm:mb-1 hr-line relative leading-9">
                    FAQ's
                </h1>
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
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="faq" class="ms-1 text-xs sm:text-sm font-medium text-blue-main">FAQ's</a>
                    </div>
                </li>
            </ol>
        </div>

        <div class="mt-10 mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-4">
            <div class="relative">
                <?php if ($faqHtml !== ''): ?>
                    <div class="ql-snow">
                        <div class="ql-editor">
                            <?= $faqHtml ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

</body>

</html>