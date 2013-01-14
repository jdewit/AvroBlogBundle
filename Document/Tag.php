<?php

namespace Avro\BlogBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document(collection="Blog_Tag")
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
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
}
