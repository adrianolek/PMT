<?php

namespace PMT\FileBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use PMT\FileBundle\Entity\File;

class LoadFileData extends AbstractFixture
{

    public function load(ObjectManager $em)
    {
        $file = new File();
        $file->setTask($this->getReference('task-1'));
        $file->setName('test.txt');
        $file->setDownloadKey('test');
        $file->setOriginalName('test.txt');
        $file->setPath('test.txt');

        $em->persist($file);

        $em->flush();

    }
}
