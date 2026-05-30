<?php
$page = "achievements";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://allenhouseghaziabad.com/achievements" />
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
            height: 250px;
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
                    Achievements
                </h1>
                <h1 class="sm:text-[32px] sm:block hidden font-[800] text-white text-left ml-[7rem] hr-line relative uppercase">
                    Our Student's Achievements
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
            </ol>
        </div>

        <div class="mt-8 mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5">
            <div class="tabs">
                <div class="flex flex-col sm:flex-row items-center gap-4 sm:justify-between border-b pb-4">
                    <ul class="flex gap-2 bg-white p-1 rounded-xl shadow-sm border border-gray-100">
                        <li>
                            <a href="javascript:void(0)" class="tab-link active-tab block py-2 px-6 font-bold rounded-lg text-xs uppercase tracking-widest">General</a>
                        </li>
                    </ul>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-[50%]">
                        <select id="yearFilter" class="bg-white w-full sm:w-auto border border-gray-200 px-5 py-3 outline-none rounded-xl text-sm shadow-sm"><option value="">All Years</option></select>
                        <input type="text" id="searchInput" class="bg-white w-full border border-gray-200 px-5 py-3 outline-none rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-blue-main/20" placeholder="Search achievements by name or year..." />
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
        let achievementData = [];
        let currentYear = '';
        let searchTerm = '';

        async function loadAgraAchievements() {
            try {
                const all = await fetchAllCmsGalleriesByBranch();
                achievementData = all.filter(isGalleryAchievementItem);
                populateGalleryYearSelect(document.getElementById('yearFilter'), achievementData);
                applyAchievementFilters();
            } catch (error) {
                console.error("Error loading achievements:", error);
            }
        }

        function applyAchievementFilters() {
            let list = filterGalleryItemsByYear(achievementData, currentYear);
            if (searchTerm.trim()) {
                const term = searchTerm.toLowerCase().trim();
                list = list.filter(function(item) {
                    const title = (item.achievementtitle || item.title || '').toLowerCase();
                    const yearStr = String(galleryItemYear(item) || '');
                    return title.includes(term) || yearStr.includes(term);
                });
            }
            renderGallery(list);
        }

        function renderGallery(data) {
            const container = document.getElementById('galleryGrid');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<div class="col-span-full py-20 text-center text-gray-400 font-medium">No achievement records found for this campus.</div>';
                return;
            }

            data.forEach(item => {
                const coverMedia = item.medias?.find(m => m.pivot?.is_cover === "1") || item.medias?.[0] || {};
                const coverImage = coverMedia.urls || 'https://via.placeholder.com/600x400?text=Achievement';
                const isPdf = coverImage.toLowerCase().endsWith('.pdf');

                const date = new Date(item.achevementdate || item.date);
                const day = date.getDate() || '';
                const month = date.toLocaleString('default', { month: 'short' }) || '';
                const year = date.getFullYear() || '';

                const card = `
                    <div class="achievement-card flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
                        <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                            <a href="achievement-gallery?id=${item.id}">
                                ${isPdf ? `
                                    <div class="flex flex-col items-center justify-center h-full">
                                        <svg class="w-16 h-16 text-red-600 mb-2" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 14h-3v3h-2v-3H8v-2h3v-3h2v3h3v2z"/></svg>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">View PDF Document</span>
                                    </div>
                                ` : `
                                    <img src="${coverImage}" alt="${item.achievementtitle}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                                `}
                            </a>
                            <div class="absolute top-4 left-4">
                                <span class="bg-blue-main text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-tighter">Campus Pride</span>
                            </div>
                        </div>

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex gap-4 mb-4">
                                <div class="w-14 text-center">
                                    <div class="bg-blue-main text-white text-[10px] font-bold py-1 rounded-t-lg">${year}</div>
                                    <div class="border border-t-0 rounded-b-lg py-2 bg-gray-50 border-gray-100">
                                        <div class="text-xl font-black text-blue-main">${day}</div>
                                        <div class="text-[9px] font-bold text-gray-400 uppercase">${month}</div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-blue-main font-bold text-base uppercase line-clamp-2 leading-snug">${item.achievementtitle || 'Untitled'}</h3>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Total Items: ${item.medias?.length || 0}</p>
                                </div>
                            </div>

                            <div class="mt-auto pt-5 border-t border-gray-50">
                                <a href="achievement-gallery?id=${item.id}" class="w-full py-3 bg-blue-main text-white text-[11px] font-bold rounded-xl hover:bg-slate-800 transition-all uppercase flex items-center justify-center gap-2">
                                    View Achievement
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2.5"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', card);
            });
        }

        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            searchTerm = e.target.value;
            applyAchievementFilters();
        });
        document.getElementById('yearFilter')?.addEventListener('change', function(e) {
            currentYear = e.target.value;
            applyAchievementFilters();
        });

        window.addEventListener('DOMContentLoaded', loadAgraAchievements);
    </script>

</body>
</html>