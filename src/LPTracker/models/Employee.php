<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class Employee extends Model
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
    protected $job;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \DateTimeImmutable
     */
    protected $lastLoginAt;

    /**
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    public function __construct(array $employeeData = [])
    {
        if (isset($employeeData['id'])) {
            $this->id = (int) $employeeData['id'];
        }
        if (isset($employeeData['name'])) {
            $this->name = $employeeData['name'];
        }
        if (isset($employeeData['job'])) {
            $this->job = $employeeData['job'];
        }
        if (isset($employeeData['type'])) {
            $this->type = $employeeData['type'];
        }
        if (isset($employeeData['last_login_at'])) {
            $this->lastLoginAt = (new \DateTimeImmutable())->setTimestamp($employeeData['last_login_at']);
        }
        if (isset($employeeData['created_at'])) {
            $this->createdAt = (new \DateTimeImmutable())->setTimestamp($employeeData['created_at']);
        }
    }

    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if ($this->id === null) {
            throw new LPTrackerSDKException('Employee ID is required');
        }

        if (empty($this->name)) {
            throw new LPTrackerSDKException('Employee name is required');
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
            'job' => $this->getJob(),
            'type' => $this->getType(),
            'last_login_at' => $this->getLastLoginAt() 
                ? $this->getLastLoginAt()->getTimestamp() 
                : null,
            'created_at' => $this->getCreatedAt() 
                ? $this->getCreatedAt()->getTimestamp() 
                : null,
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
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
