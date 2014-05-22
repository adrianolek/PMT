<?php

namespace PMT\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use PMT\ProjectBundle\Entity\Project;

class LoadProjectData extends AbstractFixture
{

    public function load(ObjectManager $em)
    {
        $project = new Project();
        $project->setName('Foo Project');

        $em->persist($project);

        $em->flush();

        $this->addReference('project-foo', $project);
    }
}
