<?php
require_once dirname(__DIR__) . '/proxy/config.php';

$gradesData = cms_fetch_json_endpoint('grades/' . BRANCH_ID);
if (!is_array($gradesData) || !isset($gradesData['data']) || !is_array($gradesData['data'])) {
    echo "<option value=''>Grades unavailable</option>";
    return;
}

$labels = [];
foreach ($gradesData['data'] as $grade) {
    if (!is_array($grade)) {
        continue;
    }
    $labels[] = $grade['grades'] ?? $grade['name'] ?? '';
}

foreach (cms_sort_grade_labels($labels) as $label) {
    echo '<option value="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($label, ENT_QUOTES, 'UTF-8')
        . '</option>';
}
