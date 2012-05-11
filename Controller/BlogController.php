<?php

namespace Avro\BlogBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Blog controller.
 *
 * @Route("/blog")
 */
class BlogController extends ContainerAware
{
    /**
     * Show all Posts.
     *
     * @Route("/", name="avro_blog_blog_index")
     * @Template()     
     */
    public function indexAction()
    {
        $posts = $this->container->get('avro_blog.post_manager')->findAllPosts();           

        return array(
            'posts' => $posts 
        );
    }
}

