<?php

namespace PMT\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Task
 *
 * @ORM\Table(name="pmt_tasks")
 * @ORM\Entity(repositoryClass="PMT\TaskBundle\Entity\TaskRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\EntityListeners({"PMT\TaskBundle\Entity\TaskListener"})
 */
class Task
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
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Choice(choices = {"planned", "waiting", "in_progress", "complete", "merge", "merged", "done" })
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Choice(choices = {"feature", "modification", "bug", "testing", "support" })
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="progress", type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(value = 0)
     * @Assert\LessThanOrEqual(value = 100)
     * 
     */
    private $progress;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(value = 0)
     * @Assert\LessThanOrEqual(value = 100)
     *
     */
    private $priority;

    /**
     * @var integer
     *
     * @ORM\Column(name="estimated_time", type="integer", nullable=true)
     * @Assert\NotBlank
     */
    private $estimatedTime;

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
     * @ORM\ManyToOne(targetEntity="\PMT\ProjectBundle\Entity\Project", inversedBy="tasks")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;
    
    /**
     * @ORM\ManyToOne(targetEntity="\PMT\UserBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\OneToMany(targetEntity="\PMT\CommentBundle\Entity\Comment", mappedBy="task", fetch="EXTRA_LAZY")
     */
    private $comments;
    
    /**
     * @ORM\OneToMany(targetEntity="\PMT\FileBundle\Entity\File", mappedBy="task", fetch="EXTRA_LAZY")
     */
    private $files;
    
    /**
     * @ORM\OneToMany(targetEntity="\PMT\TrackingBundle\Entity\Track", mappedBy="task", fetch="EXTRA_LAZY")
     */
    private $tracks;
    
    /**
     * @ORM\OneToMany(targetEntity="\PMT\TaskBundle\Entity\Event", mappedBy="task", fetch="EXTRA_LAZY")
     */
    private $events;
    
    /**
     * @ORM\ManyToMany(targetEntity="\PMT\UserBundle\Entity\User")
     * @ORM\JoinTable(name="pmt_tasks_users")
     **/
    private $assignedUsers;
    
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
     * Set name
     *
     * @param string $name
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
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

    /**
     * Set status
     *
     * @param string $status
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Task
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set progress
     *
     * @param integer $progress
     * @return Task
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
    
        return $this;
    }

    /**
     * Get progress
     *
     * @return integer 
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set estimatedTime
     *
     * @param integer $estimatedTime
     * @return Task
     */
    public function setEstimatedTime($estimatedTime)
    {
        $this->estimatedTime = $estimatedTime;
    
        return $this;
    }

    /**
     * Get estimatedTime
     *
     * @return integer 
     */
    public function getEstimatedTime()
    {
        return $this->estimatedTime;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Task
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
     * @param \DateTime $updatedAt
     * @return Task
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
     * @param \DateTime $deletedAt
     * @return Task
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
     * Set parent
     *
     * @param \PMT\ProjectBundle\Entity\Project $parent
     * @return Task
     */
    public function setParent(\PMT\ProjectBundle\Entity\Project $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \PMT\ProjectBundle\Entity\Project 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set project
     *
     * @param \PMT\ProjectBundle\Entity\Project $project
     * @return Task
     */
    public function setProject(\PMT\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \PMT\ProjectBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
    
    public function __toString()
    {
        return $this->getName();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assignedUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set user
     *
     * @param \PMT\UserBundle\Entity\User $user
     * @return Task
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
     * Add comments
     *
     * @param \PMT\CommentBundle\Entity\Comment $comments
     * @return Task
     */
    public function addComment(\PMT\CommentBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \PMT\CommentBundle\Entity\Comment $comments
     */
    public function removeComment(\PMT\CommentBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add files
     *
     * @param \PMT\FileBundle\Entity\File $files
     * @return Task
     */
    public function addFile(\PMT\FileBundle\Entity\File $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \PMT\FileBundle\Entity\File $files
     */
    public function removeFile(\PMT\FileBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }
    
    public function getCommentsCount()
    {
        return $this->comments->count();
    }
    
    public function getFilesCount()
    {
        return $this->files->count();
    }

    /**
     * Add tracks
     *
     * @param \PMT\TrackingBundle\Entity\Track $tracks
     * @return Task
     */
    public function addTrack(\PMT\TrackingBundle\Entity\Track $tracks)
    {
        $this->tracks[] = $tracks;
    
        return $this;
    }

    /**
     * Remove tracks
     *
     * @param \PMT\TrackingBundle\Entity\Track $tracks
     */
    public function removeTrack(\PMT\TrackingBundle\Entity\Track $tracks)
    {
        $this->tracks->removeElement($tracks);
    }

    /**
     * Get tracks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * Add assignedUsers
     *
     * @param \PMT\UserBundle\Entity\User $assignedUsers
     * @return Task
     */
    public function addAssignedUser(\PMT\UserBundle\Entity\User $assignedUsers)
    {
        $this->assignedUsers[] = $assignedUsers;
    
        return $this;
    }

    /**
     * Remove assignedUsers
     *
     * @param \PMT\UserBundle\Entity\User $assignedUsers
     */
    public function removeAssignedUser(\PMT\UserBundle\Entity\User $assignedUsers)
    {
        $this->assignedUsers->removeElement($assignedUsers);
    }

    /**
     * Get assignedUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssignedUsers()
    {
        return $this->assignedUsers;
    }
    
    public function getEstimatedTimeHours()
    {
        return $this->getEstimatedTime()/3600;
    }
    
    public function setEstimatedTimeHours($v)
    {
      return $this->setEstimatedTime($v*3600);
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Task
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }
    
    public static function getCategoryOptions()
    {
        return array('feature' => 'feature', 'modification' => 'modification',
            'bug' => 'bug', 'testing' => 'testing', 'support' => 'support');
    }
    
    public static function getStatusOptions()
    {
        return array(
            'planned' => 'planned',
            'waiting' => 'waiting',
            'in_progress' => 'in progress',
            'complete' => 'complete',
            'merge' => 'merge',
            'merged' => 'merged',
            'done' => 'done');
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    
        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }
    
    public function getPriorityColor()
    {
        $r = 255;
        $g = round(255-200*($this->getPriority())/100);
        $b = round(150-150*($this->getPriority())/100);
        return "rgb($r,$g,$b)";
    }
    
    public function getProjectName()
    {
        return $this->getProject()->getName();
    }
    
    public function getCategoryName()
    {
        $options = Task::getCategoryOptions();
        return $options[$this->getCategory()];
    }
    
    public function getStatusName()
    {
      $options = Task::getStatusOptions();
      return $options[$this->getStatus()];
    }
    
    public function getAdvanceStatuses()
    {
        $statuses = array_keys(self::getStatusOptions());
        $advance = array_slice($statuses, array_search($this->getStatus(), $statuses)+1);
        return array_intersect_key(self::getStatusOptions(), array_flip($advance));
    }
    
    public function getRecedeStatuses()
    {
        $statuses = array_reverse(array_keys(self::getStatusOptions()));
        $advance = array_slice($statuses, array_search($this->getStatus(), $statuses)+1);
        return array_reverse(array_intersect_key(self::getStatusOptions(), array_flip($advance)));
    }
}