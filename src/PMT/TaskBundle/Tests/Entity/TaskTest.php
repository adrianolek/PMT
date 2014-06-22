<?php
namespace PMT\TaskBundle\Tests\Entity;
use PMT\TaskBundle\Entity\Task;

/**
 * @coversDefaultClass PMT\TaskBundle\Entity\Task
 */
class TaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Task
     */
    protected $task;

    /**
     * @covers ::__construct()
     */
    protected function setUp()
    {
        $this->task = new Task;
    }

    /**
     * @covers ::getId
     */
    public function testGetId()
    {
        $this->assertNull($this->task->getId());
    }

    /**
     * @covers ::setName
     * @covers ::getName
     */
    public function testName()
    {
        $value = 'foo';
        $this->task->setName($value);
        $this->assertEquals($value, $this->task->getName());
    }

    /**
     * @covers ::setDescription
     * @covers ::getDescription
     */
    public function testDescription()
    {
        $value = 'foo';
        $this->task->setDescription($value);
        $this->assertEquals($value, $this->task->getDescription());
    }

    /**
     * @covers ::setStatus
     * @covers ::getStatus
     */
    public function testStatus()
    {
        $value = 'done';
        $this->task->setStatus($value);
        $this->assertEquals($value, $this->task->getStatus());
    }

    /**
     * @covers ::setCategory
     * @covers ::getCategory
     */
    public function testCategory()
    {
        $value = 'bug';
        $this->task->setCategory($value);
        $this->assertEquals($value, $this->task->getCategory());
    }

    /**
     * @covers ::setProgress
     * @covers ::getProgress
     */
    public function testProgress()
    {
        $value = '25';
        $this->task->setProgress($value);
        $this->assertEquals($value, $this->task->getProgress());
    }

    /**
     * @covers ::setEstimatedTime
     * @covers ::getEstimatedTime
     */
    public function testEstimatedTime()
    {
        $value = 60;
        $this->task->setEstimatedTime($value);
        $this->assertEquals($value, $this->task->getEstimatedTime());
    }

    /**
     * @covers ::setCreatedAt
     * @covers ::getCreatedAt
     */
    public function testCreatedAt()
    {
        $value = new \DateTime();
        $this->task->setCreatedAt($value);
        $this->assertEquals($value, $this->task->getCreatedAt());
    }

    /**
     * @covers ::setUpdatedAt
     * @covers ::getUpdatedAt
     */
    public function testUpdatedAt()
    {
        $value = new \DateTime();
        $this->task->setUpdatedAt($value);
        $this->assertEquals($value, $this->task->getUpdatedAt());
    }

    /**
     * @covers ::setDeletedAt
     * @covers ::getDeletedAt
     */
    public function testDeletedAt()
    {
        $value = new \DateTime();
        $this->task->setDeletedAt($value);
        $this->assertEquals($value, $this->task->getDeletedAt());
    }

    /**
     * @covers ::setParent
     * @covers ::getParent
     */
    public function testParent()
    {
        $value = $this->getMock('PMT\ProjectBundle\Entity\Project');
        $this->task->setParent($value);
        $this->assertEquals($value, $this->task->getParent());
    }

    /**
     * @covers ::setProject
     * @covers ::getProject
     */
    public function testProject()
    {
        $value = $this->getMock('PMT\ProjectBundle\Entity\Project');
        $this->task->setProject($value);
        $this->assertEquals($value, $this->task->getProject());
    }

    /**
     * @covers ::__toString
     */
    public function test__toString()
    {
        $value = 'foo';
        $this->task->setName($value);
        $this->assertEquals($value, $this->task->__toString());
    }

    /**
     * @covers ::setUser
     * @covers ::getUser
     */
    public function testUser()
    {
        $value = $this->getMock('PMT\UserBundle\Entity\User');
        $this->task->setUser($value);
        $this->assertEquals($value, $this->task->getUser());
    }

    /**
     * @covers ::addComment
     * @covers ::removeComment
     * @covers ::getComments
     * @covers ::getCommentsCount
     */
    public function testAddComment()
    {
        $comment = $this->getMock('PMT\CommentBundle\Entity\Comment');
        $this->task->addComment($comment);
        $this->assertTrue($this->task->getComments()->contains($comment));
        $this->assertEquals(1, $this->task->getCommentsCount());
        $this->task->removeComment($comment);
        $this->assertFalse($this->task->getComments()->contains($comment));
    }

    /**
     * @covers ::addFile
     * @covers ::removeFile
     * @covers ::getFiles
     * @covers ::getFilesCount
     */
    public function testAddFile()
    {
        $file = $this->getMock('PMT\FileBundle\Entity\File');
        $this->task->addFile($file);
        $this->assertTrue($this->task->getFiles()->contains($file));
        $this->assertEquals(1, $this->task->getFilesCount());
        $this->task->removeFile($file);
        $this->assertFalse($this->task->getFiles()->contains($file));
    }

    /**
     * @covers ::addTrack
     * @covers ::removeTrack
     * @covers ::getTracks
     */
    public function testAddTrack()
    {
        $track = $this->getMock('PMT\TrackingBundle\Entity\Track');
        $this->task->addTrack($track);
        $this->assertTrue($this->task->getTracks()->contains($track));
        $this->task->removeTrack($track);
        $this->assertFalse($this->task->getTracks()->contains($track));
    }

    /**
     * @covers ::addAssignedUser
     * @covers ::removeAssignedUser
     * @covers ::getAssignedUsers
     */
    public function testAddAssignedUser()
    {
        $user = $this->getMock('PMT\UserBundle\Entity\User');
        $this->task->addAssignedUser($user);
        $this->assertTrue($this->task->getAssignedUsers()->contains($user));
        $this->task->removeAssignedUser($user);
        $this->assertFalse($this->task->getAssignedUsers()->contains($user));
    }

    /**
     * @covers ::getEstimatedTimeHours
     * @covers ::setEstimatedTimeHours
     */
    public function testGetEstimatedTimeHours()
    {
        $value = 4;
        $this->task->setEstimatedTimeHours($value);
        $this->assertEquals($value, $this->task->getEstimatedTimeHours());
    }

    /**
     * @covers ::setPosition
     * @covers ::getPosition
     */
    public function testPosition()
    {
        $value = 20;
        $this->task->setPosition($value);
        $this->assertEquals($value, $this->task->getPosition());
    }

    /**
     * @covers ::getCategoryOptions
     */
    public function testGetCategoryOptions()
    {
        $value = Task::getCategoryOptions();
        $this->assertNotEmpty($value);
    }

    /**
     * @covers ::getStatusOptions
     */
    public function testGetStatusOptions()
    {
        $value = Task::getStatusOptions();
        $this->assertNotEmpty($value);
    }

    /**
     * @covers ::setPriority
     * @covers ::getPriority
     */
    public function testPriority()
    {
        $value = 50;
        $this->task->setPriority($value);
        $this->assertEquals($value, $this->task->getPriority());
    }

    /**
     * @covers ::getPriorityColor
     * @dataProvider getPriorityColorData
     */
    public function testGetPriorityColor($priority, $color)
    {
        $this->task->setPriority($priority);
        $this->assertEquals($color, $this->task->getPriorityColor());
    }

    public function getPriorityColorData()
    {
        return array(
            array(100, 'rgb(255,55,0)'),
            array(50, 'rgb(255,155,75)'),
            array(0, 'rgb(255,255,150)'),
        );
    }

    /**
     * @covers ::getProjectName
     */
    public function testGetProjectName()
    {
        $value = 'foo';
        $project = $this->getMock('PMT\ProjectBundle\Entity\Project');
        $project->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($value));

        $this->task->setProject($project);
        $this->assertEquals($value, $this->task->getProjectName());
    }

    /**
     * @covers ::getCategoryName
     */
    public function testGetCategoryName()
    {
        $this->task->setCategory('feature');
        $this->assertEquals('feature', $this->task->getCategoryName());
    }

    /**
     * @covers ::getStatusName
     */
    public function testGetStatusName()
    {
        $this->task->setStatus('in_progress');
        $this->assertEquals('in progress', $this->task->getStatusName());
    }

    /**
     * @covers ::getAdvanceStatuses
     */
    public function testGetAdvanceStatuses()
    {
        $this->task->setStatus('in_progress');
        $value = $this->task->getAdvanceStatuses();
        $this->assertCount(4, $value);
    }

    /**
     * @covers ::getRecedeStatuses
     */
    public function testGetRecedeStatuses()
    {
        $this->task->setStatus('in_progress');
        $value = $this->task->getRecedeStatuses();
        $this->assertCount(2, $value);
    }
}
