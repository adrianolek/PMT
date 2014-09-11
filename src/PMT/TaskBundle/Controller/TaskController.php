<?php

namespace PMT\TaskBundle\Controller;

use PMT\ProjectBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PMT\TaskBundle\Entity\Task;
use PMT\TaskBundle\Form\TaskType;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use PMT\TaskBundle\Form\TaskFilterType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TaskController extends Controller
{
    /**
     * @Route("/project/{project_id}/tasks", name="project_tasks")
     * @Template
     * @ParamConverter("project", options={"id" = "project_id"})
     * @Security("has_role('ROLE_MANAGER') or project.getAssignedUsers().contains(user)")
     */
    public function indexAction(Request $request, Project $project)
    {
        $em = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Projects', $this->generateUrl('projects'));
        $breadcrumbs->addItem($project->getName());
        $breadcrumbs->addItem('Tasks', $this->generateUrl('project_tasks', array('project_id' => $project->getId())));

        $filter = $this->createForm(new TaskFilterType());

        if ($request->query->has('order')) {
            $filter->submit($request);
        } else {
            $filter->submit(array(
                'statuses' => array('planned', 'waiting', 'in_progress', 'complete', 'merge', 'merged'),
                'order' => 'priority',
                'date_start' => $em->getRepository('PMTProjectBundle:Project')->getStartDate($project->getId()),
                'date_end' => strftime('%Y-%m-%d'),
            ));
        }

        list($tasks, $durations) = $em->getRepository('PMTTaskBundle:Task')->filter($filter->getData(), $project->getId(), $this->getUser()->getId());
        $in_progress = $em->getRepository('PMTTaskBundle:Task')->getInProgress();

        return $this->render('PMTTaskBundle:Task:index.html.twig', array(
            'project' => $project,
            'tasks' => $tasks,
            'durations' => $durations,
            'in_progress' => $in_progress,
            'filter' => $filter->createView(),
        ));
    }

    /**
     * @Route("/project/{project_id}/tasks/new", name="project_task_new")
     * @Template("PMTTaskBundle:Task:form.html.twig")
     * @ParamConverter("project", options={"id" = "project_id"})
     * @Security("has_role('ROLE_MANAGER') or project.getAssignedUsers().contains(user)")
     */
    public function newAction(Request $request, Project $project)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Projects', $this->generateUrl('projects'));
        $breadcrumbs->addItem($project->getName());
        $breadcrumbs->addItem('Tasks', $this->generateUrl('project_tasks', array('project_id' => $project->getId())));
        $breadcrumbs->addItem('New', $request->getUri());
        
        $em = $this->getDoctrine()->getManager();

        $task = new Task();
        $task->setProject($project);
        $task->setUser($em->getReference('PMT\UserBundle\Entity\User', $this->getUser()->getId()));
        $task->setEstimatedTime(0);
        $task->setPriority(50);
        $task->setProgress(0);
        $task->setStatus('waiting');
        $form = $this->createForm(new TaskType(), $task, array(
            'user_repository' => $em->getRepository('PMTUserBundle:User'),
            'new' => true,
        ));

        if ($request->isMethod('post')) {
          $form->submit($request);
          if ($form->isValid()) {
            $em->persist($task);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('Task %s has been created.', $task));
            $this->get('pmt.notification')->notify('new_task', $task);

            return $this->redirect($this->generateUrl('project_tasks', array('project_id' => $project->getId())));
          }
        }

        return array(
            'project' => $project,
            'task' => $task,
            'is_new' => true,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/project/{project_id}/task/{id}/edit", name="project_task_edit")
     * @Template("PMTTaskBundle:Task:form.html.twig")
     * @Security("has_role('ROLE_MANAGER') or task.getUser() == user")
     */
    public function editAction(Request $request, Task $task)
    {
        $project = $task->getProject();
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Projects', $this->generateUrl('projects'));
        $breadcrumbs->addItem($project->getName());
        $breadcrumbs->addItem('Tasks', $this->generateUrl('project_tasks', array('project_id' => $project->getId())));
        $breadcrumbs->addItem('#'.$task->getId(), $this->generateUrl('project_task', array(
            'project_id' => $project->getId(),
            'id' => $task->getId())));
        $breadcrumbs->addItem('Edit', $request->getUri());
        
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new TaskType(), $task, array('user_repository' => $em->getRepository('PMTUserBundle:User')));

        if ($request->isMethod('post')) {
          $form->submit($request);
          if ($form->isValid()) {
            $em->persist($task);
            $em->flush();

            $em->getRepository('PMTTaskBundle:Task')->updateProgress($task);

            $this->get('session')->getFlashBag()->add('success', sprintf('Task %s has been updated.', $task));

            return $this->redirect($this->generateUrl('project_task', array('project_id' => $task->getProject()->getId(), 'id' => $task->getId())));
          }
        }

        return array(
            'project' => $task->getProject(),
            'task' => $task,
            'is_new' => false,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/project/{project_id}/task/{id}/delete", name="project_task_delete")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function deleteAction(Request $request, Task $task)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($task);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', sprintf('Task %s has been deleted.', $task));

        return $this->redirect($this->generateUrl('project_tasks', array('project_id' => $task->getProject()->getId())));
    }

    /**
     * @Route("/project/{project_id}/task/{id}", name="project_task")
     * @Template
     */
    public function showAction(Request $request, Task $task)
    {
        $project = $task->getProject();

        if (!$this->get('security.context')->isGranted(new Expression(
            'has_role("ROLE_MANAGER") or object.getAssignedUsers().contains(user)'
        ), $project)) {
            throw new AccessDeniedException();
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Projects', $this->generateUrl('projects'));
        $breadcrumbs->addItem($project->getName());
        $breadcrumbs->addItem('Tasks', $this->generateUrl('project_tasks', array('project_id' => $project->getId())));
        $breadcrumbs->addItem('#'.$task->getId(), $request->getUri());

        return $this->render('PMTTaskBundle:Task:show.html.twig', array(
            'project' => $project,
            'task' => $task,
        ));
    }

    /**
     * @Route("/task/{id}/status", name="task_status")
     * @Security("has_role('ROLE_MANAGER') or task.getUser() == user or task.getAssignedUsers().contains(user)")
     */
    public function taskStatusAction(Request $request, Task $task)
    {
        $em = $this->getDoctrine()->getManager();
        $previous = $task->getStatusName();
        $task->setStatus($request->get('status'));
        $em->persist($task);
        $em->flush();

        $em->getRepository('PMTTaskBundle:Task')->updateProgress($task);

        $this->get('pmt.notification')->notify('task_status', $task, $previous);

        return $this->render(
            'PMTTaskBundle:Task:status.html.twig',
            array('task' => $task)
        );
    }

    /**
     * @Route("/tasks/order", name="tasks_order")
     */
    public function orderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('PMTTaskBundle:Task')->find($request->get('item'));

        if (!$this->get('security.context')->isGranted(new Expression(
            'has_role("ROLE_MANAGER") or object.getAssignedUsers().contains(user)'
        ), $task->getProject())) {
            throw new AccessDeniedException();
        }

        if (!$request->get('prev')) {
            $task->setPosition(0);
            $task->setPriority(100);
        } elseif (!$request->get('next')) {
            $prev = $em->getRepository('PMTTaskBundle:Task')->find($request->get('prev'));
            $task->setPosition($prev->getPosition()+1);
            $task->setPriority(0);
        } else {
            $prev = $em->getRepository('PMTTaskBundle:Task')->find($request->get('prev'));
            $next = $em->getRepository('PMTTaskBundle:Task')->find($request->get('next'));
            $task->setPosition($prev->getPosition()+1);
            $task->setPriority(($prev->getPriority()+$next->getPriority())/2);
        }

        $em->persist($task);
        $em->flush();

        return new JsonResponse(array('id' => $task->getId(), 'color' => $task->getPriorityColor()));
    }
}
