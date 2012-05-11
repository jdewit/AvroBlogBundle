<?php

namespace Avro\BlogBundle\Entity;

use Avro\BlogBundle\Entity\PostInterface;

interface PostManagerInterface
{
    /**
     * Returns an empty post instance.
     *
     * @return PostInterface
     */
    function createPost();

    /**
     * Deletes a post.
     *
     * @param PostInterface $post
     * @return void
     */
    function deletePost(PostInterface $post);

    /**
     * Finds one post by id.
     *
     * @param array $id
     * @return PostInterface
     */
    function findPost($id);

    /**
     * Finds one post by the given criteria.
     *
     * @param array $criteria
     * @return PostInterface
     */
    function findPostBy(array $criteria);

    /**
     * Returns a collection with all post instances.
     *
     * @return \Traversable
     */
    function findAllPosts();

    /**
     * Returns the post's fully qualified class name.
     *
     * @return string
     */
    function getClass();

    /**
     * Updates a post.
     *
     * @param PostInterface $post
     */
    function updatePost(PostInterface $post);

    /*
     * Increment posts views
     */
    public function incrementViews(PostInterface $post);

}
