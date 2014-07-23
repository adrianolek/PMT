<?php

namespace PMT\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PMT\FileBundle\Entity\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FileController extends Controller
{
    /**
     * @Route("/task/{task_id}/file/new", name="task_file_new")
     * @Template()
     */
    public function newAction($task_id)
    {
        $em = $this->getDoctrine()->getManager();
        $file = new File();
        $file->setTask($em->getReference('PMT\TaskBundle\Entity\Task', $task_id));
        $file->setUser($em->getReference('PMT\UserBundle\Entity\User', $this->getUser()->getId()));
        $form = $this->createFormBuilder($file)
            ->add('file')
            ->getForm();

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->handleRequest($this->getRequest());

            if ($form->isValid()) {
                $em->persist($file);
                $em->flush();

                $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
                $uploadableManager->markEntityToUpload($file, $file->getFile());

                $em->flush();

                $this->get('session')->getFlashBag()->add('success', sprintf('File %s has been uploaded.', $file));

                return $this->redirect($this->generateUrl('project_task', array('project_id' => $file->getTask()->getProject()->getId(),
                    'id' => $file->getTask()->getId())));
            }
        }

        return array(
            'task_id' => $task_id,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/task/{task_id}/files", name="task_files")
     * @Template()
     */
    public function indexAction($task_id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getReference('PMT\TaskBundle\Entity\Task', $task_id);

        return array(
            'files' => $task->getFiles()
        );
    }

    /**
     * @Route("/file/{key}", name="file")
     * @Route("/file/t/{key}", name="thumb")
     * @Template()
     */
    public function showAction(Request $request, $key)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('PMT\FileBundle\Entity\File')->findOneBy(array('download_key' => $key));

        $response = new Response(null, 200);

        if ($request->get('_route') == 'thumb') {
            $response->headers->set('X-Sendfile', $file->getThumbPath());
        } else {
            $response->headers->set('X-Sendfile', $file->getPath());
        }
        $response->headers->set('Content-Type', $file->getMimeType());
        $response->headers->set('Content-Disposition', 'inline; filename="'.$file->getName().'"');
        $response->headers->set('Cache-control', 'public');
        $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', strtotime('+1 year')));

        return $response;
    }

    /**
     * @Route("/file/{id}/delete", name="file_delete")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function deleteAction(Request $request, File $file)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($file);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', sprintf('File %s has been deleted.', $file));

        return $this->redirect($this->generateUrl('project_task', array(
            'project_id' => $file->getTask()->getProject()->getId(),
            'id' => $file->getTask()->getId(),
        )));
    }
}
