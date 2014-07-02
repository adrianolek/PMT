<?php

namespace PMT\TaskBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use PMT\TaskBundle\Entity\Task;

class LoadTaskData extends AbstractFixture
{

    public function load(ObjectManager $em)
    {
        $task = new Task();
        $task->setName('Foo task');
        $task->setProject($this->getReference('project-foo'));
        $task->setStatus('waiting');
        $task->setCategory('feature');
        $task->setDescription('This is test task.');
        $task->addAssignedUser($this->getReference('manager'));
        $task->addAssignedUser($this->getReference('user'));
        $task->setEstimatedTimeHours(1);
        $em->persist($task);

        $this->addReference('task-1', $task);

        $task = new Task();
        $task->setName('Bar task');
        $task->setProject($this->getReference('project-foo'));
        $task->setStatus('waiting');
        $task->setCategory('bug');
        $task->setEstimatedTimeHours(1);
        $em->persist($task);

        $task = new Task();
        $task->setName('Baz task');
        $task->setProject($this->getReference('project-foo'));
        $task->setStatus('waiting');
        $task->setCategory('modification');
        $task->setEstimatedTimeHours(1);
        $em->persist($task);

        $em->flush();
    }
}
