<?php
namespace Comments\Controller;

use Aptero\Mvc\Controller\AbstractActionController;

use Comments\Form\AddCommentForm;
use Zend\View\Model\JsonModel;

class CommentsController extends AbstractActionController
{
    public function indexAction()
    {
        $this->generate();

        $comments = $this->getCommentsService();

        return [
            'comments' => $comments
        ];
    }
    public function addCommentAction()
    {
        $form = new AddCommentForm();
        $form->setData($this->params()->fromPost());

        if($form->isValid()) {
            $comment = $this->getCommentsService()->addComment($form->getData());
            return new JsonModel(['html' => $this->viewHelper('comments', $comment)]);
        } else {
            return $jsonModel = new JsonModel(['errors' => $form->getMessages()]);
        }
    }

    /**
     * @return \Comments\Service\CommentsService
     */
    public function getCommentsService()
    {
        return $this->getServiceLocator()->get('Comments\Service\CommentsService');
    }
}