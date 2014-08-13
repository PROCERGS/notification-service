<?php

namespace PROCERGS\NotificationServiceBundle\Handler;

use PROCERGS\NotificationServiceBundle\Model\UserInterface;

interface UserHandlerInterface
{

    /**
     * Get an User given the id
     *
     * @api
     * @param mixed $id
     * @return UserInterface
     */
    public function get($id);

    /**
     * Get a list of Users.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post User, creates a new User
     *
     * @api
     *
     * @param array $parameters
     *
     * @return UserInterface
     */
    public function post(array $parameters);

    /**
     * Edit an User.
     *
     * @api
     *
     * @param UserInterface $notification
     * @param array                 $parameters
     *
     * @return UserInterface
     */
    public function put(UserInterface $notification, array $parameters);

    /**
     * Partially update an User.
     *
     * @api
     *
     * @param UserInterface $notification
     * @param array                 $parameters
     *
     * @return UserInterface
     */
    public function patch(UserInterface $notification, array $parameters);

}
