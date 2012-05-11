<?php
namespace Avro\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Avro\BlogBundle\Entity\Post
 * 
 * @author Joris de <joris.w.dewit@gmail.com>
 * 
 * @ORM\Entity
 * @ORM\Table(name="blog_posts")
 * @ORM\HasLifecycleCallbacks
 */
class Post 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $abstract;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $enabled;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publicationDateStart;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $views;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isFeatured;

    /** 
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Avro\BlogBundle\Entity\Tag", inversedBy="posts")
     * @ORM\JoinTable(name="blog_post_tag")
     */
    protected $tags;

    /**
     * @var \Avro\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Avro\UserBundle\Entity\User")
     */
    protected $createdBy;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Avro\AssetBundle\Entity\Image", cascade={"all"})
     */
    protected $image;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isDeleted = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /** 
     * @ORM\PrePersist 
     */
    public function PrePersist()
    {
        $this->createdAt = new \DateTime('now');
    }

    /** 
     * @ORM\PreUpdate 
     */
    public function PreUpdate()
    {
       $this->updatedAt= new \DateTime('now');
    }

    public function __construct() 
    {                                     
        $this->tags = new ArrayCollection();
            
    }

    /**
     * Get post id
     *
     * @return integer
     */   
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get title
     * 
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }    
       
    /**
     * Get abstract
     * 
     * @return text 
     */
    public function getAbstract()
    {
        return $this->abstract;
    }
    
    /**
     * Set abstract
     *
     * @param text $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }    
       
    /**
     * Get content
     * 
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }    
       
    /**
     * Get enabled
     * 
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    
    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }    
       
    /**
     * Get slug
     * 
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }    
       
    /**
     * Get publicationDateStart
     * 
     * @return datetime 
     */
    public function getPublicationDateStart()
    {
        return $this->publicationDateStart;
    }
    
    /**
     * Set publicationDateStart
     *
     * @param datetime $publicationDateStart
     */
    public function setPublicationDateStart($publicationDateStart)
    {
        $this->publicationDateStart = $publicationDateStart;
    }    
       
    /**
     * Get views
     * 
     * @return string 
     */
    public function getViews()
    {
        return $this->views;
    }
    
    /**
     * Set views
     *
     * @param string $views
     */
    public function setViews($views)
    {
        $this->views = $views;
    }    
       
    /**
     * Get isFeatured
     * 
     * @return boolean 
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }
    
    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;
    }    
       
    /**
     * Get tag
     * 
     * @return Avro\BlogBundle\Entity\Tag 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags
     *
     * @param ArrayCollection $tags
     */
    public function setTags(\Avro\BlogBundle\Entity\TagInterface $tags)
    {
        $this->tags = $tags;
    } 
    
    /**
     * Add tag to the collection of related items
     *
     * @param \Avro\BlogBundle\Entity\Tag $tag   
     */
    public function addTag(\Avro\BlogBundle\Entity\TagInterface $tag)
    {
        $this->tags->add($tag);
        $tag->set($this);
    }  

    /**
     * Remove tag from the collection of related items
     *
     * @param \Avro\BlogBundle\Entity\Tag $tag 
     */
    public function removeTag(\Avro\BlogBundle\Entity\TagInterface $tag)
    {
        $this->tags->removeElement($tag);
    }
       
    /**
     * Get createdBy
     * 
     * @return Avro\UserBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdBy
     *
     * @param manyToOne $createdBy
     */
    public function setCreatedBy(\Avro\UserBundle\Entity\User $createdBy)
    {
        $this->createdBy = $createdBy;
    }     
       
    /**
     * Get image
     * 
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Set image
     *
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }    
       
    
    /**
    * Set createdAt
    *
    * @param datetime $createdAt
    */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime $createdAt
     */
    public function getCreatedAt()
    {
       return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get isDeleted
     * 
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }
    
    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }  

    /**
     * Set deletedAt
     *
     * @param datetime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * Get deletedAt
     *
     * @return datetime $deletedAt
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * string output
     */
    public function __toString()
    {
        return $this->title;
        return $this->abstract;
        return $this->content;
        return $this->enabled;
        return $this->slug;
        return $this->publicationDateStart;
        return $this->views;
        return $this->isFeatured;
        return $this->tag;
        return $this->createdBy;
        return $this->image;
    } 
}

