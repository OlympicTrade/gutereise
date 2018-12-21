<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Json\Json;

class Breadcrumbs extends AbstractHelper
{
    public function __invoke($crumbs = null, $options = [])
    {
        $view = $this->getView();

        if($crumbs == null) {
            $crumbs = $view->breadcrumbs;
        }

        $options = array_merge(array(
            'delimiter' => '/', //' <i class="fa fa-angle-right"></i> ',
            'allLinks'  => false,
            'lastItem'  => 'span',
            'wrapper'   => false,
        ), $options);

        $html = '';

        if($options['wrapper']) {
            $html .=
                '<div class="block std-breadcrumbs">'
                    .'<div class="wrapper">';
        } else {
            $html .=
                '<div class="std-breadcrumbs">';
        }

        $count = $options['allLinks'] ? count($crumbs) : count($crumbs) - 1;

        for($i = 0; $i < $count; $i++) {
            $crumb = $crumbs[$i];

            $html .=
                '<div class="item">'
                    .'<a href="' . $crumbs[$i]['url'] . '">'
                        . $view->tr($crumb['name'], false)
                    .'</a>';

            if(isset($crumb['options'])) {
                $html .=
                    '<div class="options">';

                foreach($crumb['options'] as $url => $name) {
                    $html .= '<a href="' . $url . '">' . $view->tr($name, false) . '</a>';
                }

                $html .=
                    '</div>';
            }

            $html .=
                '</div>'
                . ($i + 1 < $count ?  '<div class="sep">' . $options['delimiter'] . '</div>' : '');
        }

        if(!$options['allLinks'] && $options['lastItem']) {
            $html .= '<div class="sep">' . $options['delimiter'] . '</div><div class="item"><' . $options['lastItem'] . ' class="crumb">' .  $view->tr($crumbs[$i]['name'], false) . '</' . $options['lastItem'] . '></div>';
        }

        if($options['wrapper']) {
            $html .=
                    '</div>'
                .'</div>';
        } else {
            $html .=
                '</div>';
        }

        //Json LD
        $ldCrumbs = array();
        for($i = 0; $i < count($crumbs); $i++) {
            $ldCrumbs[] = (object) array(
                '@type'    => 'ListItem',
                'position' => ($i + 1),
                'item' => (object) array(
                    '@id'  => 'http://' . $_SERVER['HTTP_HOST'] . $crumbs[$i]['url'],
                    'name' => $view->tr($crumbs[$i]['name'], false)
                )
            );
        }

        $jsonLd = (object) array(
            '@context'     => 'http://schema.org',
            '@type'        => 'BreadcrumbList',
            'itemListElement' => $ldCrumbs
        );

        $html .= '<script type="application/ld+json">' . Json::encode($jsonLd) . '</script>';

        return $html;
    }
}