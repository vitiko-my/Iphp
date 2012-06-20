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


    public function indexSiteAction()
    {
        return $this->render('IphpCoreBundle::indexSite.html.twig', array());
    }


    public function redirectAction()
    {
        $rubric = $this->getRubricManager()->getCurrent();

       if (!$rubric->getRedirectUrl())
       {
           throw new \Exception ('redirect url not setted');
       }

        return new \Symfony\Component\HttpFoundation\RedirectResponse($rubric->getRedirectUrl());

    }
}
