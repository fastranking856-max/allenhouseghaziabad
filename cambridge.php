<?php
require_once __DIR__ . '/includes/cms-page-helpers.php';
$cambridge_page = cmsFetchGhaziabadPage('cambridge-assessment-page-ghaziabad');
$cambridge_parts = cmsExtractPageParts($cambridge_page);
$cambridge_description = $cambridge_parts['content_html'] !== ''
    ? $cambridge_parts['content_html']
    : $cambridge_parts['table_html'];
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/cambridge" />
    <title>Cambridge Assessment | Allenhouse Ghaziabad Excellence</title>
    <meta name="description" content="Global learning begins at Allenhouse School, Ghaziabad, with the Cambridge Assessment system for next-gen achievers.">
    <?php include "includes/head.php" ?>
</head>
<style>
    .ql-editor {
        /* height: auto !important;
        padding: 0 !important; */
        white-space: normal !important;
        line-height: 26px;
        overflow: hidden;
    }
</style>
<body>

    <?php include "includes/header.php" ?>
    
    <div class="main relative  mb-[40px] sm:mb-[120px] ">
        <div class="bg-center flex items-center text-center h-[300px] common-banner">
            <div>
                <h1
                    class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 sm:mb-8 hr-line relative leading-9">
                    Cambridge Assessment
                </h1>
            </div>

            <div class="md:w-[100%]">
                <h1
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    Cambridge
                    <span class="sm:hidden"></span>Assessment
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
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <p class="ms-1 text-xs sm:text-sm font-medium text-blue-main">Academics</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-blue-main mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="cambridge" class="ms-1 text-xs sm:text-sm font-medium text-blue-main">Cambridge Assessment</a>
                    </div>
                </li>
            </ol>
        </div>

        <div class="mt-8 mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
            <div class="sm:mt-10 relative">

                <div class="ql-snow">
                                <div class="ql-editor">
                                    <?php echo $cambridge_description ?>
                                </div>
                            </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

</body>

</html>