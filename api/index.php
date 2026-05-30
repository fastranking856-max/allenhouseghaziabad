<?php

declare(strict_types=1);

if (!defined('SITE_BASE_URL')) {
    define('SITE_BASE_URL', '/');
}
$_SERVER['APS_BASE_PATH'] = '/';
$_SERVER['VERCEL'] = '1';

require_once dirname(__DIR__) . '/vercel-slim/includes/vercel-php-router.php';

$uri = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$subPath = trim($uri, '/');

$slim = dirname(__DIR__) . '/vercel-slim';
if (!is_dir($slim)) {
    require_once dirname(__DIR__) . '/includes/vercel-php-router.php';
    $slim = dirname(__DIR__);
}

vercelRoutePhpSite($slim, $subPath);
