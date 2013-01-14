<?php

namespace Avro\BlogBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/*
 * Managing class for Tags
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class TagManager
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
     * Creates a Tag
     *
     * @return Tag
     */
    public function create()
    {
        $class = $this->getClass();

        $tag = new $class();

        return $tag;
    }

    /**
     * Updates a Tag
     *
     * @param Tag $tag
     * @param boolean $andFlush Flush objectManager if true
     * @param boolean $andClear Clear objectManager if true
     */
    public function update(Tag $tag, $andFlush = true, $andClear = false)
    {
        $this->objectManager->persist($tag);

        if ($andFlush) {
            $this->flush($andClear);
        }
    }

    /**
     * Permanently delete one Tag
     *
     * @param Tag $tag
     * @param boolean $andFlush Flush objectManager if true
     * @param boolean $andClear Clear objectManager if true
     */
    public function delete(Tag $tag, $andFlush = true, $andClear = false)
    {
        $this->objectManager->remove($tag);

        if ($andFlush) {
            $this->flush($andClear);
        }

        return true;
    }

    /**
     * Find one tag by id
     *
     * @param string $id
     * @return Tag
     */
    public function find($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('Id must be specified.');
        }
        $criteria['id'] = $id;
        $criteria['owner'] = $this->owner->getId();

        $tag = $this->repository->findOneBy($criteria);

        return $tag;
    }

    /*
     * Fine one tag by id as array
     *
     * @param string $id
     */
    public function findAsArray($id)
    {
        $qb = $this->objectManager->createQueryBuilder()->select('tag')->from($this->class, 'tag');
        $qb->where('tag.id = ?1')->setParameter('1', $id);

        $result = $qb->getQuery()->getArrayResult();

        return current($result);
    }

    /**
     * Find one tag by criteria
     *
     * @parameter array $criteria
     * @return Tag
     */
    public function findOneBy($criteria = array())
    {

        return $this->repository->findOneBy($criteria);
    }

    /**
     * Find tags by criteria
     *
     * @param array $criteria
     * @param array $sortBy
     * @param string $limit
     * @return array Tags
     */
    public function findBy(array $criteria = null, array $sortBy = null, $limit = null)
    {
        return $this->repository->findBy($criteria, $sortBy, $limit);
    }

    /**
     * Find all tags
     *
     * @param boolean $showDeleted Show soft deleted tags
     *
     * @return array Tags
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
