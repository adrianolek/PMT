<?php

namespace PMT\ApiBundle\Tests\Controller;

use PMT\TestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->loadFixtures(array(
            'PMT\UserBundle\DataFixtures\ORM\LoadUserData',
            'PMT\ProjectBundle\DataFixtures\ORM\LoadProjectData',
            'PMT\TaskBundle\DataFixtures\ORM\LoadTaskData',
        ));
    }

    public function testToken()
    {
        $client = static::createClient();

        $client->request('POST', '/api/token.json', array('username' => 'foo', 'password' => 'bar'));

        $this->assertEquals(403, $client->getResponse()->getStatusCode());

        $client->request('POST', '/api/token.json', array('username' => 'manager@pmt.test', 'password' => 'manager'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent());

        $this->assertNotEmpty($response->token);
    }

    public function testProjects()
    {
        $client = static::createApiClient();

        $client->request('GET', '/api/project.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testTasks()
    {
        $client = static::createApiClient();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $project = $em->createQueryBuilder()->select('p')->from('PMT\ProjectBundle\Entity\Project', 'p')->getQuery()->getSingleResult();
        
        $client->request('GET', '/api/project/'.$project->getId().'/task.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testTask()
    {
        $client = static::createApiClient();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->getQuery()->getSingleResult();

        $client->request('GET', '/api/project/'.$task->getProject()->getId().'/task/'.$task->getId().'.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testEstimate()
    {
        $client = static::createApiClient();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->getQuery()->getSingleResult();

        $client->request('POST', '/api/task/'.$task->getId().'/estimate.json', array(
            'time' => 4,
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
