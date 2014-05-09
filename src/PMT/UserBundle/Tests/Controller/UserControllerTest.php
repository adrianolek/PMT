<?php

namespace PMT\UserBundle\Tests\Controller;

use PMT\TestBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->loadFixtures(array(
            'PMT\UserBundle\DataFixtures\ORM\LoadUserData'
        ));
    }
    
    public function testLogin()
    {
        $client = static::createClient();
       
        $client->request('GET', '/');
        
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
        
        $crawler = $client->followRedirect();

        $form = $crawler->selectButton('Log in')->form();
        
        $client->submit($form);
        $client->followRedirect();

        $this->assertRegExp(
            '/Bad credentials/',
            $client->getResponse()->getContent()
        );
        
        $form['_username'] = 'manager@pmt.test';
        $form['_password'] = 'manager';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/'));
        $this->assertTrue($client->getContainer()->get('security.context')->isGranted('ROLE_MANAGER'));
    }
    
    public function testIndex()
    {
        $client = static::createAuthClient();
        $client->request('GET', '/people');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddUser()
    {
        $client = static::createAuthClient();
        $crawler = $client->request('GET', '/people');
        $link = $crawler->selectLink('Add user')->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $form = $crawler->selectButton('Create')->form();
        
        $client->submit($form, array(
            'user[email]' => 'test@test.com',
            'user[last_name]' => 'test',
            'user[first_name]' => 'test',
            'user[plain_password]'  => 'test',
        ));

        $this->assertTrue($client->getResponse()->isRedirect('/people'));
    }
    
    public function testEditUser()
    {
        $client = static::createAuthClient();
        $crawler = $client->request('GET', '/people');
        $link = $crawler->selectLink('edit')->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Update')->form();
        $form['user[first_name]'] = 'Foo';
        $form['user[last_name]'] = 'Bar';
        
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/people'));
    }
    

}
