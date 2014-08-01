<?php

namespace PROCERGS\NotificationServiceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use PROCERGS\NotificationServiceBundle\DependencyInjection\Security\Factory\WsseFactory;

class PROCERGSNotificationServiceBundle extends Bundle
{

    public function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new WsseFactory());
    }

}
