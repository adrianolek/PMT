<?php

namespace PMT\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PMT\FileBundle\Entity\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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

                // Here, "getMyFile" returns the "UploadedFile" instance that the form bound in your $myFile property
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
     * @Route("/file/{key}.{ext}", name="file")
     * @Route("/file/t/{key}.{ext}", name="thumb")
     * @Template()
     */
    public function showAction(Request $request, $key)
    {
        $em = $this->getDoctrine()->getEntityManager();
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
}
