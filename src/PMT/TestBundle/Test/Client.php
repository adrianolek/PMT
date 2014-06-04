<?php
namespace PMT\TestBundle\Test;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;

class Client extends BaseClient
{
    public function jsonRequest($method, $uri, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
    {
        $server['HTTP_Content-Type'] = 'application/json';

        return parent::request($method, $uri, $parameters, $files, $server, $content, $changeHistory);
    }
}
