<?php

namespace Iphp\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Application\Iphp\CoreBundle\Entity\Rubric;




//use Symfony\Component\HttpFoundation\RedirectResponse;


// these import the "@Route" and "@Template" annotations
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ContentController extends Controller
{


    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('ApplicationIphpContentBundle:Content');
    }


    protected function getRubricIndex(Rubric $rubric)
    {
        return $this->getRepository()->rubricIndex($rubric);
    }


    protected function getRubricManager()
    {
        return $this->container->get('iphp.core.rubric.manager');
    }

    protected function getCurrentRubric()
    {
        return $this->getRubricManager()->getCurrent();
    }


    public function indexAction()
    {
        $content = $this->getRubricIndex($this->getCurrentRubric());

        if (!$content) throw $this->createNotFoundException('Индексный материал не найден');


        return $this->render('IphpContentBundle::content.html.twig',
                             array('content' => $content));
    }


    function listAction()
    {
        $contents = $this->getRepository()->findByRubric ($this->getCurrentRubric());


        return $this->render('IphpContentBundle::list.html.twig',
                                     array('contents' => $contents));

    }

    public function contentByIdAction($id)
    {
        $content = $this->getRepository()->findByRubricAndSlug ($this->getCurrentRubric(), $id);

        if (!$content) throw $this->createNotFoundException('Материал с кодом "'.$id.'" не найден');

        return $this->render('IphpContentBundle::content.html.twig',
                                             array('content' => $content));

    }
}
