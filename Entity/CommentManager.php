<?php
namespace Avro\BlogBundle\Entity;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/*
 * Managing class for Comment entity
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class CommentManager
{
    protected $em;
    protected $class;
    protected $repository;

    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;
        $this->repository = $em->getRepository($class);
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
        $this->em->flush();

        if ($andClear) {
            $this->em->clear($this->getClass());
        }
    }

    /**
     * Creates a Comment
     *
     * @return Comment
     */
    public function create()
    {
        $class = $this->getClass();

        $comment = new $class();

        return $comment;
    }

    /**
     * Updates a Comment
     *
     * @param Comment $comment
     * @param boolean $andFlush Flush em if true
     * @param boolean $andClear Clear em if true
     */
    public function update(Comment $comment, $andFlush = true, $andClear = false)
    {
        //clean content
        $purifier = new HTMLPurifier();
        $comment->setBody($purifier->purify($post->getBody()));

        $this->em->persist($comment);

        if ($andFlush) {
            $this->flush($andClear);
        }
    }

    /**
     * Soft delete one Comment
     *
     * @param Comment $comment
     * @param boolean $andFlush Flush em if true
     * @param boolean $andClear Clear em if true
     */
    public function softDelete(Comment $comment, $andFlush = true, $andClear = false)
    {
        $comment->setIsDeleted(true);
        $comment->setDeletedAt(new \Datetime('now'));

        $this->em->persist($comment);

        if ($andFlush) {
            $this->flush($andClear);
        }

        return true;
    }

    /**
     * Restore one Comment
     *
     * @param Comment $comment
     * @param boolean $andFlush Flush em if true
     * @param boolean $andClear Clear em if true
     */
    public function restore(Comment $comment, $andFlush = true, $andClear = false)
    {
        $comment->setIsDeleted(false);
        $comment->setDeletedAt(null);

        $this->em->persist($comment);

        if ($andFlush) {
            $this->flush($andClear);
        }

        return true;
    }

    /**
     * Permanently delete one Comment
     *
     * @param Comment $comment
     * @param boolean $andFlush Flush em if true
     * @param boolean $andClear Clear em if true
     */
    public function delete(Comment $comment, $andFlush = true, $andClear = false)
    {
        $this->em->remove($comment);

        if ($andFlush) {
            $this->flush($andClear);
        }

        return true;
    }

    /**
     * Find one comment by id
     *
     * @param string $id
     * @return Comment
     */
    public function find($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('Id must be specified.');
        }
        $criteria['id'] = $id;
        $criteria['owner'] = $this->owner->getId();

        $comment = $this->repository->findOneBy($criteria);

        return $comment;
    }

    /*
     * Fine one comment by id as array
     *
     * @param string $id
     */
    public function findAsArray($id)
    {
        $qb = $this->em->createQueryBuilder()->select('comment')->from($this->class, 'comment');
        $qb->where('comment.id = ?1')->setParameter('1', $id);

        $result = $qb->getQuery()->getArrayResult();

        return current($result);
    }

    /**
     * Find one comment by criteria
     *
     * @parameter array $criteria
     * @return Comment
     */
    public function findOneBy($criteria = array())
    {

        return $this->repository->findOneBy($criteria);
    }

    /**
     * Find comments by criteria
     *
     * @param array $criteria
     * @param array $sortBy
     * @param string $limit
     * @return array Comments
     */
    public function findBy(array $criteria = null, array $sortBy = null, $limit = null)
    {
        return $this->repository->findBy($criteria, $sortBy, $limit);
    }

    /**
     * Find active comments
     *
     * @return array Comments
     */
    public function findAllActive()
    {
        $criteria['isDeleted'] = false;

        return $this->repository->findBy($criteria);
    }

    /**
     * Search comments
     *
     * @param array $query Search criteria
     * @return array Comments
     */
    public function search(array $query = array(), $asArray = false)
    {
        if (!array_key_exists('orderBy', $query)) {
            $query['orderBy'] = 'updatedAt';
        }
        if (!array_key_exists('limit', $query)) {
            $query['limit'] = '15';
        }
        if (!array_key_exists('direction', $query)) {
            $query['direction'] = 'ASC';
        }
        if (!array_key_exists('offset', $query)) {
            $query['offset'] = '0';
        }
        if (!array_key_exists('filter', $query)) {
            $query['filter'] = 'All';
        }

        $qb = $this->em->createQueryBuilder()->select('comment')->from($this->class, 'comment');
        $qb->setFirstResult($query['offset']);
        $qb->orderBy('comment.'.$query['orderBy'], $query['direction']);
        $qb->setMaxResults($query['limit']);

        $filter = $query['filter'];
        if (is_numeric($filter)) {
            $qb->andWhere('comment.id = ?2')->setParameter(2, $filter);
        } else {
            switch($filter) {
                case 'All':
                    $qb->andWhere('comment.isDeleted = ?2')->setParameter(2, false);
                break;
                case 'Deleted':
                    $qb->andWhere('comment.isDeleted = ?2')->setParameter(2, true);
                break;
                default:
                    $qb->andWhere('comment.isDeleted = ?2')->setParameter(2, false);
                break;
            }
        }

        $index = 3;

        // remove non entity related fields
        unset($query['_token']);
        unset($query['offset']);
        unset($query['direction']);
        unset($query['orderBy']);
        unset($query['limit']);
        unset($query['filter']);

        foreach ($query as $key => $value) {
            if ((!empty($value)) && ($key != '_token')) {
                if (is_object($value)) {
                    $qb->andWhere('comment.'.$key.' = ?'.$index)->setParameter($index, $value->getId());
                } elseif ($key == 'startDate') {
                    $qb->andWhere('comment.date >= ?'.$index)->setParameter($index, $value);
                } elseif ($key == 'endDate') {
                    $qb->andWhere('comment.date <= ?'.$index)->setParameter($index, $value);
                } else  {
                    $qb->andWhere('comment.'.$key.' LIKE ?'.$index)->setParameter($index, '%'.$value.'%');
                }
                $index = $index +1;
            }
        }

        if (true === $asArray) {
            $results = $qb->getQuery()->getArrayResult();
        } else {
            $results = $qb->getQuery()->getResult();
        }

        return $results;
    }
}

