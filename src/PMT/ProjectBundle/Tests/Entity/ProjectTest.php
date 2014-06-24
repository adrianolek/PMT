<?php
namespace PMT\ProjectBundle\Tests\Entity;

use PMT\ProjectBundle\Entity\Project;

/**
 * @coversDefaultClass PMT\ProjectBundle\Entity\Project
 */
class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Project
     */
    protected $object;

    /**
     * @covers ::__construct
     */
    protected function setUp()
    {
        $this->object = new Project();
    }

    /**
     * @covers ::getId
     */
    public function testGetId()
    {
        $this->assertNull($this->object->getId());
    }

    /**
     * @covers ::setName
     * @covers ::getName
     */
    public function testName()
    {
        $value = 'foo';
        $this->object->setName($value);
        $this->assertEquals($value, $this->object->getName());
    }

    /**
     * @covers ::setCreatedAt
     * @covers ::getCreatedAt
     */
    public function testCreatedAt()
    {
        $value = new \DateTime();
        $this->object->setCreatedAt($value);
        $this->assertEquals($value, $this->object->getCreatedAt());
    }

    /**
     * @covers ::setUpdatedAt
     * @covers ::getUpdatedAt
     */
    public function testUpdatedAt()
    {
        $value = new \DateTime();
        $this->object->setUpdatedAt($value);
        $this->assertEquals($value, $this->object->getUpdatedAt());
    }

    /**
     * @covers ::setDeletedAt
     * @covers ::getDeletedAt
     */
    public function testDeletedAt()
    {
        $value = new \DateTime();
        $this->object->setDeletedAt($value);
        $this->assertEquals($value, $this->object->getDeletedAt());
    }

    /**
     * @covers ::__toString
     */
    public function test__toString()
    {
        $value = 'foo';
        $this->object->setName($value);
        $this->assertEquals($value, $this->object->__toString());
    }

    /**
     * @covers ::addTask
     * @covers ::removeTask
     * @covers ::getTasks
     */
    public function testTask()
    {
        $task = $this->getMock('PMT\TaskBundle\Entity\Task');
        $this->object->addTask($task);
        $this->assertTrue($this->object->getTasks()->contains($task));
        $this->object->removeTask($task);
        $this->assertFalse($this->object->getTasks()->contains($task));
    }

    /**
     * @covers ::addAssignedUser
     * @covers ::removeAssignedUser
     * @covers ::getAssignedUsers
     */
    public function testAssignedUser()
    {
        $user = $this->getMock('PMT\UserBundle\Entity\User');
        $this->object->addAssignedUser($user);
        $this->assertTrue($this->object->getAssignedUsers()->contains($user));
        $this->object->removeAssignedUser($user);
        $this->assertFalse($this->object->getAssignedUsers()->contains($user));
    }
}
