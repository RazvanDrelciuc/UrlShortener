<?php

namespace App\Services;

use GuzzleHttp\Client;

class GoogleSafeBrowsingService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_SAFE_BROWSING_API_KEY');
        $this->clientId = env('GOOGLE_SAFE_BROWSING_CLIENT_ID');
    }

    /**
     * Checks a single URL for threats using the Google Safe Browsing API.
     *
     * @param string $url The URL to check.
     * @return string error message.
     */
    public function checkUrl($url)
    {
        try {
            $endpoint = 'https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . $this->apiKey;

            $payload = [
                'client' => [
                    'clientId' => $this->clientId,
                    'clientVersion' => '1.5.2',
                ],
                'threatInfo' => [
                    'threatTypes' => [
                        'MALWARE',
                        'SOCIAL_ENGINEERING',
                        'THREAT_TYPE_UNSPECIFIED',
                        'UNWANTED_SOFTWARE',
                        'POTENTIALLY_HARMFUL_APPLICATION',
                    ],
                    'platformTypes' => ['ANY_PLATFORM'],
                    'threatEntryTypes' => ['URL'],
                    'threatEntries' => [['url' => $url]],
                ],
            ];

            $client = new Client();
            $response = $client->post($endpoint, [
                'json' => $payload,
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (!empty($responseData['matches'])) {
                return 'URL is identified as potentially unsafe due to detected threats.';
            } else {
                return '';
            }
        } catch (\Exception $e) {
            return 'Error occurred while checking URL safety: ' . $e->getMessage();
        }
    }


}
