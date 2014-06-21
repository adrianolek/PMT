<?php
namespace PMT\TrackingBundle\Tests\Entity;
use PMT\TrackingBundle\Entity\Track;

/**
 * @coversDefaultClass PMT\TrackingBundle\Entity\Track
 */
class TrackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Track
     */
    protected $track;

    protected function setUp()
    {
        $this->track = new Track();
    }

    /**
     * @covers ::getId
     */
    public function testGetId()
    {
        $this->assertNull($this->track->getId());
    }

    /**
     * @covers ::setStartedAt
     * @covers ::getStartedAt
     */
    public function testStartedAt()
    {
        $value = new \DateTime();
        $this->track->setStartedAt($value);
        $this->assertEquals($value, $this->track->getStartedAt());
    }

    /**
     * @covers ::setEndedAt
     * @covers ::getEndedAt
     */
    public function testEndedAt()
    {
        $value = new \DateTime();
        $this->track->setEndedAt($value);
        $this->assertEquals($value, $this->track->getEndedAt());
    }

    /**
     * @covers ::setDescription
     * @covers ::getDescription
     */
    public function testDescription()
    {
        $value = 'foo';
        $this->track->setDescription($value);
        $this->assertEquals($value, $this->track->getDescription());
    }

    /**
     * @covers ::getDuration
     */
    public function testDuration()
    {
        $start = new \DateTime('2000-02-20 10:00:00');
        $end = new \DateTime('2000-02-20 11:00:00');
        $this->track->setStartedAt($start);
        $this->track->setEndedAt($end);

        $this->assertEquals(3600, $this->track->getDuration());
    }

    /**
     * @covers ::setCreatedAt
     * @covers ::getCreatedAt
     */
    public function testCreatedAt()
    {
        $value = new \DateTime();
        $this->track->setCreatedAt($value);
        $this->assertEquals($value, $this->track->getCreatedAt());
    }

    /**
     * @covers ::setUpdatedAt
     * @covers ::getUpdatedAt
     */
    public function testUpdatedAt()
    {
        $value = new \DateTime();
        $this->track->setUpdatedAt($value);
        $this->assertEquals($value, $this->track->getUpdatedAt());
    }

    /**
     * @covers ::setDeletedAt
     * @covers ::getDeletedAt
     */
    public function testDeletedAt()
    {
        $value = new \DateTime();
        $this->track->setDeletedAt($value);
        $this->assertEquals($value, $this->track->getDeletedAt());
    }

    /**
     * @covers ::setUser
     * @covers ::getUser
     */
    public function testUser()
    {
        $value = $this->getMock('PMT\UserBundle\Entity\User');
        $this->track->setUser($value);
        $this->assertEquals($value, $this->track->getUser());
    }

    /**
     * @covers ::setTask
     * @covers ::getTask
     */
    public function testTask()
    {
        $value = $this->getMock('PMT\TaskBundle\Entity\Task');
        $this->track->setTask($value);
        $this->assertEquals($value, $this->track->getTask());
    }

    /**
     * @covers ::getDate
     * @covers ::setDate
     */
    public function testDate()
    {
        $value = '2000-02-20';
        $this->track->setDate($value);
        $this->assertEquals($value, $this->track->getDate());
    }

    /**
     * @covers ::getStartTime
     * @covers ::setStartTime
     */
    public function testStartTime()
    {
        $value = '10:00';
        $this->track->setStartTime($value);
        $this->assertEquals($value, $this->track->getStartTime());
    }

    /**
     * @covers ::getEndTime
     * @covers ::setEndTime
     */
    public function testEndTime()
    {
        $value = '12:00';
        $this->track->setEndTime($value);
        $this->assertEquals($value, $this->track->getEndTime());
    }

    /**
     * @covers ::setTimeRange
     */
    public function testSetTimeRange()
    {
        $start = new \DateTime('2000-02-20 12:00:00');
        $end = new \DateTime('2000-02-21 10:00:00');
        $this->track->setDate($start->format('Y-m-d'));
        $this->track->setStartTime($start->format('H:i'));
        $this->track->setEndTime($end->format('H:i'));
        $this->track->setTimeRange();

        $this->assertEquals($start, $this->track->getStartedAt());
        $this->assertEquals($end, $this->track->getEndedAt());
    }

    /**
     * @covers ::loadTimeRange
     */
    public function testLoadTimeRange()
    {
        $start = new \DateTime('2000-02-20 12:00:00');
        $end = new \DateTime('2000-02-21 10:00:00');
        $this->track->setStartedAt($start);
        $this->track->setEndedAt($end);
        $this->track->loadTimeRange();
    }
}
