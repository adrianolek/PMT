<?php

namespace PMT\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PMT\UserBundle\Entity\User;
use PMT\UserBundle\Form\UserType;
use Symfony\Component\Security\Core\SecurityContext;
use PMT\UserBundle\Form\LoginType;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * User controller.
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/people", name="people")
     * @Method("GET")
     * @Template
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('PMTUserBundle:User')->findAll();
        $organizations = $em->getRepository('PMTUserBundle:Organization')->findAll();

        return array(
            'users' => $users,
            'organizations' => $organizations,
        );
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/people/new", name="people_new")
     * @Template("PMTUserBundle:User:form.html.twig")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user, array(
            'validation_groups' => array('Default', 'New'),
            'is_manager' => $this->get('security.context')->isGranted('ROLE_MANAGER')
        ));

        if ($request->isMethod('post')) {
            $form->submit($request);
            if ($form->isValid()) {
                /* @var $userManager \PMT\UserBundle\Model\UserManager */
                $userManager = $this->container->get('pmt.user_manager');
                $userManager->updatePassword($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', sprintf('%s has been created.', $user->getFullName()));

                return $this->redirect($this->generateUrl('people'));
            }
        }

        return array(
            'user' => $user,
            'is_new' => true,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/people/{id}/edit", name="people_edit")
     * @Template("PMTUserBundle:User:form.html.twig")
     * @Security("has_role('ROLE_MANAGER') or user.getId() == id")
     */
    public function editAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $form = $this->createForm(new UserType(), $user, array(
            'is_manager' => $this->get('security.context')->isGranted('ROLE_MANAGER')
        ));

        if ($request->isMethod('post')) {
            $form->submit($request);
            if ($form->isValid()) {
                /* @var $userManager \PMT\UserBundle\Model\UserManager */
                $userManager = $this->container->get('pmt.user_manager');
                $userManager->updatePassword($user);
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', sprintf('%s has been updated.', $user->getFullName()));

                return $this->redirect($this->generateUrl('people'));
          }
        }

        return array(
            'user' => $user,
            'is_new' => false,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/people/delete/{id}", name="people_delete")
     * @Security("has_role('ROLE_MANAGER') and user.getId() != id")
     */
    public function deleteAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $em->remove($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', sprintf('%s has been deleted.', $user->getFullName()));

        return $this->redirect($this->generateUrl('people'));
    }

    /**
     * @Route("/login", name="login")
     * @Template
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        $form = $this->createForm(new LoginType());

        if ($session->has(SecurityContext::LAST_USERNAME)) {
            $form->setData(array('_username' => $session->get(SecurityContext::LAST_USERNAME)));
            $session->remove(SecurityContext::LAST_USERNAME);
        }

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
          $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
          $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
          $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
          $error = null;
        }

        if ($error) {
            $form_error = new FormError($error->getMessage());
            $form->addError($form_error);
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
