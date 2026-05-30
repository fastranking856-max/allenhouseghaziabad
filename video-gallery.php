<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/video-gallery" />
    <title>AllenHouse Ghaziabad| Video Gallery</title>
    <?php include "includes/head.php" ?>
</head>

<body>
     
    <?php include "includes/header.php" ?>

    <div class="main relative mb-[120px] ">
        <div class="main relative  mb-[40px] sm:mb-[120px] ">
            <div class="bg-center flex items-center text-center h-[300px] common-banner "
                >
                <div>
                    <h2
                        class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 sm:mb-8 hr-line relative leading-9">
                        Video Gallery
                    </h2>
                </div>

                <div class="md:w-[100%]">
                    <h2
                        class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">
                        Video
                        <span class="sm:hidden"></span> Gallery
                    </h2>
                </div>
            </div>

            <div class="flex m-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-xs font-medium sm:text-sm text-blue-main">
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 rtl:rotate-180 text-blue-main" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <p class="text-xs font-medium ms-1 sm:text-sm text-blue-main">Media & Events</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 rtl:rotate-180 text-blue-main" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="video-gallery" class="text-xs font-medium ms-1 sm:text-sm text-blue-main">Video
                                Gallery</a>
                        </div>
                    </li>
                </ol>
            </div>

            <div class="mt-8 mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
                <div class="relative mt-10">

                    <div>

                        <div>
                            <div class="tabs sm:mt-10 ">
                                <div class="flex gap-2 item-center sm:justify-between ">
                                    <ul class="flex gap-2 border-b sm:gap-4 bg-gray-50 " style="border-radius:12px;">
                                        <li class="flex-1">
                                            <a href="#section1"
                                                class="tab-link block text-center py-2.5 px-2 sm:text-[16px] text-[10px] sm:py-3 sm:px-5 font-semibold  text-gray-700  transition-colors hover:bg-slate-700 hover:text-white"
                                                aria-controls="section1" role="tab" tabindex="-1">Title</a>
                                        </li>
                                        <li class="flex-1">
                                            <a href="#section2"
                                                class="tab-link block text-center  py-2.5 sm:py-3 px-2 sm:px-5 sm:text-[16px] text-[10px] font-semibold   text-gray-700 transition-colors hover:bg-slate-700 hover:text-white"
                                                aria-controls="section2" role="tab" tabindex="-1">Category</a>
                                        </li>
                                        <li class="flex-1">
                                            <a href="#section3"
                                                class="tab-link block text-center py-2.5   sm:py-3 px-2 sm:px-5 sm:text-[16px] text-[10px] font-semibold   text-gray-700  transition-colors hover:bg-slate-700 hover:text-white"
                                                aria-controls="section3" role="tab" tabindex="-1">Year</a>
                                        </li>
                                     </ul>

                                    <select id="yearFilter" class="bg-gray-100 border-none px-4 py-2 rounded-lg text-sm"><option value="">All Years</option></select>
                                    <input type="text" id="first_name"
                                        class="bg-gray-100 w-[50%] border-b  text-gray-900 sm:text-[16px] text-[10px]  outline-none focus:ring-0 block px-5 py-2 "
                                        style="border-radius:9px;" placeholder="Search" />
                                </div>

                                <section id="section1" class="hidden mt-5 tab-panel" role="tabpanel"
                                    aria-hidden="true">
                                    <div id="videoGrid" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-3">
                                     </div>
                                </section>

                                <section id="section2" class="hidden mt-5 tab-panel" role="tabpanel"
                                    aria-hidden="true">
                                   
                                </section>

                                <section id="section3" class="hidden mt-5 tab-panel" role="tabpanel"
                                    aria-hidden="true">
                                 
                                </section>

                                
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
        let videoData = [];
        let currentYear = '';
        let searchTerm = '';

        function getYouTubeEmbedUrl(url) {
            if (!url) return '';
            url = fixCmsAssetUrl(url);
            let videoId = null;
            const normalMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\s&]+)/);
            if (normalMatch) videoId = normalMatch[1];
            const shortsMatch = url.match(/youtube\.com\/shorts\/([^\s?]+)/);
            if (shortsMatch) videoId = shortsMatch[1];
            return videoId ? `https://www.youtube.com/embed/${videoId}` : url;
        }

        async function loadGallery() {
            try {
                videoData = await fetchCmsGalleriesSubType(CMS_GALLERY_SUBTYPE.video);
                populateGalleryYearSelect(document.getElementById('yearFilter'), videoData);
                applyVideoFilters();
            } catch (error) {
                console.error("Failed to load gallery:", error);
            }
        }

        function applyVideoFilters() {
            let list = filterGalleryItemsByYear(videoData, currentYear);
            if (searchTerm.trim()) {
                const term = searchTerm.toLowerCase();
                list = list.filter(function(item) {
                    const title = (item.title || '').toLowerCase();
                    const type = (item.type || '').toLowerCase();
                    const dateObj = new Date(item.date);
                    if (isNaN(dateObj.getTime())) return title.includes(term) || type.includes(term);
                    const day = dateObj.getDate().toString();
                    const month = dateObj.toLocaleString('default', { month: 'short' }).toLowerCase();
                    const year = String(galleryItemYear(item) || '');
                    return title.includes(term) || type.includes(term) ||
                        day.includes(term) || month.includes(term) || year.includes(term);
                });
            }
            renderGallery(list);
        }

        function renderGallery(data) {
            const container = document.getElementById('videoGrid');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 col-span-full">No results found.</p>';
                return;
            }

            data.forEach(item => {
                const rawUrl = item.medias?.[0]?.urls || '';
                if (!rawUrl) return;

                const videoUrl = getYouTubeEmbedUrl(rawUrl);
                const date = new Date(item.date);
                if (isNaN(date.getTime())) return;

                const day = date.getDate();
                const month = date.toLocaleString('default', { month: 'short' });
                const year = date.getFullYear();

                const card = `
                    <div class="w-[100%] mx-auto bg-white border border-gray-200 rounded-lg shadow hover:shadow-[rgba(0,0,0,0.15)_0px_15px_25px,rgba(0,0,0,0.05)_0px_5px_10px] transition-shadow duration-300">
                        <iframe 
                            class="w-[100%] rounded-t-lg" 
                            height="240" 
                            src="${videoUrl}" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                        <div class="relative flex flex-col justify-between p-1 sm:p-4">
                            <div class="flex gap-4">
                                <div class="w-[30%]">
                                    <div class="bg-blue-main text-white text-center rounded-t-lg p-1 font-[700] text-[18px]">${year}</div>
                                    <div class="text-center font-[700] text-[24px] text-[#D9A414] rounded-b-lg border border-gray-300">
                                        ${day}<br><span class="text-[#223B71] text-[14px]">${month}</span>
                                    </div>
                                </div>
                                <div class="w-[70%]">
                                    <div class="text-blue-main text-[1rem] font-[700] m-2 line-clamp-2">${item.title}</div>
                                    <hr>
                                    <div class="flex gap-2 text-[9px] text-[#3B3B3B] m-2">
                                        <div>Category: <strong>${item.type}</strong></div>
                                        <div>Total Video(s): <strong>${item.medias?.length || 1}</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', card);
            });
        }

        document.getElementById('first_name').addEventListener('input', function() {
            searchTerm = this.value;
            applyVideoFilters();
        });
        document.getElementById('yearFilter').addEventListener('change', function() {
            currentYear = this.value;
            applyVideoFilters();
        });

        loadGallery();
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabPanels = document.querySelectorAll('.tab-panel');
        const indicator = document.getElementById('indicator');

        let activeTab = tabLinks[0];
        let activePanel = tabPanels[0];

        function changeTab(newTab) {
            // Hide all panels
            tabPanels.forEach(panel => panel.classList.add('hidden'));

            // Remove aria-selected and tabindex from all tabs
            tabLinks.forEach(tab => {
                tab.setAttribute('aria-selected', 'false');
                tab.setAttribute('tabindex', '-1');
            });

            // Show new tab's panel
            const targetPanel = document.getElementById(newTab.getAttribute('aria-controls'));
            targetPanel.classList.remove('hidden');
            targetPanel.setAttribute('aria-hidden', 'false');

            // Set aria-selected and tabindex on the new tab
            newTab.setAttribute('aria-selected', 'true');
            newTab.setAttribute('tabindex', '0');

            // Move indicator
            const offset = newTab.offsetLeft;
            const width = newTab.offsetWidth;
            indicator.style.left = `${offset}px`;
            indicator.style.width = `${width}px`;
        }

        function handleKeydown(event) {
            const keyCode = event.keyCode;
            let newTab;

            if (keyCode === 37) { // Left arrow
                newTab = activeTab.previousElementSibling?.querySelector('.tab-link');
            } else if (keyCode === 39) { // Right arrow
                newTab = activeTab.nextElementSibling?.querySelector('.tab-link');
            }

            if (newTab) {
                changeTab(newTab);
                activeTab = newTab;
            }
        }

        tabLinks.forEach(tabLink => {
            tabLink.addEventListener('click', (event) => {
                event.preventDefault();
                activeTab = event.target;
                changeTab(activeTab);
            });
            tabLink.addEventListener('keydown', handleKeydown);
        });

        // Initialize the first tab as active
        changeTab(activeTab);
    });
    </script>

</body>

</html>