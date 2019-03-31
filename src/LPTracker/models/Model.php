<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

abstract class Model
{
    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }

    /**
     * @return array
     */
    abstract public function toArray();

    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    abstract public function validate();
}
