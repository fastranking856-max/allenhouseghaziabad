<?php
require_once __DIR__ . "/proxy/config.php";
$url = rtrim(CMS_API_URL, '/') . "/result-acadmics/" . BRANCH_ID;
$contextOptions = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ]
];
$s_data = cms_fetch_json_url($url) ?? ['status' => 'error', 'data' => []];
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/results-and-academics" />
    <title>AllenHouse Ghaziabad| Results and Academics</title>
    <?php include "includes/head.php" ?>
</head>

<body>

    <?php include "includes/header.php" ?>

    <div class="main relative  mb-[40px] sm:mb-[120px] ">
        <div class="bg-center flex items-center  h-[300px] comman-banner">
            <div>
                <h1
                    class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 sm:mb-8 hr-line relative leading-9">
                    Results and Academics
                </h1>
            </div>

            <div class="md:w-[100%]">
                <h1
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    Results and
                    <span class="sm:hidden"></span> Academics
                </h1>
            </div>


        </div>

        <div class="flex m-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse overflow-x-auto whitespace-nowrap">
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
                        <p class="ms-1 sm:text-sm text-xs font-medium text-blue-main">Disclosures</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-blue-main mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="results-and-academics" class="ms-1 sm:text-sm text-xs font-medium text-blue-main">Results and
                            Academics</a>
                    </div>
                </li>
            </ol>
        </div>

        <div class="main relative sm:top-[20px] mb-[40px] sm:mb-[120px] mx-0 sm:mx-2">
            <div class="mt-8 mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-10">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200 ">
                            <tr>
                                <th scope="col" class="px-6 py-4">
                                    S.No.
                                </th>
                                <th scope="col" class="px-6 py-4">
                                    Documents/Information
                                </th>
                                <th scope="col" class="px-6 py-4">
                                    Upload Document Link
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                              <?php
                                $s = 1;  
                                foreach ($s_data['data'] as $row) {
                                ?>
                                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                                        <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            <?php echo $s++; ?>
                                        </th>
                                        <td class="px-6 py-2">
                                            <?php echo htmlspecialchars($row['information']); ?>
                                        </td>
                                        <td class="px-6 py-2">
                                            <a href="<?php echo htmlspecialchars($row['url'] ?? '#'); ?>"
                                                target="_blank"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="sm:mt-20 mt-10 mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-4">
            <div class="relative">
                <h2 class="text-center sm:text-[32px] text-[28px] font-[700] text-blue-main leading-9">
                    Board Examination Results
                </h2>
                <div class="mt-10">
                    <h3 class=" sm:text-[24px] text-[20px] font-[700] text-blue-main leading-9">
                        Result – Class X

                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 mt-5 ">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-200 ">
                                <tr>
                                    <th scope="col" class="px-6 py-4">
                                        S.No.
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Year
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        No. of Registered Students
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        No. of Students Passed
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Pass Percentage
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Remarks
                                    </th>

                                </tr>
                            </thead>
                             <tbody>
                                <?php
                                $url = rtrim(CMS_API_URL, '/') . "/result-class-x/" . BRANCH_ID;
                                $contextOptions = [
                                    "ssl" => [
                                        "verify_peer" => false,
                                        "verify_peer_name" => false,
                                    ]
                                ];
                                $data = cms_fetch_json_url($url) ?? ['status' => 'error', 'data' => []];
                                $s = 1;
                                foreach ($data['data'] as $row) {
                                ?>
                                    <tr class="odd:bg-white even:bg-gray-200  text-gray-700 border-b  text-[16px]">
                                        <th scope="row" class="px-6 py-2 font-medium text-gray-700 whitespace-nowrap">
                                            <?php echo $s++; ?>
                                        </th>
                                        <td class="px-6 py-2"><?php echo $row["year"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["number_of_registered_std"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["number_of_passout_std"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["passout_percentage"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["remarks"] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-10">
                    <h3 class=" sm:text-[24px] text-[20px] font-[700] text-blue-main leading-9">
                        Result – Class XII

                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 mt-5 ">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-200 ">
                                <tr>
                                    <th scope="col" class="px-6 py-4">
                                        S.No.
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Year
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        No. of Registered Students
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        No. of Students Passed
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Pass Percentage
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Remarks
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $url = rtrim(CMS_API_URL, '/') . "/result-class-xii/" . BRANCH_ID;
                                $contextOptions = [
                                    "ssl" => [
                                        "verify_peer" => false,
                                        "verify_peer_name" => false,
                                    ]
                                ];
                                $data = cms_fetch_json_url($url) ?? ['status' => 'error', 'data' => []];
                                $s = 1;
                                foreach ($data['data'] as $row) {
                                ?>
                                    <tr class="odd:bg-white even:bg-gray-200  text-gray-700 border-b  text-[16px]">
                                        <th scope="row" class="px-6 py-2 font-medium text-gray-700 whitespace-nowrap">
                                            <?php echo $s++; ?>
                                        </th>
                                        <td class="px-6 py-2"><?php echo $row["year"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["number_of_registered_std"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["number_of_passout_std"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["passout_percentage"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["remarks"] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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