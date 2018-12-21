<?php
namespace Comments\Controller;

use Aptero\Mvc\Controller\AbstractActionController;

use Comments\Form\AddCommentForm;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class CommentsController extends AbstractActionController
{
    public function addCommentAction()
    {
        $form = new AddCommentForm();

        if($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if($form->isValid()) {
                $comment = $this->getCommentsService()->addComment($form->getData());
                return new JsonModel(['html' => $this->viewHelper('comments', $comment)]);
            } else {
                return $jsonModel = new JsonModel(['errors' => $form->getMessages()]);
            }
        }

        $type = $this->params()->fromQuery('type');
        $dependId = $this->params()->fromQuery('depend');

        $form->setData([
            'depend_id'   => $dependId,
            'depend_type' => $type,
        ]);

        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables([
            'form' => $form,
            'type' => $type,
        ]);

        return $view;
    }

    /**
     * @return \Comments\Service\CommentsService
     */
    public function getCommentsService()
    {
        return $this->getServiceLocator()->get('Comments\Service\CommentsService');
    }
}