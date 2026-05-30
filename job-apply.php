<?php
$jobid = $_GET['id'] ?? null;
require_once __DIR__ . "/proxy/config.php";
require_once __DIR__ . '/includes/api-adapters.php';

$jobsResponse = cms_fetch_json_url(rtrim(CMS_API_URL, '/') . '/jobs/branch/' . BRANCH_ID);
$j_data = cmsFindJobInResponse($jobsResponse, $jobid);

if (!$j_data) {
    $redirect = 'apply-jobs';
    if ($jobid !== null && $jobid !== '') {
        $redirect .= '?id=' . urlencode((string) $jobid);
    }
    header('Location: ' . $redirect);
    exit;
}

// Job Apply
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form fields
    $fullName = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $jobId = $_POST['jobid'] ?? '';

    // Validate resume
    if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
        die('Resume file is required.');
    }

    // Move uploaded resume to a temporary location
    $resumeTmp = $_FILES['resume']['tmp_name'];
    $resumeName = $_FILES['resume']['name'];
    $resumeType = $_FILES['resume']['type'];

    // Prepare CURLFile for resume
    $resumeFile = new CURLFile($resumeTmp, $resumeType, $resumeName);

    // Prepare post data
    $postData = [
        'full_name' => $fullName,
        'email' => $email,
        'phone' => $phone,
        'jobid' => $jobId,
        'resume' => $resumeFile
    ];

    // Check if cover letter is provided and add it
    if (isset($_FILES['cover_latter']) && $_FILES['cover_latter']['error'] === UPLOAD_ERR_OK) {
        $coverTmp = $_FILES['cover_latter']['tmp_name'];
        $coverName = $_FILES['cover_latter']['name'];
        $coverType = $_FILES['cover_latter']['type'];
        $coverFile = new CURLFile($coverTmp, $coverType, $coverName);
        $postData['cover_latter'] = $coverFile;
    }

    // cURL request
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => rtrim(CMS_API_URL, '/') . '/apply-job/' . BRANCH_ID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => [
            'Content-Type: multipart/form-data'
        ],
        // For production, consider using:
        // CURLOPT_CAINFO => __DIR__ . '/cacert.pem',
    ]);
    apply_curl_ssl_options($ch);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        echo "
            <div id='successMessage' class='text-red-600 font-medium bg-green-100 border border-green-300 p-3 rounded-md mt-4 fixed top-0 left-0 right-0 z-[9999] text-center w-[400px] mx-auto'>
                <p>Job application submitted successfully.</p>
            </div>
            <script>
                setTimeout(function() {
                    var popup = document.getElementById('successMessage');
                    if (popup) {
                        popup.style.display = 'none';
                    }
                }, 5000);
            </script>
        ";
    }

    curl_close($ch);
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="Careers at Allenhouse" />
    <meta property="og:description"
        content="Explore exciting job opportunities at Allenhouse Public School and become part of our dedicated faculty." />
    <meta property="og:url" content="https://www.allenhouseschools.com/job-openings" />
    <title>Job Application</title>
    <?php include "includes/head.php" ?>
    <link rel="canonical" href="https://www.allenhouseschools.com/job-openings" />

    <meta name="description"
        content="Explore exciting job opportunities at Allenhouse Public School and become part of our dedicated faculty.">
</head>

<body>

    <?php include "includes/header.php" ?>

    <div class="main relative top-[91px] mb-[120px] container sm:mx-auto">

        <div class=" text-gray-800">

            <!-- Container -->
            <div class="max-w-5xl mx-auto p-6 space-y-8 bg-white shadow-xl mt-10 rounded-xl">

                <!-- Job Header -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <h1 class="text-3xl font-bold "><?php echo $j_data['job_title'] ?></h1>
                        <p
                            class="text-[#12B76A] text-sm ml-2  flex items-center border border-[1px] border-[#12B76A] bg-[#ECFDF3] rounded-full p-1 px-2">
                            <svg width="7" height="8" viewBox="0 0 7 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="3.5" cy="4" r="3.1" fill="#ECFDF3" stroke="#12B76A" stroke-width="0.8" />
                                <circle cx="3.50039" cy="4.00039" r="2.1" fill="#12B76A" />
                            </svg>
                            <?php echo $j_data['status'] ?>
                        </p>
                    </div>
                    <p class="text-sm">Full Time | Expected Experience: <?php echo $j_data['from_exp'] ?>-<?php echo $j_data['to_exp'] ?> years</p>
                    <p class="text-sm">Openings: <?php echo $j_data['openings'] ?> </p>
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.85276 1.53995C2.6972 1.53995 2.5572 1.59439 2.43276 1.70328C2.30832 1.81217 2.24609 1.95217 2.24609 2.12328C2.24609 2.29439 2.30832 2.44217 2.43276 2.56661C2.5572 2.69106 2.6972 2.75328 2.85276 2.75328H4.85943C5.32609 2.75328 5.73832 2.8855 6.09609 3.14995C6.45387 3.41439 6.71054 3.76439 6.86609 4.19995H2.85276C2.6972 4.19995 2.5572 4.26217 2.43276 4.38661C2.30832 4.51106 2.24609 4.65106 2.24609 4.80661C2.24609 4.96217 2.30832 5.10217 2.43276 5.22661C2.5572 5.35106 2.6972 5.41328 2.85276 5.41328H6.86609C6.71054 5.84884 6.45387 6.19884 6.09609 6.46328C5.73832 6.72773 5.32609 6.85995 4.85943 6.85995H2.85276C2.72832 6.85995 2.61943 6.89884 2.52609 6.97661C2.43276 7.05439 2.36276 7.14773 2.31609 7.25661C2.26943 7.3655 2.25387 7.48217 2.26943 7.60661C2.28498 7.73106 2.33943 7.83995 2.43276 7.93328L6.44609 11.9466C6.60165 12.1022 6.79609 12.1488 7.02943 12.0866C7.26276 12.0244 7.41054 11.8844 7.47276 11.6666C7.53498 11.4488 7.47276 11.2466 7.28609 11.0599L4.34609 8.07328H4.85943C5.66832 8.07328 6.37609 7.82439 6.98276 7.32661C7.58943 6.82884 7.95498 6.19106 8.07943 5.41328H10.2261C10.3816 5.41328 10.5217 5.35106 10.6461 5.22661C10.7705 5.10217 10.8328 4.96217 10.8328 4.80661C10.8328 4.65106 10.7705 4.51106 10.6461 4.38661C10.5217 4.26217 10.3816 4.19995 10.2261 4.19995H8.07943C7.98609 3.67106 7.76832 3.18884 7.42609 2.75328H10.2261C10.3816 2.75328 10.5217 2.69106 10.6461 2.56661C10.7705 2.44217 10.8328 2.29439 10.8328 2.12328C10.8328 1.95217 10.7705 1.81217 10.6461 1.70328C10.5217 1.59439 10.3816 1.53995 10.2261 1.53995H2.85276Z"
                                fill="#474D6A" stroke="#474D6A" stroke-width="0.3" />
                        </svg>
                        <?php echo $j_data['min_salary'] ?> - <?php echo $j_data['max_salary'] ?>
                    </p>
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6.63139 15.0075C6.75939 14.8368 6.88206 14.6662 6.99939 14.4955L7.36739 15.0075C7.25006 15.0715 7.12739 15.1035 6.99939 15.1035C6.87139 15.1035 6.74872 15.0715 6.63139 15.0075ZM6.99939 3.77552C6.54072 3.77552 6.12206 3.89018 5.74339 4.11952C5.36472 4.34885 5.06339 4.65551 4.83939 5.03952C4.61539 5.42352 4.50339 5.84218 4.50339 6.29552C4.50339 6.74885 4.61539 7.16752 4.83939 7.55152C5.06339 7.93552 5.36472 8.23952 5.74339 8.46352C6.12206 8.68752 6.54072 8.79952 6.99939 8.79952C7.45806 8.79952 7.87939 8.68752 8.26339 8.46352C8.64739 8.23952 8.94872 7.93552 9.16739 7.55152C9.38606 7.16752 9.49539 6.74885 9.49539 6.29552C9.49539 5.84218 9.38606 5.42352 9.16739 5.03952C8.94872 4.65551 8.64739 4.34885 8.26339 4.11952C7.87939 3.89018 7.45806 3.77552 6.99939 3.77552ZM5.76739 6.28752C5.76739 5.93552 5.89006 5.63685 6.13539 5.39151C6.38072 5.14618 6.66872 5.02351 6.99939 5.02351C7.33006 5.02351 7.61806 5.14618 7.86339 5.39151C8.10872 5.63685 8.23139 5.93818 8.23139 6.29552C8.23139 6.65285 8.10872 6.95418 7.86339 7.19952C7.61806 7.44485 7.33006 7.56752 6.99939 7.56752C6.66872 7.56752 6.38072 7.44485 6.13539 7.19952C5.89006 6.95418 5.76739 6.65018 5.76739 6.28752ZM6.63139 15.0075L6.99939 14.4955L7.36739 15.0075L7.62339 14.8155C7.83672 14.6555 8.05539 14.4848 8.27939 14.3035C9.00472 13.7275 9.66606 13.1142 10.2634 12.4635C11.1167 11.5568 11.7887 10.6448 12.2794 9.72752C12.9087 8.55418 13.2234 7.40485 13.2234 6.27952C13.2234 5.15418 12.9407 4.10618 12.3754 3.13552C11.8314 2.17552 11.0901 1.41818 10.1514 0.863515C9.18072 0.287515 8.13006 -0.00048542 6.99939 -0.00048542C5.86872 -0.00048542 4.81806 0.287515 3.84739 0.863515C2.90872 1.41818 2.16739 2.17552 1.62339 3.13552C1.05806 4.10618 0.775391 5.15418 0.775391 6.27952C0.775391 7.40485 1.09006 8.55418 1.71939 9.72752C2.21006 10.6448 2.88206 11.5568 3.73539 12.4635C4.33272 13.1142 4.99406 13.7275 5.71939 14.3035C5.94339 14.4848 6.16206 14.6555 6.37539 14.8155L6.63139 15.0075ZM2.03939 6.28752C2.03939 5.37018 2.26872 4.51685 2.72739 3.72752C3.16472 2.97018 3.76206 2.36485 4.51939 1.91152C5.27672 1.45818 6.10339 1.23151 6.99939 1.23151C7.89539 1.23151 8.72206 1.45818 9.47939 1.91152C10.2367 2.36485 10.8341 2.97018 11.2714 3.72752C11.7301 4.51685 11.9594 5.37018 11.9594 6.28752C11.9594 7.20485 11.6874 8.15952 11.1434 9.15152C10.7061 9.97285 10.0981 10.7995 9.31939 11.6315C8.76472 12.2288 8.14606 12.8048 7.46339 13.3595C7.30339 13.4875 7.14872 13.6102 6.99939 13.7275C6.85006 13.6102 6.69539 13.4875 6.53539 13.3595C5.85272 12.8048 5.23406 12.2288 4.67939 11.6315C3.90072 10.7995 3.29272 9.97285 2.85539 9.15152C2.31139 8.15952 2.03939 7.20485 2.03939 6.28752Z"
                                fill="#474D6A" />
                        </svg>
                        PS2, Sector 2A,Allenhouse Public School Vasundhara, Ghaziabad
                    </p>
                </div>

                <!-- Job Description Box -->
                <div class="border border-blue-300 p-6 rounded-xl space-y-4">
                    <h2 class="text-xl font-semibold">Job Description</h2>
                    <div class="grid sm:grid-cols-2 gap-4 text-sm">
                        <div><strong>Title:</strong> <?php echo $j_data['job_title'] ?></div>
                        <div><strong>Job Type:</strong> <?php echo $j_data['job_type'] ?></div>
                        <div><strong>Education:</strong> <?php echo $j_data['req_qualification'] ?></div>
                        <div>
                            <button class="font-[600] flex gap-1 items-center" id="viewDesc">
                                <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.12585 0.695312C1.50505 0.695312 1 1.19903 1 1.81815V14.1817C1 14.8008 1.50502 15.3045 2.12585 15.3045H10.5501C11.1709 15.3045 11.6759 14.8008 11.6759 14.1817V4.84366C11.6759 4.77101 11.6478 4.70119 11.5974 4.64883L7.87542 0.781416C7.82246 0.726403 7.74937 0.695312 7.67295 0.695312H2.12585ZM2.12585 1.25718H7.18081V4.90948C7.18081 5.06464 7.3066 5.19041 7.46174 5.19041H11.114V14.1817C11.114 14.491 10.8611 14.7426 10.5501 14.7426H2.12585C1.81486 14.7426 1.56187 14.491 1.56187 14.1817V1.81815C1.56187 1.50885 1.81486 1.25718 2.12585 1.25718ZM7.74267 1.45385L10.798 4.62854H7.74267V1.45385ZM6.22376 4.88808C6.15411 4.88722 6.0761 4.89605 5.98886 4.91789C5.76875 4.9729 5.41462 5.18788 5.51572 6.0093C5.56043 6.37222 5.69331 6.83461 5.89028 7.33232C5.78439 7.66408 5.66648 8.011 5.55049 8.35206C5.44124 8.67335 5.32826 9.00551 5.22765 9.31864C5.14699 9.56982 5.07299 9.80736 5.00317 10.0319C4.9053 10.3465 4.81659 10.6316 4.72954 10.8898C3.22742 11.7427 2.62739 12.4814 2.59063 12.9358C2.57568 13.1205 2.64783 13.2809 2.78859 13.376C2.85918 13.4237 2.96047 13.464 3.10123 13.464C3.24273 13.464 3.42409 13.4232 3.65412 13.3082C4.46493 12.9028 4.84768 12.2324 5.18914 11.2772C5.47756 11.1194 5.80447 10.9544 6.17472 10.7835C6.75863 10.5141 7.25595 10.3476 7.67373 10.2498C8.15689 10.7572 8.66789 11.0903 9.14739 11.0903C9.66901 11.0903 10.0369 10.918 10.1568 10.6175C10.2451 10.3959 10.1777 10.1496 9.9762 9.95853C9.69013 9.68726 9.18119 9.54809 8.54412 9.56636C8.3326 9.57246 8.10894 9.59638 7.87531 9.63741C7.34587 9.00191 6.83181 8.11951 6.48641 7.30719C6.84035 6.16502 6.96963 5.41867 6.64697 5.06814C6.56631 4.98055 6.43272 4.89068 6.22376 4.88808ZM6.21959 5.44939C6.22906 5.44947 6.23399 5.45002 6.23419 5.44939C6.24426 5.46174 6.3468 5.61161 6.1623 6.38475C6.12038 6.22548 6.09013 6.07612 6.07346 5.94064C6.03198 5.60332 6.09157 5.4714 6.12513 5.46298C6.17286 5.45108 6.20332 5.44939 6.21959 5.44939ZM6.23029 8.09576C6.5207 8.68415 6.87848 9.276 7.26836 9.77662C6.84512 9.89484 6.39846 10.0614 5.93932 10.2733C5.76587 10.3534 5.60012 10.4329 5.4421 10.5116C5.47406 10.4099 5.50653 10.3055 5.5398 10.1988C5.60908 9.9759 5.68248 9.73998 5.76266 9.49062C5.8619 9.182 5.97403 8.85208 6.08249 8.53302C6.13365 8.38267 6.18298 8.23698 6.23029 8.09576ZM8.58776 10.1276C9.0921 10.1193 9.36715 10.2321 9.45196 10.2744C9.57166 10.3342 9.61747 10.392 9.62968 10.4157C9.59958 10.4485 9.469 10.5284 9.14745 10.5284C8.90849 10.5284 8.6374 10.3826 8.35749 10.139C8.43901 10.1324 8.51571 10.1287 8.58776 10.1276ZM4.38513 11.7599C4.14705 12.2426 3.85875 12.5777 3.40284 12.8056C3.30263 12.8558 3.22848 12.8801 3.17697 12.8917C3.26311 12.7026 3.57818 12.297 4.38513 11.7599Z"
                                        fill="#FF2016" stroke="#FF2016" stroke-width="0.3" />
                                </svg>
                                <span> View Job Description</span>
                            </button>
                        </div>
                    </div>
                     <div id="openPdf" style="display:none; margin-top:15px;">
                        <embed
                            src="<?php echo $j_data['jd'][0]['jd'] ?>#toolbar=0&navpanes=0&scrollbar=0"
                            type="application/pdf"
                            width="100%"
                            height="700px">
                    </div>
                     <script>
                        document.getElementById("viewDesc").addEventListener("click", function() {
                            const pdfUrl = "<?php echo $j_data['jd'][0]['jd'] ?>";

                            // Detect mobile
                            if (/Mobi|Android|iPhone|iPad/i.test(navigator.userAgent)) {
                                // On mobile → open in default viewer (new tab)
                                window.open(pdfUrl, "_blank");
                            } else {
                                // On desktop → toggle inline PDF
                                const pdfDiv = document.getElementById("openPdf");
                                pdfDiv.style.display = pdfDiv.style.display === "none" ? "block" : "none";
                            }
                        });
                    </script>
                  </div>

                <!-- Application Form -->
                <form id="applicationForm" method="post" action="" class="space-y-6" enctype="multipart/form-data" novalidate>
                    <h2 class="text-xl font-semibold">Application Form</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label class="block mb-1 font-medium">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" id="full_name" name="full_name" required
                                class="w-full border border-gray-300 p-2 rounded-md" />
                            <p id="nameError" class="text-sm text-red-500 hidden mt-1">Only letters and spaces allowed.</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block mb-1 font-medium">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required
                                class="w-full border border-gray-300 p-2 rounded-md" />
                            <p id="emailError" class="text-sm text-red-500 hidden mt-1">Enter a valid email.</p>
                        </div>

                        <!-- Mobile No -->
                        <div>
                            <label class="block mb-1 font-medium">Mobile No <span class="text-red-500">*</span></label>
                            <input type="text" id="phone" name="phone" required maxlength="10"
                                class="w-full border border-gray-300 p-2 rounded-md" />
                            <p id="phoneError" class="text-sm text-red-500 hidden mt-1">Enter a valid 10-digit phone number (starting with 6-9).</p>
                        </div>
                    </div>

                    <!-- Resume -->
                    <div>
                        <label class="block mb-1 font-medium">Upload Resume <span class="text-red-500">*</span></label>
                        <input type="file" required name="resume" id="resume" accept=".pdf,.doc,.docx,image/*"
                            class="w-full p-2 border border-dashed border-gray-400 rounded-md" />
                        <p id="resumeError" class="text-sm text-red-500 hidden mt-1">File must be ≤ 2MB.</p>
                    </div>

                    <!-- Cover Letter -->
                    <div>
                        <label class="block mb-1 font-medium">Upload Cover Letter (optional)</label>
                        <input type="file" name="cover_latter" id="cover_latter" accept=".pdf,.doc,.docx,image/*"
                            class="w-full p-2 border border-dashed border-gray-400 rounded-md" />
                        <p id="coverError" class="text-sm text-red-500 hidden mt-1">File must be ≤ 2MB.</p>
                    </div>

                    <input type="hidden" name="jobid" value="<?php echo $jobid ?? '0'; ?>">

                    <button type="submit" name="submit" class="bg-blue-main text-white px-6 py-2 rounded-md hover:bg-red-500">
                        Submit
                    </button>
                </form>

            </div>
        </div>

    </div>
    <script>
        const form = document.getElementById("applicationForm");

        const fullName = document.getElementById("full_name");
        const email = document.getElementById("email");
        const phone = document.getElementById("phone");
        const resume = document.getElementById("resume");
        const cover = document.getElementById("cover_latter");

        const nameRegex = /^[A-Za-z\s]+$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[6-9]\d{9}$/; // Indian phone number: 10 digits starting with 6-9

        fullName.addEventListener("input", () => {
            document.getElementById("nameError").classList.toggle("hidden", nameRegex.test(fullName.value.trim()));
        });

        email.addEventListener("input", () => {
            document.getElementById("emailError").classList.toggle("hidden", emailRegex.test(email.value.trim()));
        });

        phone.addEventListener("input", () => {
            document.getElementById("phoneError").classList.toggle("hidden", phoneRegex.test(phone.value.trim()));
        });

        resume.addEventListener("change", () => {
            const file = resume.files[0];
            document.getElementById("resumeError").classList.toggle("hidden", !file || file.size <= 2 * 1024 * 1024);
        });

        cover.addEventListener("change", () => {
            const file = cover.files[0];
            document.getElementById("coverError").classList.toggle("hidden", !file || file.size <= 2 * 1024 * 1024);
        });

        form.addEventListener("submit", function(e) {
            let isValid = true;

            if (!nameRegex.test(fullName.value.trim())) {
                document.getElementById("nameError").classList.remove("hidden");
                isValid = false;
            }
            if (!emailRegex.test(email.value.trim())) {
                document.getElementById("emailError").classList.remove("hidden");
                isValid = false;
            }
            if (!phoneRegex.test(phone.value.trim())) {
                document.getElementById("phoneError").classList.remove("hidden");
                isValid = false;
            }

            const resumeFile = resume.files[0];
            if (!resumeFile || resumeFile.size > 2 * 1024 * 1024) {
                document.getElementById("resumeError").classList.remove("hidden");
                isValid = false;
            }

            const coverFile = cover.files[0];
            if (coverFile && coverFile.size > 2 * 1024 * 1024) {
                document.getElementById("coverError").classList.remove("hidden");
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

</body>

</html>