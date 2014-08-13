<?php

namespace PROCERGS\NotificationServiceBundle\Handler;

use PROCERGS\NotificationServiceBundle\Model\SenderInterface;

interface SenderHandlerInterface
{

    /**
     * Get an Sender given the id
     *
     * @api
     * @param mixed $id
     * @param mixed $userId
     * @return SenderInterface
     */
    public function get($id, $userId = null);

    /**
     * Get a list of Senders.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0, $userId = null, $orderby = null);

    /**
     * Post Sender, creates a new Sender
     *
     * @api
     *
     * @param array $parameters
     *
     * @return SenderInterface
     */
    public function post(array $parameters);

    /**
     * Edit an Sender.
     *
     * @api
     *
     * @param SenderInterface $notification
     * @param array                 $parameters
     *
     * @return SenderInterface
     */
    public function put(SenderInterface $notification, array $parameters);

    /**
     * Partially update an Sender.
     *
     * @api
     *
     * @param SenderInterface $notification
     * @param array                 $parameters
     *
     * @return SenderInterface
     */
    public function patch(SenderInterface $notification, array $parameters);

}
