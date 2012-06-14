<?php

namespace Avro\BlogBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Comment controller.
 *
 * @Route("/comment")
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class CommentController extends ContainerAware
{
    /**
     * List  Comments.
     *
     * @Route("/list/{filter}", name="avro_blog_comment_list", defaults={"filter" = "All"})
     * @Template()
     */
    public function listAction($filter)
    {
        $form = $this->container->get('avro_crm.clientList.form');
        $form->bindRequest($this->container->get('request'));

        if ('POST' == $this->container->get('request')->getMethod()) {
            if ($form->isValid()) {
                $action = $form['action']->getData();
                switch($action) {
                    case 'Search':
                        $comments = $this->container->get('avro_blog.comment.manager')->search($form->getData());
                    break;
                    case 'Edit':

                    break;
                    case 'Export':

                    break;
                }
            }
        }

        return array(
            'comments' => $comments,
            'form' => $form->createView()
        );
    }
    /**
     * Create a new  Comment.
     *
     * @Route("/new", name="avro_blog_comment_new")
     * @Template()
     */
    public function newAction()
    {
        $commentForm = $this->container->get('avro_blog.comment.form');
        $formHandler = $this->container->get('avro_blog.comment.form.handler');

        $process = $formHandler->process();
        if ($process) {
            $comment = $commentForm->getData('comment');
            $this->container->get('session')->getFlashBag()->set('success', ' Comment created.');

            return new RedirectResponse($this->container->get('router')->generate('avro_blog_post_show', array('id' => $comment->getPost()->getId())), 301);
        }

        return new RedirectResponse($this->container->get('router')->generate('avro_blog_blog_index'), 301);
    }

    /**
     * Edit one  Comment, show the edit form.
     *
     * @Route("/edit/{id}", name="avro_blog_comment_edit", defaults={"id" = false})
     * @Template()
     */
    public function editAction($id)
    {
        $comment = $this->container->get('avro_blog.comment_manager')->find($id);
        $commentForm = $this->container->get('avro_blog.comment.form');
        $formHandler = $this->container->get('avro_blog.comment.form.handler');

        $process = $formHandler->process($comment);
        if ($process) {
            $comment = $commentForm->getData('comment');
            $this->container->get('session')->getFlashBag()->set('success', ' Comment updated.');

            return new RedirectResponse($this->container->get('router')->generate('avro_blog_comment_list'));
        }

        return array(
            'commentForm' => $commentForm->createView(),
            'comment' => $comment,
        );
    }

    /**
     * Delete one  Comment.
     *
     * @Route("/delete/{id}", name="avro_blog_comment_delete", defaults={"id" = false})
     */
    public function deleteAction($id)
    {
        $comment = $this->container->get('avro_blog.comment_manager')->find($id);
        $this->container->get('avro_blog.comment_manager')->softDelete($comment);

        $this->container->get('session')->getFlashBag()->set('success', ' Comment deleted.');

        return new RedirectResponse($this->container->get('router')->generate('avro_blog_comment_list'), 301);
    }

    /**
     * Restore one  Comment.
     *
     * @Route("/restore/{id}", name="avro_blog_comment_restore", defaults={"id" = false})
     */
    public function restoreAction($id)
    {
        if ($id) {
            $comment = $this->container->get('avro_blog.comment_manager')->find($id);
            $this->container->get('avro_blog.comment_manager')->restore($comment);

            $this->container->get('session')->getFlashBag()->set('success', ' Comment restored.');
        }

        return new RedirectResponse($this->container->get('router')->generate('avro_blog_comment_list'), 301);
    }

}
