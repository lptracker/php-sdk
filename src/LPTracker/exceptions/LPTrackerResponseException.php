<?php

namespace LPTracker\exceptions;

use Throwable;

/**
 * Class LPTrackerResponseException
 * @package LPTracker\exceptions
 */
class LPTrackerResponseException extends LPTrackerSDKException
{

    /**
     * LPTrackerResponseException constructor.
     *
     * @param string $responseError
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