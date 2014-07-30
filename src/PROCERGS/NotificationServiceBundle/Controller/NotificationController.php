<?php

namespace PROCERGS\NotificationServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use PROCERGS\NotificationServiceBundle\Exception\InvalidFormException;
use PROCERGS\NotificationServiceBundle\Form\NotificationType;
use FOS\RestBundle\Request\ParamFetcherInterface;

class NotificationController extends FOSRestController
{

    /**
     * List all notifications.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Notifications")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many notifications to return.")
     * @Annotations\View(
     *   templateVar="notifications"
     * )
     *
     * @param Request                $request       the request object
     * @param ParamFetcherInterface  $paramFetcher  param fetcher service
     *
     * @return array
     */
    public function getNotificationsAction(Request $request,
                                           ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->getNotificationHandler()->all($limit, $offset);
    }

    /**
     * Get single Notification.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Notification for a given id",
     *   output = "PROCERGS\NotificationServiceBundle\Entity\Notification",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the notification is not found"
     *   }
     * )
     * @Annotations\View(templateVar="notification")
     * @param Request $request the request object
     * @param int     $id
     * @return array
     * @throws NotFoundHttpException when notification not exist
     */
    public function getNotificationAction($id)
    {
        $notification = $this->getOr404($id);

        return $notification;
    }

    /**
     * Create a Notification from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new notification from the submitted data.",
     *   input = "PROCERGS\NotificationServiceBundle\Form\NotificationType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @Annotations\View(
     *   template = "PROCERGSNotificationServiceBundle:Notification:newNotification.html.twig",
     *   statusCode = Codes::HTTP_BAD_REQUEST,
     *   templateVar = "form"
     * )
     *
     * @param Request $request
     * @return FormTypeInterface|View
     */
    public function postNotificationAction(Request $request)
    {
        try {
            $newNotification = $this->getNotificationHandler()->post(
                    $request->request->all()
            );

            $routeOptions = array(
                'id' => $newNotification->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_notification',
                            $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    public function newNotificationAction()
    {
        return $this->createForm(new NotificationType());
    }

    /**
     * Update existing notification from the submitted data or create a new notification at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "PROCERGS\NotificationServiceBundle\Form\NotificationType",
     *   statusCodes = {
     *     201 = "Returned when the Notification is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "PROCERGSNotificationServiceBundle:Notification:editNotification.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the notification id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when notification not exist
     */
    public function putNotificationAction(Request $request, $id)
    {
        try {
            if (!($notification = $this->getNotificationHandler()->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $notification = $this->getNotificationHandler()->post(
                        $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $notification = $this->getNotificationHandler()->put(
                        $notification, $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $notification->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_notification',
                            $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing notifications from the submitted data or create a new notification at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "PROCERGS\NotificationBundle\Form\NotificationType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "PROCERGSNotificationServiceBundle:Notification:editNotification.html.twig",
     *   templateVar = "form"
     * )
     *
     * @param Request $request  the request object
     * @param int     $id       the notification id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when notification not exist
     */
    public function patchNotificationAction(Request $request, $id)
    {
        try {
            $notification = $this->getNotificationHandler()->patch(
                    $this->getOr404($id), $request->request->all()
            );

            $routeOptions = array(
                'id' => $notification->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_notification', $routeOptions, Codes::HTTP_NO_CONTENT);
        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
    }

    /**
     * Fetch a Notification or throw an 404 Exception.
     *
     * @param mixed $id
     * @return NotificationInterface
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($notification = $this->getNotificationHandler()->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',
                    $id));
        }

        return $notification;
    }

    /**
     *
     * @return \PROCERGS\NotificationServiceBundle\Handler\NotificationHandlerInterface
     */
    protected function getNotificationHandler()
    {
        return $this->get('procergs_notification_service.notification.handler');
    }

}
