<?php

namespace Avro\BlogBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Tag controller.
 *
 * @Route("/tag")
 */
class TagController extends ContainerAware
{
    /**
     * Show all Tags.
     *
     * @Route("/list/{filter}", defaults={"filter" = "All"}, name="avro_blog_tag_list")
     * @Template()     
     */
    public function listAction($filter)
    {
        switch ($filter):
            case 'All':
                $tags = $this->container->get('avro_blog.tag_manager')->findAllTags();           
            break;
            case 'Deleted':
                $tags = $this->container->get('avro_blog.tag_manager')->findTagsBy(array('isActive' => false));            
            break;            
        endswitch;      

        return array(
            'tags' => $tags,
            'filter' => $filter
        );
    }

    /**
     * Create a new tag.
     *
     * @Route("/new", name="avro_blog_tag_new")
     * @Template()     
     */
    public function newAction()
    {
        $form = $this->container->get('avro_blog.tag.form');
        $formHandler = $this->container->get('avro_blog.tag.form.handler');

        $process = $formHandler->process();
        if ($process) {
            $this->container->get('session')->getFlashBag()->set('success', 'Tag created.');
            $tag = $form->getData('tag');

            return new RedirectResponse($this->container->get('router')->generate('avro_blog_tag_edit', array('id' => $tag->getId())), 301);
        }

        return array(
            'form' => $form->createview(),
            'ajax' => $this->container->get('request')->isXmlHttpRequest(),
        );

    }

    /**
     * Edit one tag, show the edit form.
     *
     * @Route("/edit/{id}", name="avro_blog_tag_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $tag = $this->container->get('avro_blog.tag_manager')->findTag($id);
        $form = $this->container->get('avro_blog.tag.form');
        $formHandler = $this->container->get('avro_blog.tag.form.handler');

        $process = $formHandler->process($tag);
        if ($process) {
            $this->container->get('session')->getFlashBag()->set('success', 'Tag updated.');

            return new RedirectResponse($this->container->get('router')->generate('avro_blog_tag_edit', array('id' => $id)));
        }

        return array(
            'form' => $form->createview(),
            'tag' => $tag,
        );
    }

    /**
     * Delete one Tag.
     *
     * @Route("/delete/{id}", name="avro_blog_tag_delete")
     * @Method("post")     
     */
    public function deleteAction($id)
    {
        $tag = $this->get('avro_blog.tag_manager')->findTag($id);
        $this->container->get('avro_blog.Tag_manager')->deleteTag($Tag);
        $this->container->get('session')->getFlashBag()->set('success', 'Tag deleted.');
        
        return new RedirectResponse($this->container->get('router')->generate('avro_blog_tag_list'));     
    }
       
}
