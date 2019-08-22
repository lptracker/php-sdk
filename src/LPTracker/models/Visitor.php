<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

/**
 * This class represents visitor as seen in LPTracker widget and should be treated as opaque.
 */
final class Visitor extends Model
{
    /**
     * @var int
     */
    private $version;

    /**
     * @var string
     */
    private $fingerprint;

    /**
     * @var string
     */
    private $browser;

    /**
     * @var string
     */
    private $ip;

    public function __construct(array $visitorData = [])
    {
        if (isset($visitorData['version'])) {
            $this->version = (int) $visitorData['version'];
        }
        if (isset($visitorData['fingerprint'])) {
            $this->fingerprint = $visitorData['fingerprint'];
        }
        if (isset($visitorData['browser'])) {
            $this->browser = $visitorData['browser'];
        }
        if (isset($visitorData['ip'])) {
            $this->ip = $visitorData['ip'];
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'version' => $this->version,
            'fingerprint' => $this->fingerprint,
            'browser' => $this->browser,
            'ip' => $this->ip,
        ];
    }

    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if ($this->version === null) {
            throw new LPTrackerSDKException('Visitor version is required');
        }

        if (empty($this->fingerprint)) {
            throw new LPTrackerSDKException('Visitor fingerprint is required');
        }

        if (empty($this->browser)) {
            throw new LPTrackerSDKException('Visitor user agent is required');
        }

        if (empty($this->ip)) {
            throw new LPTrackerSDKException('Visitor IP is required');
        }

        return true;
    }
}
