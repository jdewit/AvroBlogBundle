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
     * List Posts.
     */
    public function listAction()
    {
        $paginator = $this->get('avro_paginator.paginator');
        $paginator->setClass('AvroBlogBundle:Post');

        if ($this->container->get('security.context')->isGranted("ROLE_ADMIN")) {
            $paginator->addFilters(array(
                'approved' => array(
                    'field' => 'isApproved',
                    'value' => true,
                    'label' => 'Approved'
                ),
                'pendingApproval' => array(
                    'field' => 'isApproved',
                    'value' => false,
                    'label' => 'Pending Approval'
                )
            ));
        } else {
            $paginator->addConstraint('isApproved', true);
        }

        $posts = $paginator->getResults();

        return array(
            'posts' => $posts,
            'paginator' => $paginator,
        );
    }

    /**
     * List Posts by author.
     *
     */
    public function listByAuthorAction()
    {

        return $this->container->get('templating')->renderResponse('AvroBlogBundle:Post:edit.html.twig', array(

        ));
    }

    /**
     * Create a new Post.
     */
    public function newAction()
    {
        $post = new Post();

        $section = 'new';
        $form = $this->createForm(new PostFormType($section), $post);

        if (true === $this->processForm($form, $post, $section)) {
            $this->get('session')->getFlashBag()->set('success', 'Post created.');

            return new RedirectResponse($this->generateUrl('avro_blog_post_show', array('slug' => $post->getSlug()), 301));
        }

        return array(
            'form' => $form->createView(),
            'admin' => $this->isAdmin(),
            'section' => $section
        );
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

        $this->container->get('event_dispatcher')->dispatch('avro_blog.post.delete', new PostEvent($request, $post));

        $postManager->delete($post);

        $this->container->get('event_dispatcher')->dispatch('avro_blog.post.deleted', new PostEvent($request, $post));

        $this->container->get('session')->getFlashBag()->set('success', 'Post deleted.');

        return new RedirectResponse($this->container->get('router')->generate('avro_blog_blog_index'));
    }


    public function getPost($slug)
    {
        $post = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('AvroBlogBundle:Post')
            ->findOneBy(array('slug' => $slug));

        if (!$post) {
            throw $this->createNotFoundException('No post found');
        }

        return $post;
    }

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
