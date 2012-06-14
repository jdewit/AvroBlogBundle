<?php

namespace Avro\BlogBundle\Entity;

use Avro\BlogBundle\Entity\Tag;
use Avro\HTMLPurifierBundle\Purifier\HTMLPurifier;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TagManager
{
    protected $em;
    protected $class;
    protected $repository;
    protected $context;

    public function __construct(EntityManager $em, $class, $context)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;
        $this->context = $context;
    }

    /**
     * {@inheritDoc}
     */
    public function createTag()
    {
        $class = $this->getClass();
        $tag = new $class();

        return $tag;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteTag(Tag $tag)
    {
        $this->em->remove($tag);
        $this->em->flush();
    }

    /**
     * returns the Tag's fully qualified class name
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function findTag($id)
    {
        $tag = $this->repository->find($id);

        return $tag;
    }

    /**
     * {@inheritDoc}
     */
    public function findTagBy(array $criteria)
    {
        $tag = $this->repository->findOneBy($criteria);

        return $tag;
    }

    /**
     * {@inheritDoc}
     */
    public function findTagsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findAllTags()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function updateTag(Tag $tag, $andFlush = true)
    {
        //clean content
//        $purifier = new HTMLPurifier();
//        $tag->setContent($purifier->purify($tag->getContent()));
//        $tag->setAbstract($purifier->purify($tag->getAbstract()));
        $this->em->persist($tag);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    /*
     * {@inheritDoc}
     */
    public function incrementViews(Tag $tag)
    {
        $views = $tag->getViews();
        $tag->setViews($views + 1);
        $this->em->persist($tag);
        $this->em->flush();
    }
}
