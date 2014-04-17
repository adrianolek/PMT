<?php 

namespace PMT\DoctrineExtensions\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class UTCDateTimeType extends DateTimeType
{
  static private $utc = null;

  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value === null) {
      return null;
    }

    /* @var $value \DateTime */

    $value->setTimezone($this->getUtc());
    return $value->format($platform->getDateTimeFormatString());

  }

  public function convertToPHPValue($value, AbstractPlatform $platform)
  {
    if ($value === null) {
      return null;
    }

    $val = \DateTime::createFromFormat(
        $platform->getDateTimeFormatString(),
        $value,
        $this->getUtc()
    );
    if (!$val) {
      throw ConversionException::conversionFailed($value, $this->getName());
    }
    return $val;
  }
  
  private function getUtc()
  {
      return (self::$utc) ? self::$utc : (self::$utc = new \DateTimeZone('UTC'));
  }
}