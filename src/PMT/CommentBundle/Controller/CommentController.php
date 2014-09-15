<?php

namespace PMT\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PMT\CommentBundle\Entity\Comment;
use PMT\CommentBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
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

                $this->get('pmt.notification')->notify('new_comment', $comment);

                $response = $this->render(
                    'PMTCommentBundle:Comment:show.html.twig',
                    array('comment' => $comment)
                );
                $response->setStatusCode(Response::HTTP_CREATED);
                return $response;
            }
        }

        $response = $this->render(
            'PMTCommentBundle:Comment:form.html.twig',
            array(
                'form' => $form->createView(),
                'task_id' => $task_id,
            )
        );

        if ($form->isSubmitted() && !$form->isValid()) {
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $response;

    }

}
