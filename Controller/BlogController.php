<?php

namespace Avro\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;

use Avro\BlogBundle\Form\Type\PostFormType;

/**
 * Blog controller.
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
     * side Widget
     */
    public function sideWidgetAction()
    {
        $posts = $this->container->get('avro_blog.post_manager')->findAll();
        $tags = $this->container->get('avro_blog.tag_manager')->findAll();

        return $this->container->get('templating')->renderResponse('AvroBlogBundle:Blog:sideWidget.html.twig', array(
            'posts' => $posts,
            'tags' => $tags
        );
    }
}
