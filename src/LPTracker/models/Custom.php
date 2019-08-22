<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class Custom extends Model
{
    /**
     * @var int
     */
    protected $leadId;

    /**
     * @var int
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
     * @var mixed
     */
    protected $value;

    /**
     * @param array $customData
     * @param int $leadId
     */
    public function __construct(array $customData = [], $leadId = 0)
    {
        if (isset($customData['id'])) {
            $this->id = (int) $customData['id'];
        }
        if (isset($customData['type'])) {
            $this->type = $customData['type'];
        }
        if (isset($customData['name'])) {
            $this->name = $customData['name'];
        }
        if (isset($customData['value'])) {
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
            'type' => $this->type,
            'name' => $this->name,
            'value' => $this->value,
        ];
        return $result;
    }

    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (empty($this->id)) {
            throw new LPTrackerSDKException('Custom ID is required');
        }

        return true;
    }

    /**
     * @param int $leadId
     * @return $this
     */
    public function setLeadId($leadId)
    {
        $this->leadId = (int) $leadId;
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
