<?php
require_once 'config.php';
function fetchApiData($endpoint) {
    $normalized = trim((string) $endpoint);
    $normalized = str_replace('https://cms.allenhouseschools.com/api/', '', $normalized);
    $normalized = str_replace('http://cms.allenhouseschools.com/api/', '', $normalized);
    $normalized = ltrim($normalized, '/');
    $normalized = preg_replace('/SCLID\d+/i', (string) BRANCH_ID, $normalized) ?? $normalized;
    $data = cms_fetch_json_endpoint($normalized);
    if (!is_array($data)) {
        return null;
    }
    return $data;
}
