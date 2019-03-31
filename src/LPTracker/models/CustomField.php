<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class CustomField extends Model
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $projectId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $showInLeads;

    /**
     * @var int
     */
    protected $showInDeals;

    /**
     * @var int
     */
    protected $isMultiSelect;

    /**
     * @var int
     */
    protected $isRequired;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $isMultiLine;

    public function __construct(array $data = [])
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['project_id'])) {
            $this->projectId = $data['project_id'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['type'])) {
            $this->type = $data['type'];
        }
        if (isset($data['show_in_leads'])) {
            $this->showInLeads = $data['show_in_leads'];
        }
        if (isset($data['show_in_deals'])) {
            $this->showInDeals = $data['show_in_deals'];
        }
        if (isset($data['is_multi_select'])) {
            $this->isMultiSelect = $data['is_multi_select'];
        }
        if (isset($data['is_required'])) {
            $this->isRequired = $data['is_required'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['is_multi_line'])) {
            $this->isMultiLine = $data['is_multi_line'];
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'project_id' => $this->projectId,
            'name' => $this->name,
            'type' => $this->type,
            'show_in_leads' => $this->showInLeads,
            'show_in_deals' => $this->showInDeals,
            'is_multi_select' => $this->isMultiSelect,
            'is_required' => $this->isRequired,
            'description' => $this->description,
            'is_multi_line' => $this->isMultiLine,
        ];
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
}
