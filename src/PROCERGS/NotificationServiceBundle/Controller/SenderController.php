<?php

namespace PROCERGS\NotificationServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations as REST;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use PROCERGS\NotificationServiceBundle\Exception\InvalidFormException;
use PROCERGS\NotificationServiceBundle\Form\SenderType;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\SecurityExtraBundle\Annotation AS JMS;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @REST\Prefix("v1/users/{userId}/senders")
 */
class SenderController extends FOSRestController
{

    /**
     * List all senders.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @REST\QueryParam(name="userId", requirements="\d+", nullable=false, description="The API User id")
     * @REST\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing senders")
     * @REST\QueryParam(name="limit", requirements="\d+", default="5", description="How many senders to return.")
     * @REST\View(
     *   templateVar="senders"
     * )
     *
     * @param Request                $request       the request object
     * @param ParamFetcherInterface  $paramFetcher  param fetcher service
     *
     * @return array
     *
     * @JMS\Secure(roles="ROLE_API_V1, ROLE_ADMIN")
     * @REST\Get("")
     */
    public function getSendersAction(Request $request,
                                     ParamFetcherInterface $paramFetcher)
    {
        $userId = $paramFetcher->get('userId');
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->getSenderHandler()->all($limit, $offset, $userId);
    }

    /**
     * Get single API Sender.
     *
     * @REST\Get("/{id}")
     * @JMS\Secure(roles="ROLE_API_V1, ROLE_ADMIN")
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets an API Sender for a given id",
     *   output = "PROCERGS\NotificationServiceBundle\Entity\Sender",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the sender is not found"
     *   }
     * )
     * @REST\View(templateVar="sender")
     * @param Request $request the request object
     * @param int     $id
     * @return array
     * @throws NotFoundHttpException when sender not exist
     */
    public function getSenderAction($userId, $id)
    {
        $user = $this->getOr404($id, $userId);

        return $user;
    }

    /**
     * Create an API Sender from the submitted data.
     *
     * @JMS\Secure(roles="ROLE_ADMIN")
     * @REST\Post("")
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new sender from the submitted data.",
     *   input = "PROCERGS\NotificationServiceBundle\Form\SenderType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @REST\View(
     *   template = "PROCERGSNotificationServiceBundle:Sender:newSender.html.twig",
     *   statusCode = Codes::HTTP_BAD_REQUEST,
     *   templateVar = "form"
     * )
     *
     * @param Request $request
     * @return FormTypeInterface|View
     */
    public function postSenderAction(Request $request)
    {
        try {
            $newSender = $this->getSenderHandler()->post(
                    $request->request->all()
            );

            $routeOptions = array(
                'id' => $newSender->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_user', $routeOptions,
                            Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * @REST\Get("/new")
     * @return type
     */
    public function newSenderAction()
    {
        return $this->createForm(new SenderType());
    }

    /**
     * Update existing sender from the submitted data or create a new sender at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "PROCERGS\NotificationServiceBundle\Form\SenderType",
     *   statusCodes = {
     *     201 = "Returned when the Sender is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @REST\View(
     *  template = "PROCERGSNotificationServiceBundle:Sender:editSender.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the user id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when sender not exist
     *
     * @JMS\Secure(roles="ROLE_API_V1")
     * @REST\Put("/{id}")
     */
    public function putSenderAction(Request $request, $id)
    {
        try {
            if (!($user = $this->getSenderHandler()->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $user = $this->getSenderHandler()->post(
                        $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $user = $this->getSenderHandler()->put(
                        $user, $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $user->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_user', $routeOptions,
                            $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing sender from the submitted data or create a new sender at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "PROCERGS\NotificationBundle\Form\SenderType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @REST\View(
     *   template = "PROCERGSNotificationServiceBundle:Sender:editSender.html.twig",
     *   templateVar = "form"
     * )
     *
     * @param Request $request  the request object
     * @param int     $id       the user id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when sender not exist
     *
     * @JMS\Secure(roles="ROLE_API_V1")
     * @REST\Patch("/{id}")
     */
    public function patchSenderAction(Request $request, $id)
    {
        try {
            $user = $this->getSenderHandler()->patch(
                    $this->getOr404($id), $request->request->all()
            );

            $routeOptions = array(
                'id' => $user->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_user', $routeOptions,
                            Codes::HTTP_NO_CONTENT);
        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
    }

    /**
     * Fetch a sender or throw an 404 Exception.
     *
     * @param mixed $id
     * @param mixed $userId
     * @return SenderInterface
     * @throws NotFoundHttpException
     */
    protected function getOr404($id, $userId = null)
    {
        if (!($user = $this->getSenderHandler()->get($id, $userId))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',
                    $id));
        }

        return $user;
    }

    /**
     *
     * @return \PROCERGS\NotificationServiceBundle\Handler\SenderHandlerInterface
     */
    protected function getSenderHandler()
    {
        return $this->get('procergs_notification_service.sender.handler');
    }

    /**
     *
     * @return \PROCERGS\NotificationServiceBundle\Handler\UserHandlerInterface
     */
    protected function getUserHandler()
    {
        return $this->get('procergs_notification_service.user.handler');
    }

}
