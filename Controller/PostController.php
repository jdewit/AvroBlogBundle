<?php

namespace Avro\BlogBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Avro\BlogBundle\Document\Post;
use Avro\ImageBundle\Document\Image;
use Avro\BlogBundle\Form\Type\PostFormType;

/**
 * Post controller.
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class PostController extends Controller
{
    /**
     * List Posts.
     *
     * @Route("/posts", name="avro_blog_post_list")
     * @Template()
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
     * Create a new Post.
     *
     * @Route("/post/new", name="avro_blog_post_new")
     * @Template()
     * @Secure(roles="ROLE_USER")
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
     * @Route("/post/edit/{id}", name="avro_blog_post_edit")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id)
    {
        $post = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('AvroBlogBundle:Post')
            ->find($id);

        if (!$post) {
            throw $this->createNotFoundException('No post found');
        }

        $section = $this->get('request')->query->get('section');

        $form = $this->createForm(new PostFormType($section), $post);
        if (true === $this->processForm($form, $post, $section)) {
            $this->get('session')->getFlashBag()->set('success', 'Post updated.');

            return new RedirectResponse($this->get('router')->generate('avro_blog_post_show', array('slug' => $post->getSlug())), 301);
        }

        return array(
            'form' => $form->createView(),
            'post' => $post,
            'admin' => $this->isAdmin(),
            'section' => $section
        );
    }

    /**
     * Show one Post.
     *
     * @Route("/post/show/{slug}", name="avro_blog_post_show")
     * @Template()
     */
    public function showAction($slug)
    {
        $post = $this->getPost($slug);

        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $tags = $dm->getRepository('AvroBlogBundle:Tag')->findAll();

        return array(
            'post' => $post,
            'admin' => $this->isAdmin(),
            'tags' => $tags
        );
    }

    /**
     * Process Post form
     *
     * @param postFormType $form
     * @return boolean true is successful
     */
    public function processForm($form, $post, $section)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if (true === $form->isValid()) {
                $dm = $this->get('doctrine.odm.mongodb.document_manager');

                $image = $post->getImage();
                if ($image) {
                    $file = $image->getFile();
                    if ($file) {
                        if ($file->getFile() instanceof UploadedFile) {
                            $path = $file->getFile()->getRealPath();
                            $file->setFile($path);
                            $image->setFile($file);
                            $post->setImage($image);
                        }
                    }
                }

                $dm->persist($post);
                $dm->flush();

                return true;
            }
        }

        return false;
    }

    /**
     * @Route("/widget", name="avro_blog_post_widget")
     * @Template()
     */
    public function widgetAction()
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $qb = $dm->createQueryBuilder('AvroBlogBundle:Post');
        $qb->sort('createdAt');
        $qb->limit(10);
        $posts = $qb->getQuery()->execute();

        $qb = $dm->createQueryBuilder('AvroBlogBundle:Tag');
        $qb->sort('createdAt');
        $qb->limit(10);
        $tags = $qb->getQuery()->execute();


        return array(
            'posts' => $posts,
            'tags' => $tags
        );
    }

    /**
     * Delete onePost.
     *
     * @Route("/post/delete/{id}", name="avro_blog_post_delete")
     */
    public function deleteAction($id)
    {
        $post = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('AvroBlogBundle:Post')
            ->find($id);

        $post->setIsDeleted(true);

        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $dm->persist($post);
        $dm->flush();

        $this->container->get('session')->getFlashBag()->set('success', 'Post deleted.');

        $uri = $this->get('request')->headers->get('referer');

        return new RedirectResponse($uri);
    }

    /**
     * Restore one Post.
     *
     * @Route("/post/restore/{id}", name="avro_blog_post_restore")
     */
    public function restoreAction($id)
    {
        $post = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('AvroBlogBundle:Post')
            ->find($id);

        $post->setIsDeleted(false);
        $post->setDeletedAt(null);

        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $dm->persist($post);
        $dm->flush();

        $this->container->get('session')->getFlashBag()->set('success', 'Post restored.');

        $uri = $this->get('request')->headers->get('referer');

        return new RedirectResponse($uri);
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
        $context = $this->get('security.context');

        $user = $context->getToken()->getUser();

        if ($context->isGranted("ROLE_ADMIN")) {
            return true;
        }

        return false;
    }
}
