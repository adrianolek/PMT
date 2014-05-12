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

    public function testAddProject()
    {
        $client = static::createAuthClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Add project')->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Create')->form();

        $client->submit($form, array(
            'project[name]' => 'Foo project',
        ));

        $this->assertTrue($client->getResponse()->isRedirect('/'));
    }
}
