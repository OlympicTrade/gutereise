<?php
namespace Faq\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FaqFooter extends AbstractHelper
{
    /**
     * @var \Faq\Model\Faq
     */
    protected $faq = null;

    public function __construct($faq)
    {
        $this->faq = $faq;
    }

    public function __invoke()
    {
        $html =
            '<div class="faq">'
                .'<div class="header"><a href="/faq/">Контакты</a></div>'
                .'<div class="body">'
                    .$this->row('', 'email')
                    .$this->row('', 'skype')
                    .$this->row('', 'phone_1')
                    .$this->row('', 'address')
                .'</div>'
            .'</div>';

        return $html;
    }

    protected function row($name, $field)
    {
        $html = '';

        if($this->faq->get($field)) {
            $html .=
                '<div class="row ' . $field . '">'
                    .'<div class="icon"><i class="fa"></i></div>'
                    . $this->faq->get($field)
                .'</div>';
        }

        return $html;
    }
}