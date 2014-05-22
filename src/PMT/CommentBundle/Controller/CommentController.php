<?php

namespace PMT\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PMT\CommentBundle\Entity\Comment;
use PMT\CommentBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{

    /**
     * @Route("/task/{task_id}/comments", name="task_comments")
     * @Template()
     */
    public function indexAction($task_id)
    {
      $em = $this->getDoctrine()->getManager();
      $task = $em->getRepository('PMTPTaskBundle:Task')->findOne($task_id);

      return array(
          'comments' => $task->getComments,
      );
    }

    /**
     * @Route("/task/{task_id}/comment/new", name="task_comment_new")
     * @Template("PMTCommentBundle:Comment:form.html.twig")
     */
    public function newAction(Request $request, $task_id)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $comment->setTask($em->getReference('PMT\TaskBundle\Entity\Task', $task_id));
        $comment->setUser($em->getReference('PMT\UserBundle\Entity\User', $this->getUser()->getId()));

        $form = $this->createForm(new CommentType(), $comment);

        if ($request->isMethod('post')) {
          $form->submit($request);
          if ($form->isValid()) {
            $em->persist($comment);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Comment has been created.');
            $this->get('pmt.notification')->notify('new_comment', $comment);

            return $this->redirect($this->generateUrl('project_task',
                array('project_id' => $comment->getTask()->getProject()->getId(),
                'id' => $task_id,
            )));
          }
        }

        return array(
            'form' => $form->createView(),
            'task_id' => $task_id,
        );
    }

}
