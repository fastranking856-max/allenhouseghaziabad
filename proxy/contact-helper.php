<?php

if (!function_exists('cms_contact_pick')) {
    function cms_contact_pick(array $data, array $keys, string $default = ''): string
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data) || $data[$key] === null) {
                continue;
            }
            $value = trim((string) $data[$key]);
            if ($value !== '') {
                return $value;
            }
        }

        return $default;
    }
}

if (!function_exists('cms_normalize_contact_query_payload')) {
    /**
     * @return array<string, mixed>
     */
    function cms_normalize_contact_query_payload(array $input): array
    {
        $name = cms_contact_pick($input, ['name']);
        $email = cms_contact_pick($input, ['email']);
        $phone = cms_contact_pick($input, ['phone', 'mobile']);
        $message = cms_contact_pick($input, ['message']);
        $queryType = cms_contact_pick($input, ['query_type', 'queryType', 'query'], 'General');
        $subject = cms_contact_pick($input, ['subject'], $queryType);

        return [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'school_id' => (int) (defined('SCHOOL_ID') ? SCHOOL_ID : 1),
            'branch_id' => (int) (defined('BRANCH_ID') ? BRANCH_ID : 1),
            'query_type' => $queryType,
            'source' => cms_contact_pick($input, ['source'], 'website'),
        ];
    }
}

if (!function_exists('cms_post_contact_query')) {
    /**
     * @return array{status: int, body: string}
     */
    function cms_post_contact_query(array $input): array
    {
        if (!defined('CMS_API_URL')) {
            return [
                'status' => 500,
                'body' => json_encode(['success' => false, 'message' => 'CMS API is not configured']),
            ];
        }

        $payload = cms_normalize_contact_query_payload($input);
        if ($payload['name'] === '' || $payload['email'] === '' || $payload['phone'] === '' || $payload['message'] === '') {
            return [
                'status' => 422,
                'body' => json_encode(['success' => false, 'message' => 'Please fill all required fields.']),
            ];
        }

        $url = rtrim(CMS_API_URL, '/') . '/contact-queries';
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
                    'message' => 'Unable to submit contact form. Please try again.',
                ]),
            ];
        }

        return [
            'status' => $status,
            'body' => (string) $response,
        ];
    }
}
