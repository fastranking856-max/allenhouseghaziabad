<?php
require_once dirname(__DIR__) . '/proxy/config.php';

$grades = cms_fetch_json_endpoint('grades/' . BRANCH_ID);
if (!is_array($grades) || !isset($grades['data']) || !is_array($grades['data'])) {
    echo "<option value=''>Grades unavailable</option>";
    return;
}

$labels = [];
foreach ($grades['data'] as $grade) {
    if (!is_array($grade)) {
        continue;
    }
    $labels[] = $grade['grades'] ?? $grade['name'] ?? '';
}

$sortedGrades = cms_sort_grade_labels($labels);

echo "<option value='' disabled selected>Select Grade</option>";
foreach ($sortedGrades as $label) {
    echo '<option value="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($label, ENT_QUOTES, 'UTF-8')
        . '</option>';
}
