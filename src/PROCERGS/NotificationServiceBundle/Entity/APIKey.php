<?php

namespace PROCERGS\NotificationServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * APIKey
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class APIKey
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="APIUser", inversedBy="apiKeys")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var APIUser
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $apiKey;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $issuedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setUser(APIUser $user)
    {
        $this->user = $user;
        return $this;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;
        return $this;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

}
