<?php

namespace PROCERGS\NotificationServiceBundle\Tests\Fixtures\Entity;

use PROCERGS\NotificationServiceBundle\Entity\Notification;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadNotificationData implements FixtureInterface
{

    static public $notifications = array();

    public function load(ObjectManager $manager)
    {
        $notification = new Notification();
        $notification->setTitle('title')
                ->setShortText('shortText')
                ->setText('text')
                ->setCreatedAt(new \DateTime());

        $manager->persist($notification);
        $manager->flush();

        self::$notifications[] = $notification;
    }

}
