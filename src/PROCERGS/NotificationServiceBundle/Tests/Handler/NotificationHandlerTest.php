<?php

namespace PROCERGS\NotificationServiceBundle\Tests\Handler;

use PROCERGS\NotificationServiceBundle\Entity\Notification;
use PROCERGS\NotificationServiceBundle\Handler\NotificationHandler;

class NotificationHandlerTest extends \PHPUnit_Framework_TestCase
{

    const NOTIFICATION_CLASS = 'PROCERGS\NotificationServiceBundle\Tests\Handler\DummyNotification';

    /** @var NotificationHandler */
    protected $notificationHandler;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function testGet()
    {
        $id = 1;
        $notification = $this->getNotification();
        $this->repository->expects($this->once())
                ->method('find')
                ->with($this->equalTo($id))
                ->will($this->returnValue($notification));

        $this->notificationHandler = $this->createNotificationHandler($this->om,
                static::NOTIFICATION_CLASS, $this->formFactory);

        $this->notificationHandler->get($id);
    }

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }

        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
                ->method('getRepository')
                ->with($this->equalTo(static::NOTIFICATION_CLASS))
                ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
                ->method('getClassMetadata')
                ->with($this->equalTo(static::NOTIFICATION_CLASS))
                ->will($this->returnValue($class));
        $class->expects($this->any())
                ->method('getName')
                ->will($this->returnValue(static::NOTIFICATION_CLASS));
    }

    protected function getNotification()
    {
        $notificationClass = static::NOTIFICATION_CLASS;

        return new $notificationClass();
    }

    protected function createNotificationHandler($objectManager,
                                                 $notificationClass,
                                                 $formFactory)
    {
        return new NotificationHandler($objectManager, $notificationClass,
                $formFactory);
    }

}

class DummyNotification extends Notification
{

}
