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


    function getPaginator()
    {
        return $this->get('knp_paginator');
    }

    function paginate($query, $itemPerPage)
    {
        return $this->getPaginator()->paginate(
            $query,
            $this->get('request')->query->get('page', 1) /*page number*/,
            $itemPerPage/*limit per page*/
        );
    }

    function listAction()
    {
        $rubric = $this->getCurrentRubric();
        $query = $this->getRepository()->createQuery('c', function ($qb) use ($rubric)
        {
            $qb->fromRubric($rubric)->whereEnabled();
        });

        return $this->render(
            'IphpContentBundle::list.html.twig',
            array('contents' => $this->paginate($query, 20)));
    }

    public function contentBySlugAction($slug)
    {
        $rubric = $this->getCurrentRubric();
        $content = $this->getRepository()->createQuery('c', function ($qb) use ($rubric, $slug)
        {
            $qb->fromRubric($rubric)->whereSlug($slug)->whereEnabled();
        })->getOneOrNullResult();

        if (!$content) throw $this->createNotFoundException('Материал с кодом "' . $slug . '" не найден');

        return $this->render('IphpContentBundle::content.html.twig',
            array('content' => $content));

    }
}
