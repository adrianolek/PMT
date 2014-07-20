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

    /**
     * @ORM\PostPersist
     */
    public function postPersist(Task $task, LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();

        $qb = $em->getRepository('PMTTaskBundle:Task')->createQueryBuilder('t');
        $qb->andWhere('t.project = :project')
            ->setParameter('project', $task->getProject()->getId())
            ->andWhere('t != :task')
            ->setParameter('task', $task->getId())
            ->andWhere('t.priority >= :priority')
            ->setParameter('priority', $task->getPriority())
            ->addOrderBy('t.priority', 'ASC')
            ->addOrderBy('t.position', 'DESC')
            ->setMaxResults(1);

        $preceding = $qb->getQuery()->getOneOrNullResult();

        if ($preceding) {
            $task->setPosition($preceding->getPosition() + 1);
        } else {
            $task->setPosition(0);
        }

        $em->persist($task);
        $em->flush();
    }

}
