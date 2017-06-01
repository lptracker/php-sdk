<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

/**
 * Class Project
 * @package LPTracker\models
 */
class Project extends Model
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $page;

    /**
     * @var string
     */
    protected $domain;


    /**
     * Project constructor.
     *
     * @param array $projectData
     */
    public function __construct(array $projectData = [])
    {
        if (isset($projectData['id'])) {
            $this->id = $projectData['id'];
        }
        if (isset($projectData['name'])) {
            $this->name = $projectData['name'];
        }
        if (isset($projectData['page'])) {
            $this->page = $projectData['page'];
        }
        if (isset($projectData['domain'])) {
            $this->page = $projectData['domain'];
        }
    }


    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (empty($this->id)) {
            throw new LPTrackerSDKException('Project ID is required');
        }
        if (empty($this->name)) {
            throw new LPTrackerSDKException('Project name is required');
        }

        return true;
    }


    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'     => $this->getId(),
            'name'   => $this->getName(),
            'page'   => $this->getPage(),
            'domain' => $this->getDomain()
        ];
    }


    /**
     * @return int
     */
    public function getId()
    {
        return intval($this->id);
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
    public function getPage()
    {
        return $this->page;
    }


    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }
}