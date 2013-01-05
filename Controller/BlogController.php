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
use Avro\RatingBundle\Document\Rating;
use Avro\BlogBundle\Form\Type\PostFormType;

/**
 * Post controller.
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class BlogController extends Controller
{
    /**
     * List Posts.
     *
     * @Route("/blog/{slug}", name="avro_blog_blog_index", defaults={"slug"=false})
     * @Template()
     */
    public function indexAction($slug)
    {
        $paginator = $this->get('avro_paginator.paginator');
        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        $paginator->setClass('AvroBlogBundle:Post');
        $paginator->sortBy('createdAt', 'desc');
        $post = false;
        if ($slug) {
            $post = $dm->getRepository('AvroBlogBundle:Post')->findOneBy(array('slug' => $slug));
            return $this->get('templating')->renderResponse('AvroBlogBundle:Post:show.html.twig', array('post' => $post));
        }

        $posts = $paginator->getResults();

        return array(
            'posts' => $posts,
            'paginator' => $paginator
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
