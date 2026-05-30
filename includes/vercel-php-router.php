<?php

declare(strict_types=1);

function vercelEnsureSiteBaseUrl(): void
{
    if (defined('SITE_BASE_URL')) {
        return;
    }
    $base = $_SERVER['APS_BASE_PATH'] ?? '';
    if (is_string($base) && $base !== '') {
        define('SITE_BASE_URL', $base === '/' ? '/' : rtrim($base, '/') . '/');
        return;
    }
    if (!empty($_SERVER['VERCEL']) || (is_string(getenv('VERCEL')) && getenv('VERCEL') !== '')) {
        define('SITE_BASE_URL', '/');
    }
}

/**
 * Route a request within a single PHP site root (mirrors .htaccess rules).
 */
function vercelRoutePhpSite(string $root, string $subPath): void
{
    vercelEnsureSiteBaseUrl();
    $root = rtrim($root, '/\\');
    if (!is_dir($root)) {
        http_response_code(404);
        header('Content-Type: text/html; charset=utf-8');
        echo 'Site not found.';
        return;
    }

    chdir($root);
    $subPath = trim(str_replace(['..', '\\'], '', $subPath), '/');

    // Never serve .php as a static download — only execute via require.
    if ($subPath !== '' && preg_match('/\.php$/i', $subPath)) {
        $subPath = preg_replace('/\.php$/i', '', $subPath);
    }

    if ($subPath === '' || strcasecmp($subPath, 'home') === 0) {
        if (is_file($root . '/index.php')) {
            require $root . '/index.php';
            return;
        }
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
        echo 'index.php missing from deployment bundle.';
        return;
    }

    if (preg_match('#^blog/blog/([A-Za-z0-9_-]+)/?$#', $subPath, $m)) {
        $base = $_SERVER['APS_BASE_PATH'] ?? '/';
        header('Location: ' . rtrim($base, '/') . '/blog/' . $m[1], true, 301);
        return;
    }

    if (preg_match('#^blog/([A-Za-z0-9_-]+)/?$#', $subPath, $m)) {
        $_GET['slug'] = $m[1];
        require $root . '/detail.php';
        return;
    }

    if (preg_match('#^view/([A-Za-z0-9_-]+)/?$#', $subPath, $m)) {
        $_GET['slug'] = $m[1];
        if (is_file($root . '/detail-view.php')) {
            require $root . '/detail-view.php';
        } else {
            require $root . '/detail.php';
        }
        return;
    }

    $phpFile = $root . '/' . $subPath . '.php';
    if (is_file($phpFile)) {
        require $phpFile;
        return;
    }

    if (preg_match('#^[A-Za-z0-9_-]+$#', $subPath) && is_file($root . '/cms-page.php')) {
        $_GET['cms_path'] = $subPath;
        require $root . '/cms-page.php';
        return;
    }

    http_response_code(404);
    require $root . '/index.php';
}

function vercelSendStaticFile(string $file): void
{
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $types = [
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'mjs' => 'application/javascript; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'map' => 'application/json',
        'pdf' => 'application/pdf',
    ];
    if (isset($types[$ext])) {
        header('Content-Type: ' . $types[$ext]);
    }
    header('Cache-Control: public, max-age=2592000');
    readfile($file);
}

/**
 * @return array{site: string, subPath: string, basePath: string}|null
 */
function vercelResolveMonorepoSite(string $path): ?array
{
    $sites = ['allenhouseagra', 'allenhouseghaziabad'];
    $path = trim($path, '/');

    if (preg_match('#^api_integration/(' . implode('|', $sites) . ')(?:/(.*))?$#', $path, $m)) {
        return [
            'site' => $m[1],
            'subPath' => trim($m[2] ?? '', '/'),
            'basePath' => '/api_integration/' . $m[1] . '/',
        ];
    }

    if (preg_match('#^(' . implode('|', $sites) . ')(?:/(.*))?$#', $path, $m)) {
        return [
            'site' => $m[1],
            'subPath' => trim($m[2] ?? '', '/'),
            'basePath' => '/' . $m[1] . '/',
        ];
    }

    return null;
}
