<?php

namespace PROCERGS\NotificationServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use PROCERGS\NotificationServiceBundle\Exception\InvalidFormException;
use PROCERGS\NotificationServiceBundle\Form\UserType;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\SecurityExtraBundle\Annotation AS JMS;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends FOSRestController
{

    /**
     * List all users.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing API Users")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many users to return.")
     * @Annotations\View(
     *   templateVar="users"
     * )
     *
     * @param Request                $request       the request object
     * @param ParamFetcherInterface  $paramFetcher  param fetcher service
     *
     * @return array
     *
     * @JMS\Secure(roles="ROLE_API_V1")
     */
    public function getUsersAction(Request $request,
                                   ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->getUserHandler()->all($limit, $offset);
    }

    /**
     * Get single API User.
     *
     * @Route("/{id}", name="api_1_get_user", requirements={"id" = "\d+"})
     * @JMS\Secure(roles="ROLE_API_V1")
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets an API User for a given id",
     *   output = "PROCERGS\NotificationServiceBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     * @Annotations\View(templateVar="user")
     * @param Request $request the request object
     * @param int     $id
     * @return array
     * @throws NotFoundHttpException when user not exist
     */
    public function getUserAction($id)
    {
        if ($id === 'self') {
            return $this->getUsersSelfAction();
        }
        $user = $this->getOr404($id);

        return $user;
    }

    /**
     * Gets the current API User.
     *
     * @JMS\Secure(roles="ROLE_API_V1")
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets the current API User",
     *   output = "PROCERGS\NotificationServiceBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     * @Annotations\View(templateVar="user")
     * @param Request $request the request object
     * @param int     $id
     * @return array
     * @throws NotFoundHttpException when user not exist
     */
    public function getUsersSelfAction()
    {
        return $this->getUser();
    }

    /**
     * Create an API User from the submitted data.
     *
     * @JMS\Secure(roles="ROLE_ADMIN")
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new user from the submitted data.",
     *   input = "PROCERGS\NotificationServiceBundle\Form\UserType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @Annotations\View(
     *   template = "PROCERGSNotificationServiceBundle:User:newUser.html.twig",
     *   statusCode = Codes::HTTP_BAD_REQUEST,
     *   templateVar = "form"
     * )
     *
     * @param Request $request
     * @return FormTypeInterface|View
     */
    public function postUserAction(Request $request)
    {
        try {
            $newUser = $this->getUserHandler()->post(
                    $request->request->all()
            );

            $routeOptions = array(
                'id' => $newUser->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_user', $routeOptions,
                            Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    public function newUserAction()
    {
        return $this->createForm(new UserType());
    }

    /**
     * Update existing user from the submitted data or create a new user at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "PROCERGS\NotificationServiceBundle\Form\UserType",
     *   statusCodes = {
     *     201 = "Returned when the User is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "PROCERGSNotificationServiceBundle:User:editUser.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the user id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when user not exist
     *
     * @JMS\Secure(roles="ROLE_API_V1")
     */
    public function putUserAction(Request $request, $id)
    {
        try {
            if (!($user = $this->getUserHandler()->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $user = $this->getUserHandler()->post(
                        $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $user = $this->getUserHandler()->put(
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
     * Update existing users from the submitted data or create a new user at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "PROCERGS\NotificationBundle\Form\UserType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "PROCERGSNotificationServiceBundle:User:editUser.html.twig",
     *   templateVar = "form"
     * )
     *
     * @param Request $request  the request object
     * @param int     $id       the user id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when user not exist
     *
     * @JMS\Secure(roles="ROLE_API_V1")
     */
    public function patchUserAction(Request $request, $id)
    {
        try {
            $user = $this->getUserHandler()->patch(
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
     * Fetch an user or throw an 404 Exception.
     *
     * @param mixed $id
     * @return UserInterface
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($user = $this->getUserHandler()->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',
                    $id));
        }

        return $user;
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
