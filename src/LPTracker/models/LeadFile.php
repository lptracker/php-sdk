<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class LeadFile extends Model
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    public function __construct(array $fileData = [])
    {
        if (!empty($fileData['id'])) {
            $this->id = (int) $fileData['id'];
        }
        if (!empty($fileData['name'])) {
            $this->name = (int) $fileData['name'];
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];
        if (!empty($this->id)) {
            $result['id'] = $this->getId();
        }
        if (!empty($this->name)) {
            $result['name'] = $this->getName();
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
