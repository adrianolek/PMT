<?php

namespace PMT\TaskBundle\Tests\Controller;

use PMT\TestBundle\Test\WebTestCase;

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
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
