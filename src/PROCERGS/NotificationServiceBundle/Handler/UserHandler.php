<?php

namespace PROCERGS\NotificationServiceBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use PROCERGS\NotificationServiceBundle\Handler\UserHandlerInterface;
use PROCERGS\NotificationServiceBundle\Form\UserType;
use PROCERGS\NotificationServiceBundle\Model\UserInterface;
use PROCERGS\NotificationServiceBundle\Exception\InvalidFormException;

class UserHandler implements UserHandlerInterface
{

    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass,
                                FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    public function all($limit = 5, $offset = 0, $orderby = null)
    {
        return $this->repository->findBy(array(), $orderby, $limit, $offset);
    }

    public function get($id)
    {
        return $this->repository->find($id);
    }

    public function patch(UserInterface $user, array $parameters)
    {
        return $this->processForm($user, $parameters, 'PATCH');
    }

    public function post(array $parameters)
    {
        $user = $this->createNotification();

        return $this->processForm($user, $parameters, 'POST');
    }

    public function put(UserInterface $user, array $parameters)
    {
        return $this->processForm($user, $parameters, 'PUT');
    }

    /**
     * Processes the form.
     *
     * @param UserInterface $user
     * @param array                 $parameters
     * @param String                $method
     *
     * @return UserInterface
     *
     * @throws \PROCERGS\NotificationServiceBundle\Exception\InvalidFormException
     */
    private function processForm(UserInterface $user,
                                 array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new UserType(),
                $user, compact('method'));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $user = $form->getData();
            $this->om->persist($user);
            $this->om->flush($user);

            return $user;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createNotification()
    {
        return new $this->entityClass();
    }

}
