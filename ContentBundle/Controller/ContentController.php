<?php

namespace Iphp\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\RedirectResponse;


// these import the "@Route" and "@Template" annotations
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ContentController extends Controller
{
    public function indexAction()
    {
     die ('Yopty!');
       // return $this->render('AcmeDemoBundle:Welcome:index.html.twig');
    }


    public function contentByIdAction($id)
    {
        die ('Yopty2222! - '.$id);
    }
}
