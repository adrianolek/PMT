<?php
namespace PMT\UserBundle\Tests\Entity;

use PMT\UserBundle\Entity\Organization;

/**
 * @coversDefaultClass PMT\UserBundle\Entity\Organization
 */
class OrganizationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Organization
     */
    protected $organization;

    protected function setUp()
    {
        $this->organization = new Organization();
    }

    /**
     * @covers ::getId
     */
    public function testGetId()
    {
        $this->assertNull($this->organization->getId());
    }

    /**
     * @covers ::setName
     * @covers ::getName
     */
    public function testName()
    {
        $value = 'Foo';
        $this->organization->setName($value);
        $this->assertEquals($value, $this->organization->getName());
    }
}
