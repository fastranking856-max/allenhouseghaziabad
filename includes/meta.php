<?php
include_once 'api.php';

$metaTitle = "Allenhouse Lucknow - Quality Education for Future Leaders";
$metaDescription = "Description";

// Fetch API data
$metaData = fetchApiData("/meta-data/" . BRANCH_ID);
$metaItems = $metaData['data'] ?? [];

// Get current file name without extension
$currentFile = basename($_SERVER['PHP_SELF'], '.php');

// Map PHP file names to webpage names (shared with image alt text API)
$pageMap = include __DIR__ . '/cms-webpage-map.php';

$currentPageName = $pageMap[$currentFile] ?? null;

// Match and assign meta data
if ($currentPageName) {
    foreach ($metaItems as $item) {
        if (isset($item['webpage']['name']) && strcasecmp(trim($item['webpage']['name']), trim($currentPageName)) === 0) {
            $metaTitle = $item['title'];
            $metaDescription = $item['description'];
            break;
        }
    }
}

// Output meta tags
echo "<title>$metaTitle</title>\n";
echo "<meta name=\"description\" content=\"$metaDescription\">\n";
?>
