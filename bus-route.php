<?php
require_once __DIR__ . '/includes/cms-page-helpers.php';
$bus_data = cmsFetchBusRoutes();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/bus-route" />
    <title>AllenHouse Ghaziabad| Bus Route</title>
    <?php include "includes/head.php" ?>
</head>

<body>

    <?php include "includes/header.php" ?>

    <div class="main relative  mb-[40px] sm:mb-[120px] ">
        <div class="bg-center flex items-center text-center h-[300px] comman-banner">
            <div>
                <h1
                    class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 sm:mb-8 hr-line relative leading-9">
                    Bus Route
                </h1>
            </div>

            <div class="md:w-[100%]">
                <h1
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    Bus Route
                </h1>
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
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <p class="ms-1 sm:text-sm text-xs font-medium text-blue-main">Admission</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-blue-main mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="bus-route" class="ms-1 sm:text-sm text-xs font-medium text-blue-main">Bus Route</a>
                    </div>
                </li>
            </ol>
        </div>

        <div class="main relative sm:top-[20px] mb-[40px] sm:mb-[120px] mx-0 sm:mx-2">
            <div class="mt-8 mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
                <div class="sm:mt-10 relative">

                    <div>

                        <div class="md:w-[100%]">


                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-10">
                                <div class="overflow-x-auto">


                                    <table class="table-auto w-full border border-collapse border-gray-300">
                                        <thead>
                                            <tr class="bg-gray-200">
                                                <th class="border border-gray-300 px-4 py-2">S.No.</th>
                                                <th class="border border-gray-300 px-4 py-2">Vehicle No.</th>
                                                <!-- <th class="border border-gray-300 px-4 py-2">Route No.</th> -->
                                                <th class="border border-gray-300 px-4 py-2">Route Description</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $s = 1;

                                            if (!empty($bus_data)) {
                                                foreach ($bus_data as $row) {
                                                    // Separate routes into source, normal, and destination
                                                    $source = [];
                                                    $destination = [];
                                                    $normal = [];

                                                    foreach ($row['bus_routes'] as $route) {
                                                        if ($route['is_source'] == 1) {
                                                            $source[] = $route['routes'];
                                                        } elseif ($route['is_destination'] == 1) {
                                                            $destination[] = $route['routes'];
                                                        } else {
                                                            $normal[] = $route['routes'];
                                                        }
                                                    }

                                                    // Merge in order: source -> normal -> destination
                                                    $orderedRoutes = array_merge($source, $normal, $destination);
                                            ?>

                                                    <tr class="hover:bg-gray-100">
                                                        <td class="border border-gray-300 px-4 py-2"><?php echo $s++; ?></td>
                                                        <td class="border border-gray-300 px-4 py-2"><?php echo $row['busnumber']; ?></td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <?php echo implode(' → ', $orderedRoutes); ?>
                                                        </td>
                                                    </tr>

                                            <?php
                                                }
                                            }
                                            ?>

                                        </tbody>
                                    </table>


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