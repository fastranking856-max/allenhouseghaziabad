<?php
require_once __DIR__ . "/proxy/config.php";
$url = rtrim(CMS_API_URL, '/') . "/school-infrastructure/" . BRANCH_ID;
$contextOptions = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ]
];
$data = cms_fetch_json_url($url) ?? ['status' => 'error', 'data' => []];
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="canonical" href="https://allenhouseghaziabad.com/school-infrastructure" />
  <title>AllenHouse Ghaziabad| School Infrastructure</title>
  <?php include "includes/head.php" ?>
</head>

<body>

  <?php include "includes/header.php" ?>

  <div class="main relative  mb-[40px] sm:mb-[120px] ">
    <div class="bg-center flex items-center text-center h-[300px] common-banner">
      <div>
        <h1
          class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 sm:mb-8 hr-line relative leading-9">
          School Infrastructure
        </h1>
      </div>

      <div class="md:w-[100%]">
        <h1
          class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
          School
          <span class="sm:hidden"></span> Infrastructure
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
            <a href="school-infrastructure" class="ms-1 sm:text-sm text-xs font-medium text-blue-main">School Infrastructure</a>
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
                                        <td class="border border-gray-300 px-4 py-2">TOTAL CAMPUS AREA OF THE SCHOOL
                                            (SQ.MTR)</td>
                                        <td class="border border-gray-300 px-4 py-2">4093 SQ.MT</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">2</td>
                                        <td class="border border-gray-300 px-4 py-2">NO. AND SIZE OF THE CLASS ROOMS
                                            (SQ.FT/MTR)</td>
                                        <td class="border border-gray-300 px-4 py-2">42</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">3</td>
                                        <td class="border border-gray-300 px-4 py-2">NO. AND SIZE OF THE LABORATORIES
                                            INCLUDING COMPUTER LAB (SQ.FT/MTR)</td>
                                        <td class="border border-gray-300 px-4 py-2">9</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">4</td>
                                        <td class="border border-gray-300 px-4 py-2">INTERNET FACILITY (Y/N)</td>
                                        <td class="border border-gray-300 px-4 py-2">YES</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">5</td>
                                        <td class="border border-gray-300 px-4 py-2">NO. OF GIRLS TOILETS</td>
                                        <td class="border border-gray-300 px-4 py-2">20</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">6</td>
                                        <td class="border border-gray-300 px-4 py-2">NO. OF BOYS TOILETS</td>
                                        <td class="border border-gray-300 px-4 py-2">20</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">7</td>
                                        <td class="border border-gray-300 px-4 py-2">LINK OF YOUTUBE VIDEO OF THE
                                            INSPECTION OF SCHOOL CONVERING THE INFRASTRUCTURE OF THE SCHOOL</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            <a href="https://youtu.be/Vqs1cqjfbw0" class="text-blue-500 underline"
                                                target="_blank">Watch Video</a>
                                        </td>
                                    </tr>
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
  </div>

  <?php include "includes/footer.php" ?>
  <?php include "includes/foot.php" ?>

</body>

</html>