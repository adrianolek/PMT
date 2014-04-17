<?php
namespace PMT\ApiBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use PMT\ApiBundle\Security\Authentication\Token\ApiUserToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;

class ApiProvider extends DaoAuthenticationProvider
{
    public function supports(TokenInterface $token)
    {
        return $token instanceof ApiUserToken || $token->getProviderKey() == 'api';
    }
}