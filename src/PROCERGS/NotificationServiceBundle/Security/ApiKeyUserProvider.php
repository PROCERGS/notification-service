<?php

namespace PROCERGS\NotificationServiceBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManager;
use PROCERGS\NotificationServiceBundle\Entity\APIKey;

class ApiKeyUserProvider implements UserProviderInterface
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUsernameForApiKey($apiKey)
    {
        $apiKeys = $this->em->getRepository('PROCERGSNotificationServiceBundle:APIKey');
        $apiKeyEntity = $apiKeys->findOneBy(array('apiKey' => $apiKey, 'enabled' => true));
        if (!$apiKeyEntity instanceof APIKey) {
            return null;
        }
        $user = $apiKeyEntity->getUser();

        return $user->getUsername();
    }

    public function loadUserByUsername($username)
    {
        $apiUsers = $this->em->getRepository('PROCERGSNotificationServiceBundle:APIUser');
        return $apiUsers->findOneBy(compact('username'));
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }

}
