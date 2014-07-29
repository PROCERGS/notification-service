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

class NotificationController extends FOSRestController
{

    /**
     * Get single Notification,
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
     * @throws NotFoundHttpException when page not exist
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
     *   input = "PROCERGS\NotificationServiceBundle\Form\PageType",
     *   statusCodes = {
     *     200 = "Returned when successfull",
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

            return $this->routeRedirectView('api_1_get_notification', $routeOptions,
                            Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    public function newNotificationAction()
    {
        return $this->createForm(new NotificationType());
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

    protected function getNotificationHandler()
    {
        return $this->get('procergs_notification_service.notification.handler');
    }

}
