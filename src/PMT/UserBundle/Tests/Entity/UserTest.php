<?php
namespace PMT\UserBundle\Tests\Entity;
use PMT\UserBundle\Entity\User;

/**
 * @coversDefaultClass PMT\UserBundle\Entity\User
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @covers ::__construct
     */
    protected function setUp()
    {
        $this->user = new User();
    }

    /**
     * @covers ::__toString
     */
    public function test__toString()
    {
        $this->user->setFirstName('Foo');
        $this->user->setLastName('Bar');

        $this->assertEquals('Foo Bar', $this->user->__toString());
    }

    /**
     * @covers ::getId
     */
    public function testGetId()
    {
        $this->assertNull($this->user->getId());
    }

    /**
     * @covers ::setCreatedAt
     * @covers ::getCreatedAt
     */
    public function testCreatedAt()
    {
        $value = new \DateTime();
        $this->user->setCreatedAt($value);
        $this->assertEquals($value, $this->user->getCreatedAt());
    }

    /**
     * @covers ::setUpdatedAt
     * @covers ::getUpdatedAt
     */
    public function testUpdatedAt()
    {
        $value = new \DateTime();
        $this->user->setUpdatedAt($value);
        $this->assertEquals($value, $this->user->getUpdatedAt());
    }

    /**
     * @covers ::setDeletedAt
     * @covers ::getDeletedAt
     */
    public function testDeletedAt()
    {
        $value = new \DateTime();
        $this->user->setDeletedAt($value);
        $this->assertEquals($value, $this->user->getDeletedAt());
    }

    /**
     * @covers ::setEmail
     * @covers ::getEmail
     */
    public function testEmail()
    {
        $value = 'foo@pmt.test';
        $this->user->setEmail($value);
        $this->assertEquals($value, $this->user->getEmail());
    }

    /**
     * @covers ::setLastName
     * @covers ::getLastName
     */
    public function testLastName()
    {
        $value = 'Foo';
        $this->user->setLastName($value);
        $this->assertEquals($value, $this->user->getLastName());
    }

    /**
     * @covers ::setFirstName
     * @covers ::getFirstName
     */
    public function testFirstName()
    {
        $value = 'Foo';
        $this->user->setFirstName($value);
        $this->assertEquals($value, $this->user->getFirstName());
    }

    /**
     * @covers ::setPassword
     * @covers ::getPassword
     */
    public function testPassword()
    {
        $value = 'foo';
        $this->user->setPassword($value);
        $this->assertEquals($value, $this->user->getPassword());
    }

    /**
     * @covers ::getFullName
     */
    public function testGetFullName()
    {
        $this->user->setFirstName('Foo');
        $this->user->setLastName('Bar');

        $this->assertEquals('Foo Bar', $this->user->getFullName());
    }

    /**
     * @covers ::getPlainPassword
     * @covers ::setPlainPassword
     */
    public function testPlainPassword()
    {
        $value = 'foo';
        $this->user->setPlainPassword($value);
        $this->assertEquals($value, $this->user->getPlainPassword());
    }

    /**
     * @covers ::getRoles
     */
    public function testGetRoles()
    {
        $this->user->setRole('user');
        $this->assertEquals(array('ROLE_USER'), $this->user->getRoles());
        $this->user->setRole('manager');
        $this->assertEquals(array('ROLE_USER', 'ROLE_MANAGER'), $this->user->getRoles());
    }

    /**
     * @covers ::getUsername
     */
    public function testGetUsername()
    {
        $value = 'foo@pmt.test';
        $this->user->setEmail($value);
        $this->assertEquals($value, $this->user->getUsername());
    }

    /**
     * @covers ::eraseCredentials
     */
    public function testEraseCredentials()
    {
        $this->user->setPlainPassword('foo');
        $this->user->eraseCredentials();
        $this->assertNull($this->user->getPlainPassword());
    }

    /**
     * @covers ::getSalt
     */
    public function testGetSalt()
    {
        $this->assertNull($this->user->getSalt());
    }

    /**
     * @covers ::serialize
     * @covers ::unserialize
     */
    public function testSerialize()
    {
        $user = new User();
        $serialized = $user->serialize();

        $unserialized = new User();
        $unserialized->unserialize($serialized);

        $this->assertEquals($user, $unserialized);
    }

    /**
     * @covers ::addTask
     * @covers ::removeTask
     * @covers ::getTasks
     */
    public function testTasks()
    {
        $task = $this->getMock('PMT\TaskBundle\Entity\Task');
        $this->user->addTask($task);
        $this->assertTrue($this->user->getTasks()->contains($task));
        $this->user->removeTask($task);
        $this->assertFalse($this->user->getTasks()->contains($task));
    }

    /**
     * @covers ::addComment
     * @covers ::removeComment
     * @covers ::getComments
     */
    public function testComments()
    {
        $comment = $this->getMock('PMT\CommentBundle\Entity\Comment');
        $this->user->addComment($comment);
        $this->assertTrue($this->user->getComments()->contains($comment));
        $this->user->removeComment($comment);
        $this->assertFalse($this->user->getComments()->contains($comment));
    }

    /**
     * @covers ::addFile
     * @covers ::removeFile
     * @covers ::getFiles
     */
    public function testFiles()
    {
        $file = $this->getMock('PMT\FileBundle\Entity\File');
        $this->user->addFile($file);
        $this->assertTrue($this->user->getFiles()->contains($file));
        $this->user->removeFile($file);
        $this->assertFalse($this->user->getFiles()->contains($file));
    }

    /**
     * @covers ::addTrack
     * @covers ::removeTrack
     * @covers ::getTracks
     */
    public function testTracks()
    {
        $track = $this->getMock('PMT\TrackingBundle\Entity\Track');
        $this->user->addTrack($track);
        $this->assertTrue($this->user->getTracks()->contains($track));
        $this->user->removeTrack($track);
        $this->assertFalse($this->user->getTracks()->contains($track));
    }

    /**
     * @covers ::setApiKey
     * @covers ::getApiKey
     */
    public function testApiKey()
    {
        $value = 'foo';
        $this->user->setApiKey($value);
        $this->assertEquals($value, $this->user->getApiKey());
    }

    /**
     * @covers ::addEvent
     * @covers ::removeEvent
     * @covers ::getEvents
     */
    public function testEvents()
    {
        $event = $this->getMock('PMT\TaskBundle\Entity\Event');
        $this->user->addEvent($event);
        $this->assertTrue($this->user->getEvents()->contains($event));
        $this->user->removeEvent($event);
        $this->assertFalse($this->user->getEvents()->contains($event));
    }

    /**
     * @covers ::setRole
     * @covers ::getRole
     */
    public function testRole()
    {
        $value = 'manager';
        $this->user->setRole($value);
        $this->assertEquals($value, $this->user->getRole());
    }

    /**
     * @covers ::getRoleOptions
     */
    public function testGetRoleOptions()
    {
        $value = User::getRoleOptions();

        $this->assertNotEmpty($value);
    }
}
