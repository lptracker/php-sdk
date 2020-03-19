<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class View extends Model
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    protected $ymClientId;

    /**
     * @var string
     */
    protected $gaClientId;

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
     * @var string
     */
    protected $page;

    /**
     * @var string
     */
    protected $referer;

    /**
     * @var Visitor|null
     */
    protected $visitor;

    /**
     * @var Visitor|null
     */
    protected $realVisitor;

    public function __construct(array $viewData = [])
    {
        if (isset($viewData['id'])) {
            $this->id = (int) $viewData['id'];
        }
        if (isset($viewData['project_id'])) {
            $this->projectId = (int) $viewData['project_id'];
        }
        if (isset($viewData['uuid'])) {
            $this->uuid = $viewData['uuid'];
        }
        if (isset($viewData['ym_client_id'])) {
            $this->ymClientId = $viewData['ym_client_id'];
        }
        if (isset($viewData['ga_client_id'])) {
            $this->gaClientId = $viewData['ga_client_id'];
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
        if (isset($viewData['page'])) {
            $this->page = $viewData['page'];
        }
        if (isset($viewData['referer'])) {
            $this->referer = $viewData['referer'];
        }
        if (isset($viewData['visitor'])) {
            $this->visitor = new Visitor($viewData['visitor']);
        }
        if (isset($viewData['real_visitor'])) {
            $this->realVisitor = new Visitor($viewData['real_visitor']);
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

        if ($this->visitor !== null) {
            $this->visitor->validate();
        }
        if ($this->realVisitor !== null) {
            $this->realVisitor->validate();
        }
        return true;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            'id' => $this->getId(),
            'project_id' => $this->getProjectId(),
            'uuid' => $this->getUuid(),
            'ym_client_id' => $this->getYmClientId(),
            'ga_client_id' => $this->getGaClientId(),
            'source' => $this->getSource(),
            'campaign' => $this->getCampaign(),
            'keyword' => $this->getKeyword(),
            'seo_system' => $this->getSeoSystem(),
            'page' => $this->getPage(),
            'referer' => $this->getReferer(),
            'visitor' => $this->visitor !== null
                ? $this->visitor->toArray()
                : null,
            'real_visitor' => $this->realVisitor !== null
                ? $this->realVisitor->toArray()
                : null,
        ];
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
     * @return $this
     */
    public function setSeoSystem($seoSystem)
    {
        $this->seoSystem = $seoSystem;
        return $this;
    }

    /**
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $page
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param string $referer
     * @return $this
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
        return $this;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getYmClientId()
    {
        return $this->ymClientId;
    }

    /**
     * @return string
     */
    public function getGaClientId()
    {
        return $this->gaClientId;
    }

    /**
     * @return Visitor|null
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * @return Visitor|null
     */
    public function getRealVisitor()
    {
        return $this->realVisitor;
    }
}
