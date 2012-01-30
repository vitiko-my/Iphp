<?php


namespace Iphp\CoreBundle\Admin;


use Iphp\TreeBundle\Admin\TreeAdmin;




use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\FormatterBundle\Formatter\Pool as FormatterPool;

use Knp\Menu\ItemInterface as MenuItemInterface;


class RubricAdmin extends TreeAdmin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;


    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
                ->add('status')
                ->add('title')
                ->add('abstract');

    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->with('Основные')
                ->add('status', null, array('required' => false, 'label' => 'Показывать на сайте'))

                ->add('title', null, array('label' => 'Заголовок'))
                ->add('parent', null, array(
                                           'label' => 'Родительская рубрика',
                                           'property' => 'titleLevelIndented',
                                           /*    'required' => true*/),
                      array('template' => 'dsadas'))
                ->add('path', 'text', array('label' => 'Директория'))
                ->add('abstract', null, array('label' => 'Анонс'))
                ->add('controllerName', null, array('label' => 'Название контроллера'))
                ->end()// ->with('Options', array('collapsed' => true))
            //  ->add('commentsCloseAt')
            //  ->add('commentsEnabled', null, array('required' => false))
            // ->add('commentsDefaultStatus', 'choice', array('choices' => Comment::getStatusList(), 'expanded' => true))
            //   ->end();

        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('title', null, array(
                                                    'label' => 'Заголовок',
                                                    'template' => 'IphpTreeBundle:CRUD:base_treelist_field.html.twig '))
                ->add('fullPath', null, array('label' => 'Путь'))
                ->add('controllerName', null, array('label' => 'Контроллер'));

    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('title');

    }


/*    protected function configureSideMenu(MenuItemInterface $menu, $action, Admin $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            $this->trans('sidemenu.link_viewcontent'),
            array('uri' => $admin->generateUrl('edit', array('id' => $id)))
        );

    }*/

    public function setUserManager($userManager)
    {
        $this->userManager = $userManager;
    }

    public function getUserManager()
    {
        return $this->userManager;
    }

}
