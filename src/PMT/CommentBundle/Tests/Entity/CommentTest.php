<?php
namespace PMT\CommentBundle\Tests\Entity;
use PMT\CommentBundle\Entity\Comment;

/**
 * @coversDefaultClass PMT\CommentBundle\Entity\Comment
 */
class CommentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Comment
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Comment();
    }

    /**
     * @covers ::getId
     */
    public function testGetId()
    {
        $this->assertNull($this->object->getId());
    }

    /**
     * @covers ::setContent
     * @covers ::getContent
     */
    public function testSetContent()
    {
        $value = 'foo';
        $this->object->setContent($value);
        $this->assertEquals($value, $this->object->getContent());
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
     * @covers ::setTask
     * @covers ::getTask
     */
    public function testTask()
    {
        $value = $this->getMock('PMT\TaskBundle\Entity\Task');
        $this->object->setTask($value);
        $this->assertEquals($value, $this->object->getTask());
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
}
