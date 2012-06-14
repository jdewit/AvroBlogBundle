<?php
namespace Avro\BlogBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

use Avro\BlogBundle\Entity\Tag;
use Avro\BlogBundle\Entity\TagManager;

class TagFormHandler
{
    protected $request;
    protected $tagManager;
    protected $form;

    public function __construct(Form $form, Request $request, TagManager $tagManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->tagManager = $tagManager;
    }

    public function process(Tag $tag = null)
    {
        if (null === $tag) {
            $tag = $this->tagManager->createTag('');
        }

        $this->form->setData($tag);

        if ('POST' == $this->request->getMethod()) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($tag);

                return true;
            }
        }

        return false;
    }

    protected function onSuccess(Tag $tag)
    {
        $this->tagManager->updateTag($tag);
    }
}
