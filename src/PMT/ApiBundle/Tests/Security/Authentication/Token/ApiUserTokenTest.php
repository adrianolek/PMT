<?php
namespace PMT\ApiBundle\Tests\Security\Authentication\Token;

use PMT\ApiBundle\Security\Authentication\Token\ApiUserToken;

/**
 * @coversDefaultClass PMT\ApiBundle\Security\Authentication\Token\ApiUserToken
 */
class ApiUserTokenTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $token = new ApiUserToken();
        $this->assertFalse($token->isAuthenticated());

        $token = new ApiUserToken(array('ROLE_USER'));
        $this->assertTrue($token->isAuthenticated());
    }

    /**
     * @covers ::getCredentials
     */
    public function testGetCredentials()
    {
        $token = new ApiUserToken();
        $this->assertEmpty($token->getCredentials());
    }

}
