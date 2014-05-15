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
            'PMT\ProjectBundle\DataFixtures\ORM\LoadProjectData'
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
            'task[name]' => 'Foo project',
            'task[category]' => 'feature'
        ));

        $this->assertTrue($client->getResponse()->isRedirect($tasks_url));
    }
}
