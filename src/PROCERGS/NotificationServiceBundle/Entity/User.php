<?php

namespace PROCERGS\NotificationServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use PROCERGS\NotificationServiceBundle\Model\UserInterface;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User implements UserInterface
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
     * @ORM\Column(type="string", length=255)
     */
    private $username;
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;
    private $accountNonExpired;
    private $credentialsNonExpired;
    private $accountNonLocked;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="APIKey", mappedBy="user")
     * @var ArrayCollection
     */
    protected $apiKeys;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Sender", mappedBy="owner")
     * @var ArrayCollection
     */
    private $senders;

    public function __construct($username, $password = null,
                                array $roles = array(), $enabled = true,
                                $userNonExpired = true,
                                $credentialsNonExpired = true,
                                $userNonLocked = true)
    {
        if (empty($username)) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }

        $this->username = $username;
        $this->password = $password;
        $this->enabled = $enabled;
        $this->accountNonExpired = $userNonExpired;
        $this->credentialsNonExpired = $credentialsNonExpired;
        $this->accountNonLocked = $userNonLocked;
        $this->roles = $roles;

        $this->apiKeys = new ArrayCollection();
        $this->senders = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setEnabled($enabled = true)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return $this->accountNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return $this->accountNonLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return $this->credentialsNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {

    }

    public function getApiKeys()
    {
        return $this->apiKeys;
    }

    public function setApiKeys(ArrayCollection $apiKeys)
    {
        $this->apiKeys = $apiKeys;
        return $this;
    }

    public function getSenders()
    {
        return $this->senders;
    }

    public function setSenders(ArrayCollection $senders)
    {
        $this->senders = $senders;
        return $this;
    }

    public function __toString()
    {
        return $this->getUsername();
    }

}
