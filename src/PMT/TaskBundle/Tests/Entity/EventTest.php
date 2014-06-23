<?php
namespace PMT\TaskBundle\Tests\Entity;
use PMT\TaskBundle\Entity\Event;

/**
 * @coversDefaultClass PMT\TaskBundle\Entity\Event
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Event
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Event();
    }

    /**
     * @covers ::getId
     */
    public function testGetId()
    {
        $this->assertNull($this->object->getId());
    }

    /**
     * @covers ::setType
     * @covers ::getType
     */
    public function testType()
    {
        $value = 'task';
        $this->object->setType($value);
        $this->assertEquals($value, $this->object->getType());
    }

    /**
     * @covers ::setData
     * @covers ::getData
     */
    public function testData()
    {
        $value = array('foo' => 'bar');
        $this->object->setData($value);
        $this->assertEquals($value, $this->object->getData());
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
     * @covers ::setUser
     * @covers ::getUser
     */
    public function testUser()
    {
        $value = $this->getMock('PMT\UserBundle\Entity\User');
        $this->object->setUser($value);
        $this->assertEquals($value, $this->object->getUser());
    }

    /**
     * @covers ::setTask
     * @covers ::getTask
     */
    public function testTask()
    {
        $value = $this->getMock('PMT\TaskBundle\Entity\Task');
        $this->object->setTask($value);
        $this->assertEquals($value, $this->object->getTask());
    }
}
