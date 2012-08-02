<?php

namespace Iphp\CoreBundle\Controller;

use \Elastica_Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends RubricAwareController
{


    /**
     * @param $index
     * @param $type
     * @return \FOQ\ElasticaBundle\Finder\PaginatedFinderInterface
     */
    public function getFinder($index, $type = '')
    {
        return $this->get('foq_elastica.finder.' . $index . ($type ? '.' . $type : ''));

    }


    public function searchAction(Request $request)
    {
        //$rubrics = $this->getRubricManager()->getCurrent()->getChildren();

        $searchString = trim($request->get('search'));

        print '"' . $searchString . '"';
        $finder = $this->getFinder('iphp'/*,'content'*/);


        if ($searchString) {

            $query = Elastica_Query::create($searchString);
            //$query->addParam('analyzer', 'ru_analyzer');
            $paginator = $this->get('knp_paginator');
            $results = $paginator->paginate($finder->createPaginatorAdapter($query ));
        }
        else $results = null;

        //print sizeof(  $contents );

        return $this->render('IphpCoreBundle:Search:search.html.twig', array(

            'title' => $this->getCurrentRubric()->getTitle(),
            'search' => $searchString,
            'results' => $results
        ));
    }


}
