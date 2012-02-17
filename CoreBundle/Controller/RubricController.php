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
    protected function getRubricFactory()
    {
        return $this->container->get('iphp.core.rubric.fabric');
    }

    protected function getCurrentRubric()
    {
        return $this->getRubricFactory()->getCurrent();
    }


    public function indexSubrubricsAction()
    {
        $rubrics = $this->getRubricFactory()->getCurrent()->getChildren();

        return $this->render('IphpCoreBundle::indexSubrubrics.html.twig', array('rubrics' => $rubrics));
    }
}
