<?php
$page = "blog"
 ?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="canonical" href="https://allenhouseghaziabad.com/blog" />
    <?php include "includes/meta.php"; ?>
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
    "name": "Blog",
    "item": "https://allenhouseghaziabad.com/blog"
  }]
}
</script>

    <?php include "includes/head.php" ?>
</head>

<body>

    <?php include "includes/header.php" ?>
    <?php
    require_once __DIR__ . '/includes/environment.php';
    require_once __DIR__ . '/includes/cms-page-helpers.php';
    cmsPrefetchBlogPage();
    require_once __DIR__ . '/includes/api-adapters.php';
    $blogItems = cmsBlogListForDisplay();
    $blogDetailHref = static function (string $slug): string {
        return site_base_url() . 'blog/' . rawurlencode($slug);
    };
    ?>
    <div class="main relative sm:top-[20px] mb:[40px] sm:mb-[120px] mx-0 sm:mx-2">
        <div class="mt-8 mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-4">
            <div class="sm:mt-10 mt-5 relative">

                <h1
                    class="text-[32px] sm:hidden block font-[700] text-blue-main  text-center mb-5 sm:mb-8 hr-line relative leading-9">
                    Our Latest Blogs
                </h1>
                <div>

                    <div class="md:w-[100%]">
                        <h1
                            class="sm:text-[32px] sm:block hidden font-[700] text-blue-main  text-center sm:mb-1 hr-line relative leading-9">
                            Our Latest Blogs
                        </h1>
                        <div>

                            <div class="mx-auto sm:mt-10 mt-5 md:w-[50%]">
                                <div class="searchInputWrapper rounded-[10px] " style="background:#ECECEC;">
                                    <i class="searchInputIcon fa fa-search pl-4"></i>
                                    <input class="searchInput" type="text" placeholder='Search' style="background:#ECECEC; padding:10px; outline:none;">
                                    </input>
                                </div>
                            </div>
                            <!-- Results Message -->
                            <div id="noResults" class="text-center text-red-600 text-lg mt-6 hidden">
                                No blog posts found matching your search.
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 sm:my-10 my-5 sm:grid-cols-2 mx-auto gap-4 gap-y-10" id="blogList">
                                <?php if (empty($blogItems)): ?>
                                    <p class="text-gray-500 col-span-full text-center">No blog posts available at the moment.</p>
                                <?php endif; ?>
                                <?php foreach ($blogItems as $data) {
                                    $title = $data['title'];
                                    $description = strip_tags($data['description']);
                                    $dateFormatted = $data['date_formatted'] !== '' ? $data['date_formatted'] : date('d F Y', strtotime($data['date'] ?? ''));
                                ?>
                                    <div class="blog-card max-w-sm bg-white border border-gray-200 rounded-[20px] shadow hover:shadow-[rgba(0,0,0,0.15)_0px_15px_25px,rgba(0,0,0,0.05)_0px_5px_10px] transition-shadow duration-300"
                                        data-title="<?= htmlspecialchars(strtolower($title)) ?>"
                                        data-date="<?= htmlspecialchars(strtolower($dateFormatted)) ?>"
                                        data-description="<?= htmlspecialchars(strtolower($description)) ?>">
                                        <a href="<?= htmlspecialchars($blogDetailHref($data['slug'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="w-[100%] block">
                                            <img class="rounded-t-[20px] w-[100%]" src="<?php echo $data['media']['urls'] ?>" alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>" />
                                            <div class="p-4">
                                                <div class="text-[14px] text-gray-600"><?= $dateFormatted ?></div>
                                                <div class="font-[700] text-[22px] text-blue-main mt-2"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></div>
                                                <span class="text-blue-main text-[16px] flex items-center my-3 font-[600] gap-2 group">
                                                    View More
                                                    <svg class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out" width="16" height="16" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_777_2487)">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.65008 3.91156C9.9831 3.91156 10.2531 4.18153 10.2531 4.51456L10.2531 9.63112C10.2531 9.96414 9.9831 10.2341 9.65008 10.2341C9.31705 10.2341 9.04708 9.96414 9.04708 9.63112L9.04708 5.97031L4.10714 10.9103C3.87165 11.1457 3.48986 11.1457 3.25438 10.9103C3.01889 10.6748 3.01889 10.293 3.25438 10.0575L8.19432 5.11755L4.53352 5.11755C4.20049 5.11755 3.93052 4.84758 3.93052 4.51456C3.93052 4.18153 4.20049 3.91156 4.53352 3.91156L9.65008 3.91156Z" fill="#223B71" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_777_2487">
                                                                <rect width="15" height="13" fill="white" transform="translate(13.2695) rotate(90)" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
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

    <script>
        var searchbar = document.getElementById("searchbar");
        var searchbarinput = document.getElementById("searchbarinput");
        var dropdown = document.getElementById("dropdown");

        var resultlist = document.getElementById("resultlist");
        var lis = resultlist.getElementsByTagName("li");

        function darksoulsearch() {

            searchbarinput.style.borderRadius = "25px 25px 0 0";

            resultlist.style.display = "flex";

            dropdown.style.animation = "height 0.5s 1 linear forwards";
            dropdown.style.height = "fit-content";
            dropdown.style.maxHeight = "200px";
            dropdown.style.overflowX = "hidden";
            dropdown.style.overflowY = "scroll";
            dropdown.style.transition = "all 0.5s";

        }

        function closesearch() {

            searchbarinput.style.borderRadius = "25px";
            dropdown.style.animation = "revheight 0.5s 1 linear forwards";
            dropdown.style.height = "fit-content";
            dropdown.style.maxHeight = "0px";
            dropdown.style.overflowX = "hidden";
            dropdown.style.overflowY = "scroll";
            dropdown.style.transition = "all 0.5s";

            resultlist.style.display = "none";
        }

        window.addEventListener("click", function(event) {
            if (event.target != searchbarinput) {
                closesearch();
                console.log("body");
            }

        });


        searchbarinput.addEventListener("input", function() {

            var searchValue = searchbarinput.value.toLowerCase();

            for (let i = 0; i < lis.length; i++) {
                var li = lis[i];
                var liName = li.textContent.toLowerCase();

                if (liName.includes(searchValue)) {
                    darksoulsearch();
                    li.style.display = "flex";
                } else {
                    li.style.display = "none";
                }
            }
        });
    </script>
    <!-- Search Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.searchInput');
            const blogCards = document.querySelectorAll('.blog-card');
            const noResults = document.getElementById('noResults');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                let matchCount = 0;

                blogCards.forEach(card => {
                    const title = card.dataset.title;
                    const date = card.dataset.date;
                    const description = card.dataset.description;

                    if (
                        title.includes(query) ||
                        date.includes(query) ||
                        description.includes(query)
                    ) {
                        card.style.display = 'block';
                        matchCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (matchCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            });
        });
    </script>



</body>

</html>