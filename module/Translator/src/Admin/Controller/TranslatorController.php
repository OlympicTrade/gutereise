<?php
namespace TranslatorAdmin\Controller;

use Aptero\Mvc\Controller\Admin\AbstractActionController;
use Aptero\Service\Admin\TableService;
use TranslatorAdmin\Model\Translator;
use TransportsAdmin\Model\Transport;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\View\Model\JsonModel;

class TranslatorController extends AbstractActionController
{
    public function indexAction()
    {
        $this->generate();

        $list = Translator::getEntityCollection();

        $filters = $this->params()->fromQuery();

        switch ($filters['filter']) {
            case 'empty-de':
                $list->select()->where(['de' => '']);
                break;
            case 'empty-en':
                $list->select()->where(['en' => '']);
                break;
            case 'no-url':
                $list->select()->where(['url' => '']);
                break;
            case 'no-editor':
                $list->select()->where
                    ->notLike('ru', '%</p>%');
                break;
            default:
        }

        if($filters['search']) {
            $list->select()->where->like('ru', '%' . $filters['search'] . '%');
        }

        $list->select()
            ->order('ru DESC')
            ->where->in('id', $list->getSql()->select($list->table())
                ->columns([new Expression('MIN(id)')])
                ->group('ru')
            );

        return [
            'items' => $list->getPaginator($_GET['page'],20)
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