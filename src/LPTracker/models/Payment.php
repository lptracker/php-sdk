<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class Payment extends Model
{
    /**
     * @var string
     */
    protected $category;

    /**
     * @var string
     */
    protected $purpose;

    /**
     * @var float
     */
    protected $sum;

    public function __construct(array $paymentData = [])
    {
        if (!empty($paymentData['category'])) {
            $this->category = $paymentData['category'];
        }
        if (!empty($paymentData['purpose'])) {
            $this->purpose = $paymentData['purpose'];
        }
        if (!empty($paymentData['sum'])) {
            $this->sum = $paymentData['sum'];
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];
        if (!empty($this->category)) {
            $result['category'] = $this->getCategory();
        }
        if (!empty($this->purpose)) {
            $result['purpose'] = $this->getPurpose();
        }
        if (!empty($this->sum)) {
            $result['sum'] = $this->getSum();
        }
        return $result;
    }

    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (empty($this->category)) {
            throw new LPTrackerSDKException('Payment category is required');
        }

        if (empty($this->purpose)) {
            throw new LPTrackerSDKException('Payment purpose is required');
        }

        if (!isset($this->sum)) {
            throw new LPTrackerSDKException('Payment sum is required');
        }

        return true;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     * @return $this
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
        return $this;
    }

    /**
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @param float $sum
     * @return $this
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }
}
