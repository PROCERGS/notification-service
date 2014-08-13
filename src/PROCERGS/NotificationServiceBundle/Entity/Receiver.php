<?php

namespace PROCERGS\NotificationServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use PROCERGS\NotificationServiceBundle\Model\ReceiverInterface;

/**
 * Receiver encapsulates the receiver of the notification.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Receiver implements ReceiverInterface
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
     * @ORM\OneToMany(targetEntity="ReceiverOption", mappedBy="receiver")
     * @var ArrayCollection
     */
    private $options;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="receiver")
     * @var ArrayCollection
     */
    private $notifications;

    public function __construct()
    {
        $this->options = new ArrayCollection();
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
     * @return Receiver
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

}
