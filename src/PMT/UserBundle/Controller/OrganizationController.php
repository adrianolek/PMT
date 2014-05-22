<?php
namespace PMT\UserBundle\Controller;

use PMT\UserBundle\Entity\Organization;
use PMT\UserBundle\Form\OrganizationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class OrganizationController extends Controller
{

    /**
     * Displays a form to create a new Organization entity.
     *
     * @Route("/organizations/new", name="organization_new")
     * @Template("PMTUserBundle:Organization:form.html.twig")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function newAction(Request $request)
    {
        $organization = new Organization();
        $form = $this->createForm(new OrganizationType(), $organization);

        if ($request->isMethod('post')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($organization);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', sprintf('%s has been created.', $organization->getName()));

                return $this->redirect($this->generateUrl('people'));
            }
        }

        return array(
            'organization' => $organization,
            'is_new' => true,
            'form' => $form->createView(),
        );
    }

}
