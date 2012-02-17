<?php

namespace Iphp\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends RubricController
{
    /**
     * Вызывается в шаблоне с помощью render
     * @param string $template
     * @param string $rubric
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function MenuAction($template = '', $rubric = '')
    {
        $rubrics = $this->getRubricsRepository()->getTreeRecordset(
            function($qb)
            {
                $qb->andWhere('r.level > 0')->orderBy('r.left', 'ASC');
            });

        return $this->render($template, array('rubrics' => $rubrics, 'currentRubric' => $rubric));
    }
}
