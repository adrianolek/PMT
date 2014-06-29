<?php
namespace PMT\FileBundle\Tests\Twig;

use PMT\FileBundle\Twig\FileExtension;

/**
 * @coversDefaultClass PMT\FileBundle\Twig\FileExtension
 */
class FileExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileExtension
     */
    protected $object;

    /**
     * @covers ::__construct()
     */
    protected function setUp()
    {
        $router = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Routing\Router')
          ->disableOriginalConstructor()->getMock();

        $router->expects($this->any())->method('generate')->will($this->returnValue('route'));
        $this->object = new FileExtension($router);
    }

    /**
     * @covers ::getName
     */
    public function testGetName()
    {
        $this->assertEquals('file', $this->object->getName());
    }

    /**
     * @covers ::getFunctions
     */
    public function testGetFunctions()
    {
        $functions = $this->object->getFunctions();

        $this->assertContainsOnlyInstancesOf('Twig_SimpleFunction', $functions);
    }

    /**
     * @covers ::getThumbPath
     */
    public function testImage()
    {
        $file = $this->getMock('PMT\FileBundle\Entity\File');
        $file->expects($this->once())->method('isImage')->will($this->returnValue(true));
        $path = $this->object->getThumbPath($file);
        $this->assertEquals('route', $path);
    }

    /**
     * @covers ::getThumbPath
     */
    public function testKnownExtension()
    {
        $file = $this->getMock('PMT\FileBundle\Entity\File');
        $file->expects($this->once())->method('isImage')->will($this->returnValue(false));
        $file->expects($this->exactly(2))->method('getExtension')->will($this->returnValue('doc'));
        $path = $this->object->getThumbPath($file);
        $this->assertEquals('/images/types/doc.png', $path);
    }

    /**
     * @covers ::getThumbPath
     */
    public function testUnknownExtension()
    {
        $file = $this->getMock('PMT\FileBundle\Entity\File');
        $file->expects($this->once())->method('isImage')->will($this->returnValue(false));
        $file->expects($this->once())->method('getExtension')->will($this->returnValue('zzz'));
        $path = $this->object->getThumbPath($file);
        $this->assertEquals('/images/types/other.png', $path);
    }
}
