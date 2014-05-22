<?php

namespace PMT\ApiBundle\Controller;

use PMT\ProjectBundle\Entity\Project;
use PMT\TaskBundle\Entity\Task;
use PMT\TaskBundle\Form\TaskFilterType;
use PMT\TrackingBundle\Entity\Track;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class DefaultController
 * @Route("/api")
 */
class DefaultController extends Controller
{

    /**
     * @Route("/project.json")
     */
    public function projectsAction()
    {
        $projects = array();
        $em = $this->getDoctrine()->getManager();

        $ps = $em->getRepository('PMTProjectBundle:Project')->findFor(
            $this->get('security.context')->isGranted('ROLE_MANAGER'),
            $this->getUser()
        );

        foreach ($ps as $project) {
            /* @var $project Project */
            $projects[] = array('id' => $project->getId(), 'name' => $project->getName());
        }

        return new JsonResponse(array('projects' => $projects));
    }

    /**
     * @Route("/project/{project_id}/task.json")
     * @ParamConverter("project", options={"id" = "project_id"})
     * @Security("has_role('ROLE_MANAGER') or project.getAssignedUsers().contains(user)")
     */
    public function tasksAction(Request $request, Project $project)
    {
        $tasks = array();
        $em = $this->getDoctrine()->getManager();

        $filter = $this->createForm(new TaskFilterType());
        $filter->submit(array(
            'statuses' => array('waiting', 'in_progress', 'complete', 'merge', 'merged'),
            'order' => 'priority',
            'date_start' => $em->getRepository('PMTProjectBundle:Project')->getStartDate($project->getId()),
            'date_end' => strftime('%Y-%m-%d'),
        ));

        list($ts, $durations) = $em->getRepository('PMTTaskBundle:Task')->filter($filter->getData(), $project->getId(), $this->getUser()->getId());

        foreach ($ts as $task) {
            /* @var $task Task */
            $tasks[] = array('id' => $task->getId(),
                'name' => $task->getName(),
                'progress' => $task->getProgress(),
                'priorityColor' => $task->getPriorityColor());
        }

        return new JsonResponse(array(
            'project' => array(
                'id' => $project->getId(),
                'name' => $project->getName()),
            'tasks' => $tasks));
    }

    /**
     * @Route("/project/{project_id}/task/{id}.json")
     */
    public function taskAction(Request $request, Task $task)
    {
        $project = $task->getProject();

        if (!$this->get('security.context')->isGranted(new Expression(
            'has_role("ROLE_MANAGER") or object.getAssignedUsers().contains(user)'
        ), $project)) {
            throw new AccessDeniedException();
        }

        return new JsonResponse(array(
            'project' => array(
                'id' => $project->getId(),
                'name' => $project->getName()),
            'task' => array(
                'id' => $task->getId(),
                'name' => $task->getName(),
                'estimatedTime' => $task->getEstimatedTime(),
                'description' => $task->getDescription(),
            )));
    }

    /**
     * @Route("/task/{id}/estimate.json")
     */
    public function estimateAction(Request $request, Task $task)
    {
        if (!$this->get('security.context')->isGranted(new Expression(
            'has_role("ROLE_MANAGER") or object.getAssignedUsers().contains(user)'
        ), $task->getProject())) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $task->setEstimatedTimeHours($request->get('time'));

        $em->persist($task);
        $em->flush();

        return new JsonResponse(array(
            'task' => array(
                'id' => $task->getId(),
                'name' => $task->getName(),
                'estimatedTime' => $task->getEstimatedTime(),
                'description' => $task->getDescription(),
        )));
    }

    /**
     * @Route("/tracking.json")
     * @param  Request               $request
     * @return JsonResponse
     * @throws AccessDeniedException
     */
    public function createTrackAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $track = new Track();
        $track->setUser($em->getReference('PMT\UserBundle\Entity\User', $this->getUser()->getId()));

        if ($request->get('taskId')) {
            $track->setTask($em->getReference('PMT\TaskBundle\Entity\Task', $request->get('taskId')));
            $task = $track->getTask();

            if (!$this->get('security.context')->isGranted(new Expression(
                'has_role("ROLE_MANAGER") or object.getAssignedUsers().contains(user)'
            ), $task->getProject())) {
                throw new AccessDeniedException();
            }

            if (!$task->getAssignedUsers()->contains($this->getUser())) {
                $task->addAssignedUser($em->getReference('PMT\UserBundle\Entity\User', $this->getUser()->getId()));
            }

            if ($task->getStatus() == 'waiting') {
                $task->setStatus('in_progress');
            }
        }

        $track->setStartedAt(new \DateTime('-10 seconds'));
        $track->setEndedAt(new \DateTime());

        $em->persist($track);
        if (isset($task)) {
            $em->persist($task);
        }
        $em->flush();

        $response = new JsonResponse(array('id' => $track->getId()), 201);

        return $response;
    }

    /**
     * @Route("/tracking/{id}.json")
     * @param  Request      $request
     * @param  Track        $track
     * @return JsonResponse
     * @Security("track.getUser() == user")
     */
    public function updateTrackAction(Request $request, Track $track)
    {
        $em = $this->getDoctrine()->getManager();

        $now = new \DateTime();
        $track->setEndedAt($now);
        $track->setDescription($request->get('description'));

        $em->persist($track);
        $em->flush();

        if ($task = $track->getTask()) {
            if ($request->get('complete')) {
                $task->setProgress(100);
                if ($task->getStatus() == 'in_progress') {
                    $task->setStatus('complete');
                }
                $em->persist($track);
                $em->flush();
            } else {
                $em->getRepository('PMTTaskBundle:Task')->updateProgress($track->getTask());
            }
        }

        $response = new JsonResponse(array('id' => $track->getId()));

        return $response;
    }
}
