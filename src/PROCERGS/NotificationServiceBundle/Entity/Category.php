<?php

namespace PROCERGS\NotificationServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use PROCERGS\NotificationServiceBundle\Model\CategoryInterface;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category implements CategoryInterface
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="default_icon", type="string", length=255)
     */
    private $defaultIcon;

    /**
     * @var string
     *
     * @ORM\Column(name="default_title", type="string", length=255)
     */
    private $defaultTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="default_short_text", type="string", length=255)
     */
    private $defaultShortText;

    /**
     * @var string
     *
     * @ORM\Column(name="mail_template", type="text")
     */
    private $mailTemplate;

    /**
     * @var string
     *
     * @ORM\Column(name="mail_sender_address", type="string", length=255)
     */
    private $mailSenderAddress;

    /**
     * @var boolean
     *
     * @ORM\Column(name="emailable", type="boolean")
     */
    private $emailable;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Sender", inversedBy="categories")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $application;

    /**
     * @var string
     *
     * @ORM\Column(name="html_template", type="text")
     */
    private $htmlTemplate;

    /**
     * @var string
     *
     * @ORM\Column(name="markdown_template", type="text")
     */
    private $markdownTemplate;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="category")
     */
    protected $notifications;

    public function __construct()
    {
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
     * Set defaultIcon
     *
     * @param string $defaultIcon
     * @return Category
     */
    public function setDefaultIcon($defaultIcon)
    {
        $this->defaultIcon = $defaultIcon;

        return $this;
    }

    /**
     * Get defaultIcon
     *
     * @return string
     */
    public function getDefaultIcon()
    {
        return $this->defaultIcon;
    }

    /**
     * Set defaultTitle
     *
     * @param string $defaultTitle
     * @return Category
     */
    public function setDefaultTitle($defaultTitle)
    {
        $this->defaultTitle = $defaultTitle;

        return $this;
    }

    /**
     * Get defaultTitle
     *
     * @return string
     */
    public function getDefaultTitle()
    {
        return $this->defaultTitle;
    }

    /**
     * Set defaultShortText
     *
     * @param string $defaultShortText
     * @return Category
     */
    public function setDefaultShortText($defaultShortText)
    {
        $this->defaultShortText = $defaultShortText;

        return $this;
    }

    /**
     * Get defaultShortText
     *
     * @return string
     */
    public function getDefaultShortText()
    {
        return $this->defaultShortText;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set mailTemplate
     *
     * @param string $mailTemplate
     * @return Category
     */
    public function setMailTemplate($mailTemplate)
    {
        $this->mailTemplate = $mailTemplate;

        return $this;
    }

    /**
     * Get mailTemplate
     *
     * @return string
     */
    public function getMailTemplate()
    {
        return $this->mailTemplate;
    }

    /**
     * Set mailSenderAddress
     *
     * @param string $mailSenderAddress
     * @return Category
     */
    public function setMailSenderAddress($mailSenderAddress)
    {
        $this->mailSenderAddress = $mailSenderAddress;

        return $this;
    }

    /**
     * Get mailSenderAddress
     *
     * @return string
     */
    public function getMailSenderAddress()
    {
        return $this->mailSenderAddress;
    }

    /**
     * Set emailable
     *
     * @param boolean $emailable
     * @return Category
     */
    public function setEmailable($emailable)
    {
        $this->emailable = $emailable;

        return $this;
    }

    /**
     * Get emailable
     *
     * @return boolean
     */
    public function getEmailable()
    {
        return $this->emailable;
    }

    /**
     * Set application
     *
     * @param string $application
     * @return Category
     */
    public function setApplication($application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return string
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set htmlTemplate
     *
     * @param string $htmlTemplate
     * @return Category
     */
    public function setHtmlTemplate($htmlTemplate)
    {
        $this->htmlTemplate = $htmlTemplate;

        return $this;
    }

    /**
     * Get htmlTemplate
     *
     * @return string
     */
    public function getHtmlTemplate()
    {
        return $this->htmlTemplate;
    }

    /**
     * Set markdownTemplate
     *
     * @param string $markdownTemplate
     * @return Category
     */
    public function setMarkdownTemplate($markdownTemplate)
    {
        $this->markdownTemplate = $markdownTemplate;

        return $this;
    }

    /**
     * Get markdownTemplate
     *
     * @return string
     */
    public function getMarkdownTemplate()
    {
        return $this->markdownTemplate;
    }

    public function getNotifications()
    {
        return $this->notifications;
    }

    public function setNotifications(ArrayCollection $notifications)
    {
        $this->notifications = $notifications;
        return $this;
    }

}
