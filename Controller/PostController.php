<?php

namespace Avro\BlogBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Post controller.
 *
 * @Route("/post")
 */
class PostController extends ContainerAware
{
    /**
     * Show all Posts.
     *
     * @Route("/list/{filter}", defaults={"filter" = "Featured"}, name="avro_blog_post_list")
     * @Route("/list/{filter}", defaults={"filter" = "Featured"}, name="avro_blog_post_list_featured")
     * @Route("/list/{filter}", defaults={"filter" = "Latest"}, name="avro_blog_post_list_latest")
     * @Template()     
     */
    public function listAction($filter)
    {
        switch ($filter):
            case 'Featured':
                $posts = $this->container->get('avro_blog.post_manager')->findAllPosts();           
            break;
            case 'Latest':
                $posts = $this->container->get('avro_blog.post_manager')->findAllPosts();            
            break;            
        endswitch;      

        return array(
            'posts' => $posts,
            'filter' => $filter
        );
    }

    /**
     * Create a new post.
     *
     * @Route("/new", name="avro_blog_post_new")
     * @Template()     
     */
    public function newAction()
    {
        $form = $this->container->get('avro_blog.post.form');
        $formHandler = $this->container->get('avro_blog.post.form.handler');

        $process = $formHandler->process();
        if ($process) {
            $this->container->get('session')->getFlashBag()->set('success', 'Post created.');
            $post = $form->getData('post');

            return new RedirectResponse($this->container->get('router')->generate('avro_blog_post_show', array('id' => $post->getId())), 301);
        }

        return array(
            'form' => $form->createview(),
            'ajax' => $this->container->get('request')->isXmlHttpRequest(),
        );

    }

    /**
     * Edit one post, show the edit form.
     *
     * @Route("/edit/{id}", name="avro_blog_post_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $post = $this->container->get('avro_blog.post_manager')->findPost($id);

        if (!$post) {
            $this->container->get('session')->getFlashBag()->set('error', 'Post not found.');
            
            return new RedirectResponse($this->container->get('router')->generate('avro_blog_post_list'));
        }

        $form = $this->container->get('avro_blog.post.form');
        $formHandler = $this->container->get('avro_blog.post.form.handler');

        $process = $formHandler->process($post);
        if ($process) {
            $this->container->get('session')->getFlashBag()->set('success', 'Post updated.');

            return new RedirectResponse($this->container->get('router')->generate('avro_blog_post_show', array('id' => $id)));
        }

        return array(
            'form' => $form->createview(),
            'post' => $post,
        );
    }

    /**
     * Show one post.
     *
     * @Route("/show/{id}", name="avro_blog_post_show")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function showAction($id)
    {
        $post = $this->container->get('avro_blog.post_manager')->findPost($id);

        $postManager = $this->container->get('avro_blog.post_manager');
        $postManager->incrementViews($post);

        return array(
            'post' => $post,
        );
    }

    /**
     * Delete one Post.
     *
     * @Route("/delete/{id}", name="avro_blog_post_delete")
     */
    public function deleteAction($id)
    {
        $post = $this->container->get('avro_blog.post_manager')->findPost($id);
        $this->container->get('avro_blog.post_manager')->deletePost($post);
        $this->container->get('session')->getFlashBag()->set('success', 'Post deleted.');

        return new RedirectResponse($this->container->get('router')->generate('avro_blog_post_list'));     
    }
       
}
