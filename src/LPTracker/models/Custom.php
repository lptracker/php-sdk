<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class Custom extends Model
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var integer
     */
    protected $leadId;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param array $customData
     * @param int $leadId
     */
    public function __construct(array $customData = [], $leadId = 0)
    {
        if (!empty($customData['id'])) {
            $this->id = $customData['id'];
        }
        if (!empty($customData['type'])) {
            $this->type = $customData['type'];
        }
        if (!empty($customData['name'])) {
            $this->name = $customData['name'];
        }
        if (!empty($customData['value'])) {
            $this->value = $customData['value'];
        }
        if ($leadId > 0) {
            $this->leadId = $leadId;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            'id' => $this->id,
        ];
        if (!empty($this->type)) {
            $result['type'] = $this->type;
        }
        if (!empty($this->name)) {
            $result['name'] = $this->name;
        }
        if (!empty($this->value)) {
            $result['value'] = $this->value;
        }
        return $result;
    }

    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (empty($this->id)) {
            throw new LPTrackerSDKException('Id is required');
        }

        return true;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $leadId
     * @return $this
     */
    public function setLeadId($leadId)
    {
        $this->leadId = $leadId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLeadId()
    {
        return $this->leadId;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
