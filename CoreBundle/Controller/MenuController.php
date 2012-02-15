<?php

namespace Iphp\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends Controller
{

    function getRubricsRepository()
    {
        return $this->getDoctrine()->getRepository('ApplicationIphpCoreBundle:Rubric');
    }

    public function MenuAction($template = '', $rubric = '')
    {


        $rubrics = $this->getRubricsRepository()->getTreeRecordset(
            function($qb)
            {
                $qb->andWhere('r.level > 0')->orderBy('r.left', 'ASC');
            });


        // print '--->'.$rubric.':';
        // print_r ($this->container->get ('request')->get('_route'));


        //print serialize($rubrics[0]);
        return $this->render($template, array('rubrics' => $rubrics, 'currentRubric' => $rubric));

    }
}
