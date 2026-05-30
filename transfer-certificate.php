<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/transfer-certificate" />
    <title>AllenHouse Ghaziabad| Transfer Certificate</title>
    <?php include "includes/head.php" ?>
</head>

 <body>

    <?php include "includes/header.php" ?>

    <div class="main relative mb-[40px] sm:mb-[120px] ">

        <div class="bg-center flex items-center text-center h-[300px] comman-banner">
            <div>
                <h1
                    class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 sm:mb-8 hr-line relative leading-9">
                    Transfer Certificate
                </h1>
            </div>

            <div class="md:w-[100%]">
                <h1
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    Transfer
                    <span class="sm:hidden"></span> Certificate
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
                        <a href="transfer-certificate" class="ms-1 sm:text-sm text-xs font-medium text-blue-main">Transfer Certificate</a>
                    </div>
                </li>
            </ol>
        </div>

        <div class=" mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-4">
            <div class="sm:mt-10 relative">


                <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md mt-10">
                    <form class=" block items-center gap-5" id="transfer-Form">
                        <!-- Admission No. -->
                        <div class="flex flex-col">
                            <label for="admissionNo" class="text-gray-700">Admission No.</label>
                            <input type="text" id="admissionNo" name="admissionNo" class="mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <span id="addmission-error" class="text-red-500 text-sm mt-1 block"></span>
                        </div>
                        <!-- Date of Birth -->
                        <div class="flex flex-col sm:mt-5 mt-3">
                            <label for="dob" class="text-gray-700">Date of Birth</label>
                            <input type="date" name="dob"
                                class="mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <p class="text-red-500 text-xs mt-1" id="dobError" style="display: none;">This Date
                                of Birth is required.</p>
                        </div>

                        <!-- Search Button -->
                        <div class="mt-6">
                            <button type="submit"
                                class="px-3 py-2 bg-blue-main text-white rounded-md  focus:outline-none focus:ring-2  w-full">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Results Table -->
                <div id="resultContainer" class="mt-6 hidden">
                    <table class="min-w-full border border-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                            <tr class="bg-gray-100">
                                <th class="px-6 py-4">Student Name</th>
                                <th class="px-6 py-4">Admission No.</th>
                                <th class="px-6 py-4">Class</th>
                                <th class="px-6 py-4">Parent Name</th>
                                <th class="px-6 py-4">DOB</th>
                                <th class="px-6 py-4">Download TC</th>
                            </tr>
                        </thead>
                        <tbody id="resultTableBody"></tbody>
                    </table>
                </div>

                <!-- No result message -->
                <p id="noResult" class="text-center text-red-500 mt-4 hidden">No data found.</p>

            </div>
        </div>
    </div>
    </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

    <?php include "includes/tc-search-script.php"; ?>

</body>

</html>