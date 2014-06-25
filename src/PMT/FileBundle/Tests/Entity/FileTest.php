<?php
namespace PMT\FileBundle\Tests\Entity;
use PMT\FileBundle\Entity\File;

/**
 * @coversDefaultClass PMT\FileBundle\Entity\File
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var File
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new File();
    }

    /**
     * @covers ::setFile
     * @covers ::getFile
     */
    public function testFile()
    {
        $value = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()->getMock();
        $this->object->setFile($value);
        $this->assertEquals($value, $this->object->getFile());
    }

    /**
     * @covers ::getId
     */
    public function testGetId()
    {
        $this->assertNull($this->object->getId());
    }

    /**
     * @covers ::setPath
     * @covers ::getPath
     */
    public function testPath()
    {
        $value = 'foo';
        $this->object->setPath($value);
        $this->assertEquals($value, $this->object->getPath());
    }

    /**
     * @covers ::setMimeType
     * @covers ::getMimeType
     */
    public function testMimeType()
    {
        $value = 'foo';
        $this->object->setMimeType($value);
        $this->assertEquals($value, $this->object->getMimeType());
    }

    /**
     * @covers ::setSize
     * @covers ::getSize
     */
    public function testSize()
    {
        $value = 2048;
        $this->object->setSize($value);
        $this->assertEquals($value, $this->object->getSize());
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
     * @covers ::getUploadPath
     */
    public function testGetUploadPath()
    {
        $this->assertEquals('uploads/0/', $this->object->getUploadPath());
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
     * @covers ::setOriginalName
     */
    public function testSetOriginalName()
    {
        $value = 'foo';
        $file = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()->getMock();
        $file->expects($this->once())
            ->method('getClientOriginalName')
            ->will($this->returnValue($value));
        $this->object->setFile($file);
        $this->object->setOriginalName();

        $this->assertEquals($value, $this->object->getName());
    }

    /**
     * @covers ::updateDownloadKey
     * @covers ::setDownloadKey
     * @covers ::getDownloadKey
     */
    public function testUpdateDownloadKey()
    {
        $file = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()->getMock();
        $this->object->setFile($file);
        $this->object->updateDownloadKey();
        $this->assertNotEmpty($this->object->getDownloadKey());
    }

    /**
     * @covers ::createThumb
     */
    public function testCreateThumb()
    {
        $this->markTestIncomplete();
    }

    /**
     * @covers ::getThumbPath
     * @todo   Implement testGetThumbPath().
     */
    public function testGetThumbPath()
    {
        $this->object->setPath('foo.jpg');

        $this->assertEquals('./foo_thumb.jpg', $this->object->getThumbPath());
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
     * @covers ::isImage
     * @todo   Implement testIsImage().
     */
    public function testIsImage()
    {
        $this->object->setMimeType('image/png');
        $this->assertTrue($this->object->isImage());
        $this->object->setMimeType('text/plain');
        $this->assertFalse($this->object->isImage());
    }

    /**
     * @covers ::getExtension
     * @todo   Implement testGetExtension().
     */
    public function testGetExtension()
    {
        $this->object->setPath('foo.jpg');
        $this->assertEquals('jpg', $this->object->getExtension());
        $this->object->setPath('foo');
        $this->assertNull($this->object->getExtension());
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
