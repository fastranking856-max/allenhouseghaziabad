<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | AllenHouse Ghaziabad</title>
    <link rel="canonical" href="https://allenhouseghaziabad.com/event" />
    <?php include "includes/head.php" ?>
    <style>
        .active-tab {
            background-color: #334155 !important; /* slate-700 */
            color: white !important;
        }
        /* Ensures titles and descriptions don't break the grid height */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
    </style>
</head>

<body>
 
    <?php include "includes/header.php" ?>

    <div class="main relative mb-[40px] sm:mb-[120px]">
        <div class="bg-center flex items-center text-center h-[300px] comman-banner">
            <div class="w-full">
                <h1 class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 hr-line relative leading-9">Events</h1>
                <h1 class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem]">Events</h1>
            </div>
        </div>

        <div class="flex m-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="/" class="text-xs font-medium text-blue-main hover:underline">Home</a>
                </li>
                <svg class="w-3 h-3 text-blue-main mx-1" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                <li class="text-xs font-medium text-gray-500">Media & Events</li>
                <svg class="w-3 h-3 text-blue-main mx-1" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                <li class="text-xs font-medium text-gray-500">Events</li>
            </ol>
        </div>

        <div class="mt-8 mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5">
            <div class="tabs">
                <div class="flex flex-col sm:flex-row items-center gap-4 sm:justify-between border-b pb-4">
                    <ul class="flex gap-2 bg-gray-50 p-1 rounded-xl">
                        <li>
                            <a href="#section1" class="tab-link active-tab block py-2.5 px-6 font-semibold text-gray-700 rounded-lg transition-all">Latest Events</a>
                        </li>
                    </ul>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-[50%]">
                        <select id="yearFilter" class="bg-gray-100 w-full sm:w-auto border-none px-5 py-3 outline-none rounded-xl text-sm"><option value="">All Years</option></select>
                        <input type="text" id="eventSearch" class="bg-gray-100 w-full border-none px-5 py-3 outline-none rounded-xl text-sm" placeholder="Search by title, date, or year..." />
                    </div>
                </div>

                <section id="section1" class="mt-10">
                    <div id="galleryGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        </div>
                </section>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

     <script>
        let eventData = [];
        let currentYear = '';
        let searchTerm = '';

        async function loadEvents() {
            try {
                const all = await fetchAllCmsGalleriesByBranch();
                eventData = all.filter(isGalleryEventItem);
                populateGalleryYearSelect(document.getElementById('yearFilter'), eventData);
                applyEventFilters();
            } catch (err) {
                console.error("API Error:", err);
            }
        }

        function applyEventFilters() {
            let list = filterGalleryItemsByYear(eventData, currentYear);
            if (searchTerm.trim()) {
                const term = searchTerm.toLowerCase().trim();
                list = list.filter(function(item) {
                    const title = (item.title || '').toLowerCase();
                    const year = String(galleryItemYear(item) || '');
                    return title.includes(term) || year.includes(term);
                });
            }
            renderEvents(list);
        }

        function renderEvents(data) {
            const container = document.getElementById('galleryGrid');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 col-span-full py-20">No events found for Ghaziabad campus.</p>';
                return;
            }

            data.forEach(item => {
                const coverMedia = item.medias.find(m => m.pivot?.is_cover === "1") || item.medias[0] || {};
                const coverUrl = coverMedia.urls || 'https://via.placeholder.com/600x400?text=Event+Image';
                
                const date = new Date(item.date);
                const day = date.getDate();
                const month = date.toLocaleString('default', { month: 'short' });
                const year = date.getFullYear();

                // Strip HTML tags for clean description preview
                const cleanDesc = item.description ? item.description.replace(/<[^>]*>?/gm, '') : 'Details for this event will be shared soon.';

                const card = `
                <div class="flex flex-col bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="relative overflow-hidden">
                        <img class="w-full h-[240px] object-cover transition-transform duration-500 group-hover:scale-105" 
                             src="${coverUrl}" alt="${item.title}" />
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex gap-4 mb-4">
                            <div class="flex-shrink-0 w-16 text-center">
                                <div class="bg-blue-main text-white py-1 rounded-t-lg font-bold text-xs">${year}</div>
                                <div class="border border-t-0 rounded-b-lg py-1 bg-gray-50">
                                    <div class="text-xl font-bold text-[#D9A414]">${day}</div>
                                    <div class="text-[10px] font-bold text-blue-main uppercase">${month}</div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-blue-main font-bold text-md leading-tight mb-2 uppercase line-clamp-2">${item.title}</h3>
                                <p class="text-gray-500 text-xs line-clamp-3 leading-relaxed">
                                    ${cleanDesc}
                                </p>
                            </div>
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                             <div class="text-[10px] text-gray-400 font-bold uppercase">${item.medias.length} Photos</div>
                             <a href="event-gallery?id=${item.id}">
                                <button class="px-5 py-2 rounded-lg bg-blue-main text-white text-xs font-bold hover:bg-[#053B7A] transition-colors flex items-center gap-2">
                                    VIEW MORE
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                             </a>
                        </div>
                    </div>
                </div>`;
                container.insertAdjacentHTML('beforeend', card);
            });
        }

        document.getElementById('eventSearch').addEventListener('input', function() {
            searchTerm = this.value;
            applyEventFilters();
        });
        document.getElementById('yearFilter').addEventListener('change', function() {
            currentYear = this.value;
            applyEventFilters();
        });

        window.addEventListener('DOMContentLoaded', loadEvents);
    </script>
</body>
</html>