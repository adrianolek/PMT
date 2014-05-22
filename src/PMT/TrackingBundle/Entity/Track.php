<?php

namespace PMT\TrackingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Track
 *
 * @ORM\Table(name="pmt_tracks")
 * @ORM\Entity(repositoryClass="PMT\TrackingBundle\Entity\TrackRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\HasLifecycleCallbacks()
 */
class Track
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ended_at", type="datetime", nullable=true)
     */
    private $endedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity="\PMT\UserBundle\Entity\User", inversedBy="tracks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="\PMT\TaskBundle\Entity\Task", inversedBy="tracks")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    private $task;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startedAt
     *
     * @param  \DateTime $startedAt
     * @return Track
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;

        $tz = new \DateTimeZone(date_default_timezone_get());
        $this->startTime = $startedAt->setTimezone($tz)->format('H:i:s');
        $this->date = $this->getStartedAt()->setTimezone($tz)->format('Y-m-d');

        return $this;
    }

    /**
     * Get startedAt
     *
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set endedAt
     *
     * @param  \DateTime $endedAt
     * @return Track
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;

        $tz = new \DateTimeZone(date_default_timezone_get());
        $this->endTime = $endedAt->setTimezone($tz)->format('H:i:s');

        return $this;
    }

    /**
     * Get endedAt
     *
     * @return \DateTime
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return Track
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function getDuration()
    {
        return $this->getEndedAt()->getTimestamp() - $this->getStartedAt()->getTimestamp();
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Track
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param  \DateTime $updatedAt
     * @return Track
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param  \DateTime $deletedAt
     * @return Track
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set user
     *
     * @param  \PMT\UserBundle\Entity\User $user
     * @return Track
     */
    public function setUser(\PMT\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PMT\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set task
     *
     * @param  \PMT\TaskBundle\Entity\Task $task
     * @return Track
     */
    public function setTask(\PMT\TaskBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \PMT\TaskBundle\Entity\Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @Assert\NotBlank
     * @Assert\Regex(pattern="/^\d{4}-\d{2}-\d{2}$/")
     */
    private $date;

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($v)
    {
        $this->date = $v;
    }

    /**
     * @Assert\NotBlank
     * @Assert\Regex(pattern="/^\d{2}:\d{2}(:\d{2})?$/")
     */
    private $startTime;

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime($v)
    {
        $this->startTime = $v;
    }

    /**
     * @Assert\NotBlank
     * @Assert\Regex(pattern="/^\d{2}:\d{2}(:\d{2})?$/")
     */
    private $endTime;
    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setEndTime($v)
    {
        return $this->endTime = $v;
    }

    /**
     * @ORM\PreFlush()
     */
    public function setTimeRange()
    {
        if (isset($this->date) && $this->getStartTime() && $this->getEndTime()) {
            $this->setStartedAt(new \DateTime($this->date.' '.$this->getStartTime()));
            $this->setEndedAt(new \DateTime($this->date.' '.$this->getEndTime()));

            if ($this->getStartedAt()->getTimestamp() > $this->getEndedAt()->getTimestamp()) {
                $this->getEndedAt()->add(new \DateInterval('P1D'));
            }
        }
    }

    /**
     * @ORM\PostLoad()
     */
    public function loadTimeRange()
    {
        $tz = new \DateTimeZone(date_default_timezone_get());

        if ($this->getStartedAt()) {
            $this->startTime = $this->getStartedAt()->setTimezone($tz)->format('H:i:s');
            $this->date = $this->getStartedAt()->setTimezone($tz)->format('Y-m-d');
        }

        if ($this->getEndedAt()) {
            $this->endTime = $this->getEndedAt()->setTimezone($tz)->format('H:i:s');
        }
    }
}
