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

        $client = static::createAuthClient(array('user' => 'user'));
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
            'project[name]' => '',
        ));

        $this->assertFalse($client->getResponse()->isRedirection());

        $client->submit($form, array(
            'project[name]' => 'Foo project',
        ));

        $this->assertTrue($client->getResponse()->isRedirect('/'));
    }

    public function testEditProject()
    {
        $client = static::createAuthClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('edit')->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Update')->form();

        $form['project[name]'] = '';

        $client->submit($form);

        $this->assertFalse($client->getResponse()->isRedirection());

        $form['project[name]'] = 'Bar project';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/'));
    }

    public function testDeleteProject()
    {
        $client = static::createAuthClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('edit')->last()->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('Delete')->link();

        $client->click($link);

        $this->assertTrue($client->getResponse()->isRedirect('/'));
    }
}
