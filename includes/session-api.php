<?php
require_once dirname(__DIR__) . '/proxy/config.php';

$data = cms_fetch_json_endpoint('school-sessions');
if (!is_array($data)) {
    return [];
}

// return the array
if (isset($data['data']['data']) && is_array($data['data']['data'])) {
    return $data['data']['data'];
}
return [];
?>