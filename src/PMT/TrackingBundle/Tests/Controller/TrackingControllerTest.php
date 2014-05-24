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

    public function testAdd()
    {
        $client = static::createAuthClient();
        $crawler = $client->request('GET', '/tracking');
        $link = $crawler->selectLink('Add entry')->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Create')->form();

        $client->submit($form, array(
            'track[date]' => '2014-01-01',
            'track[startTime]' => '08:00',
            'track[endTime]' => '09:00',
        ));
        $this->assertTrue($client->getResponse()->isRedirect('/tracking'));
    }
}
