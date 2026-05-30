(function (global) {
    var GALLERY_YEAR_LOOKBACK = 8;
    var CACHE_TTL_MS = 15 * 60 * 1000;
    var inflightRaw = null;

    function cacheKey() {
        return 'cms_gallery_branch_' + cmsBranchId + '_v2';
    }

    function readCache() {
        try {
            var raw = sessionStorage.getItem(cacheKey());
            if (!raw) return null;
            var parsed = JSON.parse(raw);
            if (!parsed || !parsed.expires || parsed.expires < Date.now()) {
                sessionStorage.removeItem(cacheKey());
                return null;
            }
            return Array.isArray(parsed.data) ? parsed.data : null;
        } catch (e) {
            return null;
        }
    }

    function writeCache(data) {
        try {
            sessionStorage.setItem(cacheKey(), JSON.stringify({
                expires: Date.now() + CACHE_TTL_MS,
                data: data
            }));
        } catch (e) {
            /* quota exceeded — ignore */
        }
    }

    function galleryYearRange() {
        var endYear = new Date().getFullYear() + 1;
        var startYear = endYear - GALLERY_YEAR_LOOKBACK;
        var years = [];
        for (var y = startYear; y <= endYear; y++) years.push(y);
        return years;
    }

    function galleryItemYear(item) {
        var d = item && (item.date || item.achevementdate || item.created_at);
        if (!d) return null;
        var dt = new Date(d);
        return isNaN(dt.getTime()) ? null : dt.getFullYear();
    }

    async function fetchCmsGalleriesBranchYear(year) {
        var response = await fetch(baseUrl + '/api/galleries/branch/' + cmsBranchId + '/year/' + year);
        if (!response.ok) return [];
        var result = await response.json();
        return Array.isArray(result.data) ? result.data : [];
    }

    function mergeGalleryItems(batches) {
        var seen = {};
        var merged = [];
        batches.forEach(function (items) {
            items.forEach(function (item) {
                if (!item || !item.id || seen[item.id]) return;
                seen[item.id] = true;
                merged.push(item);
            });
        });
        merged.sort(function (a, b) {
            var da = new Date(a.date || a.created_at || 0).getTime();
            var db = new Date(b.date || b.created_at || 0).getTime();
            return db - da;
        });
        return merged;
    }

    async function fetchAllCmsGalleriesRawByBranch() {
        var cached = readCache();
        if (cached) {
            refreshGalleryCacheInBackground();
            return cached;
        }
        if (inflightRaw) return inflightRaw;

        inflightRaw = (async function () {
            var years = galleryYearRange();
            var currentYear = new Date().getFullYear();
            var priorityYears = [currentYear, currentYear - 1, currentYear + 1];
            var otherYears = years.filter(function (y) {
                return priorityYears.indexOf(y) === -1;
            });

            var priorityBatches = await Promise.all(priorityYears.map(fetchCmsGalleriesBranchYear));
            var merged = mergeGalleryItems(priorityBatches);

            if (otherYears.length) {
                var otherBatches = await Promise.all(otherYears.map(fetchCmsGalleriesBranchYear));
                merged = mergeGalleryItems([merged].concat(otherBatches));
            }

            writeCache(merged);
            return merged;
        })().finally(function () {
            inflightRaw = null;
        });

        return inflightRaw;
    }

    function refreshGalleryCacheInBackground() {
        if (inflightRaw) return;
        inflightRaw = (async function () {
            var years = galleryYearRange();
            var batches = await Promise.all(years.map(fetchCmsGalleriesBranchYear));
            var merged = mergeGalleryItems(batches);
            writeCache(merged);
            return merged;
        })().finally(function () {
            inflightRaw = null;
        });
    }

    async function fetchAllCmsGalleriesByBranch() {
        var raw = await fetchAllCmsGalleriesRawByBranch();
        return raw.map(normalizeCmsGalleryItem);
    }

    async function fetchCmsGalleriesSubType(subTypeId) {
        var all = await fetchAllCmsGalleriesByBranch();
        var id = Number(subTypeId);
        return all.filter(function (item) {
            return Number(item.subTypeId) === id;
        });
    }

    async function fetchCmsGalleriesBranch() {
        return fetchAllCmsGalleriesByBranch();
    }

    function populateGalleryYearSelect(selectEl, items, selectedYear) {
        if (!selectEl) return;
        var yearSet = {};
        (items || []).forEach(function (item) {
            var y = galleryItemYear(item);
            if (y) yearSet[y] = true;
        });
        var years = Object.keys(yearSet).map(Number).sort(function (a, b) { return b - a; });
        selectEl.innerHTML = '<option value="">All Years</option>';
        years.forEach(function (year) {
            var opt = document.createElement('option');
            opt.value = String(year);
            opt.textContent = String(year);
            if (selectedYear && String(year) === String(selectedYear)) opt.selected = true;
            selectEl.appendChild(opt);
        });
    }

    function filterGalleryItemsByYear(items, yearVal) {
        if (yearVal === '' || yearVal == null) return items || [];
        var want = String(yearVal);
        return (items || []).filter(function (item) {
            var y = galleryItemYear(item);
            return y != null && String(y) === want;
        });
    }

    function gallerySubTypeName(item) {
        return String((item && (item.type || item.achivementtype || item.subTypeName)) || '').toLowerCase();
    }

    function isGalleryEventItem(item) {
        var t = gallerySubTypeName(item);
        return t === 'events' || t.indexOf('event') !== -1;
    }

    function isGalleryPhotoItem(item) {
        var t = gallerySubTypeName(item);
        return t === 'photo_gallery' || t.indexOf('photo') !== -1;
    }

    function isGalleryVideoItem(item) {
        var t = gallerySubTypeName(item);
        return t === 'video_gallery' || t.indexOf('video') !== -1;
    }

    function isGalleryPrintItem(item) {
        var t = gallerySubTypeName(item);
        return t === 'print_media' || t.indexOf('print') !== -1;
    }

    function isGalleryAwardItem(item) {
        return gallerySubTypeName(item) === 'awards';
    }

    function isGalleryAchievementItem(item) {
        var t = gallerySubTypeName(item);
        return t === 'achievements' && !/topper/i.test(item.achievementtitle || item.title || '');
    }

    global.CMS_GALLERY_YEAR_LOOKBACK = GALLERY_YEAR_LOOKBACK;
    global.galleryItemYear = galleryItemYear;
    global.fetchAllCmsGalleriesByBranch = fetchAllCmsGalleriesByBranch;
    global.fetchCmsGalleriesBranch = fetchCmsGalleriesBranch;
    global.fetchCmsGalleriesSubType = fetchCmsGalleriesSubType;
    global.populateGalleryYearSelect = populateGalleryYearSelect;
    global.filterGalleryItemsByYear = filterGalleryItemsByYear;
    global.isGalleryEventItem = isGalleryEventItem;
    global.isGalleryPhotoItem = isGalleryPhotoItem;
    global.isGalleryVideoItem = isGalleryVideoItem;
    global.isGalleryPrintItem = isGalleryPrintItem;
    global.isGalleryAwardItem = isGalleryAwardItem;
    global.isGalleryAchievementItem = isGalleryAchievementItem;
    global.gallerySubTypeName = gallerySubTypeName;
    global.clearCmsGalleryCache = function () {
        try { sessionStorage.removeItem(cacheKey()); } catch (e) {}
    };
})(typeof window !== 'undefined' ? window : this);
