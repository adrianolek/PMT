<?php

namespace PMT\UserBundle\Tests\Controller;

use PMT\TestBundle\Test\WebTestCase;

class OrganizationControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->loadFixtures(array(
            'PMT\UserBundle\DataFixtures\ORM\LoadUserData'
        ));
    }

    public function testNew()
    {
        $client = static::createAuthClient();
        $crawler = $client->request('GET', '/organizations/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Create')->form();

        $client->submit($form, array(
            'organization[name]' => 'ACME',
        ));

        $this->assertTrue($client->getResponse()->isRedirect('/people'));
    }


}
