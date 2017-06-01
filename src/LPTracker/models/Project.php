<?php

namespace LPTracker\models;

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
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (isset($options['id'])) {
            $this->id = $options['id'];
        }
        if (isset($options['name'])) {
            $this->name = $options['name'];
        }
        if (isset($options['page'])) {
            $this->page = $options['page'];
        }
        if (isset($options['domain'])) {
            $this->page = $options['domain'];
        }
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