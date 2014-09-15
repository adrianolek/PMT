<?php

namespace PMT\CommentBundle\Tests\Controller;

use Doctrine\ORM\EntityManager;
use PMT\TestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CommentControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->loadFixtures(array(
            'PMT\UserBundle\DataFixtures\ORM\LoadUserData',
            'PMT\ProjectBundle\DataFixtures\ORM\LoadProjectData',
            'PMT\TaskBundle\DataFixtures\ORM\LoadTaskData',
        ));
    }

    public function testAdd()
    {
        $client = static::createAuthClient();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->setMaxResults(1)->getQuery()->getSingleResult();

        $crawler = $client->request('GET', '/task/' . $task->getId() . '/comment/new');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Create')->form();

        $client->submit($form, array(
            'comment[content]' => '',
        ));

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $client->getResponse()->getStatusCode());

        $client->submit($form, array(
            'comment[content]' => 'Foo Bar',
        ));

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }
}
