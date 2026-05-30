<?php

if (!function_exists('cms_subscriber_name_from_email')) {
    function cms_subscriber_name_from_email(string $email): string
    {
        $local = strstr($email, '@', true);
        if ($local === false) {
            return 'Newsletter Subscriber';
        }
        $local = trim(str_replace(['.', '_', '-'], ' ', $local));
        if ($local === '') {
            return 'Newsletter Subscriber';
        }

        return ucwords($local);
    }
}

if (!function_exists('cms_normalize_subscriber_payload')) {
    /**
     * @return array<string, mixed>
     */
    function cms_normalize_subscriber_payload(array $input): array
    {
        $email = trim((string) ($input['email'] ?? ''));
        $name = trim((string) ($input['name'] ?? ''));
        if ($name === '') {
            $name = cms_subscriber_name_from_email($email);
        }

        return [
            'name' => $name,
            'email' => $email,
            'school_id' => (int) (defined('SCHOOL_ID') ? SCHOOL_ID : 1),
            'branch_id' => (int) (defined('BRANCH_ID') ? BRANCH_ID : 1),
            'status' => 'active',
            'source' => 'website',
        ];
    }
}

if (!function_exists('cms_post_subscriber')) {
    /**
     * @return array{status: int, body: string}
     */
    function cms_post_subscriber(array $input): array
    {
        if (!defined('CMS_API_URL')) {
            return [
                'status' => 500,
                'body' => json_encode(['success' => false, 'message' => 'CMS API is not configured']),
            ];
        }

        $payload = cms_normalize_subscriber_payload($input);
        if ($payload['email'] === '') {
            return [
                'status' => 422,
                'body' => json_encode(['success' => false, 'message' => 'Email is required.']),
            ];
        }

        $url = rtrim(CMS_API_URL, '/') . '/subscribers';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 20,
        ]);
        if (function_exists('apply_curl_ssl_options')) {
            apply_curl_ssl_options($ch);
        }

        $response = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $status < 200) {
            return [
                'status' => $status > 0 ? $status : 502,
                'body' => json_encode([
                    'success' => false,
                    'message' => 'Unable to subscribe. Please try again.',
                ]),
            ];
        }

        return [
            'status' => $status,
            'body' => (string) $response,
        ];
    }
}
