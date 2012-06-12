<?php
namespace Avro\BlogBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Avro\BlogBundle\Entity\Comment;
use Avro\BlogBundle\Entity\CommentManager;

/*
 * Comment Form Handler
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class CommentFormHandler
{
    protected $form;
    protected $request;
    protected $commentManager;

    public function __construct(Form $form, Request $request, CommentManager $commentManager)
    {
        $this->form = $form;
        $this->request = $request;  
        $this->commentManager = $commentManager;
    }

    /*
     * Process the form
     *
     * @param Comment 
     *
     * @return boolean true if successful
     * @return array $errors if unsuccessful
     */
    public function process(Comment $comment = null)
    {
        if (null === $comment) {
            $comment = $this->commentManager->create();
        }

        $this->form->setData($comment);

        if ('POST' == $this->request->getMethod()) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($comment);

                return true;
            }
        }

        return false;
    }

    /*
     * Update Comment if valid
     *
     * @param Comment
     */
    protected function onSuccess(Comment $comment)
    {
        $this->commentManager->update($comment);
    }
}
