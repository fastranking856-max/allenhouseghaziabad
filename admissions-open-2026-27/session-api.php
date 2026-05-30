<?php
require_once dirname(__DIR__) . '/proxy/config.php';

$data = cms_fetch_json_endpoint('school-sessions');
if (!is_array($data)) {
    return [];
}

return $data['data']['data'] ?? [];
?>