<?php

namespace PMT\TrackingBundle\Controller;

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
     * @Route("/tracking", name="tracking")
     * @Route("/user/{id}/tracking", name="user_tracking")
     * @Template
     */
    public function indexAction(Request $request)
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

        if ($request->get('id')) {
            $user = $em->getRepository('PMTUserBundle:User')->find($request->get('id'));
        } else {
            $user = $this->getUser();
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
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
     * @Route("/tracking/new", name="track_new")
     * @Template("PMTTrackingBundle:Tracking:form.html.twig")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function newAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();

      $track = new Track();
      $track->setUser($em->getReference('PMT\UserBundle\Entity\User', $this->getUser()->getId()));

      $form = $this->createForm(new TrackType($this->getUser()->getId()), $track);

      if ($request->isMethod('post')) {
        $form->submit($request);
        if ($form->isValid()) {
            $em->persist($track);
            $em->flush();

            if ($track->getTask()) {
                $em->getRepository('PMTTaskBundle:Task')->updateProgress($track->getTask());
            }

            $this->get('session')->getFlashBag()->add('success', 'Entry has been created.');

            return $this->redirect($this->generateUrl('tracking'));
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

            return $this->redirect($this->generateUrl('tracking'));
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
      $em = $this->getDoctrine()->getManager();

      $em->remove($track);
      $em->flush();

      if ($track->getTask()) {
          $em->getRepository('PMTTaskBundle:Task')->updateProgress($track->getTask());
      }

      $this->get('session')->getFlashBag()->add('success', 'Entry has been deleted.');

      return $this->redirect($this->generateUrl('tracking'));
    }
}
