<?php
namespace Iphp\TreeBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;


class TreeAdmin extends Admin
{

    function getListTemplate()
    {
        return 'IphpTreeBundle:CRUD:tree.html.twig';
    }


/*    public function configure()
    {
       $this->templates['list'] = 'IphpTreeBundle:CRUD:tree.html.twig';
    }*/

/*    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getListTemplate(), array(
            'action'   => 'list',
            'form'     => $formView,
            'datagrid' => $datagrid
        ));
    }*/
}