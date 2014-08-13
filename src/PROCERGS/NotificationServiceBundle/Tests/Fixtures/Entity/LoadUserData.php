<?php

namespace PROCERGS\NotificationServiceBundle\Tests\Fixtures\Entity;

use PROCERGS\NotificationServiceBundle\Entity\User;
use PROCERGS\NotificationServiceBundle\Entity\APIKey;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData implements FixtureInterface
{

    static public $users = array();
    static public $keys = array();

    public function load(ObjectManager $manager)
    {
        $user = new User('admin');
        $user->setEnabled();
        $user->setRoles(array('ROLE_ADMIN'));

        $manager->persist($user);
        $manager->flush();

        $key = new APIKey();
        $key->setApiKey('123')
                ->setUser($user)
                ->setEnabled(true)
                ->setIssuedAt(new \DateTime());
        $manager->persist($key);
        $manager->flush();

        self::$users[] = $user;
    }

}
