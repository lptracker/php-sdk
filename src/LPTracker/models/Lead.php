<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

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
     * @var Contact
     */
    protected $contact;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var integer
     */
    protected $funnelId;

    /**
     * @var integer
     */
    protected $viewId;

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

    public function __construct(array $leadData = [])
    {
        if (!empty($leadData['id'])) {
            $this->id = (int) $leadData['id'];
        }
        if (!empty($leadData['contact_id'])) {
            $this->contactId = (int) $leadData['contact_id'];
        }
        if (!empty($leadData['contact'])) {
            $this->contact = new Contact($leadData['contact']);
        }
        if (!empty($leadData['name'])) {
            $this->name = $leadData['name'];
        }
        if (!empty($leadData['funnel'])) {
            $this->funnelId = (int) $leadData['funnel'];
        }
        if (!empty($leadData['stage_id'])) {
            $this->funnelId = (int) $leadData['stage_id'];
        }
        if (!empty($leadData['view_id'])) {
            $this->viewId = (int) $leadData['view_id'];
        }
        if (!empty($leadData['view'])) {
            $this->view = new View($leadData['view']);
        }
        if (isset($leadData['owner'])) {
            $this->ownerId = (int) $leadData['owner'];
        }
        if (isset($leadData['owner_id'])) {
            $this->ownerId = (int) $leadData['owner_id'];
        }
        if (!empty($leadData['payments']) && is_array($leadData['payments'])) {
            foreach ($leadData['payments'] as $paymentData) {
                $paymentModel = new Payment($paymentData);
                $this->addPayment($paymentModel);
            }
        }
        if (!empty($leadData['custom']) && is_array($leadData['custom'])) {
            foreach ($leadData['custom'] as $customData) {
                $customModel = new Custom($customData, $this->id);
                $this->addCustom($customModel);
            }
        }
        if (!empty($leadData['lead_date'])) {
            $date = \DateTime::createFromFormat('d.m.Y H:i', $leadData['lead_date']);
            $this->setCreatedAt($date);
        }
        if (!empty($leadData['created_at'])) {
            $date = \DateTime::createFromFormat('d.m.Y H:i', $leadData['created_at']);
            $this->setCreatedAt($date);
        }
        if (!empty($leadData['deal_date'])) {
            $this->options['deal_date'] = $leadData['deal_date'];
        }
        if (!empty($leadData['params']) && is_array($leadData['params'])) {
            $this->options['params'] = $leadData['params'];
        }
    }

    /**
     * @param bool $toSave
     * @return array
     */
    public function toArray($toSave = false)
    {
        $result = [];
        if (!empty($this->contactId)) {
            $result['contact_id'] = $this->contactId;
        }
        if (!empty($this->contact)) {
            if ($toSave) {
                if (!empty($this->contact->getId())) {
                    $result['contact_id'] = $this->contact->getId();
                } else {
                    $result['contact'] = $this->contact->toArray();
                }
            } else {
                $result['contact'] = $this->contact->toArray();
            }
        }
        if (!empty($this->id)) {
            $result['id'] = $this->getId();
        }
        if (!empty($this->name)) {
            $result['name'] = $this->getName();
        }
        if (!empty($this->funnelId)) {
            $result['funnel'] = $this->getFunnelId();
            $result['stage_id'] = $this->getFunnelId();
        }
        if (!empty($this->ownerId)) {
            $result['owner'] = $this->getOwnerId();
            $result['owner_id'] = $this->getOwnerId();
        }
        if (!empty($this->createdAt)) {
            $result['lead_date'] = $this->getCreatedAt()->format('d.m.Y H:i');
        }
        if (!empty($this->viewId)) {
            $result['view_id'] = $this->viewId;
        }
        if (!empty($this->view)) {
            if ($toSave) {
                if (!empty($this->view->getId())) {
                    $result['view_id'] = $this->view->getId();
                } else {
                    $result['view'] = $this->view->toArray();
                }
            } else {
                $result['view'] = $this->view->toArray();
            }
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
        if (empty($this->contactId) && empty($this->contact)) {
            throw new LPTrackerSDKException('Contact ID or contact is required');
        }

        if ((int) $this->contactId < 0) {
            throw new LPTrackerSDKException('Invalid contact ID');
        }

        if (!empty($this->funnelId) && (int) $this->funnelId <= 0) {
            throw new LPTrackerSDKException('Invalid funnel ID');
        }

        if (!empty($this->ownerId) && (int) $this->ownerId < 0) {
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
        return (int) $this->id;
    }

    /**
     * @return int
     */
    public function getContactId()
    {
        return (int) $this->contactId;
    }

    /**
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
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
     * @return int
     */
    public function getFunnelId()
    {
        return $this->funnelId;
    }

    /**
     * @param int $funnelId
     * @return $this
     */
    public function setFunnelId($funnelId)
    {
        $this->funnelId = (int) $funnelId;
        return $this;
    }

    /**
     * @return int
     */
    public function getViewId()
    {
        return (int) $this->viewId;
    }

    /**
     * @return View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param View $view
     * @return $this
     */
    public function setView(View $view)
    {
        $this->view = $view;
        return $this;
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
     * @return $this
     */
    public function setPayments(array $payments)
    {
        $this->payments = $payments;
        return $this;
    }

    /**
     * @param Payment $payment
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
     * @return $this
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = (int) $ownerId;
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
     * @return $this
     */
    public function setCustoms(array $customs)
    {
        $this->customs = $customs;
        return $this;
    }

    /**
     * @param Custom $custom
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
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
