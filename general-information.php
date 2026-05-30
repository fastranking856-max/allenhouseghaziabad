<?php
require_once __DIR__ . "/proxy/config.php";
$url = rtrim(CMS_API_URL, '/') . "/general-information/" . BRANCH_ID;
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
    <link rel="canonical" href="https://allenhouseghaziabad.com/general-information" />
    <title>AllenHouse Ghaziabad | General Information</title>
    <?php include "includes/head.php" ?>
</head>

<body>

    <?php include "includes/header.php" ?>

    <div class="main relative  mb-[40px] sm:mb-[120px] ">
        <div class="bg-center flex items-center  h-[300px] comman-banner">
            <div>
                <h1
                    class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 sm:mb-8 hr-line relative leading-9">
                    General Information
                </h1>
            </div>

            <div class="md:w-[100%]">
                <h1
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    General
                    <span class="sm:hidden"></span> Information
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
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 9 4-4-4-4" />
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
                        <a href="general-information" class="ms-1 sm:text-sm text-xs font-medium text-blue-main">General Information</a>
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
                                    Information
                                </th>
                                <th scope="col" class="px-6 py-4">
                                    Details
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                             
                        <?php
                            if ($s_data && $s_data['status'] === 'success' && isset($s_data['data'])) {
                                $i = 1;
                                foreach ($s_data['data'] as $row) {
                                    echo '<tr class="odd:bg-white even:bg-gray-50 border-b">';
                                    echo '<th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap">' . $i++ . '.</th>';
                                    echo '<td class="px-6 py-2">' . strip_tags($row['information']) . '</td>';

                                    // Make email clickable
                                    if (stripos($row['information'], 'mail') !== false) {
                                        $email = strip_tags($row['details']);
                                        echo '<td class="px-6 py-2"><a href="mailto:' . $email . '">' . $email . '</a></td>';
                                    } else {
                                        echo '<td class="px-6 py-2">' . strip_tags($row['details']) . '</td>';
                                    }

                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3" class="px-6 py-4 text-red-500">No information found.</td></tr>';
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

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

</body>

</html>