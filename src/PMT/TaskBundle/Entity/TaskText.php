<?php

namespace PMT\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskText
 *
 * @ORM\Table(name="pmt_tasks_texts",options={"engine"="MyISAM"},
 *   indexes={@ORM\Index(columns={"name"},flags={"FULLTEXT"}),@ORM\Index(columns={"description"},flags={"FULLTEXT"}),@ORM\Index(columns={"name","description"},flags={"FULLTEXT"})})
 * @ORM\Entity(repositoryClass="PMT\TaskBundle\Entity\TaskTextRepository")
 */
class TaskText
{

    /**
     * @var integer
     *
     * @ORM\Column(name="task_id", type="integer")
     * @ORM\Id
     */
    private $taskId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * Set taskId
     *
     * @param integer $taskId
     * @return TaskText
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * Get taskId
     *
     * @return integer 
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TaskText
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
     * @return TaskText
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
}
