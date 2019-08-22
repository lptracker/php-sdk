<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class Stage extends Model
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $notify;

    /**
     * @var bool
     */
    protected $inLeads;

    /**
     * @var bool
     */
    protected $inDeals;

    public function __construct(array $stageData = [])
    {
        if (isset($stageData['id'])) {
            $this->id = (int) $stageData['id'];
        }
        if (isset($stageData['name'])) {
            $this->name = $stageData['name'];
        }
        if (isset($stageData['notify'])) {
            $this->notify = (bool) $stageData['notify'];
        }
        if (isset($stageData['in_leads'])) {
            $this->inLeads = (bool) $stageData['in_leads'];
        }
        if (isset($stageData['in_deals'])) {
            $this->inDeals = (bool) $stageData['in_deals'];
        }
    }

    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (empty($this->id)) {
            throw new LPTrackerSDKException('Stage ID is required');
        }

        if (empty($this->name)) {
            throw new LPTrackerSDKException('Stage name is required');
        }

        return true;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'notify' => $this->shouldNotify(),
            'in_leads' => $this->shouldShowInLeads(),
            'in_deals' => $this->shouldShowInDeals(),
        ];
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
     * @return bool
     */
    public function shouldNotify()
    {
        return $this->notify;
    }

    /**
     * @return bool
     */
    public function shouldShowInLeads()
    {
        return $this->inLeads;
    }

    /**
     * @return bool
     */
    public function shouldShowInDeals()
    {
        return $this->inDeals;
    }
}
