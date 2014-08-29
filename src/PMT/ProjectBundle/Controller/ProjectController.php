<?php

namespace PMT\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PMT\ProjectBundle\Form\ProjectType;
use PMT\ProjectBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProjectController extends Controller
{
    /**
     * @Route("/", name="projects")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('Projects', $this->generateUrl('projects'));
        
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('PMTProjectBundle:Project')->findFor(
            $this->get('security.context')->isGranted('ROLE_MANAGER'),
            $this->getUser()
        );

        return array(
            'projects' => $projects
        );
    }

    /**
     * @Route("/projects/new", name="project_new")
     * @Template("PMTProjectBundle:Project:form.html.twig")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function newAction(Request $request)
    {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('Projects', $this->generateUrl('projects'));
        $breadcrumbs->addItem('New', $request->getUri());
        
        $project = new Project();
        $form = $this->createForm(new ProjectType(), $project);

        if ($request->isMethod('post')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', sprintf('Project %s has been created.', $project));

                return $this->redirect($this->generateUrl('projects'));
            }
        }

        return array(
            'project' => $project,
            'is_new' => true,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/project/{id}/edit", name="project_edit")
     * @Template("PMTProjectBundle:Project:form.html.twig")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function editAction(Request $request, Project $project)
    {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('Projects', $this->generateUrl('projects'));
        $breadcrumbs->addItem('Edit', $request->getUri());
        
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ProjectType(), $project, array('user_repository' => $em->getRepository('PMTUserBundle:User')));

        if ($request->isMethod('post')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($project);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', sprintf('Project %s has been updated.', $project));

                return $this->redirect($this->generateUrl('projects'));
            }
        }

        $view = $form->createView();

        return array(
            'project' => $project,
            'is_new' => false,
            'form' => $view,
        );
    }

    /**
     * @Route("/project/{id}/delete", name="project_delete")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function deleteAction(Request $request, Project $project)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($project);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', sprintf('Project %s has been deleted.', $project));

        return $this->redirect($this->generateUrl('projects'));
    }
}
