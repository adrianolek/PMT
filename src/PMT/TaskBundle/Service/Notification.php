<?php 

namespace PMT\TaskBundle\Service;

use Doctrine\ORM\EntityManager;
use ICanBoogie\Inflector;
use PMT\TaskBundle\Entity\Task;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;
use PMT\CommentBundle\Entity\Comment;

class Notification
{
    private $em;
    private $mailer;
    private $router;
    private $sc;
    
    public function __construct(EntityManager $em, \Swift_Mailer $mailer, Router $router, SecurityContext $sc)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->router = $router;
        $this->sc = $sc;
    }
    
    public function notify($type, $object)
    {
        $inflector = Inflector::get();
        
        $args = func_get_args();
        $type = array_shift($args);
        $method = 'notify'.$inflector->camelize($type);
        
        if(method_exists($this, $method))
        {
            call_user_func_array(array($this, $method), $args);
        }
    }
    
    private function notifyTaskStatus(Task $task, $previous)
    {
        $user = $this->sc->getToken()->getUser();
        
        $this->sendEmail('['.$task->getProject().'] Status change to '.$task->getStatusName().': #'.$task->getId().' '.$task->getName(),
            $user.' changed task status from '.$previous.' to '.$task->getStatusName().': #'.$task->getId().' '.$task->getName()."\n"
            .$this->getTaskUrl($task));
    }
    
    private function notifyNewTask(Task $task)
    {
    
        $this->sendEmail('['.$task->getProject().'] New task: #'.$task->getId().' '.$task->getName(),
            $task->getUser().' added new task: #'.$task->getId().' '.$task->getName()."\n"
            .$this->getTaskUrl($task)."\n\n"
            .$task->getDescription());
    }
    
    private function notifyNewComment(Comment $comment)
    {
        $task = $comment->getTask();
        $this->sendEmail('['.$task->getProject().'] New comment: #'.$task->getId().' '.$task->getName(),
            $comment->getUser().' added new comment: #'.$task->getId().' '.$task->getName()."\n"
            .$this->getTaskUrl($task)."\n\n"
            .$comment->getContent());
    }
    
    private function sendEmail($subject, $body)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('notify@pmt', 'PMT Notification')
            ->setBcc($this->getRecipients())
            ->setBody($body);
        
        $this->mailer->send($message);
    }
    
    private function getRecipients()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from('PMTUserBundle:User', 'u')
            ->andWhere($qb->expr()->isNotNull('u.email'));
        
        $emails = array();
        foreach($qb->getQuery()->getResult() as $user)
        {
            $emails[] = $user->getEmail();
        }

        return array_unique(array_diff($emails, array($this->sc->getToken()->getUser()->getEmail())));
    }
    
    private function getTaskUrl($task)
    {
        return $this->router->generate('project_task', array('project_id' => $task->getProject()->getId(), 'id' => $task->getId()), true);
    }
}