<?php
require_once __DIR__ . '/includes/cms-page-helpers.php';
$row = cmsBeyondAcademicsRow('sports-academy-page-ghaziabad');
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include "includes/head.php" ?>
    <link rel="canonical" href="https://allenhouseghaziabad.com/sports" />
    <title>Top CBSE School in Ghaziabad with Sports Facilities</title>
    <meta name="description" content="Allenhouse is the top CBSE school in Ghaziabad with sports facilities that nurture academic success and athletic excellence.">
</head>

<body>

    <?php include "includes/header.php" ?>
    <!-- Start -->
    <div class="bg-center flex items-center text-left h-[300px] comman-banner">
        <div>
            <h2 class="text-[32px] sm:hidden block font-[700] text-white text-left mb-5 sm:mb-8 hr-line relative leading-9 pl-4 ">
                Sports Academy
            </h2>
        </div>

        <div class="md:w-[100%]">
            <h2 class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                Sports Academy </span>
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
                    <svg class="rtl:rotate-180 w-3 h-3 text-blue-main mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"></path>
                    </svg>
                    <p class="ms-1 text-xs sm:text-sm font-medium text-blue-main">Beyond Academics
                    </p>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-blue-main mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"></path>
                    </svg>
                    <a href="sports" class="ms-1 text-xs sm:text-sm font-medium text-blue-main">Sports Academy</a>
                </div>
            </li>
        </ol>
    </div>
    <div class="main relative sm:top-[20px]  mb-[40px] sm:mb-[120px] mx-0 sm:mx-2">
        <div class="mt-8 mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
            <div class="sm:mt-10 relative">


                <div class="sm:flex gap-10">
                    <div class="sm:w-[40%]">
                        <?php if (!empty($row['media']['urls'])): ?>
                        <img src="<?php echo htmlspecialchars($row['media']['urls']); ?>" alt="Sports Academy" class="w-[105%]">
                        <?php endif; ?>
                    </div>
                    <div class="sm:w-[60%] sm:mt-0 mt-3">

                        <!-- <h2 class="text-[22px] font-[700] hr-line relative leading-6 text-gray-600">Northwest Sports Academy</h2>
                        <p class=" text-[16px] text-gray-500 font-[400] mt-2">
                            At Northwest Sports Academy, we nurture children's full potential by integrating sports as a vital part of education. We offer an international-standard sporting experience that truly makes a difference. </p>
                        <p class=" text-[16px] text-gray-500 font-[400] mt-2">
                            We’ve created a dynamic environment where young athletes can follow their passion. And we didn’t do it alone — our academy’s framework has been shaped with insights from national and international players and coaches, bringing in the best practices from across the world. </p>
                        <h2 class="text-[22px] font-[700] hr-line relative leading-6 mt-3 text-gray-600">What Makes Us Unique?</h2>
                        <ul class=" text-[16px] text-gray-500 font-[400] mt-3 ml-5" style="list-style:disc">
                            <li class="mt-1">18 Locations across Lucknow, Kanpur, Ghaziabad, Bareilly, Jhansi & Saharanpur</li>
                            <li class="mt-1">Top Coaches with national and international experience</li>
                        </ul> -->
                        <div class="ql-snow">
                            <div class="ql-editor">
                                <?php echo $row['description']; ?>
                            </div>
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