<?php
namespace PMT\TaskBundle\Tests\Entity;

use PMT\TaskBundle\Entity\TaskText;

/**
 * @coversDefaultClass PMT\TaskBundle\Entity\TaskText
 */
class TaskTextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TaskText
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new TaskText();
    }

    /**
     * @covers ::setTaskId
     * @covers ::getTaskId
     */
    public function testTaskId()
    {
        $value = 1;
        $this->object->setTaskId($value);
        $this->assertEquals($value, $this->object->getTaskId());
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
     * @covers ::setDescription
     * @covers ::getDescription
     */
    public function testDescription()
    {
        $value = 'foo';
        $this->object->setDescription($value);
        $this->assertEquals($value, $this->object->getDescription());
    }
}
