<?php
namespace PMT\DoctrineExtensions\Tests\DBAL\Types;
use Doctrine\DBAL\Types\Type;
use Doctrine\Tests\DBAL\Mocks;

require_once __DIR__ . '/../../../../../../vendor/doctrine/dbal/tests/Doctrine/Tests/TestInit.php';

/**
 * @coversDefaultClass PMT\DoctrineExtensions\DBAL\Types\UTCDateTimeType
 */
class UTCDateTimeTypeTest extends \PHPUnit_Framework_TestCase
{
    protected $_platform;

    /**
     * @var \PMT\DoctrineExtensions\DBAL\Types\UTCDateTimeType
     */
    protected $_type;

    protected function setUp()
    {
        $this->_platform = new \Doctrine\Tests\DBAL\Mocks\MockPlatform();
        if (!Type::hasType('utcdatetime')) {
            Type::addType('utcdatetime', 'PMT\DoctrineExtensions\DBAL\Types\UTCDateTimeType');
        }
        $this->_type = Type::getType('utcdatetime');
    }

    /**
     * @covers ::convertToDatabaseValue
     */
    public function testConvertToDatabaseValue()
    {
        $date = new \DateTime('1985-09-01 10:10:10', new \DateTimeZone('Europe/Warsaw'));

        $expected = '1985-09-01 08:10:10';
        $actual = $this->_type->convertToDatabaseValue($date, $this->_platform);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers ::convertToPHPValue
     */
    public function testConvertToPHPValue()
    {
        $date = $this->_type->convertToPHPValue('1985-09-01 10:10:10', $this->_platform);
        $this->assertInstanceOf('DateTime', $date);
        $this->assertEquals('1985-09-01 10:10:10', $date->format('Y-m-d H:i:s'));
        $this->assertEquals('UTC', $date->getTimezone()->getName());
    }

    public function testInvalidDateTimeFormatConversion()
    {
        $this->setExpectedException('Doctrine\DBAL\Types\ConversionException');
        $this->_type->convertToPHPValue('abcdefg', $this->_platform);
    }

    public function testNullConversion()
    {
        $this->assertNull($this->_type->convertToPHPValue(null, $this->_platform));
        $this->assertNull($this->_type->convertToDatabaseValue(null, $this->_platform));
    }
}
