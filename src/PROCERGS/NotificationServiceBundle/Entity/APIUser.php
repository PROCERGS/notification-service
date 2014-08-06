<?php

namespace PROCERGS\NotificationServiceBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * APIUser
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class APIUser implements UserInterface
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

    public function __construct($username, $password, array $roles = array(),
                                $enabled = true, $userNonExpired = true,
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
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
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

}
