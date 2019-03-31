<?php

namespace LPTracker\exceptions;

class LPTrackerResponseException extends LPTrackerSDKException
{
    /**
     * @param array|string $responseError
     */
    public function __construct($responseError)
    {
        if (is_array($responseError)) {
            parent::__construct($responseError['message'], $responseError['code']);
        } else {
            parent::__construct($responseError);
        }
    }
}
