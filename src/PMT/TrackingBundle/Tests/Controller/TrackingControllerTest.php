<?php

namespace PMT\TrackingBundle\Tests\Controller;

use Doctrine\ORM\EntityManager;
use PMT\TestBundle\Test\WebTestCase;

class TrackingControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->loadFixtures(array(
            'PMT\UserBundle\DataFixtures\ORM\LoadUserData',
            'PMT\ProjectBundle\DataFixtures\ORM\LoadProjectData',
            'PMT\TaskBundle\DataFixtures\ORM\LoadTaskData',
            'PMT\TrackingBundle\DataFixtures\ORM\LoadTrackData'
        ));
    }

    public function testIndex()
    {
        $client = static::createAuthClient();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $user = $em->createQueryBuilder()->select('u')->from('PMT\UserBundle\Entity\User', 'u')->setMaxResults(1)->getQuery()->getSingleResult();

        $client->request('GET', '/user/' . $user->getId() . '/tracking');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAdd()
    {
        $client = static::createAuthClient();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->setMaxResults(1)->getQuery()->getSingleResult();
        $user = $em->createQueryBuilder()->select('u')->from('PMT\UserBundle\Entity\User', 'u')->setMaxResults(1)->getQuery()->getSingleResult();

        $trackingPath = '/user/' . $user->getId() . '/tracking';
        $crawler = $client->request('GET', $trackingPath);
        $link = $crawler->selectLink('Add entry')->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Create')->form();

        $client->submit($form, array(
            'track[endTime]' => '09:00',
        ));
        $this->assertFalse($client->getResponse()->isRedirection());

        $client->submit($form, array(
            'track[task]' => $task->getId(),
            'track[date]' => '2014-01-01',
            'track[startTime]' => '08:00',
            'track[endTime]' => '09:00',
        ));
        $this->assertTrue($client->getResponse()->isRedirect($trackingPath));
    }

    public function testEdit()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $user = $em->getRepository('PMTUserBundle:User')->findOneBy(array('email' => 'manager@pmt.test'));
        $trackingPath = '/user/' . $user->getId() . '/tracking';

        $client = static::createAuthClient();
        $crawler = $client->request('GET', $trackingPath.'?date_start=2014-01-01&date_end=2014-01-31');
        $link = $crawler->selectLink('Edit')->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Update')->form();

        $form['track[date]'] = '';
        $client->submit($form);
        $this->assertFalse($client->getResponse()->isRedirection());

        $form['track[date]'] = '2014-01-02';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect($trackingPath));
    }

    public function testDelete()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $user = $em->getRepository('PMTUserBundle:User')->findOneBy(array('email' => 'manager@pmt.test'));
        $trackingPath = '/user/' . $user->getId() . '/tracking';

        $client = static::createAuthClient();
        $crawler = $client->request('GET', $trackingPath.'?date_start=2014-01-01&date_end=2014-01-31');
        $link = $crawler->selectLink('Delete')->link();
        $crawler = $client->click($link);

        $this->assertTrue($client->getResponse()->isRedirect($trackingPath));
    }
}
