<?php

namespace PMT\ApiBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use PMT\ApiBundle\Security\Authentication\Token\ApiUserToken;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ApiListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;
    protected $em;
    protected $httpUtils;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, HttpUtils $httpUtils, EntityManager $em)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->httpUtils = $httpUtils;
        $this->em = $em;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if($this->httpUtils->checkRequestPath($request, '/api/token.json') && $request->get('username'))
        {
            $username = trim($request->get('username'));
            $password = $request->get('password');
            try {
              $token = $this->authenticationManager->authenticate(new UsernamePasswordToken($username, $password, 'api'));
            }
            catch(\Exception $e) {
            }

            if(isset($token) && $token->isAuthenticated())
            {
                $user = $token->getUser();
                $user->setApiKey(uniqid('', true));
                $this->em->persist($user);
                $this->em->flush();
                $response = new JsonResponse(array('token' => $user->getApiKey()));
                $response->setStatusCode(200);
                $event->setResponse($response);
                return;
            }
        }
        elseif($request->headers->has('x-auth-token') &&
          $user = $this->em->getRepository('PMTUserBundle:User')->findOneBy(array('api_key' => $request->headers->get('x-auth-token'))))
        {
            $token = new ApiUserToken($user->getRoles());
            $token->setUser($user);
            $this->securityContext->setToken($token);
            return;
        }



        if($request->getMethod() == 'OPTIONS')
        {
          $response = new Response();
        }
        else
        {
          $response = new JsonResponse(array('error' => 'forbidden'));
          $response->setStatusCode(403);
        }

        $event->setResponse($response);
    }
}