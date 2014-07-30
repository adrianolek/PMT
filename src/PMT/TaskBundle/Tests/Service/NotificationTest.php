<?php
namespace PMT\TaskBundle\Tests\Service;
use PMT\TaskBundle\Service\Notification;

/**
 * @coversDefaultClass PMT\TaskBundle\Service\Notification
 */
class NotificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Notification
     */
    protected $object;

    protected function setUp()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $mailer = $this->getMockBuilder('\Swift_Mailer')->disableOriginalConstructor()->getMock();
        $router = $this->getMockBuilder('Symfony\Component\Routing\Router')->disableOriginalConstructor()->getMock();
        $sc = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')->disableOriginalConstructor()->getMock();
        $this->object = new Notification($em, $mailer, $router, $sc, array('name' => 'PMT Notification', 'email' => 'noreply@pmt.local'));
    }

    /**
     * @covers ::notify
     * @expectedException \Exception
     * @expectedExceptionMessage Undefined status foo
     */
    public function testUndefinedStatus()
    {
        $task = $this->getMock('PMT\TaskBundle\Entity\Task');
        $this->object->notify('foo', $task);
    }
}
