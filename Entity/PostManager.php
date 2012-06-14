<?php

namespace Avro\BlogBundle\Entity;

use Avro\BlogBundle\Entity\Post;
use Avro\HTMLPurifierBundle\Purifier\HTMLPurifier;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostManager
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
    public function createPost()
    {
        $class = $this->getClass();
        $post = new $class();

        return $post;
    }

    /**
     * {@inheritDoc}
     */
    public function deletePost(Post $post)
    {
        $this->em->remove($post);
        $this->em->flush();
    }

    /**
     * returns the Post's fully qualified class name
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function findPost($id)
    {
        $post = $this->repository->find($id);

        return $post;
    }

    /**
     * {@inheritDoc}
     */
    public function findPostBy(array $criteria)
    {
        $post = $this->repository->findOneBy($criteria);

        return $post;
    }

    /**
     * {@inheritDoc}
     */
    public function findPostsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findAllPosts()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function updatePost(Post $post, $andFlush = true)
    {
        //clean content
//        $purifier = new HTMLPurifier();
//        $post->setContent($purifier->purify($post->getContent()));
//        $post->setAbstract($purifier->purify($post->getAbstract()));
        $this->em->persist($post);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    /*
     * {@inheritDoc}
     */
    public function incrementViews(Post $post)
    {
        $views = $post->getViews();
        $post->setViews($views + 1);
        $this->em->persist($post);
        $this->em->flush();
    }
}
