<?php

namespace Avro\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;

use Avro\BlogBundle\Form\Type\PostFormType;
use Avro\BlogBundle\Event\PostEvent;

/**
 * Post controller.
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class PostController extends ContainerAware
{
    /**
     * List all approved Posts.
     */
    public function listAction()
    {
        $postManager = $this->container->get('avro_blog.post_manager');

        $posts = $postManager->findAll();

        return array(
            'posts' => $posts
        );
    }

    /**
     * List all unapproved Posts.
     */
    public function listUnapprovedAction()
    {
        $postManager = $this->container->get('avro_blog.post_manager');

        $posts = $postManager->findBy(array('isApproved' => false));

        return array(
            'posts' => $posts
        );
    }

    /**
     * List Posts by author.
     *
     */
    public function listByAuthorAction($id)
    {
        $postManager = $this->container->get('avro_blog.post_manager');

        $posts = $postManager->findBy(array('createdBy' => $id));

        return $this->container->get('templating')->renderResponse('AvroBlogBundle:Post:edit.html.twig', array(
            'posts' => $posts
        ));
    }

    /**
     * Create a new Post.
     */
    public function newAction(Request $request)
    {
        $post = new Post();

        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('avro_blog.post.create', new PostEvent($request, $post));

        $form = $this->container->get('form.factory')->create(new PostFormType('new'), $post);

        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $dispatcher->dispatch('avro_blog.post.created', new PostEvent($request, $post));
                $this->get('session')->getFlashBag()->set('success', 'Post created.');

                return new RedirectResponse($this->container->get('router')->generate('avro_blog_post_show', array('slug' => $post->getSlug()), 301));
            }
        }

        return $this->container->get('templating')->renderResponse('AvroBlogBundle:Post:new.html.twig', array(
            'form' => $form->createView(),
            'admin' => $this->isAdmin(),
            'section' => $section
        ));
    }

    /**
     * Edit one Post, show the edit form.
     *
     */
    public function editAction(Request $request, $slug)
    {
        $postManager = $this->container->get('avro_blog.post_manager');
        $post = $postManager->findOneBy(array('slug' => $slug));

        if (!$post) {
            throw $this->createNotFoundException('No post found');
        }

        $section = $this->container->get('request')->query->get('section');

        $form = $this->container->get('form.factory')->create(new PostFormType($section), $post);
        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $eventDispatcher = $this->container->get('event_dispatcher');
                $eventDispatcher->dispatch('avro_blog.post_update', new PostEvent($request, $post));
                $postManager->update($post);

                $this->container->get('session')->getFlashBag()->set('success', 'Post updated.');
                $eventDispatcher->dispatch('avro_blog.post_updated', new PostEvent($request, $post));

                return new RedirectResponse($this->container->get('router')->generate('avro_blog_post_show', array('slug' => $post->getSlug())), 301);
            }
        }

        return $this->container->get('templating')->renderResponse('AvroBlogBundle:Post:edit.html.twig', array(
            'form' => $form->createView(),
            'post' => $post,
            'admin' => $this->isAdmin(),
            'section' => $section
        ));
    }

    /**
     * Show one Post.
     *
     */
    public function showAction($slug)
    {
        $postManager = $this->container->get('avro_blog.post_manager');

        $post = $postManager->findOneBy(array('slug' => $slug));

        return $this->container->get('templating')->renderResponse('AvroBlogBundle:Post:show.html.twig', array(
            'post' => $post,
            'admin' => $this->isAdmin()
        ));
    }

    /**
     * Delete onePost.
     */
    public function deleteAction(Request $request, $id)
    {
        $postManager = $this->container->get('avro_blog.post_manager');

        $post = $postManager->find($id);

        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('avro_blog.post.delete', new PostEvent($request, $post));

        $postManager->delete($post);

        $dispatcher->dispatch('avro_blog.post.deleted', new PostEvent($request, $post));

        $this->container->get('session')->getFlashBag()->set('success', 'Post deleted.');

        return new RedirectResponse($this->container->get('router')->generate('avro_blog_blog_index'));
    }

    /**
     * Is user an admin?
     */
    public function isAdmin()
    {
        $context = $this->container->get('security.context');

        $user = $context->getToken()->getUser();

        if ($context->isGranted("ROLE_ADMIN")) {
            return true;
        }

        return false;
    }
}
