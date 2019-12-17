<?php
namespace TranslatorAdmin\Controller;

use Aptero\Mvc\Controller\Admin\AbstractActionController;
use Aptero\Service\Admin\TableService;
use TranslatorAdmin\Model\Translator;
use TransportsAdmin\Model\Transport;
use Zend\View\Model\JsonModel;

class TranslatorController extends AbstractActionController
{
    public function indexAction()
    {
        $this->generate();

        $translatorList = Translator::getEntityCollection();

        $filters = $this->params()->fromQuery();

        switch ($filters['filter']) {
            case 'empty-de':
                $translatorList->select()->where(['de' => '']);
                break;
            case 'empty-en':
                $translatorList->select()->where(['en' => '']);
                break;
            default:
        }

        if($filters['search']) {
            $translatorList->select()->where->like('ru', '%' . $filters['search'] . '%');
        }

        $translatorList->select()
            ->order('id DESC');

        return [
            'items' => $translatorList->getPaginator()
        ];
    }

    public function syncAction()
    {
        $save = function ($lang) {
            $translatorList = Translator::getEntityCollection();
            $translatorList->select()
                ->order('id DESC')
                ->where
                    ->notEqualTo('code', '')
                    ->notEqualTo('ru', '')
                    ->notEqualTo($lang, '');

            $arr = [];

            foreach ($translatorList as $row) {
                $row->set('code', Translator::getCode($row->get('ru')))->save();

                $str = $row->get($lang);
                if(strpos($str, '<p>') === false) {
                    $str = str_replace(["\n", "\r"], ['<br>', ''], $str);
                }

                $arr[$row->get('code')] = $str;
            }

            file_put_contents(MAIN_DIR . '/module/Translator/translates/' . $lang . '.php', '<?php return ' . var_export($arr, true) . ';');
        };

        $save('en');
        $save('de');

        return new JsonModel([]);
    }

    public function updateAction()
    {
        $data = $this->params()->fromPost();
        $id = $data['id'];

        $translator = Translator::factory(['id' => $id]);
        $translator->set($data['lang'], $data['text']);

        $translator->save();

        return new JsonModel([
            'id' => $translator->getId()
        ]);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromPost('id');
        $translator = Translator::factory(['id' => $id]);
        $translator->remove();
        return new JsonModel([]);
    }
}