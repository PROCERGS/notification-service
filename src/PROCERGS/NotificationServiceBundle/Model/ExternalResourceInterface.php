<?php

namespace PROCERGS\NotificationServiceBundle\Model;

interface ExternalResourceInterface
{

    /**
     * Gets the resources's internal/local id
     */
    public function getId();

    /**
     * Gets the resources's external/remote id. It can be the user's username on
     * Citizen Login, twitter handle or whatever the unique id the integrated
     * service uses to identify it's users.
     */
    public function getExternalId();
}
