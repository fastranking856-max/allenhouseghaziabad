<?php
$page = "about-us";

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/about-us" />
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
    "name": "About Us",
    "item": "https://allenhouseghaziabad.com/about-us"
  }]
}
</script>

    <?php include "includes/head.php" ?>
 </head>

<body>

    <?php include "includes/header.php" ?>
    <?php
        require_once __DIR__ . '/includes/api.php';

        $about_page = null;
        if (function_exists('cms_curl_get_json') && defined('CMS_API_URL')) {
            $about_page = cms_curl_get_json(rtrim(CMS_API_URL, '/') . '/pages/about-us-page-ghaziabad');
        }
        if (!is_array($about_page)) {
            $about_page = fetchApiData('pages/about-us-page-ghaziabad');
        }

        $about_sections = is_array($about_page['data']['sections'] ?? null) ? $about_page['data']['sections'] : [];
        $welcome_section = $about_sections[1] ?? null;
        $principal_section = $about_sections[4] ?? null;
        $principal_text_col = $principal_section['columns'][0] ?? null;
        $principal_image_col = $principal_section['columns'][1] ?? null;

        if (!function_exists('cmsAboutAssetUrl')) {
            function cmsAboutAssetUrl(?string $path): string
            {
                if ($path === null || $path === '') {
                    return '';
                }
                $path = trim($path);
                $prefix = rtrim(API_BASE_URL, '/') . '/';
                if (strpos($path, $prefix) === 0) {
                    $rest = substr($path, strlen($prefix));
                    if (preg_match('#^https?://#i', $rest)) {
                        return $rest;
                    }
                }
                if (preg_match('#^https?://#i', $path)) {
                    return $path;
                }
                return rtrim(API_BASE_URL, '/') . '/' . ltrim($path, '/');
            }
        }

        $heading_data = fetchApiData('aboutus-heading-content/' . BRANCH_ID);
        $heading_row = $heading_data['data'][0] ?? null;
        $heading_title = trim((string) ($heading_row['title'] ?? 'Allenhouse Ghaziabad'));
        $heading_description = trim((string) ($heading_row['description'] ?? ''));
    ?>

    <div class="main relative ">
         <div class="bg-center flex items-center text-center h-[300px] common-banner "
            >
            <div>
                <h1
                    class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 sm:mb-8 hr-line relative leading-9">
                    About Us
                </h1>
            </div>

            <div class="md:w-[100%]">
                <h1
                    class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                    About Us
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
                                d="m1 9 4-4-4-4"></path>
                        </svg>
                        <a href="about-us" class="ms-1 sm:text-sm text-xs font-medium text-blue-main">About Us</a>
                    </div>
                </li>
            </ol>
        </div>
        <div
            class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 mx-3 sm:py-10 py-0 sm:p-20 p-0">
            <div>
                <?php if (!empty($welcome_section['content_heading'])): ?>
                    <?= $welcome_section['content_heading'] ?>
                <?php else: ?>
                <h1 class="text-[16px] font-[700] text-gray-500 leading-8 text-center relative">WELCOME TO <br><span
                        class="text-[32px] font-[700] text-blue-main hr-line uppercase"><?= htmlspecialchars($heading_title) ?></span>
                </h1>
                <?php endif; ?>

                <?php if (!empty($welcome_section['content'])): ?>
                <div class="ql-snow mt-5">
                    <div class="ql-editor">
                        <?= $welcome_section['content'] ?>
                    </div>
                </div>
                <?php elseif ($heading_description !== ''): ?>
                <p class="text-[16px] text-gray-500 mt-5"><?= htmlspecialchars($heading_description) ?></p>
                <?php endif; ?>
             </div>
        </div>

        <div
            class="mt-[-80px] 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3 sm:mt-3 bg-center sm:mb-10 mb-0 sm:px-20 p-0 relative">
            
            <div class="sm:flex gap-10 items-center">
                <div class="mx-3 pb-0 sm:pt-0 pt-[100px] sm:w-[50%]">
                    <div class="sm:text-left text-center">
                        <h2 class="text-[30px] font-[700] text-blue-main uppercase hr-line inline relative">Vision</h2>
                        <p class="text-[18px] text-gray-500 mt-1">Our group envisions a dynamic and transformative
                            learning
                            ecosystem that empowers children to become visionary thinkers, compassionate global citizens
                            and
                            ingenious problem solvers.</p>
                    </div>
                </div>
                <div class="sm:w-[50%] ">
                    <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/lOAxTO9EyZpnJ882BghlO9jMqGCVJiDVUGzol8C8.png" alt="">
                </div>
            </div>
        </div>

        <div
            class="mt-[-80px] 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3 sm:mt-0 bg-center sm:mb-10 mb-0 sm:px-20 p-0 relative">
            
            <div class="flex sm:flex-row flex-col-reverse  items-center gap-10">
                <div class="sm:w-[50%] ">
                    <img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/QqgSTew4Wvc74maSBHUZNpws8x0EVD48oVzw443c.png" alt="">
                </div>
                <div class="mx-3 pb-0 sm:pt-0 pt-[100px] sm:w-[50%]">
                    <div class="sm:text-left text-center">
                        <h2 class="text-[30px] font-[700] text-blue-main uppercase hr-line inline relative">Mission</h2>
                        <p class="text-[18px] text-gray-500 mt-1">Our mission is to revolutionize education by providing
                            an
                            immersive, personalized and holistic learning experience to every child, cultivating
                            intellectual agility, emotional intelligence and the mindset needed to thrive in a rapidly
                            evolving global landscape.</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($principal_section && !empty($principal_section['columns'])): ?>
        <div class="mt-8 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto px-4 sm:px-5">
            <div class="mt-10 relative">
                <div class="sm:flex gap-10">
                    <div class="sm:w-[50%]">
                        <?php if (!empty($principal_text_col['content_heading'])): ?>
                            <?= $principal_text_col['content_heading'] ?>
                        <?php else: ?>
                        <h2 class="text-[30px] font-[700] text-blue-main sm:text-left text-center mb-5 hr-line relative">
                            Principal's Message
                        </h2>
                        <?php endif; ?>
                        <?php if (!empty($principal_text_col['content'])): ?>
                        <div class="ql-snow">
                            <div class="ql-editor">
                                <?= $principal_text_col['content'] ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="sm:w-[50%] mt-4">
                        <?php if (!empty($principal_image_col['image_url'])): ?>
                        <img src="<?= htmlspecialchars(cmsAboutAssetUrl($principal_image_col['image_url'])) ?>"
                            class="border-[1px] border-gray-100 sm:w-auto w-[100%]" alt="Principal">
                        <?php endif; ?>
                        <?php if (!empty($principal_image_col['content'])): ?>
                        <div class="mt-1">
                            <?= $principal_image_col['content'] ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>


        <div class="mb-5 ab-cr-bg sm:mt-10 sm:p-4">
            <div class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto">
                <div class="relative about-carousel opne-hide-circle">

                    <h2 class="text-[30px] font-[700] sm:text-white text-blue-main sm:text-left text-center mt-4 hr-line relative">A
                        Visionary Institution for Excellence</h2>
                    <!-- Slides -->
                    <div class="overflow-hidden mt-5 mx-3 " data-glide-el="track">
                        <ul
                            class="mt-5 relative w-full overflow-hidden p-0 whitespace-no-wrap flex flex-no-wrap [backface-visibility: hidden] [transform-style: preserve-3d] [touch-action: pan-Y] [will-change: transform]">
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/CjybzKrsqJIuD9Bflj5NduZ1Jb678Jt9adbui5DX.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/739b1rcOR5KDqw1vsST0wW3zmhvfqNLahR9iSfhp.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/4Y3kttdJ4eKe9agzuOOTiyrVGnEHTgQhtwy4cQRE.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/WPDIoR9pjmUDLjA7g6mUW4R2WjQh2tQlF5kuwnqd.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/yoZ7m1Si0pnE65pRns0yYGH7JZRvG9s0dBzfd74E.jpg"
                                    class="w-full max-w-full m-auto" /></li>

                        </ul>
                    </div>
                    <!-- Controls -->
                    <div class="absolute left-0 xl:flex hidden items-center justify-between w-full h-0 px-4 top-[60%] hide-circle"
                        data-glide-el="controls">
                        <button
                            class="inline-flex items-center relative sm:right-[66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir="<" aria-label="prev slide">
                            <i class="fa-solid fa-angle-left"></i>
                        </button>
                        <button
                            class="inline-flex items-center relative sm:left-[66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir=">" aria-label="next slide">
                            <i class="fa-solid fa-angle-right"></i>
                        </button>
                    </div>
                </div>
                <div class="bg-blue-main px-3 mt-[-140px] pt-[160px] pb-5">
                    <p class="text-white">At Allenhouse Public School, Ghaziabad, we cultivate young minds into
                        compassionate and well-groomed future leaders of tomorrow. Our legacy of excellence in education
                        empowers our students to excel both academically and personally.
                    </p>
                    <p class="text-white mt-3">We offer multi-faceted education that brings together meticulous academics with hands-on learning experiences. We adopt cutting-edge technologies, offering tailored programs in Robotics, Animation, and STEM education to kindle thirst and build technical proficiency. These experiential learnings prepare them for careers in rapidly evolving fields.</p>
                    <p class="text-white mt-3">In addition to academics, we place a strong emphasis on SSFL-Smart skills
                        for learning, helping students develop leadership qualities, eloquent communication skills, and
                        emotional intelligence. Join us at APS, where we ensure every child has the opportunity to
                        explore their passions and grow into a well-rounded individual.</p>
                </div>
            </div>
        </div>

        <div class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto px-3 mb-5">
            <div class="relative about-carousel2  opne-hide-circle">
                <!-- Slides -->
                <div class="overflow-hidden mt-5" data-glide-el="track">
                    <ul
                        class="mt-5 relative w-full overflow-hidden p-0 whitespace-no-wrap flex flex-no-wrap [backface-visibility: hidden] [transform-style: preserve-3d] [touch-action: pan-Y] [will-change: transform]">

                        <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/tEGUmxR4JhmKxuaGy8WdumOykoKGMXue7fcLAPgV.jpg"
                                class="w-full max-w-full m-auto" /></li>
                        <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/kSLBAkEyULW7IPQItgMm2oKDaFvMo4fU2vJc6OKw.jpg"
                                class="w-full max-w-full m-auto" /></li>
                        <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/lAMHEAzgyQQiVBw6GUhwuy9tTD9CBbqXMvVOah3A.jpg"
                                class="w-full max-w-full m-auto" /></li>
                        <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/wZrqfqUFkS5ddd5F4jRrWs18RWoYdQLkjXo3pKzX.jpg"
                                class="w-full max-w-full m-auto" /></li>
                        <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/OcBtx6tA42SoRgddJqhjVMgTSLJJkJz6VmA6o77u.jpg"
                                class="w-full max-w-full m-auto" /></li>
                    </ul>
                </div>
                <!-- Controls -->
                <div class="absolute left-0 xl:flex hidden items-center justify-between w-full h-0 px-4 top-[60%] hide-circle"
                    data-glide-el="controls">
                    <button
                        class="inline-flex items-center relative sm:right-[66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                        data-glide-dir="<" aria-label="prev slide">
                        <i class="fa-solid fa-angle-left"></i>
                    </button>
                    <button
                        class="inline-flex items-center relative sm:left-[66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                        data-glide-dir=">" aria-label="next slide">
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[18px] text-gray-500">
                    Our extensive amenities cater to sports enthusiasts and include a multi-purpose ground for various
                    field games, a skating rink, a swimming pool, a basketball court and indoor gaming rooms. We also
                    provide a dedicated yoga hall and separate spaces for Western and Indian classical, vocal and
                    instrumental music, arts and crafts and dance, to nurture students' diverse talents.

                </p>
                <p class="text-[18px] text-gray-500 mt-3">
                    Our institution's holistic approach to education is manifested in the numerous accolades that
                    Superhouse has garnered over the years. Our students have emerged victorious in the Wiz National
                    Spell Bee and various International Olympiads and we also organise the most cherished Erudite
                    International Literary Fest, an immersive event organised by and for the students.
                </p>
            </div>
        </div>



        <div class="mb-5 ab-cr-bg sm:mt-10 sm:p-4">
            <div class="2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto">
                <div class="relative  glide-last opne-hide-circle">

                    <!-- Slides -->
                    <div class="overflow-hidden mt-5 mx-3 " data-glide-el="track">
                        <ul
                            class="mt-5 relative w-full overflow-hidden p-0 whitespace-no-wrap flex flex-no-wrap [backface-visibility: hidden] [transform-style: preserve-3d] [touch-action: pan-Y] [will-change: transform]">
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/vZ4vghRszdkLcUZSUxN18OitpOLpwCz8Y6NPiHGU.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/czF2ys91H4s0kJ9CUjOcrEjHEuQwXXdbjaiiukKc.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/6FP5fYQEmT6pZwiCH5tYmh00uRMwXOo5jElm79MP.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/2XDQo9xZsPirBbIGj5VKq2ZQW1Xia3h8Q5NLv5sJ.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/v6ppX19pl6rhB6oUPIDEzAfQUA05OkD6EO03ZiIB.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/v5D2YbJULC3suSdejAf2eTGKu33fOmSxgUhNr0jk.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/IalwERTITaq7Tf3ycLNxo1yFug3y6OmzLDqGTfCn.jpg"
                                    class="w-full max-w-full m-auto" /></li>
                            <li><img src="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/wUBRkSTb7vIvMnsLju0uzSe9az4xT7WruKbBo81p.jpg"
                                    class="w-full max-w-full m-auto" /></li>

                        </ul>
                    </div>
                    <!-- Controls -->
                    <div class="absolute left-0 xl:flex hidden items-center justify-between w-full h-0 px-4 top-[60%] hide-circle"
                        data-glide-el="controls">
                        <button
                            class="inline-flex items-center relative sm:right-[66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir="<" aria-label="prev slide">
                            <i class="fa-solid fa-angle-left"></i>
                        </button>
                        <button
                            class="inline-flex items-center relative sm:left-[66px] justify-center hover:bg-red-500 hover:text-white w-8 h-8 transition duration-300 border rounded-full lg:w-10 lg:h-10 text-slate-700 border-slate-700 hover:text-slate-900 hover:border-slate-900 focus-visible:outline-none bg-white/20"
                            data-glide-dir=">" aria-label="next slide">
                            <i class="fa-solid fa-angle-right"></i>
                        </button>
                    </div>
                </div>
                <div class="bg-blue-main px-3 mt-[-140px] pt-[160px] pb-5 text-center">
                    <p class="text-white"> Being a proud part of the Superhouse Educational Foundation, here at
                        Allenhouse, Ghaziabad, we are all about nurturing young minds. We foster 21st-century skills and
                        ensure today’s education is the stepping stone to tomorrow’s leadership!
                    </p>

                </div>
            </div>
        </div>



        <?php include "includes/footer.php" ?>
    </div>
    <?php include "includes/foot.php" ?>
    <script>
    (function () {
        if (typeof window.safeMountGlide !== 'function') {
            return;
        }
        var mount = window.safeMountGlide;
        var glideOpts = {
            type: 'carousel',
            focusAt: 1,
            perView: 4,
            autoplay: 3500,
            animationDuration: 700,
            gap: 24,
            breakpoints: {
                1680: { perView: 4 },
                1024: { perView: 3 },
                820: { perView: 2 },
                640: { perView: 1 }
            }
        };
        mount('.about-carousel', glideOpts);
        mount('.glide-last', glideOpts);
        mount('.about-carousel2', glideOpts);
    })();
    </script>
</body>

</html>