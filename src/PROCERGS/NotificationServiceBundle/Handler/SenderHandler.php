<?php

namespace PROCERGS\NotificationServiceBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use PROCERGS\NotificationServiceBundle\Handler\SenderHandlerInterface;
use PROCERGS\NotificationServiceBundle\Form\SenderType;
use PROCERGS\NotificationServiceBundle\Model\SenderInterface;
use PROCERGS\NotificationServiceBundle\Exception\InvalidFormException;

class SenderHandler implements SenderHandlerInterface
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

    public function all($limit = 5, $offset = 0, $userId = null, $orderby = null)
    {
        $params = array();
        if ($userId > 0) {
            $params['owner.id'] = $userId;
        }
        return $this->repository->findBy($params, $orderby, $limit, $offset);
    }

    public function get($id, $userId = null)
    {
        $params = compact('id');
        if ($userId > 0) {
            $params['owner'] = $userId;
        }
        return $this->repository->findOneBy($params);
    }

    public function patch(SenderInterface $sender, array $parameters)
    {
        return $this->processForm($sender, $parameters, 'PATCH');
    }

    public function post(array $parameters)
    {
        $sender = $this->createNotification();

        return $this->processForm($sender, $parameters, 'POST');
    }

    public function put(SenderInterface $sender, array $parameters)
    {
        return $this->processForm($sender, $parameters, 'PUT');
    }

    /**
     * Processes the form.
     *
     * @param SenderInterface $sender
     * @param array                 $parameters
     * @param String                $method
     *
     * @return SenderInterface
     *
     * @throws \PROCERGS\NotificationServiceBundle\Exception\InvalidFormException
     */
    private function processForm(SenderInterface $sender,
                                 array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new SenderType(),
                $sender, compact('method'));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $sender = $form->getData();
            $this->om->persist($sender);
            $this->om->flush($sender);

            return $sender;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createNotification()
    {
        return new $this->entityClass();
    }

}
