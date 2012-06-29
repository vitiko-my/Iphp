<?php

namespace Iphp\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Application\Iphp\CoreBundle\Entity\Rubric;
use Iphp\CoreBundle\Controller\RubricAwareController;


class ContentController extends RubricAwareController
{


    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('ApplicationIphpContentBundle:Content');
    }


    protected function getRubricIndex(Rubric $rubric)
    {
        return $this->getRepository()->rubricIndex($rubric);
    }

    public function indexAction()
    {
        $content = $this->getRubricIndex($this->getCurrentRubric());

        //if (!$content) throw $this->createNotFoundException('Индексный материал не найден');
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
            $qb->fromRubric($rubric)->whereEnabled()
                    ->addOrderBy ('c.date','DESC')->addOrderBy ('c.updatedAt','DESC');
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


    function searchAction(Request $request)
    {
        $searchStr = $request->get ('search');


        $query =    $searchStr  ? $this->getRepository()->createQuery('c', function ($qb) use($searchStr)
        {
            $qb->whereEnabled()->search ($searchStr);
        }) : null;

        return $this->render(
            'IphpContentBundle::search.html.twig',
            array('contents' => $query ? $this->paginate($query, 20) :  array(), 'searchStr' => $searchStr ));
    }
}
