<?php

namespace PMT\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PMT\ProjectBundle\Entity\Project;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProjectData implements FixtureInterface
{


    public function load(ObjectManager $em)
    {
        $project = new Project();
        $project->setName('Foo Project');

        $em->persist($project);

        $em->flush();
    }
}