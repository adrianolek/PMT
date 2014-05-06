<?php

namespace PMT\UserBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testLogin()
    {
        $this->loadFixtures(array(
            'PMT\UserBundle\DataFixtures\ORM\LoadUserData'
        ));

        $client = static::createClient();
       
        $crawler = $client->request('GET', '/');
        
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
        
        $crawler = $client->followRedirect();

        $form = $crawler->selectButton('Log in')->form();
        
        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertRegExp(
            '/Bad credentials/',
            $client->getResponse()->getContent()
        );
        
        $form['_username'] = 'manager@pmt';
        $form['_password'] = 'manager';

        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/'));
        $this->assertTrue($client->getContainer()->get('security.context')->isGranted('ROLE_MANAGER'));
    }
    
    public function testIndex()
    {
        $client = static::createClient(array(), array('PHP_AUTH_USER' => 'manager@pmt', 'PHP_AUTH_PW' => 'manager'));
        $crawler = $client->request('GET', '/people');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        return $crawler->selectLink('Add user')->link();
    }

    /**
     * @depends testIndex
     */
    public function testAddUser($link)
    {
        $client = static::createClient(array(), array('PHP_AUTH_USER' => 'manager@pmt', 'PHP_AUTH_PW' => 'manager'));
        $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    

}
