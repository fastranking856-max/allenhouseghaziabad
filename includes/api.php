<?php
require_once dirname(__DIR__) . '/proxy/config.php';

if (!function_exists('normalizeLegacyEndpoint')) {
    function normalizeLegacyEndpoint(string $endpoint): string
    {
        $normalized = trim($endpoint);
        $normalized = str_replace('https://cms.allenhouseschools.com/api/', '', $normalized);
        $normalized = str_replace('http://cms.allenhouseschools.com/api/', '', $normalized);
        $normalized = ltrim($normalized, '/');
        $normalized = preg_replace('/SCLID\d+/i', (string) BRANCH_ID, $normalized) ?? $normalized;

        return $normalized;
    }
}

if (!function_exists('apiDataList')) {
    /**
     * @param array<string, mixed>|null $response
     * @return list<array<string, mixed>>
     */
    function apiDataList(?array $response): array
    {
        if (!is_array($response)) {
            return [];
        }
        $data = $response['data'] ?? null;

        return is_array($data) ? $data : [];
    }
}

if (!function_exists('fetchApiData')) {
    function fetchApiData($endpoint)
    {
        $normalized = normalizeLegacyEndpoint((string) $endpoint);
        $data = cms_fetch_json_endpoint($normalized);
        if (!is_array($data)) {
            return ['status' => 'error', 'data' => []];
        }

        return $data;
    }
}