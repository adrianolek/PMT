<?php

namespace PMT\UserBundle\Model;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\ORM\EntityManager;

class UserManager
{
    protected $encoderFactory;
    protected $em;
    protected $class;
    protected $repository;

    public function __construct(EncoderFactoryInterface $encoderFactory, EntityManager $em, $class)
    {
        $this->encoderFactory = $encoderFactory;
        $this->em = $em;
        $this->repository = $this->em->getRepository($class);
    }

    public function updatePassword(UserInterface $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    protected function getEncoder(UserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

}
