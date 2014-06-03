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

    public function testAuth()
    {
        $client = static::createClient();

        $client->request('POST', '/api/token.json', array('username' => 'foo', 'password' => 'bar'));

        $this->assertEquals(403, $client->getResponse()->getStatusCode());

        $client->request('POST', '/api/token.json', array('username' => 'manager@pmt.test', 'password' => 'manager'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent());

        $this->assertNotEmpty($response->token);
    }
    
    public function testToken()
    {
        $client = static::createApiClient();

        $client->request('GET', '/api/token.json');

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
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $project = $em->createQueryBuilder()->select('p')->from('PMT\ProjectBundle\Entity\Project', 'p')->getQuery()->getSingleResult();

        $client = static::createApiClient();
        $client->request('GET', '/api/project/'.$project->getId().'/task.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client = static::createApiClient(array('key' => 'userkey'));
        $client->request('GET', '/api/project/'.$project->getId().'/task.json');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testTask()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->getQuery()->getSingleResult();

        $client = static::createApiClient();
        $client->request('GET', '/api/project/'.$task->getProject()->getId().'/task/'.$task->getId().'.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client = static::createApiClient(array('key' => 'userkey'));
        $client->request('GET', '/api/project/'.$task->getProject()->getId().'/task/'.$task->getId().'.json');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testEstimate()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->getQuery()->getSingleResult();

        $client = static::createApiClient();
        $client->request('POST', '/api/task/'.$task->getId().'/estimate.json', array(
            'time' => 4,
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client = static::createApiClient(array('key' => 'userkey'));
        $client->request('POST', '/api/task/'.$task->getId().'/estimate.json', array(
            'time' => 4,
        ));

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
    
    public function testTrack()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->getQuery()->getSingleResult();


        $client = static::createApiClient();
        $client->request('POST', '/api/tracking.json', array(
            'taskId' => $task->getId(),
        ));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent());

        $client->request('POST', '/api/tracking/'.$response->id.'.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('POST', '/api/tracking/'.$response->id.'.json', array(
            'complete' => 1,
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client = static::createApiClient(array('key' => 'userkey'));
        $client->request('POST', '/api/tracking.json', array(
            'taskId' => $task->getId(),
        ));

        $this->assertEquals(403, $client->getResponse()->getStatusCode());

        $client->request('POST', '/api/tracking/'.$response->id.'.json', array(
            'complete' => 1,
        ));
        
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
