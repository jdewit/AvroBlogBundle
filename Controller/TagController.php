<?php

namespace Avro\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Avro\BlogBundle\Document\Tag;
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
     * @Route("/", name="avro_blog_tag_list")
     * @Template()
     */
    public function listAction()
    {
        $paginator = $this->get('avro_paginator.paginator');
        $paginator->setClass('AvroBlogBundle:Tag');
        $tags = $paginator->getResults();

        return array(
            'tags' => $tags,
            'paginator' => $paginator,
        );
    }


    /**
     * @Route("/widget", name="avro_blog_tag_widget")
     * @Template()
     */
    public function widgetAction()
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $tags = $dm->getRepository('AvroBlogBundle:Tag')->findAll();

        return array(
            'tags' => $tags
        );
    }

    /**
     * Create one Tag
     *
     * @Route("/new", name="avro_blog_tag_new")
     * @Template()
     *
     */
    public function newAction()
    {
        $tag = new Tag();
        $form = $this->createForm(new TagFormType(), $tag);

        if ($this->processForm($form, $tag)) {
            $this->get('session')->getFlashBag()->set('success', 'Tag created.');

            $uri = $this->get('request')->headers->get('referer');

            return new RedirectResponse($uri);
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Edit one Tag, show the edit form.
     *
     * @Route("/edit/{id}", name="avro_blog_tag_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $tag = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('AvroBlogBundle:Tag')
            ->find($id);

        if (!$tag) {
            throw $this->createNotFoundException('No tag found');
        }

        $form = $this->createForm(new TagFormType(), $tag);
        if ($this->processForm($form, $tag)) {
            $this->get('session')->getFlashBag()->set('success', 'Tag updated.');

            return new RedirectResponse($this->generateUrl('avro_blog_tag_list'));
        }

        return array(
            'form' => $form->createView(),
            'tag' => $tag
        );
    }

    /**
     * Delete oneTag.
     *
     * @Route("/delete/{id}", name="avro_blog_tag_delete")
     */
    public function deleteAction($id)
    {
        $tag = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('AvroBlogBundle:Tag')
            ->find($id);


        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $dm->remove($tag);
        $dm->flush();

        $this->container->get('session')->getFlashBag()->set('success', 'Tag deleted.');

        $uri = $this->get('request')->headers->get('referer');

        return new RedirectResponse($uri);
    }

    /**
     * Restore one Tag.
     *
     * @Route("/restore/{id}", name="avro_blog_tag_restore")
     */
    public function restoreAction($id)
    {
        $tag = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('AvroBlogBundle:Tag')
            ->find($id);

        $tag->setIsDeleted(false);
        $tag->setDeletedAt(null);

        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $dm->persist($tag);
        $dm->flush();

        $this->container->get('session')->getFlashBag()->set('success', 'Tag restored.');

        $uri = $this->get('request')->headers->get('referer');

        return new RedirectResponse($uri);
    }

    /**
     * Process Tag Form
     */
    public function processForm($form, $tag)
    {
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if (true === $form->isValid()) {
                $dm = $this->get('doctrine.odm.mongodb.document_manager');

                $dm->persist($tag);
                $dm->flush();

                return true;
            }
        }

        return false;
    }
}
