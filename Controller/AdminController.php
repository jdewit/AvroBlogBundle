<?php

namespace Avro\BlogBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends ContainerAware
{
                        
    /**
     * Show one admin.
     *
     * @Route("/show/{id}", name="avro_blog_admin_show")
     * @Template()
     */
    public function showAction($id)
    {
        $admin = $this->container->get('avro_blog.admin_manager')->findAdmin($id);

        return array(
            'admin' => $admin,
        );
    }
                      
}
