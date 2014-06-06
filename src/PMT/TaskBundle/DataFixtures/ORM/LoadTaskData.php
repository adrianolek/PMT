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
        $em->persist($task);

        $em->flush();

        $this->addReference('task-1', $task);
    }
}
