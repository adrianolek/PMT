<?php

namespace PMT\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TaskListener
{

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function saveText(Task $task, LifecycleEventArgs $event)
    {
        $event->getEntityManager()->getRepository('PMTTaskBundle:TaskText')->saveText($task);
    }

    /** @ORM\PreRemove */
    public function deleteText(Task $task, LifecycleEventArgs $event)
    {
        $event->getEntityManager()->getRepository('PMTTaskBundle:TaskText')->deleteText($task);
    }

}
