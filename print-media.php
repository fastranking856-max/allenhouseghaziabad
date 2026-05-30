<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Media | AllenHouse Ghaziabad</title>
    <link rel="canonical" href="https://allenhouseghaziabad.com/print-media" />
    <?php include "includes/head.php" ?>
    <style>
        .active-tab {
            background-color: #334155 !important; /* slate-700 */
            color: white !important;
        }
        .print-card {
            transition: all 0.3s ease;
        }
        .print-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>

    <?php include "includes/header.php" ?>

    <div class="main relative mb-[40px] sm:mb-[120px]">
        <div class="bg-center flex items-center text-center h-[300px] common-banner">
            <div class="w-full">
                <h1 class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 hr-line relative leading-9 uppercase">
                    Print Media
                </h1>
                <h1 class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem] uppercase">
                    Print Media
                </h1>
            </div>
        </div>

        <div class="flex m-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="/" class="text-xs font-medium text-blue-main hover:underline text-blue-main">Home</a>
                </li>
                <svg class="w-3 h-3 text-blue-main mx-1" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                <li class="text-xs font-medium text-blue-main">Media & Events</li>
                <svg class="w-3 h-3 text-blue-main mx-1" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                <li class="text-xs font-medium text-gray-500">Print Media</li>
            </ol>
        </div>

        <div class="mt-8 mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5">
            <div class="tabs">
                <div class="flex flex-col sm:flex-row items-center gap-4 sm:justify-between border-b pb-4">
                    <ul class="flex gap-2 bg-gray-50 p-1 rounded-xl">
                        <li>
                            <a href="javascript:void(0)" class="tab-link active-tab block py-2.5 px-6 font-semibold text-gray-700 rounded-lg transition-all">Latest News</a>
                        </li>
                    </ul>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-[50%]">
                        <select id="yearFilter" class="bg-gray-100 w-full sm:w-auto border-none px-5 py-3 outline-none rounded-xl text-sm"><option value="">All Years</option></select>
                        <input type="text" id="printSearch" class="bg-gray-100 w-full border-none px-5 py-3 outline-none rounded-xl text-sm" placeholder="Search news by title, newspaper, or year..." />
                    </div>
                </div>

                <section class="mt-10">
                    <div id="printGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        </div>
                </section>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

    <script>
        let printData = [];
        let currentYear = '';
        let searchTerm = '';

        async function loadPrintMedia() {
            try {
                printData = await fetchCmsGalleriesSubType(CMS_GALLERY_SUBTYPE.print);
                populateGalleryYearSelect(document.getElementById('yearFilter'), printData);
                applyPrintFilters();
            } catch (error) {
                console.error("Error loading Ghaziabad Print Media:", error);
            }
        }

        function applyPrintFilters() {
            let list = filterGalleryItemsByYear(printData, currentYear);
            if (searchTerm.trim()) {
                const term = searchTerm.toLowerCase().trim();
                list = list.filter(function(item) {
                    const title = (item.title || '').toLowerCase();
                    const category = (item.type || '').toLowerCase();
                    const year = String(galleryItemYear(item) || '');
                    return title.includes(term) || category.includes(term) || year.includes(term);
                });
            }
            renderPrintMedia(list);
        }

        function renderPrintMedia(data) {
            const container = document.getElementById('printGrid');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 col-span-full py-20">No news clippings found for Ghaziabad campus.</p>';
                return;
            }

            data.forEach(item => {
                const coverImage = (item.medias?.[0]?.urls) || item.media?.urls || 'https://via.placeholder.com/600x400?text=AllenHouse+News';
                const title = item.title || 'Press Release';
                const category = item.type || 'Media';
                
                let day = '--', month = '--', year = '----';
                if (item.date) {
                    const d = new Date(item.date);
                    if (!isNaN(d.getTime())) {
                        day = d.getDate();
                        month = d.toLocaleString('default', { month: 'short' });
                        year = d.getFullYear();
                    }
                }

                const card = `
                <div class="print-card flex flex-col bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl overflow-hidden group">
                    <div class="relative overflow-hidden aspect-video">
                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                             src="${coverImage}" alt="${title}" />
                        <div class="absolute top-4 left-4 bg-blue-main text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-tighter">
                            ${category}
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex gap-4 mb-6">
                            <div class="flex-shrink-0 w-16 text-center">
                                <div class="bg-blue-main text-white py-1 rounded-t-lg font-bold text-xs">${year}</div>
                                <div class="border border-t-0 rounded-b-lg py-1 bg-gray-50">
                                    <div class="text-xl font-bold text-[#D9A414]">${day}</div>
                                    <div class="text-[10px] font-bold text-blue-main uppercase">${month}</div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-blue-main font-bold text-md leading-tight mb-2 uppercase line-clamp-3">${title}</h3>
                            </div>
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                             <span class="text-[10px] text-gray-400 font-bold uppercase">Press Room</span>
                             <a href="print-media-detail?id=${item.id}">
                                <button class="px-5 py-2 rounded-lg bg-blue-main text-white text-xs font-bold hover:bg-[#053B7A] transition-colors flex items-center gap-2">
                                    READ ARTICLE
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                             </a>
                        </div>
                    </div>
                </div>`;
                container.insertAdjacentHTML('beforeend', card);
            });
        }

        document.getElementById('printSearch').addEventListener('input', function() {
            searchTerm = this.value;
            applyPrintFilters();
        });
        document.getElementById('yearFilter').addEventListener('change', function() {
            currentYear = this.value;
            applyPrintFilters();
        });

        window.addEventListener('DOMContentLoaded', loadPrintMedia);
    </script>
</body>
</html>