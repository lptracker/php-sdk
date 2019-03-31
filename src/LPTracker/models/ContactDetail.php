<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class ContactDetail extends Model
{
    const TYPE_PHONE = 'phone';
    const TYPE_EMAIL = 'email';
    const TYPE_SKYPE = 'skype';
    const TYPE_ICQ = 'icq';
    const TYPE_FACEBOOK = 'facebook';
    const TYPE_VK = 'vk';

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
    protected $type;

    /**
     * @var string
     */
    protected $data;

    public function __construct(array $detailData = [])
    {
        if (isset($detailData['id'])) {
            $this->id = $detailData['id'];
        }
        if (isset($detailData['contact_id'])) {
            $this->contactId = $detailData['contact_id'];
        }
        if (isset($detailData['type'])) {
            $this->type = $detailData['type'];
        }
        if (isset($detailData['data'])) {
            $this->data = $detailData['data'];
        }
    }

    /**
     * @return array
     */
    public static function getAllTypes()
    {
        return [
            self::TYPE_PHONE,
            self::TYPE_EMAIL,
            self::TYPE_SKYPE,
            self::TYPE_ICQ,
            self::TYPE_FACEBOOK,
            self::TYPE_VK,
        ];
    }

    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (empty($this->type)) {
            throw new LPTrackerSDKException('Detail type can not be null: ' . $this->__toString());
        }

        if (!in_array($this->type, self::getAllTypes(), true)) {
            throw new LPTrackerSDKException('Detail type not in (' . implode(',', self::getAllTypes()) . '): ' . $this->__toString());
        }

        if (empty($this->data)) {
            throw new LPTrackerSDKException('Detail data can not be null: ' . $this->__toString());
        }

        return true;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            'type' => $this->type,
            'data' => $this->data,
        ];

        if (!empty($this->id)) {
            $result['id'] = $this->getId();
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
     * @return int
     */
    public function getContactId()
    {
        return $this->contactId;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
