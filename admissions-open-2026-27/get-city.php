<?php
require_once dirname(__DIR__) . '/proxy/config.php';

$citiesData = cms_fetch_json_endpoint('cities/' . SCHOOL_ID);
if (!is_array($citiesData) || !isset($citiesData['data']) || !is_array($citiesData['data'])) {
    echo "<option value=''>Cities unavailable</option>";
    return;
}

$uniqueCities = [];
foreach ($citiesData['data'] as $city) {
    if (!is_array($city)) {
        continue;
    }
    $cityName = trim((string) ($city['name'] ?? ''));
    if ($cityName === '') {
        continue;
    }
    $key = strtolower($cityName);
    if (isset($uniqueCities[$key])) {
        continue;
    }
    $uniqueCities[$key] = $cityName;
}

$cityNames = array_values($uniqueCities);
sort($cityNames, SORT_NATURAL | SORT_FLAG_CASE);

foreach ($cityNames as $cityName) {
    $safe = htmlspecialchars($cityName, ENT_QUOTES, 'UTF-8');
    echo "<option value=\"{$safe}\">{$safe}</option>";
}
