<?php

namespace Avro\BlogBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

use Avro\BlogBundle\Model\Post as AbstractPost;

/**
 * @ODM\Document(collection="Blog_Post")
 */
class Post extends AbstractPost
{

    /**
     * @ODM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ODM\String
     */
    protected $slug;

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
}
