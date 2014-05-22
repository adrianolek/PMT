<?php
namespace PMT\TestBundle\Test;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    public static function createAuthClient(array $options = array(), array $server = array())
    {
        $server = array_merge($server, array(
            'PHP_AUTH_USER' => 'manager@pmt.test',
            'PHP_AUTH_PW' => 'manager'
        ));

        return parent::createClient($options, $server);
    }
}
