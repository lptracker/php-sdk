<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

/**
 * Class View
 * @package LPTracker\models
 */
class View extends Model
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
    protected $source;

    /**
     * @var string
     */
    protected $campaign;

    /**
     * @var string
     */
    protected $keyword;

    /**
     * @var string
     */
    protected $seoSystem;


    /**
     * View constructor.
     *
     * @param array $viewData
     */
    public function __construct(array $viewData = [])
    {
        if (isset($viewData['id'])) {
            $this->id = $viewData['id'];
        }
        if (isset($viewData['project_id'])) {
            $this->projectId = $viewData['project_id'];
        }
        if (isset($viewData['source'])) {
            $this->source = $viewData['source'];
        }
        if (isset($viewData['campaign'])) {
            $this->campaign = $viewData['campaign'];
        }
        if (isset($viewData['keyword'])) {
            $this->keyword = $viewData['keyword'];
        }
        if (isset($viewData['seo_system'])) {
            $this->seoSystem = $viewData['seo_system'];
        }
    }


    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (empty($this->projectId)) {
            throw new LPTrackerSDKException('Project ID is required');
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
        ];

        if ( ! empty($this->id)) {
            $result['id'] = $this->getId();
        }
        if ( ! empty($this->source)) {
            $result['source'] = $this->getSource();
        }
        if ( ! empty($this->campaign)) {
            $result['campaign'] = $this->getCampaign();
        }
        if ( ! empty($this->keyword)) {
            $result['keyword'] = $this->getKeyword();
        }
        if ( ! empty($this->seoSystem)) {
            $result['seo_system'] = $this->getSeoSystem();
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
    public function getProjectId()
    {
        return $this->projectId;
    }


    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }


    /**
     * @param string $source
     *
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }


    /**
     * @return string
     */
    public function getCampaign()
    {
        return $this->campaign;
    }


    /**
     * @param string $campaign
     *
     * @return $this
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }


    /**
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }


    /**
     * @param string $keyword
     *
     * @return $this
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }


    /**
     * @return string
     */
    public function getSeoSystem()
    {
        return $this->seoSystem;
    }


    /**
     * @param string $seoSystem
     *
     * @return $this
     */
    public function setSeoSystem($seoSystem)
    {
        $this->seoSystem = $seoSystem;

        return $this;
    }
}