<?php

namespace PMT\TaskBundle\Tests\Controller;

use Doctrine\ORM\EntityManager;
use PMT\TestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class TaskControllerTest extends WebTestCase
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
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Bar Project')->link();
        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('Foo Project')->link();
        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', $client->getRequest()->getUri(), array(
            'order' => 'date',
            'assignment' => 'assigned',
            'categories' => array('bug', 'feature'),
            'statuses' => array('waiting', 'done'),
            'date_start' => '2010-01-05',
            'date_end' => '2014-06-02',
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', $client->getRequest()->getUri(), array(
            'order' => 'progress',
            'assignment' => 'unassigned',
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', $client->getRequest()->getUri(), array(
            'order' => 'name',
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddTask()
    {
        $client = static::createAuthClient();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $project = $em->createQueryBuilder()->select('p')->from('PMT\ProjectBundle\Entity\Project', 'p')->setMaxResults(1)->getQuery()->getSingleResult();

        $tasks_url = '/project/' . $project->getId() . '/tasks';
        $crawler = $client->request('GET', $tasks_url);

        $link = $crawler->selectLink('Add task')->link();

        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Create')->form();

        $client->submit($form, array(
            'task[name]' => '',
        ));

        $this->assertFalse($client->getResponse()->isRedirection());

        $client->submit($form, array(
            'task[name]' => 'Foo',
            'task[category]' => 'feature'
        ));

        $this->assertTrue($client->getResponse()->isRedirect($tasks_url));
    }

    public function testShowTask()
    {
        $client = static::createAuthClient();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $project = $em->createQueryBuilder()->select('p')->from('PMT\ProjectBundle\Entity\Project', 'p')->setMaxResults(1)->getQuery()->getSingleResult();

        $crawler = $client->request('GET', '/project/' . $project->getId() . '/tasks');

        $link = $crawler->selectLink('Foo task')->link();

        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client = static::createAuthClient(array('user' => 'user'));

        $client->click($link);

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testEditTask()
    {
        $client = static::createAuthClient();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->setMaxResults(1)->getQuery()->getSingleResult();

        $task_url = '/project/' . $task->getProject()->getId() . '/task/' . $task->getId();
        $crawler = $client->request('GET', $task_url);

        $link = $crawler->selectLink('Edit')->link();

        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Update')->form();

        $client->submit($form, array(
            'task[name]' => '',
        ));

        $this->assertFalse($client->getResponse()->isRedirection());

        $client->submit($form, array(
            'task[name]' => 'Foo',
            'task[category]' => 'feature'
        ));

        $this->assertTrue($client->getResponse()->isRedirect($task_url));
    }

    public function testDeleteTask()
    {
        $client = static::createAuthClient();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->setMaxResults(1)->getQuery()->getSingleResult();

        $crawler = $client->request('GET', '/project/' . $task->getProject()->getId() . '/task/' . $task->getId());

        $link = $crawler->selectLink('Edit')->link();

        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('Delete')->link();

        $crawler = $client->click($link);

        $this->assertTrue($client->getResponse()->isRedirect('/project/' . $task->getProject()->getId() . '/tasks'));

        $this->assertEquals(0, $crawler->selectLink($task->getName())->count());
    }

    public function testStatus()
    {
        $client = static::createAuthClient();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->setMaxResults(1)->getQuery()->getSingleResult();

        $client->request('POST', '/task/' . $task->getId() . '/status', array(
            'status' => 'in_progress',
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testOrder()
    {
        $client = static::createAuthClient();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $tasks = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->getQuery()->getResult();

        $client->request('POST', '/tasks/order', array(
            'item' => $tasks[0]->getId(),
            'prev' => $tasks[1]->getId(),
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('POST', '/tasks/order', array(
            'item' => $tasks[0]->getId(),
            'next' => $tasks[1]->getId(),
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('POST', '/tasks/order', array(
            'item' => $tasks[0]->getId(),
            'prev' => $tasks[1]->getId(),
            'next' => $tasks[2]->getId(),
        ));

        $client->request('POST', '/tasks/order', array(
            'item' => $tasks[0]->getId(),
            'prev' => $tasks[2]->getId(),
            'next' => $tasks[1]->getId(),
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client = static::createAuthClient(array('user' => 'user'));

        $client->request('POST', '/tasks/order', array(
            'item' => $tasks[0]->getId(),
            'prev' => $tasks[1]->getId(),
        ));

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
