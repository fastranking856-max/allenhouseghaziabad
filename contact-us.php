<?php
$page = "contact-us";
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/contact-us" />
    <?php include "includes/meta.php" ?>
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Home",
    "item": "https://allenhouseghaziabad.com/"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "Contact Us",
    "item": "https://allenhouseghaziabad.com/contact-us"
  }]
}
</script>

    <?php include "includes/head.php" ?>
</head>

<body>

    <?php include "includes/header.php" ?>

    <div class="main relative">
        <style>
            .bg-gredient-image::before {
                content: '';
                position: absolute;
                height: 100%;
                width: 100%;
                background: linear-gradient(270deg, rgba(217, 217, 217, 0) 0%, #132959 88.65%);
            }
        </style>
        <div class="">
            <div class="ralative relative bg-gredient-image">
                <div class="bg-center flex items-center text-left h-[300px] comman-banner">
                    <div>
                        <h2 class="text-[32px] sm:hidden block font-[700] text-white text-left mb-5 sm:mb-8 hr-line relative leading-9 pl-4">
                            Contact Us
                        </h2>
                    </div>
                    <div class="md:w-[100%]">
                        <h2 class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                            Contact Us
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex m-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center text-xs sm:text-sm font-medium text-blue-main">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-blue-main mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"></path>
                        </svg>
                        <a href="contact-us" class="ms-1 text-xs sm:text-sm font-medium text-blue-main">Contact Us</a>
                    </div>
                </li>
            </ol>
        </div>

        <div class="lg:w-[1024px] md:w-[800px] sm:w-[640px] sm:mx-auto sm:px-5 px-5 mb-10 relative mt-10">
            <div class="md:flex sm:mx-2 bg-images">
                <div class="sm:w-[50%] sm:mt-4 mt-10">
                    <div class="sm:text-left">
                        <h2 class="sm:text-[32px] text-[28px] font-[700] text-blue-main uppercase leading-9">Let's talk with us</h2>
                        <p class="sm:text-[20px] text-[18px] text-gray-500 font-[400] sm:mt-3 mt-2">Fill out the form & we'll be in <br> touch soon!</p>
                    </div>
                    <div class="mt-4 sm:mt-6">
                        <div class="flex gap-2 mb-3">
                            <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/ZmL7hSpo40CpVDMhScotlXkFAz0XlzJyoGhO2Pq4.png" alt="" class="w-[28px] block h-[30px] mt-[3px]">
                            <p class="text-gray-700 font-[400] sm:text-[18px] text-[16px]">
                                <?php echo $addressdata['data'][0]['addressline1'] ?><br>
                                <?php echo $addressdata['data'][0]['addressline2'] ?> - <?php echo $addressdata['data'][0]['pincode'] ?>
                            </p>
                        </div>
                        <div class="flex gap-2 mb-3">
                            <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/oJ6mhPYvNt2N9dgrQ8zY1IeW1ds6f8l9E9yvAiDS.png" alt="" class="w-[23px] block h-[23px]">
                            <p class="text-gray-700 font-[400] sm:text-[18px] text-[16px]"> For <strong>Admission</strong> Enquiries: <a href="tel:<?php echo $addressdata['data'][0]['contact1'] ?>"> +91-<?php echo $addressdata['data'][0]['contact1'] ?></a></p>
                        </div>
                        <div class="flex gap-2 mb-3">
                            <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/oJ6mhPYvNt2N9dgrQ8zY1IeW1ds6f8l9E9yvAiDS.png" alt="" class="w-[23px] block h-[23px]">
                            <p class="text-gray-700 font-[400] sm:text-[18px] text-[16px]"> For <strong>General</strong> Enquiries: <a href="tel:919717652378"> +91 9717652378</a></p>
                        </div>
                        <div class="flex gap-2">
                            <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/Uk9fc8u92eMqkV3meFQNiUoRYB2DxzNi4d5T17Id.png" alt="" class="w-[23px] block h-[23px]">
                            <p class="text-gray-700 font-[400] sm:text-[18px] text-[16px]"><a href="mailto:<?php echo $addressdata['data'][0]['email'] ?>"><?php echo $addressdata['data'][0]['email'] ?></a></p>
                        </div>
                    </div>
                </div>

                <div class="sm:w-[50%]">
                    <div class="mt-5">
                        <form id="contactForm">
                            <div>
                                <!-- NAME FIELD - 2 CONDITIONS + ERRORS -->
                                <div>
                                    <input type="text" id="cname" placeholder="Name" maxlength="50"
                                        class="w-full border-[1px] p-[11px] rounded-[5px] outline-none" required>
                                    <span id="cname-error" class="text-red-500 text-sm mt-1 hidden">Only letters and spaces allowed.</span>
                                    <span id="cname-length-error" class="text-red-500 text-sm mt-1 hidden">Only 50 characters allowed.</span>
                                </div>

                                <!-- MOBILE -->
                                <div class="mt-4">
                                    <input type="text" placeholder="Mobile" id="cmobile"
                                        class="w-full border-[1px] p-[11px] rounded-[5px] outline-none" required>
                                    <div id="mobileErrorss" class="text-red-500 text-sm mt-1 hidden">Please enter a valid 10-digit Indian mobile number.</div>
                                </div>

                                <!-- EMAIL - FIXED -->
                                <div class="mt-4">
                                    <input type="email" placeholder="E-mail" id="cemail"
                                        class="w-full border-[1px] p-[11px] rounded-[5px] outline-none" required>
                                    <span id="emailErrorss" class="text-red-500 text-sm mt-1 hidden">Please enter a valid email address.</span>
                                </div>

                                <div class="mt-4">
                                    <select name="class-selection" id="cquery" required
                                        class="w-full border border-gray-300 p-2 rounded-md text-gray-500 text-[#808080d4]">
                                        <option value="" disabled selected>Select Query</option>
                                        <option value="General">General</option>
                                        <option value="Admission Related">Admission Related</option>
                                        <option value="Job Related">Job Related</option>
                                        <option value="Suggestion/Feedback">Suggestion/Feedback</option>
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <textarea id="cmessage" class="w-full border-[1px] p-[11px] rounded-[5px]" placeholder="Message" required></textarea>
                                </div>

                                <div id="successPopup" class="relative bg-green-500 text-white px-4 py-2 rounded mb-5 hidden" style="z-index:999">
                                    Form submitted successfully!
                                </div>

                                <div class="mt-4">
                                    <button type="submit" id="contactSubmitBtn"
                                        class="uppercase p-3 bg-blue-main w-full text-white font-[600] text-[18px] transition hover:bg-red-500">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <iframe class="w-full"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4343.957477721101!2d77.3880961!3d28.668943700000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cf0718d9f1e49%3A0xb42a93a4c772a5fa!2sAllenhouse%20Public%20School!5e1!3m2!1sen!2sin!4v1747910023867!5m2!1sen!2sin"
                height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <?php include "includes/form-proxy-client.php"; ?>
        <?php include "includes/contact-form-script.php"; ?>

        <?php include "includes/footer.php" ?>
        <?php include "includes/foot.php" ?>
    </div>
</body>
</html>