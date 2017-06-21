<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

/**
 * Class Contact
 * @package LPTracker\models
 */
class Contact extends Model
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $projectId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $profession;

    /**
     * @var string
     */
    protected $site;

    /**
     * @var ContactDetail[]
     */
    protected $details = [];

    /**
     * @var ContactField[]
     */
    protected $fields = [];


    /**
     * Contact constructor.
     *
     * @param array $contactData
     */
    public function __construct(array $contactData = [])
    {
        if ( ! empty($contactData['id'])) {
            $this->id = intval($contactData['id']);
        }
        if ( ! empty($contactData['project_id'])) {
            $this->projectId = intval($contactData['project_id']);
        }
        if ( ! empty($contactData['name'])) {
            $this->name = $contactData['name'];
        }
        if ( ! empty($contactData['profession'])) {
            $this->profession = $contactData['profession'];
        }
        if ( ! empty($contactData['site'])) {
            $this->site = $contactData['site'];
        }
        if ( ! empty($contactData['details']) && is_array($contactData['details'])) {
            foreach ($contactData['details'] as $detail) {
                $detailModel = new ContactDetail($detail);
                $this->addDetail($detailModel);
            }
        }
        if ( ! empty($contactData['fields']) && is_array($contactData['fields'])) {
            foreach ($contactData['fields'] as $fieldData) {
                $fieldData['contact_id'] = $this->id;
                $fieldModel = new ContactField($fieldData);
                $this->addField($fieldModel);
            }
        }
    }


    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (!empty($this->id) && empty($this->projectId)) {
            throw new LPTrackerSDKException('Project ID is required');
        }
        if (empty($this->details)) {
            throw new LPTrackerSDKException('The contact does not have valid detail');
        }
        foreach ($this->details as $detail) {
            if ( ! $detail->validate()) {
                throw new LPTrackerSDKException('The contact does not have valid detail');
            }
        }

        return true;
    }


    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            'project_id' => $this->getProjectId(),
            'details'    => [],
        ];
        if ( ! empty($this->id)) {
            $result['id'] = $this->getId();
        }
        if ( ! empty($this->name)) {
            $result['name'] = $this->getName();
        }
        if ( ! empty($this->profession)) {
            $result['profession'] = $this->getProfession();
        }
        if ( ! empty($this->site)) {
            $result['site'] = $this->getSite();
        }
        foreach ($this->getDetails() as $detail) {
            $result['details'][] = $detail->toArray();
        }
        foreach ($this->getFields() as $field) {
            $result['fields'][$field->getId()] = $field->getValue();
        }

        return $result;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return intval($this->id);
    }


    /**
     * @return int
     */
    public function getProjectId()
    {
        return intval($this->projectId);
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }


    /**
     * @param string $profession
     *
     * @return $this
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }


    /**
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }


    /**
     * @param string $site
     *
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }


    /**
     * @return ContactDetail[]
     */
    public function getDetails()
    {
        return $this->details;
    }


    /**
     * @param ContactDetail[] $details
     *
     * @return $this
     */
    public function setDetails(array $details)
    {
        /** @var ContactDetail $detail */
        foreach ($details as $detail) {
            $detail->validate();
        }

        $this->details = $details;

        return $this;
    }


    /**
     * @param ContactDetail $detail
     *
     * @return $this
     */
    public function addDetail(ContactDetail $detail)
    {
        $detail->validate();
        $this->details[] = $detail;

        return $this;
    }


    /**
     * @return ContactField[]
     */
    public function getFields()
    {
        return $this->fields;
    }


    /**
     * @param ContactField[] $fields
     *
     * @return $this
     */
    public function setFields(array $fields)
    {
        /** @var ContactDetail $detail */
        foreach ($fields as $field) {
            $field->validate();
        }

        $this->fields = $fields;

        return $this;
    }


    /**
     * @param ContactField $field
     *
     * @return $this
     */
    public function addField(ContactField $field)
    {
        $field->validate();
        $this->fields[] = $field;

        return $this;
    }
}