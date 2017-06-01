<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 29.05.17
 * Time: 19:26
 */

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

/**
 * Class Model
 * @package LPTracker\models
 */
abstract class Model
{

    /**
     * @return array
     */
    abstract public function toArray();


    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    abstract public function validate();


    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }
}