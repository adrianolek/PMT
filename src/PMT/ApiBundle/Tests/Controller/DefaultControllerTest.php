<?php

namespace PMT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testProject()
    {
        $client = static::createClient(array(), array(
            'HTTP_x-auth-token' => 'pmt',
        ));

        $crawler = $client->request('GET', '/api/project.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
