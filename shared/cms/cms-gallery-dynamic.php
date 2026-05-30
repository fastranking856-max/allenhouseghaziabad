<?php

declare(strict_types=1);

/**
 * Renders a CMS gallery listing block (year filter + grid) for dynamic cms-page routes.
 *
 * @param array<string, mixed> $page
 * @param array<string, mixed> $galleryMeta from cmsPageGalleryMeta()
 */
function cmsRenderDynamicGalleryPage(array $page, array $galleryMeta): void
{
    $pageData = is_array($page['data'] ?? null) ? $page['data'] : [];
    $title = cmsPageTitle($page, 'Gallery');
    $breadcrumbs = is_array($pageData['breadcrumbs'] ?? null) ? $pageData['breadcrumbs'] : [];
    $config = [
        'mode' => (string) ($galleryMeta['mode'] ?? 'events'),
        'subType' => $galleryMeta['subType'] ?? null,
        'detailBase' => (string) ($galleryMeta['detailBase'] ?? 'event-gallery'),
    ];
    ?>
    <div class="bg-center flex items-center text-center h-[300px] comman-banner common-banner">
        <div class="w-full px-4">
            <h1 class="text-[32px] font-[700] text-white text-left sm:ml-[7rem] leading-9"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
        </div>
    </div>

    <?php if ($breadcrumbs !== []): ?>
    <div class="flex m-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center flex-wrap gap-1 md:space-x-2">
            <li><a href="/" class="text-xs font-medium text-blue-main hover:underline">Home</a></li>
            <?php foreach ($breadcrumbs as $crumb): ?>
                <?php if (!is_array($crumb)) { continue; } ?>
                <svg class="w-3 h-3 text-blue-main mx-1" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                <li class="text-xs font-medium text-gray-500"><?= htmlspecialchars((string) ($crumb['label'] ?? $crumb['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ol>
    </div>
    <?php endif; ?>

    <div class="mt-8 mx-4 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5">
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:justify-end border-b pb-4 mb-6">
            <select id="cmsDynamicYearFilter" class="bg-gray-100 w-full sm:w-auto border-none px-5 py-3 outline-none rounded-xl text-sm"><option value="">All Years</option></select>
            <input type="text" id="cmsDynamicGallerySearch" class="bg-gray-100 w-full sm:w-[320px] border-none px-5 py-3 outline-none rounded-xl text-sm" placeholder="Search..." />
        </div>
        <div id="cmsDynamicGalleryGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"></div>
    </div>

    <script>
    (function() {
        var cfg = <?= json_encode($config, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
        var subTypeMap = typeof CMS_GALLERY_SUBTYPE !== 'undefined' ? CMS_GALLERY_SUBTYPE : { achievements: 1, photo: 5, video: 6, print: 7 };
        var items = [];
        var currentYear = '';
        var searchTerm = '';

        function subTypeIdFor(key) {
            if (!key) return null;
            return subTypeMap[key] || null;
        }

        function filterItems(all) {
            if (cfg.mode === 'events' && typeof isGalleryEventItem === 'function') {
                return all.filter(isGalleryEventItem);
            }
            var id = subTypeIdFor(cfg.subType);
            if (id) {
                return all.filter(function(item) {
                    return item.subTypeId === id || (item.subTypeName || '').toLowerCase() === String(cfg.subType).toLowerCase();
                });
            }
            return all;
        }

        function applyFilters() {
            var list = typeof filterGalleryItemsByYear === 'function'
                ? filterGalleryItemsByYear(items, currentYear)
                : items;
            if (searchTerm.trim()) {
                var term = searchTerm.toLowerCase().trim();
                list = list.filter(function(item) {
                    var title = (item.title || '').toLowerCase();
                    var year = String(typeof galleryItemYear === 'function' ? galleryItemYear(item) : '');
                    return title.includes(term) || year.includes(term);
                });
            }
            render(list);
        }

        function render(data) {
            var container = document.getElementById('cmsDynamicGalleryGrid');
            if (!container) return;
            container.innerHTML = '';
            if (!data.length) {
                container.innerHTML = '<p class="text-center text-gray-500 col-span-full py-20">No items found.</p>';
                return;
            }
            data.forEach(function(item) {
                var medias = item.medias || [];
                var coverMedia = medias.find(function(m) { return m.pivot && m.pivot.is_cover === '1'; }) || medias[0] || {};
                var coverUrl = coverMedia.urls || 'https://via.placeholder.com/600x400?text=Gallery';
                var date = item.date ? new Date(item.date) : null;
                var day = date ? date.getDate() : '';
                var month = date ? date.toLocaleString('default', { month: 'short' }) : '';
                var year = date ? date.getFullYear() : '';
                var cleanDesc = item.description ? item.description.replace(/<[^>]*>?/gm, '') : '';
                var detailUrl = cfg.detailBase + '?id=' + encodeURIComponent(item.id);
                var card = '<div class="flex flex-col bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl transition-all overflow-hidden group">' +
                    '<img class="w-full h-[240px] object-cover" src="' + coverUrl + '" alt="" loading="lazy" />' +
                    '<div class="p-6 flex-1 flex flex-col">' +
                    '<h3 class="text-blue-main font-bold text-md mb-2 uppercase">' + (item.title || '') + '</h3>' +
                    (cleanDesc ? '<p class="text-gray-500 text-xs mb-3">' + cleanDesc.substring(0, 120) + '</p>' : '') +
                    (year ? '<p class="text-xs text-gray-400 mb-3">' + day + ' ' + month + ' ' + year + '</p>' : '') +
                    '<a href="' + detailUrl + '" class="mt-auto inline-block px-5 py-2 rounded-lg bg-blue-main text-white text-xs font-bold">View more</a>' +
                    '</div></div>';
                container.insertAdjacentHTML('beforeend', card);
            });
        }

        async function load() {
            try {
                var all;
                if (cfg.mode === 'subtype' && cfg.subType && typeof fetchCmsGalleriesSubType === 'function') {
                    var sid = subTypeIdFor(cfg.subType);
                    all = sid ? await fetchCmsGalleriesSubType(sid) : await fetchAllCmsGalleriesByBranch();
                } else if (typeof fetchAllCmsGalleriesByBranch === 'function') {
                    all = await fetchAllCmsGalleriesByBranch();
                } else {
                    all = [];
                }
                items = filterItems(all || []);
                if (typeof populateGalleryYearSelect === 'function') {
                    populateGalleryYearSelect(document.getElementById('cmsDynamicYearFilter'), items);
                }
                applyFilters();
            } catch (e) {
                console.error('Gallery load failed', e);
            }
        }

        document.getElementById('cmsDynamicGallerySearch').addEventListener('input', function() {
            searchTerm = this.value;
            applyFilters();
        });
        document.getElementById('cmsDynamicYearFilter').addEventListener('change', function() {
            currentYear = this.value;
            applyFilters();
        });
        window.addEventListener('DOMContentLoaded', load);
    })();
    </script>
    <?php
}
