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
    protected $version;

    /**
     * @var string
     */
    protected $fingerprint;

    /**
     * @var string
     */
    protected $browser;

    /**
     * @var string
     */
    protected $ip;

    public function __construct(array $visitorData = [])
    {
        if (isset($visitorData['version'], $visitorData['fingerprint'], $visitorData['browser'], $visitorData['ip'])) {
            $this->version = (int) $visitorData['version'];
            $this->fingerprint = $visitorData['fingerprint'];
            $this->browser = $visitorData['browser'];
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
