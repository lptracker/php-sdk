<?php

namespace LPTracker;

use anlutro\cURL\cURL;
use anlutro\cURL\Request;
use LPTracker\authentication\AccessToken;
use LPTracker\exceptions\LPTrackerResponseException;
use LPTracker\exceptions\LPTrackerServerException;

class LPTrackerRequest
{
    /**
     * @param string $actionUrl
     * @param array $data
     * @param string $method
     * @param AccessToken|null $token
     * @param string $baseUrl
     * @return mixed
     * @throws LPTrackerResponseException
     * @throws LPTrackerServerException
     */
    public static function sendRequest(
        $actionUrl,
        array $data = [],
        $method = 'GET',
        AccessToken $token = null,
        $baseUrl = ''
    ) {
        if (empty($baseUrl)) {
            $baseUrl = LPTrackerBase::DEFAULT_ADDRESS;
        }
        $url = $baseUrl . $actionUrl;
        $curl = new cURL();
        $request = $curl->newRequest($method, $url, $data, Request::ENCODING_JSON);
        $request->setHeader('Content-Type', 'application/json');
        if ($token instanceof AccessToken) {
            $request->setHeader('token', $token->getValue());
        }
        $response = $request->send();
        if ($response === false) {
            throw new LPTrackerServerException('Can`t get response from server');
        }

        $body = json_decode($response->body, true);
        if ($body === false) {
            throw new LPTrackerServerException('Can`t decode response');
        }

        if (!empty($body['errors'])) {
            if (!empty($body['errors'][0]['message'])) {
                throw new LPTrackerResponseException($body['errors'][0]['message']);
            }

            throw new LPTrackerResponseException($body['errors'][0]);
        }

        if (empty($body['status']) || $body['status'] !== 'success') {
            throw new LPTrackerResponseException('Unknown response error');
        }

        return $body['result'];
    }
}
