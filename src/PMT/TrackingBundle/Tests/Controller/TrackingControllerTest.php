<?php

namespace PMT\TrackingBundle\Tests\Controller;

use PMT\TestBundle\Test\WebTestCase;

class TrackingControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createAuthClient();
        $client->request('GET', '/tracking');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
