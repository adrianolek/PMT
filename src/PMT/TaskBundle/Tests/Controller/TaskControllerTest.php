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
            'PMT\TaskBundle\DataFixtures\ORM\LoadTaskData'
        ));
    }

    public function testIndex()
    {
        $client = static::createAuthClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Foo Project')->link();
        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddTask()
    {
        $client = static::createAuthClient();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $project = $em->createQueryBuilder()->select('p')->from('PMT\ProjectBundle\Entity\Project', 'p')->getQuery()->getSingleResult();

        $tasks_url = '/project/' . $project->getId() . '/tasks';
        $crawler = $client->request('GET', $tasks_url);

        $link = $crawler->selectLink('Add task')->link();

        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Create')->form();

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
        $project = $em->createQueryBuilder()->select('p')->from('PMT\ProjectBundle\Entity\Project', 'p')->getQuery()->getSingleResult();

        $crawler = $client->request('GET', '/project/' . $project->getId() . '/tasks');

        $link = $crawler->selectLink('Foo task')->link();

        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testEditTask()
    {
        $client = static::createAuthClient();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\task', 't')->getQuery()->getSingleResult();

        $task_url = '/project/' . $task->getProject()->getId() . '/task/' . $task->getId();
        $crawler = $client->request('GET', $task_url);

        $link = $crawler->selectLink('Edit')->link();

        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Update')->form();

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
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\task', 't')->getQuery()->getSingleResult();

        $crawler = $client->request('GET', '/project/' . $task->getProject()->getId() . '/task/' . $task->getId());

        $link = $crawler->selectLink('Edit')->link();

        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('Delete')->link();

        $crawler = $client->click($link);

        $this->assertTrue($client->getResponse()->isRedirect('/project/' . $task->getProject()->getId() . '/tasks'));

        $this->assertEquals(0, $crawler->selectLink($task->getName())->count());
    }
}
