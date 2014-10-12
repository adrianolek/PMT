<?php

namespace PMT\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * File
 *
 * @ORM\Table(name="pmt_files", indexes={@ORM\Index(name="download_key_idx",columns={"download_key"})})
 * @ORM\Entity(repositoryClass="PMT\FileBundle\Entity\FileRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Uploadable(filenameGenerator="SHA1", pathMethod="getUploadPath")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class File
{
    private static $image_mimes = array('image/png', 'image/jpeg', 'image/gif');

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     * @Gedmo\UploadableFilePath
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=255, nullable=true)
     * @Gedmo\UploadableFileMimeType
     */
    private $mimeType;

    /**
     * @var float
     *
     * @ORM\Column(name="size", type="decimal", nullable=true)
     * @Gedmo\UploadableFileSize
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="download_key", type="string", length=255, nullable=true)
     */
    private $download_key;

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
     * @ORM\ManyToOne(targetEntity="\PMT\TaskBundle\Entity\Task", inversedBy="files")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    private $task;

    /**
     * @ORM\ManyToOne(targetEntity="\PMT\UserBundle\Entity\User", inversedBy="files")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @Assert\NotBlank
     * @Assert\File(maxSize="10485760")
     */
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
      $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
      return $this->file;
    }

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
     * Set path
     *
     * @param  string $path
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set mimeType
     *
     * @param  string $mimeType
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size
     *
     * @param  float $size
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return File
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
     * @return File
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
     * @return File
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
     * Set task
     *
     * @param  \PMT\TaskBundle\Entity\Task $task
     * @return File
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

    public function getUploadPath($defaultPath)
    {
        return sprintf('%s/uploads/%s/%s', $defaultPath, floor($this->getId()/10000), $this->getId());
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return File
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
     * @ORM\PrePersist()
     */
    public function setOriginalName()
    {
        if ($this->getFile()) {
            $this->setName($this->getFile()->getClientOriginalName());
        }
    }

    /**
     * @Orm\PostPersist()
     */
    public function updateDownloadKey()
    {
      if ($this->getFile()) {
        $this->setDownloadKey(sha1($this->getId().rand()));
      }
    }

    /**
     * @Orm\PostUpdate()
     */
    public function createThumb()
    {
      if ($this->isImage()) {
          $image = new \Imagick();
          $image->readImage($this->getPath());
          $image->cropThumbnailImage(250, 200);
          $image->writeImage($this->getThumbPath());
      }
    }

    public function getThumbPath()
    {
        $info = pathinfo($this->getPath());
        $path = $info['dirname'].'/'.$info['filename'].'_thumb';
        if (isset($info['extension']) && $info['extension']) {
            $path .= '.'.$info['extension'];
        }

        return $path;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function isImage()
    {
        return in_array($this->getMimeType(), self::$image_mimes);
    }

    public function getExtension()
    {
        $info = pathinfo($this->getPath());
        if (isset($info['extension']) && $info['extension']) {
            return strtolower($info['extension']);
        }
    }

    /**
     * Set download_key
     *
     * @param  string $downloadKey
     * @return File
     */
    public function setDownloadKey($downloadKey)
    {
        $this->download_key = $downloadKey;

        return $this;
    }

    /**
     * Get download_key
     *
     * @return string
     */
    public function getDownloadKey()
    {
        return $this->download_key;
    }

    /**
     * Set user
     *
     * @param  \PMT\UserBundle\Entity\User $user
     * @return File
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
}
