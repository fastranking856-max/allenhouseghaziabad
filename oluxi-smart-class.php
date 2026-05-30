<?php
require_once __DIR__ . '/includes/cms-page-helpers.php';
$row = cmsBeyondAcademicsRow('oluxi-smart-skills-page-ghaziabad');
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/head.php" ?>
    <link rel="canonical" href="https://allenhouseghaziabad.com/oluxi-smart-class" />
    <title>Life Skills for Students | APS Ghaziabad Oluxi Program</title>
    <meta name="description"
        content="Discover life skills for students through the Oluxi Program at APS Ghaziabad - fostering leadership and resilience.">
</head>

<body>

    <?php include "includes/header.php" ?>
    <div class="main relative mb-[40px] sm:mb-[120px]">
        <!-- Start -->
        <div class="bg-center flex items-center text-left h-[300px] comman-banner">
            <div>
                <h2
                    class="text-[32px] sm:hidden block font-[700] text-white text-left mb-5 sm:mb-8 hr-line relative leading-9 pl-4 ">
                    Oluxi Smart Skills
                </h2>
            </div>

            <div class="md:w-[100%]">
                <h2
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    Oluxi Smart Skills</span>
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
                        <p class="ms-1 sm:text-sm text-xs font-medium text-blue-main">Beyond Academics
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
                        <a href="oluxi-smart-class" class="ms-1 sm:text-sm text-xs font-medium text-blue-main">Oluxi
                            Smart Skills</a>
                    </div>
                </li>
            </ol>
        </div>
        <!-- End -->
        <div class="mt-8 mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
            <div class="mt-10 relative">

                <div class="sm:flex gap-9">
                    <div class="sm:w-[40%]">
                        <?php if (!empty($row['media']['urls'])): ?>
                        <img src="<?php echo htmlspecialchars($row['media']['urls']); ?>" alt="Oluxi Smart Skills" class="w-[100%]">
                        <?php endif; ?>
                    </div>
                    <div class="sm:w-[60%]">
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