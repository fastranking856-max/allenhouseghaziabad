<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery | AllenHouse Ghaziabad</title>
    <link rel="canonical" href="https://allenhouseghaziabad.com/photo-gallery" />
    <?php include "includes/head.php" ?>
    <style>
        .active-tab {
            background-color: #334155 !important; /* slate-700 */
            color: white !important;
        }
        .gallery-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

    <?php include "includes/header.php" ?>

    <div class="main relative mb-[40px] sm:mb-[120px]">
        <div class="bg-center flex items-center text-center h-[300px] comman-banner">
            <div class="w-full">
                <h1 class="text-[32px] sm:hidden block font-[700] text-white text-left pl-4 mb-5 hr-line relative leading-9 uppercase">
                    Photo Gallery
                </h1>
                <h1 class="sm:text-[32px] sm:block hidden font-[700] text-white text-left sm:mb-1 hr-line relative leading-9 ml-[7rem] uppercase">
                    Photo Gallery
                </h1>
            </div>
        </div>

        <div class="flex m-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="/" class="text-xs font-medium text-blue-main hover:underline">Home</a>
                </li>
                <svg class="w-3 h-3 text-blue-main mx-1" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                <li class="text-xs font-medium text-gray-500">Photo Gallery</li>
            </ol>
        </div>

        <div class="mt-8 mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5">
            <div class="tabs">
                <div class="flex flex-col sm:flex-row items-center gap-4 sm:justify-between border-b pb-4">
                    <ul class="flex gap-2 bg-gray-50 p-1 rounded-xl">
                        <li>
                            <a href="#section1" class="tab-link active-tab block py-2.5 px-6 font-semibold text-gray-700 rounded-lg transition-all">All Albums</a>
                        </li>
                    </ul>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-[50%]">
                        <select id="yearFilter" class="bg-gray-100 w-full sm:w-auto border-none px-5 py-3 outline-none rounded-xl text-sm"><option value="">All Years</option></select>
                        <input type="text" id="gallerySearch" class="bg-gray-100 w-full border-none px-5 py-3 outline-none rounded-xl text-sm" placeholder="Search albums by title or year..." />
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
    let galleryData = [];
    let currentYear = '';
    let searchTerm = '';

    async function loadGallery() {
        try {
            galleryData = await fetchCmsGalleriesSubType(CMS_GALLERY_SUBTYPE.photo);
            populateGalleryYearSelect(document.getElementById('yearFilter'), galleryData);
            applyGalleryFilters();
        } catch (error) {
            console.error("Ghaziabad Gallery API Error:", error);
        }
    }

    function applyGalleryFilters() {
        let list = filterGalleryItemsByYear(galleryData, currentYear);
        if (searchTerm.trim()) {
            const term = searchTerm.toLowerCase().trim();
            list = list.filter(function(item) {
                const title = (item.title || '').toLowerCase();
                const year = String(galleryItemYear(item) || '');
                return title.includes(term) || year.includes(term);
            });
        }
        renderGallery(list);
    }

    function renderGallery(data) {
        const container = document.getElementById('galleryGrid');
        container.innerHTML = '';

        if (data.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500 col-span-full py-20">No albums found for Agra campus.</p>';
            return;
        }

        data.forEach(item => {
            const coverMedia = item.medias.find(m => m.pivot?.is_cover === "1") || item.medias[0] || {};
            const coverUrl = coverMedia.urls || 'https://via.placeholder.com/500x350?text=AllenHouse+Agra';
            
            const date = new Date(item.date);
            const day = date.getDate();
            const month = date.toLocaleString('default', { month: 'short' });
            const year = date.getFullYear();

            const card = `
            <div class="gallery-card flex flex-col bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden group">
                <div class="relative overflow-hidden">
                    <img class="w-full h-[240px] object-cover transition-transform duration-500 group-hover:scale-110" 
                         src="${coverUrl}" alt="${item.title}" />
                    <div class="absolute top-4 right-4 bg-blue-main text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                        ${item.type}
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
                            <h3 class="text-blue-main font-bold text-lg leading-tight uppercase line-clamp-2">${item.title}</h3>
                        </div>
                    </div>

                    <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-100">
                         <span class="text-xs text-gray-400 font-bold">${item.medias.length} Photos</span>
                         <a href="gallery?id=${item.id}">
                            <button class="px-5 py-2 rounded-lg bg-blue-main text-white text-xs font-bold hover:bg-[#053B7A] transition-colors flex items-center gap-2">
                                VIEW ALBUM
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                         </a>
                    </div>
                </div>
            </div>`;
            container.insertAdjacentHTML('beforeend', card);
        });
    }

    document.getElementById('gallerySearch').addEventListener('input', function() {
        searchTerm = this.value;
        applyGalleryFilters();
    });
    document.getElementById('yearFilter').addEventListener('change', function() {
        currentYear = this.value;
        applyGalleryFilters();
    });

    window.addEventListener('DOMContentLoaded', loadGallery);
</script>
</body>
</html>