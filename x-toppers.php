<?php
$page = "x-toppers";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/x-toppers" />
    <title>Class X Board Toppers | AllenHouse Ghaziabad</title>
    <?php include "includes/head.php" ?>

    <style>
        .active-tab {
            background-color: #004a8d !important; /* Blue Main */
            color: #ffffff !important;
        }
        .topper-card {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .topper-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .pdf-preview-card {
            height: 200px;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #e2e8f0;
        }
    </style>
</head>

<body class="bg-slate-50">

    <?php include "includes/header.php" ?>

    <div class="main relative mb-[40px] sm:mb-[120px]">
        <div class="bg-center flex items-center text-left h-[300px] common-banner">
            <div class="w-full">
                <h1 class="text-[32px] sm:hidden block font-[800] text-white pl-4 mb-5 hr-line relative uppercase">
                    X Toppers
                </h1>
                <h1 class="sm:text-[32px] sm:block hidden font-[800] text-white text-left ml-[7rem] hr-line relative uppercase">
                    Class X Board Toppers
                </h1>
            </div>
        </div>

        <div class="flex m-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="/" class="text-xs font-medium text-blue-main uppercase tracking-tighter">Home</a>
                </li>
                <svg class="w-3 h-3 text-blue-main mx-1" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                <li class="text-xs font-medium text-blue-main uppercase tracking-tighter">Achievements</li>
                <svg class="w-3 h-3 text-blue-main mx-1" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                <li class="text-xs font-medium text-blue-main uppercase tracking-tighter">X Toppers</li>
            </ol>
        </div>

        <div class="mt-8 mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5">
            <div class="tabs">
                <div class="flex flex-col sm:flex-row items-center gap-4 sm:justify-between border-b pb-4 border-gray-200">
                    <ul class="flex gap-2 bg-white p-1 rounded-xl shadow-sm border border-gray-100">
                        <li>
                            <a href="javascript:void(0)" class="tab-link active-tab block py-2 px-6 font-bold rounded-lg text-xs sm:text-sm uppercase tracking-widest">High Achievers</a>
                        </li>
                    </ul>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-[50%]">
                        <select id="yearFilter" class="bg-white w-full sm:w-auto border border-gray-200 px-5 py-3 outline-none rounded-xl text-sm shadow-sm"><option value="">All Years</option></select>
                        <input type="text" id="xSearch" class="bg-white w-full border border-gray-200 px-5 py-3 outline-none rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-blue-main/20" placeholder="Search toppers by name or year..." />
                    </div>
                </div>

                <section class="mt-10">
                    <div id="galleryGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        </div>
                </section>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/foot.php" ?>

    <script>
        let xTopperData = [];
        let currentYear = '';
        let searchTerm = '';

        async function loadXToppers() {
            try {
                const all = await fetchAllCmsGalleriesByBranch();
                xTopperData = all.filter(function(item) {
                    return gallerySubTypeName(item) === 'x_toppers';
                });
                populateGalleryYearSelect(document.getElementById('yearFilter'), xTopperData);
                applyTopperFilters();
            } catch (err) {
                console.error("Ghaziabad X Toppers API Error:", err);
            }
        }

        function applyTopperFilters() {
            let list = filterGalleryItemsByYear(xTopperData, currentYear);
            if (searchTerm.trim()) {
                const term = searchTerm.toLowerCase().trim();
                list = list.filter(function(item) {
                    const title = (item.achievementtitle || item.title || '').toLowerCase();
                    const yearStr = String(galleryItemYear(item) || '');
                    return title.includes(term) || yearStr.includes(term);
                });
            }
            renderToppers(list);
        }

        function renderToppers(data) {
            const container = document.getElementById('galleryGrid');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<div class="col-span-full py-20 text-center text-gray-400 font-medium tracking-wide">No Class X records currently available for this campus.</div>';
                return;
            }

            data.forEach(item => {
                const cover = item.medias?.find(m => m.pivot?.is_cover === "1") || item.medias?.[0];
                const imgUrl = cover ? cover.urls : '';
                const isPdf = imgUrl.toLowerCase().endsWith('.pdf');
                
                const d = new Date(item.achevementdate || item.date);
                const day = d.getDate();
                const month = d.toLocaleString('default', { month: 'short' });
                const year = d.getFullYear();

                const card = `
                <div class="topper-card flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-50">
                        ${isPdf ? `
                            <div class="pdf-preview-card">
                                <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 14h-3v3h-2v-3H8v-2h3v-3h2v3h3v2z"/></svg>
                                <span class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-tighter">View Merit PDF</span>
                            </div>
                        ` : `
                            <img src="${imgUrl || 'https://via.placeholder.com/600x400?text=Class+X+Topper'}" alt="${item.achievementtitle}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" />
                        `}
                        <div class="absolute top-4 left-4">
                            <span class="bg-blue-main text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter shadow-sm">Academic Excellence</span>
                        </div>
                    </div>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex gap-4 mb-4">
                            <div class="w-14 text-center">
                                <div class="bg-blue-main text-white text-[10px] font-bold py-1 rounded-t-lg">${year}</div>
                                <div class="border border-t-0 rounded-b-lg py-2 bg-gray-50 border-gray-100">
                                    <div class="text-xl font-black text-blue-main leading-tight">${day}</div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase">${month}</div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-blue-main font-bold text-base uppercase line-clamp-2 leading-snug">${item.achievementtitle}</h3>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1 font-semibold">Board Topper | Class X</p>
                            </div>
                        </div>

                        <div class="mt-auto pt-5 border-t border-gray-50">
                            <a href="achievement-gallery?id=${item.id}">
                                <button class="w-full py-3 bg-blue-main text-white text-[11px] font-black rounded-xl hover:bg-slate-800 transition-all uppercase flex items-center justify-center gap-2">
                                    VIEW ACHIEVEMENT
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2.5"/></svg>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>`;
                container.insertAdjacentHTML('beforeend', card);
            });
        }

        document.getElementById('xSearch').addEventListener('input', function(e) {
            searchTerm = e.target.value;
            applyTopperFilters();
        });
        document.getElementById('yearFilter').addEventListener('change', function(e) {
            currentYear = e.target.value;
            applyTopperFilters();
        });

        window.addEventListener('DOMContentLoaded', loadXToppers);
    </script>
</body>
</html>