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
     * Map contact form fields to CMS /api/contact-queries schema.
     *
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

        require_once __DIR__ . '/cms-http.php';

        return cms_curl_post_json($url, $payload, 'Unable to submit contact form. Please try again.');
    }
}
