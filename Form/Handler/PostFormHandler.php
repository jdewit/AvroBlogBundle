<?php
namespace Avro\BlogBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

use Avro\BlogBundle\Entity\Post;
use Avro\BlogBundle\Entity\PostManager;

class PostFormHandler
{
    protected $request;
    protected $postManager;
    protected $form;

    public function __construct(Form $form, Request $request, PostManager $postManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->postManager = $postManager;
    }

    public function process(Post $post = null)
    {
        if (null === $post) {
            $post = $this->postManager->createPost();
        }

        $this->form->setData($post);

        if ('POST' == $this->request->getMethod()) {
            $this->form->bindRequest($this->request);
            if ($this->form->isValid()) {
                $this->onSuccess($post);

                return true;
            }
        }

        return false;
    }

    protected function onSuccess(Post $post)
    {
        $this->postManager->updatePost($post);
    }
}
