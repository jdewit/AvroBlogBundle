<?php
namespace Avro\BlogBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ODM\Document(collection="Blog_Post")
 */
class Post {

    /**
     * @ODM\Id(strategy="auto")
     */
    public $id;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ODM\String
     */
    private $slug;

    /**
     * Title.
     *
     * @ODM\String
     */
    protected $title;

    /**
     * Summary.
     *
     * @ODM\String
     */
    protected $summary;

    /**
     * Content
     *
     * @ODM\String
     */
    protected $content;

    /**
     * @ODM\Int
     */
    protected $views;

    /**
     * @ODM\Boolean
     */
    protected $isApproved = false;

    /**
     * @ODM\Boolean
     */
    protected $isPublic = true;

    /**
     * @ODM\Boolean
     */
    protected $isFeatured;

    /**
     * @ODM\EmbedOne(targetDocument="Avro\ImageBundle\Document\Image")
     */
    protected $image;

    /**
     * @ODM\ReferenceMany(targetDocument="Avro\BlogBundle\Document\Tag", simple=true)
     */
    protected $tags;

    /**
     * CreatedBy
     *
     * @ODM\String
     */
    protected $createdBy;

    /**
     * @ODM\date
     */
    protected $createdAt;

    /**
     * @ODM\date
     */
    protected $updatedAt;

    /**
     * @ODM\Boolean
     */
    protected $isDeleted;

    /**
     * @ODM\date
     */
    protected $deletedAt;

    /**
     * @ODM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * @ODM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now');
    }

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param date $updatedAt
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Post
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean $isDeleted
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set deletedAt
     *
     * @param date $deletedAt
     * @return Post
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return date $deletedAt
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Post
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Get summary
     *
     * @return string $summary
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set views
     *
     * @param int $views
     * @return Post
     */
    public function setViews($views)
    {
        $this->views = $views;
        return $this;
    }

    /**
     * Get views
     *
     * @return int $views
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set isApproved
     *
     * @param boolean $isApproved
     * @return Post
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;
        return $this;
    }

    /**
     * Get isApproved
     *
     * @return boolean $isApproved
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }

    public function getIsPublic()
    {
        return $this->isPublic;
    }

    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     * @return Post
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;
        return $this;
    }

    /**
     * Get isFeatured
     *
     * @return boolean $isFeatured
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * Set image
     *
     * @param Avro\ImageBundle\Document\Image $image
     * @return Post
     */
    public function setImage(\Avro\ImageBundle\Document\Image $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return Avro\ImageBundle\Document\Image $image
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }
    public function addTag(\Avro\BlogBundle\Document\Tag $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * Set createdBy
     *
     * @param $createdBy
     * @return Post
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * Get createdBy
     *
     * @return  $createdBy
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function __alias()
    {
        return 'post';
    }

    public function __sections()
    {
        return array(
            'title',
            'summary',
            'content',
            'image',
        );
    }
}
