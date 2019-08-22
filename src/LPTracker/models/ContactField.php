<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class ContactField extends Model
{
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_DATE = 'date';
    const TYPE_NUMBER = 'number';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $contactId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $value;

    public function __construct(array $fieldData = [])
    {
        if (isset($fieldData['id'])) {
            $this->id = $fieldData['id'];
        }
        if (isset($fieldData['contact_id'])) {
            $this->contactId = $fieldData['contact_id'];
        }
        if (isset($fieldData['name'])) {
            $this->name = $fieldData['name'];
        }
        if (isset($fieldData['type'])) {
            $this->type = $fieldData['type'];
        }
        if (isset($fieldData['value'])) {
            $this->value = $fieldData['value'];
        }
    }

    /**
     * @return array
     */
    public static function getAllTypes()
    {
        return [
            self::TYPE_STRING,
            self::TYPE_TEXT,
            self::TYPE_DATE,
            self::TYPE_NUMBER,
        ];
    }

    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (empty($this->id)) {
            throw new LPTrackerSDKException('Field ID is required');
        }

        return true;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
        ];
        if (!empty($this->value)) {
            $result['value'] = $this->getValue();
        }
        return $result;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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

    /**
     * @return int
     */
    public function getContactId()
    {
        return $this->contactId;
    }
}
