<?php

namespace PMT\FileBundle\Tests\Controller;

use Doctrine\ORM\EntityManager;
use PMT\TestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->loadFixtures(array(
            'PMT\UserBundle\DataFixtures\ORM\LoadUserData',
            'PMT\ProjectBundle\DataFixtures\ORM\LoadProjectData',
            'PMT\TaskBundle\DataFixtures\ORM\LoadTaskData',
            'PMT\FileBundle\DataFixtures\ORM\LoadFileData',
        ));
    }

    public function testAdd()
    {
        $client = static::createAuthClient();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->setMaxResults(1)->getQuery()->getSingleResult();

        $crawler = $client->request('GET', '/task/' . $task->getId() . '/file/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Upload')->form();

        $client->submit($form, array(
            'form[file]' => ''
        ));

        $this->assertFalse($client->getResponse()->isRedirection());

        $client->submit($form, array(
            'form[file]' => new UploadedFile(
                'src/PMT/FileBundle/DataFixtures/File/test.txt', 'test.txt'
            )
        ));

        $this->assertTrue($client->getResponse()->isRedirect('/project/' . $task->getProject()->getId() . '/task/' . $task->getId()));
    }

    public function testList()
    {
        $client = static::createAuthClient();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $task = $em->createQueryBuilder()->select('t')->from('PMT\TaskBundle\Entity\Task', 't')->setMaxResults(1)->getQuery()->getSingleResult();

        $client->request('GET', '/task/' . $task->getId() . '/files');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $client = static::createAuthClient();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $file = $em->createQueryBuilder()->select('f')->from('PMT\FileBundle\Entity\File', 'f')->getQuery()->getSingleResult();

        $client->request('GET', '/file/' . $file->getDownloadKey());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->has('X-Sendfile'));

        $client->request('GET', '/file/t/' . $file->getDownloadKey());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->has('X-Sendfile'));
    }

    public function testDelete()
    {
        $client = static::createAuthClient();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var $em EntityManager */
        $file = $em->createQueryBuilder()->select('f')->from('PMT\FileBundle\Entity\File', 'f')->getQuery()->getSingleResult();

        $client->request('GET', '/file/' . $file->getId() . '/delete');

        $this->assertTrue($client->getResponse()->isRedirect('/project/' . $file->getTask()->getProject()->getId() . '/task/' . $file->getTask()->getId()));
    }
}
