<?php

namespace PMT\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use PMT\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $em)
    {
        $user = new User();
        $user->setRole('manager');
        $user->setEmail('manager@pmt.test');
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;
        $user->setPassword($encoder->encodePassword('manager', $user->getSalt()));
        $user->setApiKey('managerkey');

        $em->persist($user);

        $this->addReference('manager', $user);

        $user = new User();
        $user->setRole('user');
        $user->setEmail('user@pmt.test');
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;
        $user->setPassword($encoder->encodePassword('user', $user->getSalt()));
        $user->setApiKey('userkey');
        
        $em->persist($user);

        $em->flush();
    }
}
