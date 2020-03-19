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
     * @var bool
     */
    protected $showInLeads;

    /**
     * @var bool
     */
    protected $showInDeals;

    /**
     * @var bool
     */
    protected $isMultiSelect;

    /**
     * @var bool
     */
    protected $isRequired;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    protected $isMultiLine;

    /**
     * @var Category[]
     */
    protected $categories = [];

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
            $this->showInLeads = (bool) $data['show_in_leads'];
        }
        if (isset($data['show_in_deals'])) {
            $this->showInDeals = (bool) $data['show_in_deals'];
        }
        if (isset($data['is_multi_select'])) {
            $this->isMultiSelect = (bool) $data['is_multi_select'];
        }
        if (isset($data['is_required'])) {
            $this->isRequired = (bool) $data['is_required'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['is_multi_line'])) {
            $this->isMultiLine = (bool) $data['is_multi_line'];
        }
        if (!empty($data['categories']) && is_array($data['categories'])) {
            foreach ($data['categories'] as $categoryData) {
                $categoryModel = new Category($categoryData);
                $this->addCategory($categoryModel);
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
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
        foreach ($this->getCategories() as $category) {
            $result['categories'][] = $category->toArray();
        }
        return $result;
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CustomField
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     * @return CustomField
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
        return $this;
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
     * @return CustomField
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
     * @return CustomField
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowInLeads()
    {
        return $this->showInLeads;
    }

    /**
     * @param bool $showInLeads
     * @return CustomField
     */
    public function setShowInLeads($showInLeads)
    {
        $this->showInLeads = (bool) $showInLeads;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowInDeals()
    {
        return $this->showInDeals;
    }

    /**
     * @param bool $showInDeals
     * @return CustomField
     */
    public function setShowInDeals($showInDeals)
    {
        $this->showInDeals = (bool) $showInDeals;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiSelect()
    {
        return $this->isMultiSelect;
    }

    /**
     * @param bool $isMultiSelect
     * @return CustomField
     */
    public function setIsMultiSelect($isMultiSelect)
    {
        $this->isMultiSelect = (bool) $isMultiSelect;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->isRequired;
    }

    /**
     * @param bool $isRequired
     * @return CustomField
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = (bool) $isRequired;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return CustomField
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiLine()
    {
        return $this->isMultiLine;
    }

    /**
     * @param bool $isMultiLine
     * @return CustomField
     */
    public function setIsMultiLine($isMultiLine)
    {
        $this->isMultiLine = (bool) $isMultiLine;
        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     * @return $this
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
        return $this;
    }
}
