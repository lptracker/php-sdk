<?php

namespace LPTracker\authentication;

class AccessToken
{
    /**
     * @var string
     */
    protected $value = '';

    /**
     * @var \DateTime|null
     */
    protected $expiresAt;

    /**
     * @param string $accessToken
     * @param int $expiresAt
     */
    public function __construct($accessToken, $expiresAt = 0)
    {
        $this->value = $accessToken;
        if ($expiresAt) {
            $this->setExpiresAtFromTimeStamp($expiresAt);
        } else {
            $this->resetExpiresAt();
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    public function resetExpiresAt()
    {
        $this->expiresAt = new \DateTime();
        $this->expiresAt->modify('+1 day');
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return bool|null
     */
    public function isExpired()
    {
        if ($this->getExpiresAt() instanceof \DateTime) {
            return $this->getExpiresAt()->getTimestamp() < time();
        }

        return null;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $timeStamp
     */
    protected function setExpiresAtFromTimeStamp($timeStamp)
    {
        $dt = new \DateTime();
        $dt->setTimestamp($timeStamp);
        $this->expiresAt = $dt;
    }
}
