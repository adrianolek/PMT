<?php

namespace PMT\ProjectBundle\Tests\Controller;

use PMT\TestBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
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
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
