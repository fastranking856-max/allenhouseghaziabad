<?php
require_once dirname(__DIR__) . '/proxy/config.php';
function fetchMultipleApiData($endpoints)
{
    $baseUrl = rtrim(CMS_API_URL, '/');
    $mh = curl_multi_init();
    $curlHandles = [];
    $responses = [];
    // Create all curl handles
    foreach ($endpoints as $key => $endpoint) {
        $ch = curl_init();
        $resolvedEndpoint = str_replace('{BRANCH_ID}', (string) BRANCH_ID, $endpoint);
        curl_setopt($ch, CURLOPT_URL, $baseUrl . $resolvedEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        apply_curl_ssl_options($ch);
        curl_multi_add_handle($mh, $ch);
        $curlHandles[$key] = $ch;
    }
    $running = null;
    do {
        $status = curl_multi_exec($mh, $running);

        if ($status > CURLM_OK) {
             break;
        }
        curl_multi_select($mh);
        usleep(10000);
    } while ($running > 0);

    // Collect responses
    foreach ($curlHandles as $key => $ch) {
        $content = curl_multi_getcontent($ch);
        $responses[$key] = json_decode($content, true);
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    curl_multi_close($mh);
    return $responses;
}
$endpoints = [
    'hero_banner'   => '/landing-page-hero-banner/{BRANCH_ID}',
    'hero_text'     => '/landing-page-hero-banner-text/{BRANCH_ID}',
    'about_us'      => '/landing-page-about-us/{BRANCH_ID}',
    'shaping_data'  => '/landing-page-future-ready-skills/{BRANCH_ID}',
    'advance_data'  => '/landing-page-advance-academic-model/{BRANCH_ID}',
    'why_data'      => '/landing-page-why-do-family-choose/{BRANCH_ID}',
    'century_data'  => '/landing-page-21-century-skill/{BRANCH_ID}',
    'social_data'   => '/landing-page-social-media/{BRANCH_ID}',
    'legacy_data'   => '/landing-page-legacy-of-excellence/{BRANCH_ID}',
    'testimonial_data' => '/landing-page-parents-insights/{BRANCH_ID}',
    'address_data'  => '/landing-page-footer-address/{BRANCH_ID}',
    'cta_data'      => '/landing-page-cta/{BRANCH_ID}',
    'achievement_data'      => '/landing-page-allenhouse-achievement/{BRANCH_ID}',
    'life_data' => '/anding-page-life-at-allenhouse/{BRANCH_ID}',
    'whatsapp' => '/whatsapp/{BRANCH_ID}',
    'brochure' => '/school-brochure/{BRANCH_ID}',
    'fee' => '/fee-structure/{BRANCH_ID}',
    'world_class' => '/landing-page-our-achievers/{BRANCH_ID}'
];

$data = fetchMultipleApiData($endpoints);
$hero_banner   = $data['hero_banner'];
$hero_text     = $data['hero_text'];
$about_us      = $data['about_us'];
$shaping_data = $data['shaping_data'];
$advance_data  = $data['advance_data'];
$why_data = $data['why_data'];
$century_data = $data['century_data'];
$social_data = $data['social_data'];
$legacy_data = $data['legacy_data'];
$testimonial_data = $data['testimonial_data'];
$address_data = $data['address_data'];
$cta_data = $data['cta_data'];
$achievement_data = $data['achievement_data'];
$life_data = $data['life_data'];
$whatsapp = $data['whatsapp'];
$brochure = $data['brochure'];
$fee = $data['fee'];
$world_class = $data['world_class'];

?>