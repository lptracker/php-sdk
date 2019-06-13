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
     * @var integer
     */
    protected $leadId;

    /**
     * @var string
     */
    protected $name;

    public function __construct(array $fileData = [])
    {
        if (!empty($fileData['id'])) {
            $this->id = (int) $fileData['id'];
        }
        if (!empty($fileData['lead_id'])) {
            $this->leadId = (int) $fileData['lead_id'];
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
        if (!empty($this->leadId)) {
            $result['lead_id'] = $this->getLeadId();
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
     * @return int
     */
    public function getLeadId()
    {
        return (int) $this->leadId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
