<?php
// ============================================================
// MPD (Mandatory Public Disclosure) — Consolidated Page
// Allenhouse Ghaziabad — branch-config driven
// ============================================================

require_once __DIR__ . "/proxy/config.php";
$schoolId = (string) BRANCH_ID;
$contextOptions = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ]
];
$context = stream_context_create($contextOptions);

// --- 1. General Information ---
$gi_url    = rtrim(CMS_API_URL, '/') . "/general-information/" . $schoolId;
$gi_data   = cms_fetch_json_url($gi_url) ?? ['status' => 'error', 'data' => []];

// --- 2. Documents and Information ---
$di_url    = rtrim(CMS_API_URL, '/') . "/document-information/" . $schoolId;
$di_data   = cms_fetch_json_url($di_url) ?? ['status' => 'error', 'data' => []];

// --- 3. Results and Academics ---
$ra_url    = rtrim(CMS_API_URL, '/') . "/result-acadmics/" . $schoolId;
$ra_data   = cms_fetch_json_url($ra_url) ?? ['status' => 'error', 'data' => []];

// --- 3a. Class X Results ---
$rx_url    = rtrim(CMS_API_URL, '/') . "/result-class-x/" . $schoolId;
$rx_data   = cms_fetch_json_url($rx_url) ?? ['status' => 'error', 'data' => []];

// --- 3b. Class XII Results ---
$rxii_url  = rtrim(CMS_API_URL, '/') . "/result-class-xii/" . $schoolId;
$rxii_data = cms_fetch_json_url($rxii_url) ?? ['status' => 'error', 'data' => []];

// --- 4. Staff (Teaching) ---
$st_url    = rtrim(CMS_API_URL, '/') . "/staff-teaching/" . $schoolId;
$st_data   = cms_fetch_json_url($st_url) ?? ['status' => 'error', 'data' => []];

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/mandatory-public-disclosure" />
    <title>AllenHouse Ghaziabad | Mandatory Public Disclosure</title>
    <?php include "includes/head.php" ?>
</head>

<body>

    <?php include "includes/header.php" ?>

    <div class="main relative mb-[40px] sm:mb-[120px]">
        <!-- Banner -->
        <div class="bg-center flex items-center h-[300px] comman-banner">
            <div>
                <h1 class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 sm:mb-8 hr-line relative leading-9">
                    Mandatory Public Disclosure
                </h1>
            </div>
            <div class="md:w-[100%]">
                <h1 class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    Mandatory Public Disclosure
                </h1>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="flex m-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse overflow-x-auto whitespace-nowrap">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center sm:text-sm text-xs font-medium text-blue-main">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-blue-main mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <p class="ms-1 sm:text-sm text-xs font-medium text-blue-main">Mandatory Public Disclosure</p>
                    </div>
                </li>
            </ol>
        </div>

        <div class="mt-8 mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">

            <!-- ==================== 1. GENERAL INFORMATION ==================== -->
            <section class="mb-16">
                <h2 class="sm:text-[28px] text-[22px] font-[700] text-blue-main mb-6">
                    1. General Information
                </h2>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4">S.No.</th>
                                <th scope="col" class="px-6 py-4">Information</th>
                                <th scope="col" class="px-6 py-4">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($gi_data && $gi_data['status'] === 'success' && isset($gi_data['data'])) {
                                $i = 1;
                                foreach ($gi_data['data'] as $row) {
                                    echo '<tr class="odd:bg-white even:bg-gray-50 border-b">';
                                    echo '<th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap">' . $i++ . '.</th>';
                                    echo '<td class="px-6 py-2">' . strip_tags($row['information']) . '</td>';
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
            </section>

            <!-- ==================== 2. DOCUMENTS AND INFORMATION ==================== -->
            <section class="mb-16">
                <h2 class="sm:text-[28px] text-[22px] font-[700] text-blue-main mb-6">
                    2. Documents and Information
                </h2>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4">S.No.</th>
                                <th scope="col" class="px-6 py-4">Documents/Information</th>
                                <th scope="col" class="px-6 py-4">Upload Document Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($di_data && isset($di_data['data'])) {
                                $s = 1;
                                foreach ($di_data['data'] as $row) {
                            ?>
                                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                                        <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap"><?php echo $s++; ?></th>
                                        <td class="px-6 py-2"><?php echo htmlspecialchars($row['information']); ?></td>
                                        <td class="px-6 py-2">
                                            <a href="<?php echo htmlspecialchars($row['url'] ?? '#'); ?>" target="_blank"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="3" class="px-6 py-4 text-red-500">No documents found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ==================== 3. RESULTS AND ACADEMICS ==================== -->
            <section class="mb-16">
                <h2 class="sm:text-[28px] text-[22px] font-[700] text-blue-main mb-6">
                    3. Results and Academics
                </h2>

                <!-- 3a. Academic Documents -->
                <h3 class="sm:text-[22px] text-[18px] font-[600] text-blue-main mb-4">Academic Documents</h3>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-10">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4">S.No.</th>
                                <th scope="col" class="px-6 py-4">Documents/Information</th>
                                <th scope="col" class="px-6 py-4">Upload Document Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($ra_data && isset($ra_data['data'])) {
                                $s = 1;
                                foreach ($ra_data['data'] as $row) {
                            ?>
                                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                                        <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap"><?php echo $s++; ?></th>
                                        <td class="px-6 py-2"><?php echo htmlspecialchars($row['information']); ?></td>
                                        <td class="px-6 py-2">
                                            <a href="<?php echo htmlspecialchars($row['url'] ?? '#'); ?>" target="_blank"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="3" class="px-6 py-4 text-red-500">No information found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- 3b. Board Examination Results -->
                <h3 class="sm:text-[22px] text-[18px] font-[600] text-blue-main mb-4">Board Examination Results</h3>

                <!-- Class X -->
                <h4 class="sm:text-[18px] text-[16px] font-[600] text-blue-main mt-8 mb-3">Result – Class X</h4>
                <div class="overflow-x-auto shadow-md sm:rounded-lg mb-8">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4">S.No.</th>
                                <th scope="col" class="px-6 py-4">Year</th>
                                <th scope="col" class="px-6 py-4">No. of Registered Students</th>
                                <th scope="col" class="px-6 py-4">No. of Students Passed</th>
                                <th scope="col" class="px-6 py-4">Pass Percentage</th>
                                <th scope="col" class="px-6 py-4">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($rx_data && isset($rx_data['data'])) {
                                $s = 1;
                                foreach ($rx_data['data'] as $row) {
                            ?>
                                    <tr class="odd:bg-white even:bg-gray-200 text-gray-700 border-b text-[16px]">
                                        <th scope="row" class="px-6 py-2 font-medium text-gray-700 whitespace-nowrap"><?php echo $s++; ?></th>
                                        <td class="px-6 py-2"><?php echo $row["year"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["number_of_registered_std"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["number_of_passout_std"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["passout_percentage"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["remarks"] ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="6" class="px-6 py-4 text-red-500">No Class X results found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Class XII -->
                <h4 class="sm:text-[18px] text-[16px] font-[600] text-blue-main mt-8 mb-3">Result – Class XII</h4>
                <div class="overflow-x-auto shadow-md sm:rounded-lg mb-8">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4">S.No.</th>
                                <th scope="col" class="px-6 py-4">Year</th>
                                <th scope="col" class="px-6 py-4">No. of Registered Students</th>
                                <th scope="col" class="px-6 py-4">No. of Students Passed</th>
                                <th scope="col" class="px-6 py-4">Pass Percentage</th>
                                <th scope="col" class="px-6 py-4">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($rxii_data && isset($rxii_data['data'])) {
                                $s = 1;
                                foreach ($rxii_data['data'] as $row) {
                            ?>
                                    <tr class="odd:bg-white even:bg-gray-200 text-gray-700 border-b text-[16px]">
                                        <th scope="row" class="px-6 py-2 font-medium text-gray-700 whitespace-nowrap"><?php echo $s++; ?></th>
                                        <td class="px-6 py-2"><?php echo $row["year"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["number_of_registered_std"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["number_of_passout_std"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["passout_percentage"] ?></td>
                                        <td class="px-6 py-2"><?php echo $row["remarks"] ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="6" class="px-6 py-4 text-red-500">No Class XII results found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ==================== 4. STAFF (TEACHING) ==================== -->
            <section class="mb-16">
                <h2 class="sm:text-[28px] text-[22px] font-[700] text-blue-main mb-6">
                    4. Staff (Teaching)
                </h2>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4">S.No.</th>
                                <th scope="col" class="px-6 py-4">Information</th>
                                <th scope="col" class="px-6 py-4">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($st_data && isset($st_data['data'])) {
                                $s = 1;
                                foreach ($st_data['data'] as $row) {
                            ?>
                                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                                        <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap"><?php echo $s++; ?>.</th>
                                        <td class="px-6 py-2"><?php echo strip_tags($row['information']); ?></td>
                                        <td class="px-6 py-2"><?= $row['details']; ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="3" class="px-6 py-4 text-red-500">No staff information found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ==================== 5. SCHOOL INFRASTRUCTURE ==================== -->
            <section class="mb-16">
                <h2 class="sm:text-[28px] text-[22px] font-[700] text-blue-main mb-6">
                    5. School Infrastructure
                </h2>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 bg-gray-200">S.NO.</th>
                                <th class="border border-gray-300 px-4 py-2 bg-gray-200">INFORMATION</th>
                                <th class="border border-gray-300 px-4 py-2 bg-gray-200">DETAILS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">1</td>
                                <td class="border border-gray-300 px-4 py-2">TOTAL CAMPUS AREA OF THE SCHOOL (In SQ.MTR)</td>
                                <td class="border border-gray-300 px-4 py-2">4093 SQ.MT</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">2</td>
                                <td class="border border-gray-300 px-4 py-2">NO. AND SIZE OF THE CLASS ROOMS (In SQ.MTR)</td>
                                <td class="border border-gray-300 px-4 py-2">42 & 47 Sqm</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">3</td>
                                <td class="border border-gray-300 px-4 py-2">NO. AND SIZE OF THE LABORATORIES INCLUDING COMPUTER LAB (In SQ.MTR)</td>
                                <td class="border border-gray-300 px-4 py-2">9 & 172.7 Sqm</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">4</td>
                                <td class="border border-gray-300 px-4 py-2">NO. AND SIZE OF THE LIBRARIES (In SQ.MTR)</td>
                                
                                <td class="border border-gray-300 px-4 py-2">1 & 172.7 Sqm</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">5</td>
                                <td class="border border-gray-300 px-4 py-2">INTERNET FACILITY (Y/N)</td>
                                <td class="border border-gray-300 px-4 py-2">YES</td>
                                
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">6</td>
                                <td class="border border-gray-300 px-4 py-2">NO. OF GIRLS TOILETS</td>
                                <td class="border border-gray-300 px-4 py-2">20</td>
                                </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">7</td>
                                <td class="border border-gray-300 px-4 py-2">NO. OF BOYS TOILETS</td>
                                <td class="border border-gray-300 px-4 py-2">20</td>
                            </tr>
                             <tr>
                                <td class="border border-gray-300 px-4 py-2">8</td>
                                <td class="border border-gray-300 px-4 py-2">NO. OF CWSN TOILETS</td>
                                <td class="border border-gray-300 px-4 py-2">1</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">9</td>
                                <td class="border border-gray-300 px-4 py-2">LINK OF YOUTUBE VIDEO OF THE INSPECTION OF SCHOOL COVERING THE INFRASTRUCTURE OF THE SCHOOL</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <a href="https://youtu.be/Vqs1cjqfbw0" class="text-blue-500 underline" target="_blank">Watch Video</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

</body>

</html>