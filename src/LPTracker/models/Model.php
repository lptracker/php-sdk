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
    public abstract function toArray();


    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public abstract function validate();


    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }
}