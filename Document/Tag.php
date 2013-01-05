<?php
namespace Avro\BlogBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document(collection="Blog_Tag")
 */
class Tag {

    /**
     * @ODM\Id(strategy="auto")
     */
    public $id;

    /**
     * Tag name.
     *
     * @ODM\String
     */
    protected $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ODM\String
     */
    protected $slug;

    /**
     * @ODM\Boolean
     */
    protected $isDeleted;

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
     * Set name
     *
     * @param string $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }


    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }
}
