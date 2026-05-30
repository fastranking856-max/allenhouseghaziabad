<?php
// Central config for API endpoints and branch selection.
define('API_BASE_URL', 'https://apscmsnew.fastranking.cloud');
define('CMS_API_URL', rtrim(API_BASE_URL, '/') . '/api');
define('LEGACY_API_BASE_URL', 'https://cms.allenhouseschools.com');
define('LEGACY_CMS_API_URL', rtrim(LEGACY_API_BASE_URL, '/') . '/api');
define('SCHOOL_ID', '1');
define('BRANCH_ID', 3);
@ini_set('default_socket_timeout', '8');

if (!function_exists('cms_sort_grade_labels')) {
    /**
     * @param array<int, mixed> $labels
     * @return list<string>
     */
    function cms_sort_grade_labels(array $labels): array
    {
        $gradeOrder = [
            'P.G-I', 'P.G-II', 'Nursery', 'Prep',
            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'X12', 'XI', 'XII',
        ];
        $unique = [];
        foreach ($labels as $label) {
            $label = trim((string) $label);
            if ($label !== '' && !in_array($label, $unique, true)) {
                $unique[] = $label;
            }
        }
        usort($unique, static function (string $a, string $b) use ($gradeOrder): int {
            $posA = array_search($a, $gradeOrder, true);
            $posB = array_search($b, $gradeOrder, true);
            if ($posA === false && $posB === false) {
                return strnatcasecmp($a, $b);
            }
            if ($posA === false) {
                return 1;
            }
            if ($posB === false) {
                return -1;
            }

            return $posA <=> $posB;
        });

        return $unique;
    }
}

if (!function_exists('apply_curl_ssl_options')) {
    /**
     * @param resource|\CurlHandle $ch
     */
    function apply_curl_ssl_options($ch): void
    {
        $isLocal = false;
        $host = strtolower($_SERVER['HTTP_HOST'] ?? '');
        if ($host === 'localhost' || strpos($host, '127.0.0.1') === 0 || strpos($host, 'localhost:') === 0) {
            $isLocal = true;
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !$isLocal);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $isLocal ? 0 : 2);
    }
}

if (!function_exists('cms_curl_json_cache_ref')) {
    /**
     * @return array<string, array|null>
     */
    function &cms_curl_json_cache_ref(): array
    {
        static $cache = [];

        return $cache;
    }
}

if (!function_exists('cms_curl_seed_json_cache')) {
    function cms_curl_seed_json_cache(string $url, ?array $data): void
    {
        $cache = &cms_curl_json_cache_ref();
        $cache[$url] = $data;
    }
}

if (!function_exists('cms_curl_get_json')) {
    function cms_curl_get_json(string $url, int $timeoutSeconds = 6): ?array
    {
        $cache = &cms_curl_json_cache_ref();

        if (array_key_exists($url, $cache)) {
            return $cache[$url];
        }

        $timeoutSeconds = max(2, $timeoutSeconds);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, min(5, $timeoutSeconds));
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeoutSeconds);
        apply_curl_ssl_options($ch);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            $cache[$url] = null;
            return null;
        }
        $decoded = json_decode((string) $response, true);
        $cache[$url] = is_array($decoded) ? $decoded : null;
        return $cache[$url];
    }
}

if (!function_exists('cms_prefetch_json_urls')) {
    /**
     * Fetch multiple CMS URLs in parallel and seed the request cache.
     *
     * @param list<string> $urls
     */
    function cms_prefetch_json_urls(array $urls): void
    {
        if (!function_exists('curl_multi_init')) {
            return;
        }

        $cache = &cms_curl_json_cache_ref();
        $pending = [];
        foreach ($urls as $url) {
            $url = trim($url);
            if ($url === '' || array_key_exists($url, $cache)) {
                continue;
            }
            $pending[] = $url;
        }

        if ($pending === []) {
            return;
        }

        $mh = curl_multi_init();
        if ($mh === false) {
            return;
        }

        $handles = [];
        foreach ($pending as $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            apply_curl_ssl_options($ch);
            curl_multi_add_handle($mh, $ch);
            $handles[$url] = $ch;
        }

        $running = null;
        do {
            $status = curl_multi_exec($mh, $running);
            if ($running > 0) {
                curl_multi_select($mh, 1.0);
            }
        } while ($running > 0 && $status === CURLM_OK);

        foreach ($handles as $url => $ch) {
            $response = curl_multi_getcontent($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($response === false || $httpCode !== 200) {
                $cache[$url] = null;
            } else {
                $decoded = json_decode((string) $response, true);
                $cache[$url] = is_array($decoded) ? $decoded : null;
            }
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }

        curl_multi_close($mh);
    }
}

if (!function_exists('cms_prefetch_endpoints')) {
    /**
     * @param list<string> $endpoints
     */
    function cms_prefetch_endpoints(array $endpoints): void
    {
        $urls = [];
        foreach ($endpoints as $endpoint) {
            $endpoint = ltrim((string) $endpoint, '/');
            if ($endpoint === '') {
                continue;
            }
            if (cms_is_new_cms_route($endpoint)) {
                $urls[] = rtrim(CMS_API_URL, '/') . '/' . $endpoint;
                $urls[] = rtrim(LEGACY_CMS_API_URL, '/') . '/' . $endpoint;
            } else {
                $urls[] = rtrim(LEGACY_CMS_API_URL, '/') . '/' . $endpoint;
            }
        }
        cms_prefetch_json_urls($urls);
    }
}

if (!function_exists('cms_is_new_cms_route')) {
    function cms_is_new_cms_route(string $endpoint): bool
    {
        $prefixes = [
            'public/',
            'pages/',
            'menus/',
            'galleries/',
            'blogs/',
            'statistics/',
            'flyers/',
            'scrolling-texts/',
            'jobs/',
            'grades/',
            'cities/',
            'school-sessions',
        ];

        foreach ($prefixes as $prefix) {
            if (strpos($endpoint, $prefix) === 0) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('cms_fetch_json_endpoint')) {
    function cms_fetch_json_endpoint(string $endpoint): ?array
    {
        $endpoint = ltrim($endpoint, '/');
        static $cache = [];

        if (array_key_exists($endpoint, $cache)) {
            return $cache[$endpoint];
        }

        $primaryUrl = rtrim(CMS_API_URL, '/') . '/' . $endpoint;
        $legacyUrl = rtrim(LEGACY_CMS_API_URL, '/') . '/' . $endpoint;
        $preferPrimary = cms_is_new_cms_route($endpoint);

        if ($preferPrimary) {
            $data = cms_curl_get_json($primaryUrl);
            if (!is_array($data)) {
                $data = cms_curl_get_json($legacyUrl);
            }
        } else {
            // Classic Ghaziabad endpoints exist on legacy API only; avoid a second slow miss.
            $data = cms_curl_get_json($legacyUrl);
        }

        $cache[$endpoint] = is_array($data) ? $data : null;
        return $cache[$endpoint];
    }
}

if (!function_exists('cms_fetch_json_url')) {
    function cms_fetch_json_url(string $url): ?array
    {
        $normalized = str_replace('\\', '/', trim($url));
        $normalized = preg_replace('#^https?://[^/]+/api/#i', '', $normalized) ?? $normalized;
        $normalized = ltrim($normalized, '/');
        return cms_fetch_json_endpoint($normalized);
    }
}

if (!function_exists('cms_post_json_endpoint')) {
    function cms_post_json_endpoint(string $path, array $payload): array
    {
        $path = ltrim($path, '/');
        $urls = [
            rtrim(API_BASE_URL, '/') . '/' . $path,
            rtrim(LEGACY_API_BASE_URL, '/') . '/' . $path,
        ];
        $urls = array_values(array_unique($urls));

        foreach ($urls as $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            apply_curl_ssl_options($ch);
            $response = curl_exec($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response !== false && $status >= 200 && $status < 500) {
                return [
                    'status' => $status,
                    'body' => (string) $response,
                ];
            }
        }

        return [
            'status' => 502,
            'body' => json_encode(['status' => 'error', 'message' => 'Upstream API unavailable']),
        ];
    }
}