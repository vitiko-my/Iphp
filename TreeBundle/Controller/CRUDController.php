<?php

namespace Iphp\TreeBundle\Controller;


use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Controller\CRUDController as SonataCRUDController;

class CRUDController extends SonataCRUDController
{

    public function changePositionAction($id, $parent, $after)
    {

        $node = $this->admin->getObject($id);

        if ($parent == 0) $parent = 1;


        try {
            $this->admin->changePosition($node, $parent, $after);

            $result = true;
            $message = 'ОК';
        }
        catch (\Exception $exception)
        {
            $message = $exception->getMessage();
            $result = false;
        }
        $response = new Response(json_encode(
            array('id' => $id,
                'parent' => $parent,
                'after' => $after,
                'result' => $result,
                'message' => $message)));
        $response->headers->set('Content-Type', 'application/json');


        return $response;
    }


    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        //  $datagrid = $this->admin->getDatagrid();
        //  $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        //   $this->get('twig')->getExtension('form')->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getListTemplate(), array(
            'action' => 'list',
            'treeIterator' => $this->admin->getTreeIterator()
            // 'form' => $formView,
            //'datagrid' => $datagrid
        ));
    }


}
