<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

/**
 * Class Lead
 * @package LPTracker\models
 */
class Lead extends Model
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $contactId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var integer
     */
    protected $funnelId;

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
     * @var View
     */
    protected $view;

    /**
     * @var integer
     */
    protected $ownerId;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var Payment[]
     */
    protected $payments = [];

    /**
     * @var Custom[]
     */
    protected $customs = [];

    /**
     * @var array
     */
    protected $options = [];


    /**
     * Lead constructor.
     *
     * @param array $leadData
     */
    public function __construct(array $leadData = [])
    {
        if ( ! empty($leadData['id'])) {
            $this->id = intval($leadData['id']);
        }
        if ( ! empty($leadData['contact_id'])) {
            $this->contactId = intval($leadData['contact_id']);
        }
        if ( ! empty($leadData['name'])) {
            $this->name = $leadData['name'];
        }
        if ( ! empty($leadData['funnel'])) {
            $this->funnelId = intval($leadData['funnel']);
        }
        if ( ! empty($leadData['source'])) {
            $this->source = $leadData['source'];
        }
        if ( ! empty($leadData['campaign'])) {
            $this->campaign = $leadData['campaign'];
        }
        if ( ! empty($leadData['keyword'])) {
            $this->keyword = $leadData['keyword'];
        }
        if ( ! empty($leadData['view'])) {
            $this->view = new View($leadData['view']);
        }
        if ( ! empty($leadData['owner'])) {
            $this->ownerId = intval($leadData['owner']);
        }
        if ( ! empty($leadData['payments']) && is_array($leadData['payments'])) {
            foreach ($leadData['payments'] as $paymentData) {
                $paymentModel = new Payment($paymentData);
                $this->addPayment($paymentModel);
            }
        }
        if ( ! empty($leadData['custom']) && is_array($leadData['custom'])) {
            foreach ($leadData['custom'] as $customData) {
                $customModel = new Custom($customData, $this->id);
                $this->addCustom($customModel);
            }
        }
        if ( ! empty($leadData['lead_date'])) {
            $date = \DateTime::createFromFormat('d.m.Y H:i', $leadData['lead_date']);
            $this->setCreatedAt($date);
        }
        if ( ! empty($leadData['deal_date'])) {
            $this->options['deal_date'] = $leadData['deal_date'];
        }
        if ( ! empty($leadData['params']) && is_array($leadData['params'])) {
            $this->options['params'] = $leadData['params'];
        }
    }


    /**
     * @param bool $toSave
     *
     * @return array
     */
    public function toArray($toSave = false)
    {
        $result = [
            'contact_id' => $this->contactId,
        ];
        if ( ! empty($this->id)) {
            $result['id'] = $this->getId();
        }
        if ( ! empty($this->name)) {
            $result['name'] = $this->getName();
        }
        if ( ! empty($this->funnelId)) {
            $result['funnel'] = $this->getFunnelId();
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
        if ( ! empty($this->ownerId)) {
            $result['owner'] = $this->getOwnerId();
        }
        if ( ! empty($this->createdAt)) {
            $result['lead_date'] = $this->getCreatedAt()->format('d.m.Y H:i');
        }
        if ( ! empty($this->view)) {
            $result['view'] = $this->view->toArray();
        }
        foreach ($this->getPayments() as $payment) {
            $result['payments'][] = $payment->toArray();
        }
        foreach ($this->getCustoms() as $custom) {
            if ($toSave) {
                $result['custom'][$custom->getId()] = $custom->getValue();
            } else {
                $result['custom'][] = $custom->toArray();
            }
        }
        foreach ($this->options as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }


    /**
     * @return bool
     * @throws LPTrackerSDKException
     */
    public function validate()
    {
        if (empty($this->contactId)) {
            throw new LPTrackerSDKException('Contact ID is required');
        }
        if (intval($this->contactId) <= 0) {
            throw new LPTrackerSDKException('Invalid contact id');
        }
        if ( ! empty($this->funnelId) && intval($this->funnelId) <= 0) {
            throw new LPTrackerSDKException('Invalid funnel ID');
        }
        if ( ! empty($this->ownerId) && intval($this->ownerId) < 0) {
            throw new LPTrackerSDKException('Invalid owner ID');
        }
        foreach ($this->getPayments() as $payment) {
            $payment->validate();
        }

        return true;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return intval($this->id);
    }


    /**
     * @return int
     */
    public function getContactId()
    {
        return intval($this->contactId);
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
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return int
     */
    public function getFunnelId()
    {
        return $this->funnelId;
    }


    /**
     * @param int $funnelId
     *
     * @return $this
     */
    public function setFunnelId($funnelId)
    {
        $this->funnelId = intval($funnelId);

        return $this;
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
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
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
     * @return View
     */
    public function getView()
    {
        return $this->view;
    }


    /**
     * @return Payment[]
     */
    public function getPayments()
    {
        return $this->payments;
    }


    /**
     * @param array $payments
     *
     * @return $this
     */
    public function setPayments(array $payments)
    {
        $this->payments = $payments;

        return $this;
    }


    /**
     * @param Payment $payment
     *
     * @return $this
     */
    public function addPayment(Payment $payment)
    {
        $this->payments[] = $payment;

        return $this;
    }


    /**
     * @return int
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }


    /**
     * @param int $ownerId
     *
     * @return $this
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = intval($ownerId);

        return $this;
    }


    /**
     * @return Custom[]
     */
    public function getCustoms()
    {
        return $this->customs;
    }


    /**
     * @param Custom[] $customs
     *
     * @return $this
     */
    public function setCustoms(array $customs)
    {
        $this->customs = $customs;

        return $this;
    }


    /**
     * @param Custom $custom
     *
     * @return $this
     */
    public function addCustom(Custom $custom)
    {
        $this->customs[] = $custom;

        return $this;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}