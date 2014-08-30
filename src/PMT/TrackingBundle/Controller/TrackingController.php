<?php

namespace PMT\TrackingBundle\Controller;

use PMT\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use PMT\TrackingBundle\Form\TrackingFilterType;
use PMT\TrackingBundle\Entity\Track;
use PMT\TrackingBundle\Form\TrackType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TrackingController extends Controller
{
    /**
     * @Route("/user/{id}/tracking", name="user_tracking")
     * @Security("has_role('ROLE_MANAGER') or user.getId() == id")
     * @Template
     */
    public function indexAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();

        $filter = $this->createForm(new TrackingFilterType());

        if ($request->query->has('date_start')) {
            $filter->submit($request);
        } else {
            $filter->submit(array(
                'date_start' => strftime('%Y-%m-01'),
                'date_end' => strftime('%Y-%m-%d'),
            ));
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('People', $this->generateUrl('people'));
        $breadcrumbs->addItem($user->getFullName());
        $breadcrumbs->addItem('Time Tracking', $request->getUri());

        list($total, $tracks) = $em->getRepository('PMTTrackingBundle:Track')->filter($filter->getData(), $user->getId());

        return array(
            'user' => $user,
            'filter' => $filter->createView(),
            'tracks' => $tracks,
            'total' => $total,
            'url' => $request->getBaseUrl().$request->getPathInfo()
        );
    }

    /**
     * @Route("/user/{id}/tracking/new", name="user_track_new")
     * @Template("PMTTrackingBundle:Tracking:form.html.twig")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function newAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();

        $track = new Track();
        
        $track->setUser($user);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('People', $this->generateUrl('people'));
        $breadcrumbs->addItem($user->getFullName());
        $breadcrumbs->addItem('Time Tracking', $this->generateUrl('user_tracking', array('id' => $user->getId())));
        $breadcrumbs->addItem('New', $request->getUri());

        $form = $this->createForm(new TrackType($user->getId()), $track);

        if ($request->isMethod('post')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($track);
                $em->flush();

                if ($track->getTask()) {
                    $em->getRepository('PMTTaskBundle:Task')->updateProgress($track->getTask());
                }

                $this->get('session')->getFlashBag()->add('success', 'Entry has been created.');

                return $this->redirect($this->generateUrl('user_tracking', array('id' => $user->getId())));
            }
        }

        return array(
            'track' => $track,
            'is_new' => true,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/track/{id}/edit", name="track_edit")
     * @Template("PMTTrackingBundle:Tracking:form.html.twig")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function editAction(Request $request, Track $track)
    {
        $user = $track->getUser();
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('People', $this->generateUrl('people'));
        $breadcrumbs->addItem($user->getFullName());
        $breadcrumbs->addItem('Time Tracking', $this->generateUrl('user_tracking', array('id' => $user->getId())));
        $breadcrumbs->addItem('Edit', $request->getUri());

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new TrackType($track->getUser()->getId()), $track);

        if ($request->isMethod('post')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($track);
                $em->flush();

                if ($track->getTask()) {
                    $em->getRepository('PMTTaskBundle:Task')->updateProgress($track->getTask());
                }

                $this->get('session')->getFlashBag()->add('success', 'Entry has been updated.');

                return $this->redirect($this->generateUrl('user_tracking', array('id' => $user->getId())));
            }
        }

        return array(
            'track' => $track,
            'is_new' => false,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/track/{id}/delete", name="track_delete")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function deleteAction(Request $request, Track $track)
    {
        $user = $track->getUser();
        $em = $this->getDoctrine()->getManager();

        $em->remove($track);
        $em->flush();

        if ($track->getTask()) {
            $em->getRepository('PMTTaskBundle:Task')->updateProgress($track->getTask());
        }

        $this->get('session')->getFlashBag()->add('success', 'Entry has been deleted.');

        return $this->redirect($this->generateUrl('user_tracking', array('id' => $user->getId())));
    }
}
