<?php

namespace PROCERGS\NotificationServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use PROCERGS\NotificationServiceBundle\Model\SenderInterface;

/**
 * Sender encapsulates the sender/application/service of the notification.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Sender implements SenderInterface
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="externalId", type="string", length=255)
     */
    private $externalId;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="application")
     * @var ArrayCollection
     */
    private $categories;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="sender")
     * @var ArrayCollection
     */
    private $notifications;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="senders")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set externalId
     *
     * @param string $externalId
     * @return Sender
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function getNotifications()
    {
        return $this->notifications;
    }

    public function setCategories(ArrayCollection $categories)
    {
        $this->categories = $categories;
        return $this;
    }

    public function setNotifications(ArrayCollection $notifications)
    {
        $this->notifications = $notifications;
        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner(User $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    public function __toString()
    {
        return $this->getExternalId();
    }

}
