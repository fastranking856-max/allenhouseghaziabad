<?php

declare(strict_types=1);

if (!function_exists('is_app_local')) {
    function is_app_local(): bool
    {
        $env = getenv('APP_ENV');
        if (is_string($env) && $env !== '') {
            $e = strtolower(trim($env));
            if ($e === 'production' || $e === 'prod') {
                return false;
            }
            if ($e === 'local' || $e === 'development' || $e === 'dev') {
                return true;
            }
        }

        $host = strtolower($_SERVER['HTTP_HOST'] ?? '');
        if ($host === '' || $host === 'localhost') {
            return true;
        }
        if (strpos($host, '127.0.0.1') === 0 || strpos($host, 'localhost:') === 0) {
            return true;
        }
        if (strpos($host, '.local') !== false || strpos($host, '.test') !== false) {
            return true;
        }

        return false;
    }
}

if (!function_exists('is_production_public_host')) {
    function is_production_public_host(): bool
    {
        $host = strtolower($_SERVER['HTTP_HOST'] ?? '');

        return $host === 'allenhouseghaziabad.com' || $host === 'www.allenhouseghaziabad.com';
    }
}

if (!function_exists('is_vercel_deployment')) {
    function is_vercel_deployment(): bool
    {
        if (!empty($_SERVER['VERCEL'])) {
            return true;
        }
        $vercelEnv = getenv('VERCEL');
        if (is_string($vercelEnv) && $vercelEnv !== '' && $vercelEnv !== '0') {
            return true;
        }
        $host = strtolower($_SERVER['HTTP_HOST'] ?? '');

        return str_contains($host, 'vercel.app');
    }
}

if (!function_exists('site_base_url')) {
    /**
     * Web path prefix for this site (e.g. /api_integration/allenhouseghaziabad/ or /).
     */
    function site_base_url(): string
    {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }

        if (defined('SITE_BASE_URL')) {
            $cached = SITE_BASE_URL;
            return $cached;
        }

        if (is_vercel_deployment()) {
            $cached = '/';
            return $cached;
        }

        if (!empty($_SERVER['APS_BASE_PATH'])) {
            $base = (string) $_SERVER['APS_BASE_PATH'];
            $cached = $base === '/' ? '/' : rtrim($base, '/') . '/';
            return $cached;
        }

        $siteRoot = rtrim(str_replace('\\', '/', realpath(dirname(__DIR__)) ?: dirname(__DIR__)), '/');
        $docRoot = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: ($_SERVER['DOCUMENT_ROOT'] ?? '')), '/');

        if ($docRoot !== '' && stripos($siteRoot, $docRoot) === 0) {
            $relative = ltrim(substr($siteRoot, strlen($docRoot)), '/');
            if ($relative === 'vercel-slim' || str_starts_with($relative, 'vercel-slim/')) {
                $cached = '/';
                return $cached;
            }
            $cached = $relative === '' ? '/' : '/' . $relative . '/';
            return $cached;
        }

        if (str_ends_with($siteRoot, '/vercel-slim') || str_ends_with($siteRoot, '\\vercel-slim')) {
            $cached = '/';
            return $cached;
        }

        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        if ($scriptName !== '' && $scriptName[0] === '/') {
            $dir = dirname($scriptName);
            if ($dir === '/' || $dir === '.' || str_contains($dir, 'vercel-slim')) {
                $cached = '/';
                return $cached;
            }
            $cached = rtrim($dir, '/') . '/';
            return $cached;
        }

        $cached = '/';
        return $cached;
    }
}

if (!function_exists('site_asset_url')) {
    function site_asset_url(string $path): string
    {
        $path = ltrim($path, '/');
        if (is_vercel_deployment()) {
            return '/' . $path;
        }

        $url = site_base_url() . $path;
        if (str_contains($url, '/vercel-slim/')) {
            return preg_replace('#/vercel-slim/#', '/', $url, 1);
        }

        return $url;
    }
}

if (!function_exists('cmsMenuUrl')) {
    function cmsMenuUrl(?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '#';
        }
        if (str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:')) {
            return $url;
        }

        if (preg_match('#^https?://#i', $url)) {
            if (is_app_local()) {
                $path = parse_url($url, PHP_URL_PATH) ?: '';
                $host = strtolower(parse_url($url, PHP_URL_HOST) ?: '');
                if (
                    $path !== '' &&
                    (
                        $host === 'allenhouseghaziabad.com'
                        || $host === 'www.allenhouseghaziabad.com'
                        || $host === 'localhost'
                        || $host === '127.0.0.1'
                    )
                ) {
                    return site_base_url() . ltrim($path, '/');
                }
            }

            return $url;
        }

        if ($url === '/') {
            return site_base_url();
        }

        return site_base_url() . ltrim($url, '/');
    }
}
