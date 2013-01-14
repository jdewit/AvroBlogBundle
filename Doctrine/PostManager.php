<?php

namespace Avro\BlogBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Avro\BlogBundle\Model\PostInterface;

/*
 * Managing class for Post
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class PostManager
{
    protected $class;
    protected $repository;
    protected $objectManager;

    public function __construct(ObjectManager $objectManager, $class)
    {
        $this->objectManager = $objectManager;
        $metadata = $objectManager->getClassMetadata($class);
        $this->class = $class;
        $this->repository = $objectManager->getRepository($class);
    }

    /**
     * @return fully qualified class name
     */
    public function getClass()
    {
        return $this->class;
    }

    /*
     * Flush the entity manager
     *
     * @param boolean $andClear Clears instances of this class from the entity manager
     */
    public function flush($andClear)
    {
        $this->objectManager->flush();

        if ($andClear) {
            $this->objectManager->clear($this->getClass());
        }
    }

    /**
     * Creates a Post
     *
     * @return Post
     */
    public function create()
    {
        $class = $this->getClass();

        $post = new $class();

        return $post;
    }

    /**
     * Updates a Post
     *
     * @param PostInterface $post
     * @param boolean $andFlush Flush objectManager if true
     * @param boolean $andClear Clear objectManager if true
     */
    public function update(PostInterface $post, $andFlush = true, $andClear = false)
    {
        $this->objectManager->persist($post);

        if ($andFlush) {
            $this->flush($andClear);
        }
    }

    /**
     * Soft delete one Post
     *
     * @param Post $post
     * @param boolean $andFlush Flush objectManager if true
     * @param boolean $andClear Clear objectManager if true
     */
    public function softDelete(Post $post, $andFlush = true, $andClear = false)
    {
        $post->setIsDeleted(true);
        $post->setDeletedAt(new \Datetime('now'));

        $this->objectManager->persist($post);

        if ($andFlush) {
            $this->flush($andClear);
        }

        return true;
    }

    /**
     * Restore one Post
     *
     * @param Post $post
     * @param boolean $andFlush Flush em if true
     * @param boolean $andClear Clear em if true
     */
    public function restore(Post $post, $andFlush = true, $andClear = false)
    {
        $post->setIsDeleted(false);
        $post->setDeletedAt(null);

        $this->em->persist($post);

        if ($andFlush) {
            $this->flush($andClear);
        }

        return true;
    }

    /**
     * Permanently delete one Post
     *
     * @param Post $post
     * @param boolean $andFlush Flush em if true
     * @param boolean $andClear Clear em if true
     */
    public function delete(Post $post, $andFlush = true, $andClear = false)
    {
        $this->em->remove($post);

        if ($andFlush) {
            $this->flush($andClear);
        }

        return true;
    }

    /**
     * Find one post by id
     *
     * @param string $id
     * @return Post
     */
    public function find($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('Id must be specified.');
        }
        $criteria['id'] = $id;
        $criteria['owner'] = $this->owner->getId();

        $post = $this->repository->findOneBy($criteria);

        return $post;
    }

    /*
     * Fine one post by id as array
     *
     * @param string $id
     */
    public function findAsArray($id)
    {
        $qb = $this->em->createQueryBuilder()->select('post')->from($this->class, 'post');
        $qb->where('post.id = ?1')->setParameter('1', $id);

        $result = $qb->getQuery()->getArrayResult();

        return current($result);
    }

    /**
     * Find one post by criteria
     *
     * @parameter array $criteria
     * @return Post
     */
    public function findOneBy($criteria = array())
    {

        return $this->repository->findOneBy($criteria);
    }

    /**
     * Find posts by criteria
     *
     * @param array $criteria
     * @param array $sortBy
     * @param string $limit
     * @return array Posts
     */
    public function findBy(array $criteria = null, array $sortBy = null, $limit = null)
    {
        return $this->repository->findBy($criteria, $sortBy, $limit);
    }

    /**
     * Find all posts
     *
     * @param boolean $showDeleted Show soft deleted posts
     *
     * @return array Posts
     */
    public function findAll($showDeleted = false)
    {
        $criteria = array();
        if ($showDeleted) {
            $criteria['isDeleted'] = false;
        }

        return $this->repository->findBy($criteria);
    }
}
