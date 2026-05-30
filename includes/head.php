<?php
require_once __DIR__ . '/environment.php';
require_once dirname(__DIR__) . '/proxy/config.php';
$apsCssBase = is_vercel_deployment() ? '/' : site_base_url();
?>
<link rel="stylesheet" href="<?= htmlspecialchars($apsCssBase, ENT_QUOTES, 'UTF-8') ?>assets/css/styles.css?v=0.05">
<link rel="stylesheet" href="<?= htmlspecialchars($apsCssBase, ENT_QUOTES, 'UTF-8') ?>assets/css/responsive.css?v=0.05">
<script src="<?= htmlspecialchars($apsCssBase, ENT_QUOTES, 'UTF-8') ?>assets/js/tailwind.js"></script>
<link rel="icon" type="image/x-icon"
    href="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/ZORSdJXNGlZAVQJ4ZNWZsMEVZ1thgvBkL8JeOSXH.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
<meta name="robots" content="index, follow" />
<meta name="google-site-verification" content="Bwha_jocXP5WsblJnlNc0z4WQmAgMrbfo4IEsd3earw" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<style>
    .ql-editor {
        height: auto !important;
        padding: 0 !important;
        white-space: normal !important;
    }

    /* Quill snow adds bullets to all lists — hide unless list-style is set explicitly */
    .ql-editor ul:not([style*="list-style"]),
    .ql-editor ol:not([style*="list-style"]) {
        list-style: none !important;
        list-style-type: none !important;
        padding-left: 0 !important;
    }

    .ql-editor ul:not([style*="list-style"]) > li,
    .ql-editor ol:not([style*="list-style"]) > li {
        list-style: none !important;
        list-style-type: none !important;
    }

    .ql-editor ul:not([style*="list-style"]) > li::before,
    .ql-editor ol:not([style*="list-style"]) > li::before,
    .ql-editor ul:not([style*="list-style"]) li[data-list]::before,
    .ql-editor ol:not([style*="list-style"]) li[data-list]::before {
        content: none !important;
        display: none !important;
    }

    ul:not([style*="list-style"]) > li::marker,
    ol:not([style*="list-style"]) > li::marker {
        content: none !important;
    }
</style>

<script>
var baseUrl = "<?= rtrim(API_BASE_URL, '/') ?>";
var branchId = "<?= BRANCH_ID ?>";
var cmsBranchId = <?= (int) BRANCH_ID ?>;

function fixCmsAssetUrl(url) {
    if (!url) return '';
    if (url.indexOf('https://apscmsnew.fastranking.cloud/https://') === 0) {
        return url.replace('https://apscmsnew.fastranking.cloud/', '');
    }
    return url;
}

function normalizeCmsGalleryItem(item) {
    var medias = (item.media || []).map(function(m, i) {
        var url = fixCmsAssetUrl(m.media_url || m.media_file || '');
        return {
            urls: url,
            pivot: { is_cover: i === 0 ? '1' : '0' }
        };
    });
    var subType = (item.gallery_sub_type && item.gallery_sub_type.sub_type_name) || item.gallery_type || '';
    return {
        id: item.id,
        title: item.heading || item.gallery_title || '',
        achievementtitle: item.heading || item.gallery_title || '',
        achivementdescription: item.content || '',
        description: item.content || '',
        achevementdate: item.date,
        date: item.date,
        created_at: item.created_at || item.date,
        achivementtype: subType,
        type: subType,
        subTypeId: (item.gallery_sub_type && item.gallery_sub_type.id) || null,
        subTypeName: subType,
        galleryType: item.gallery_type || '',
        medias: medias
    };
}

var CMS_GALLERY_SUBTYPE = { achievements: 1, photo: 5, video: 6, print: 7 };

window.onerror = function(msg, url, line, col, error) {
    console.error("GLOBAL ERROR:", {
        msg: msg,
        url: url,
        line: line,
        col: col,
        error: error
    });
};

window.onunhandledrejection = function(event) {
    console.error("PROMISE REJECTION:", event.reason);
};

console.log("JS STARTED");
</script>
<script src="<?= htmlspecialchars($apsCssBase, ENT_QUOTES, 'UTF-8') ?>assets/js/gallery-year-api.js?v=2"></script>
<!-- Google Tag Manager -->
<script>
(function(w, d, s, l, i) {
    w[l] = w[l] || [];
    w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
    });
    var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
    j.async = true;
    j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
    f.parentNode.insertBefore(j, f);
})(window, document, 'script', 'dataLayer', 'GTM-PMKBJSX5');
</script>
<!-- End Google Tag Manager -->

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "@id": "https://allenhouseghaziabad.com/#website",
  "url": "https://allenhouseghaziabad.com/",
  "name": "Allenhouse Ghaziabad",
  "alternateName": "Allenhouse School Ghaziabad",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://allenhouseghaziabad.com/?s={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "School",
  "@id": "https://allenhouseghaziabad.com/#school",
  "name": "Allenhouse Ghaziabad",
  "alternateName": "Allenhouse School Ghaziabad",
  "url": "https://allenhouseghaziabad.com/",
  "logo": "https://allenhouseghaziabad.com/assets/images/logo.jpg",
  "image": "https://lh3.googleusercontent.com/p/AF1QipNOOz1lc5UFPKu7brzesd-elk7qgnVze2RaieoO=w432-h240-k-no",
  "telephone": "+91-6390907005",
  "email": "contact@allenhouseghaziabad.com",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "PS2, Sector 2A, Vasundhara
",
    "addressLocality": "Ghaziabad",
    "addressRegion": "Uttar Pradesh",
    "postalCode": "282007",
    "addressCountry": "IN"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "28.6689484",
    "longitude": "77.3855212"
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday"
    ],
    "opens": "08:00",
    "closes": "16:00"
  },
  "sameAs": [
    "https://www.facebook.com/APSVasundharaghaziabad/",
    "https://www.instagram.com/allenhouse.ghaziabad/",
    "https://www.linkedin.com/school/allenhouse-public-school-vasundhara-ghaziabad/",
    "https://www.youtube.com/@allenhousepublicschoolgzb9965"
  ]
}
</script>
<meta property="og:url" content="https://allenhouseghaziabad.com/">
<meta property="og:type" content="website">
<meta property="og:title" content="Best CBSE Board School in Ghaziabad | APS Ghaziabad">
<meta property="og:description" content=Searching for a top-rated CBSE school in Ghaziabad? APS Ghaziabad delivers quality English medium education, expert faculty & modern facilities. Apply now.">
<meta property="og:image" content="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/oxvfd8qsR1gA8Inax1SY8kxnAiYAZ6bsXLaPRLsc.png">
<meta property="og:site_name" content="Allenhouse Ghaziabad">


<meta name="twitter:card" content="summary_large_image">
<meta property="twitter:domain" content="allenhouseghaziabad.com">
<meta property="twitter:url" content="https://allenhouseghaziabad.com/">
<meta name="twitter:title" content="Best CBSE Board School in Ghaziabad | APS Ghaziabad">
<meta name="twitter:description" content="Searching for a top-rated CBSE school in Ghaziabad? APS Ghaziabad delivers quality English medium education, expert faculty & modern facilities. Apply now.">
<meta name="twitter:image" content="https://myschool-assets.s3.ap-south-1.amazonaws.com/uploads/oxvfd8qsR1gA8Inax1SY8kxnAiYAZ6bsXLaPRLsc.png">
