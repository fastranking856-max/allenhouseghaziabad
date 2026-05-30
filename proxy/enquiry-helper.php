<?php

if (!function_exists('cms_enquiry_pick')) {
    function cms_enquiry_pick(array $data, array $keys, string $default = ''): string
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

if (!function_exists('cms_enquiry_source_fields')) {
    /**
     * @return array{source: string, source_type: string}
     */
    function cms_enquiry_source_fields(string $sourceRaw, string $sourceTypeRaw = ''): array
    {
        $sourceType = strtolower(trim($sourceTypeRaw));
        if (in_array($sourceType, ['organic', 'paid', 'referral', 'social', 'direct'], true)) {
            return [
                'source' => 'website',
                'source_type' => $sourceType,
            ];
        }

        $source = strtolower(trim($sourceRaw));
        if ($source === '' || $source === 'website' || $source === 'landing page') {
            return ['source' => 'website', 'source_type' => 'organic'];
        }
        if (
            str_contains($source, 'google')
            || str_contains($source, 'ads')
            || str_contains($source, 'facebook')
            || str_contains($source, 'instagram')
        ) {
            return ['source' => 'website', 'source_type' => 'paid'];
        }

        return ['source' => 'website', 'source_type' => 'organic'];
    }
}

if (!function_exists('cms_normalize_enquiry_payload')) {
    /**
     * @return array<string, mixed>
     */
    function cms_normalize_enquiry_payload(array $input): array
    {
        $studentName = cms_enquiry_pick($input, ['student_name', 'studentName']);
        $parentName = cms_enquiry_pick($input, ['parent_name', 'parentName']);
        $email = cms_enquiry_pick($input, ['email']);
        $phone = cms_enquiry_pick($input, ['phone', 'mobile']);
        $grade = cms_enquiry_pick($input, ['grade']);
        $city = cms_enquiry_pick($input, ['city']);
        $session = cms_enquiry_pick($input, ['session']);
        $pincode = cms_enquiry_pick($input, ['pincode']);

        $sourceRaw = cms_enquiry_pick($input, ['source'], 'website');
        $sourceTypeRaw = cms_enquiry_pick($input, ['source_type', 'sourceType']);
        $sourceInfo = cms_enquiry_source_fields($sourceRaw, $sourceTypeRaw);

        $message = cms_enquiry_pick($input, ['message']);
        if ($message === '') {
            $gradeLabel = $grade !== '' ? $grade : 'the selected grade';
            $message = 'I want admission details for ' . $gradeLabel . '.';
            if ($session !== '') {
                $message = 'I want admission details for ' . $gradeLabel . ' (Session ' . $session . ').';
            }
        }

        $contactName = cms_enquiry_pick($input, ['name']);
        if ($contactName === '') {
            $contactName = $parentName !== '' ? $parentName : $studentName;
        }

        return [
            'school_id' => (int) (defined('SCHOOL_ID') ? SCHOOL_ID : 1),
            'branch_id' => (int) (defined('BRANCH_ID') ? BRANCH_ID : 1),
            'name' => $contactName,
            'email' => $email,
            'phone' => $phone,
            'student_name' => $studentName,
            'parent_name' => $parentName,
            'grade' => $grade,
            'city' => $city,
            'enquiry_type' => cms_enquiry_pick($input, ['enquiry_type'], 'admission'),
            'subject' => cms_enquiry_pick($input, ['subject'], 'Admission Enquiry'),
            'message' => $message,
            'priority' => cms_enquiry_pick($input, ['priority'], 'medium'),
            'status' => cms_enquiry_pick($input, ['status'], 'new'),
            'source' => $sourceInfo['source'],
            'source_type' => $sourceInfo['source_type'],
            'session' => $session,
            'pincode' => $pincode,
        ];
    }
}

if (!function_exists('cms_post_enquiry')) {
    /**
     * @return array{status: int, body: string}
     */
    function cms_post_enquiry(array $input): array
    {
        if (!defined('CMS_API_URL')) {
            return [
                'status' => 500,
                'body' => json_encode(['success' => false, 'message' => 'CMS API is not configured']),
            ];
        }

        $payload = cms_normalize_enquiry_payload($input);
        $url = rtrim(CMS_API_URL, '/') . '/enquiries';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
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
                    'message' => 'Unable to submit enquiry. Please try again.',
                ]),
            ];
        }

        return [
            'status' => $status,
            'body' => (string) $response,
        ];
    }
}
