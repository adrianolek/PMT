<?php

namespace PMT\ProjectBundle\Tests\Controller;

use PMT\TestBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->loadFixtures(array(
            'PMT\UserBundle\DataFixtures\ORM\LoadUserData',
            'PMT\ProjectBundle\DataFixtures\ORM\LoadProjectData',
            'PMT\TaskBundle\DataFixtures\ORM\LoadTaskData'
        ));
    }

    public function testSearch()
    {
        $client = static::createAuthClient();
        $client->request('GET', '/search?term=test');

        $results = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute('results', $results);
        $this->assertObjectHasAttribute('more', $results);
        $this->assertCount(1, $results->results);
    }

}
