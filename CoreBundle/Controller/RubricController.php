<?php

namespace Iphp\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RubricController extends Controller
{

    function getRubricsRepository()
    {
        return $this->getDoctrine()->getRepository('ApplicationIphpCoreBundle:Rubric');
    }


    /**
     *  TODO: есть дублирование с ContentController
     */
    protected function getRubricManager()
    {
        return $this->container->get('iphp.core.rubric.manager');
    }

    protected function getCurrentRubric()
    {
        return $this->getRubricManager()->getCurrent();
    }


    public function indexSubrubricsAction()
    {
        $rubrics = $this->getRubricManager()->getCurrent()->getChildren();

        return $this->render('IphpCoreBundle::indexSubrubrics.html.twig', array('rubrics' => $rubrics));
    }
}
