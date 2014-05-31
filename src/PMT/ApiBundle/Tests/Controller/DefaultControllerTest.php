<?php

namespace PMT\ApiBundle\Tests\Controller;

use PMT\TestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->loadFixtures(array(
            'PMT\UserBundle\DataFixtures\ORM\LoadUserData'
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
}
