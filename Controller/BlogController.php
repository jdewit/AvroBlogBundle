<?php

namespace Avro\BlogBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerAware;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Avro\BlogBundle\Document\Post;
use Avro\ImageBundle\Document\Image;
use Avro\RatingBundle\Document\Rating;
use Avro\BlogBundle\Form\Type\PostFormType;

/**
 * Post controller.
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class BlogController extends ContainerAware
{
    /**
     * Blog home page.
     */
    public function indexAction($slug)
    {
        $postManager = $this->container->get('avro_blog.post_manager');

        if ($slug) {
            $post = $postManager->findOneBy(array('slug' => $slug));

            return $this->container->get('templating')->renderResponse('AvroBlogBundle:Post:show.html.twig', array('post' => $post));
        }

        $posts = $postManager->findAll();

        return $this->container->get('templating')->renderResponse('AvroBlogBundle:Blog:index.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * @Template()
     */
    public function sideWidgetAction()
    {
        $posts = $this->container->get('avro_blog.post_manager')->findAll();
        $tags = $this->container->get('avro_blog.tag_manager')->findAll();

        return array(
            'posts' => $posts,
            'tags' => $tags
        );
    }

    /**
     * List Posts by tag.
     *
     * @Template()
     */
    public function tagAction($slug)
    {
        $paginator = $this->get('avro_paginator.paginator');
        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        $paginator->setClass('AvroBlogBundle:Post');

        $tag = false;
        if ($slug) {
            $tag = $dm->getRepository('AvroBlogBundle:Tag')->findOneBy(array('slug' => $slug));

            $paginator->addConstraint('tags.id', $tag->getId());
        }

        $posts = $paginator->getResults();

        return array(
            'posts' => $posts,
            'paginator' => $paginator,
            'tag' => $tag
        );
    }


}
