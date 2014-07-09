<?php

namespace PMT\TaskBundle\Service;

use Doctrine\ORM\EntityManager;
use ICanBoogie\Inflector;
use PMT\TaskBundle\Entity\Task;
use PMT\UserBundle\Entity\User;
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

        if (method_exists($this, $method)) {
            call_user_func_array(array($this, $method), $args);
        } else {
            throw new \Exception(sprintf('Undefined status %s', $type));
        }
    }

    private function notifyTaskStatus(Task $task, $previous)
    {
        $recipients = $this->getTaskRecipients($task);

        return $this->sendEmail(
            strtr('[%project%] Status change to %status%: #%task_id% %task_name%', array(
                '%project%' => $task->getProject(),
                '%status%' => $task->getStatusName(),
                '%task_id%' => $task->getId(),
                '%task_name%' => $task->getName(),
            )),
            strtr("%user% changed task status from %previous% to %current%: #%task_id% %task_name%\n%url%", array(
                '%user%' => $this->getUser(),
                '%previous%' => $previous,
                '%current%' => $task->getStatusName(),
                '%task_id%' => $task->getId(),
                '%task_name%' => $task->getName(),
                '%url%' => $this->getTaskUrl($task),
            )),
            $recipients
        );
    }

    private function notifyNewTask(Task $task)
    {
        $recipients = $this->getTaskRecipients($task);

        $this->sendEmail(
            strtr('[%project%] New task: #%task_id% %task_name%', array(
                '%project%' => $task->getProject(),
                '%task_id%' => $task->getId(),
                '%task_name%' => $task->getName(),
            )),
            strtr("%user% added new task: #%task_id% %task_name%\n%url%\n\n%description%", array(
                '%user%' => $this->getUser(),
                '%task_id%' => $task->getId(),
                '%task_name%' => $task->getName(),
                '%url%' => $this->getTaskUrl($task),
                '%description%' => $task->getDescription(),
            )),
            $recipients
        );
    }

    private function notifyNewComment(Comment $comment)
    {
        $task = $comment->getTask();

        $recipients = $this->getTaskRecipients($task);

        $this->sendEmail(
            strtr('[%project%] New comment: #%task_id% %task_name%', array(
                '%project%' => $task->getProject(),
                '%task_id%' => $task->getId(),
                '%task_name%' => $task->getName(),
            )),
            strtr("%user% added new comment: #%task_id% %task_name%\n%url%\n\n%content%", array(
                '%user%' => $this->getUser(),
                '%task_id%' => $task->getId(),
                '%task_name%' => $task->getName(),
                '%url%' => $this->getTaskUrl($task),
                '%content%' => $comment->getContent(),
            )),
            $recipients
        );
    }

    /**
     * @param  Task     $task
     * @return string[]
     *
     * Returns emails for users involved in a task, which are:
     * - task adder
     * - assigned users
     * - users who commented on task
     */
    private function getTaskRecipients(Task $task)
    {
        if ($task->getAssignedUsers()->count()) {
            $emails = $this->getEmails($task->getAssignedUsers());
        } else {
            $emails = $this->getEmails($task->getProject()->getAssignedUsers());
        }

        if ($task->getUser() && $task->getUser()->getEmail()) {
            $emails[] = $task->getUser()->getEmail();
        }

        $emails = array_merge(
            $emails,
            $this->getEmails($this->em->getRepository('PMTTaskBundle:Task')->getCommentUsers($task))
        );

        return $emails;
    }

    /**
     * @param  string         $subject
     * @param  string         $body
     * @param  string[]       $recipients
     * @return \Swift_Message
     */
    private function sendEmail($subject, $body, $recipients)
    {
        $recipients = array_unique(array_diff($recipients, array($this->getUser()->getEmail())));

        if (empty($recipients)) {
            return false;
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('notify@pmt', 'PMT Notification')
            ->setBcc($recipients)
            ->setBody($body);

        $this->mailer->send($message);

        return $message;
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return $this->sc->getToken()->getUser();
    }

    /**
     * @param  User[]   $users
     * @return string[]
     */
    private function getEmails($users)
    {
        $emails = array();

        foreach ($users as $user) {
            if ($user->getEmail()) {
                $emails[] = $user->getEmail();
            }
        }

        return $emails;
    }

    private function getTaskUrl(Task $task)
    {
        return $this->router->generate('project_task', array('project_id' => $task->getProject()->getId(), 'id' => $task->getId()), true);
    }
}
