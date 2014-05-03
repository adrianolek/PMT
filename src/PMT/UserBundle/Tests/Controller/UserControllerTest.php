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
       
        $client->request('GET', '/');
        
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }

}
