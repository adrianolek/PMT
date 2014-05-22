<?php
namespace PMT\ApiBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use PMT\ApiBundle\Security\Authentication\Token\ApiUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;

class ApiProvider extends DaoAuthenticationProvider
{
    public function supports(TokenInterface $token)
    {
        return $token instanceof ApiUserToken || $token->getProviderKey() == 'api';
    }
}
