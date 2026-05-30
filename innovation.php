<?php
require_once __DIR__ . '/includes/cms-page-helpers.php';
$row = cmsBeyondAcademicsRow('innovation-hub-page-ghaziabad');
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/innovation" />
    <title>Innovation Hub for Students by Allenhouse Ghaziabad</title>
    <meta name="description"
        content="The Innovation Hub for Students by Allenhouse Ghaziabad empowers young minds through design thinking and technology.">
    <?php include "includes/head.php" ?>
</head>

<body>

    <?php include "includes/header.php" ?>
    <div class="main relative  mb-[120px] ">
        <!-- Start -->
        <div
            class="flex items-center text-left sm:h-[400px] h-[300px] bg-center bg-cover 
            bg-[url('https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/WEBw5oEzRppMsG1V3Wp9eQSFI7dgY3npO4YOpzT8.jpg')] sm:bg-[url('https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/fw0bga0OfyihnIPCvRiWfRgVCnkxMwsXKQ8YoqjT.jpg')]">

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
                        <p class="ms-1 sm:text-sm text-xs font-medium text-blue-main">Beyond Academic
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
                        <a href="innovation" class="ms-1 text-sm font-medium text-blue-main">Innovation Hub</a>
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
                         <img src="<?php echo htmlspecialchars($row['media']['urls']); ?>" alt="Innovation Hub" class="w-[100%]">
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

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

</body>

</html>