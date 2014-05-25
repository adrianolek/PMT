<?php

namespace PMT\TrackingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use PMT\TrackingBundle\Entity\Track;

class LoadTrackData extends AbstractFixture
{

    public function load(ObjectManager $em)
    {
        $track = new Track();
        $track->setUser($this->getReference('manager'));
        $track->setDate('2014-01-01');
        $track->setStartTime('08:00');
        $track->setEndTime('09:00');

        $em->persist($track);

        $em->flush();
    }
}
