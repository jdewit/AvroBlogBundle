<?php

namespace Avro\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;

use Avro\BlogBundle\Form\Type\TagFormType;

/**
 * Tag controller.
 *
 * @Route("/tag")
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class TagController extends ContainerAware
{
    /**
     * List Tags.
     *
     */
    public function listAction()
    {
        $tags = $this->container->get('avro_blog.tag_manager')->findAll();

        return $this->container->get('templating')->renderResponse('AvroBlogBundle:Tag:list.html.twig', array(
            'tags' => $tags
        ));
    }

    /**
     * Create one Tag
     */
    public function newAction()
    {
        $tagManager = $this->container->get('avro_blog.tag_manager');
        $tag = $tagManager->create();

        $form = $this->container->get('form.factory')->create(new TagFormType(), $post);

        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $tagManager->update($tag);
                $this->get('session')->getFlashBag()->set('success', 'Tag created.');

                return new RedirectResponse($this->container->get('router')->generate('avro_blog_tag_list'));
            }
        }

        return $this->container->get('templating')->renderResponse('AvroBlogBundle:Tag:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Edit one Tag, show the edit form.
     */
    public function editAction($id)
    {
        $tagManager = $this->container->get('avro_blog.tag_manager');

        $tag = $tagManager->find($id);

        if (!$tag) {
            throw $this->createNotFoundException('No tag found');
        }

        $form = $this->container->get('form.factory')->create(new TagFormType(), $post);
        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $tagManager->update($tag);
                $this->get('session')->getFlashBag()->set('success', 'Tag updated.');

                return new RedirectResponse($this->container->get('router')->generate('avro_blog_tag_list'));
            }
        }


        return $this->container->get('templating')->renderResponse('AvroBlogBundle:Tag:edit.html.twig', array(
            'form' => $form->createView(),
            'tag' => $tag
        ));
    }

    /**
     * Delete oneTag.
     */
    public function deleteAction($id)
    {
        $tagManager = $this->container->get('avro_blog.tag_manager');
        $tag = $tagManager->find($id);

        $tagManager->delete($tag);

        $this->container->get('session')->getFlashBag()->set('success', 'Tag deleted.');

        return new RedirectResponse($this->container->get('router')->generate('avro_blog_tag_list'));
    }
}
