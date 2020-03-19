<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class Category extends Model
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
     * @var string
     */
    protected $color;

    public function __construct(array $categoryData = [])
    {
        if (isset($categoryData['id'])) {
            $this->id = (int) $categoryData['id'];
        }
        if (!empty($categoryData['category'])) {
            $this->name = $categoryData['category'];
        }
        if (!empty($categoryData['purpose'])) {
            $this->color = $categoryData['purpose'];
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];
        if (!empty($this->id)) {
            $result['id'] = $this->getId();
        }
        if (!empty($this->name)) {
            $result['category'] = $this->getName();
        }
        if (!empty($this->color)) {
            $result['purpose'] = $this->getColor();
        }
        return $result;
    }

    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (empty($this->name)) {
            throw new LPTrackerSDKException('Category name is required');
        }

        return true;
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
     * @param string $name
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
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }
}
