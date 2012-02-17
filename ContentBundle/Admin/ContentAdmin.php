<?php


namespace Iphp\ContentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\FormatterBundle\Formatter\Pool as FormatterPool;

use Knp\Menu\ItemInterface as MenuItemInterface;


class ContentAdmin extends Admin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var Pool
     */
    protected $formatterPool;

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
        // ->add('author')
                ->add('enabled', null, array( 'label' => 'Показывать на сайте'))
                ->add('title', null, array('label' => 'Заголовок'))
                ->add('abstract', null, array('label' => 'Анонс'))
                ->add('content', null, array('label' => 'Текст'));
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
                ->add('enabled', null, array('required' => false, 'label' => 'Показывать на сайте'))

        //  ->add('rubric', 'sonata_type_model', array('label' => 'Рубрика', 'required' => true), array('edit' => 'list'))


                ->add('rubric', null,
            array(
                'label' => 'Рубрика',
                'property' => 'titleLevelIndented'))


        // ->add('author', 'sonata_type_model', array( 'label' => 'Автор'), array('edit' => 'list'))
        //  ->add('images', 'sonata_type_model', array(), array('edit' => 'inline' /*,
        //                                                    'inline' => 'table' */))
                ->add('title', null, array('label' => 'Заголовок'))
                ->add('abstract', null, array('label' => 'Анонс'))
          /*      ->add('contentFormatter', 'sonata_formatter_type_selector',
            array(
                'source' => 'rawContent',
                'target' => 'content',
                'label' => 'Форматирование'
            ))*/
                ->add('rawContent', null, array('label' => 'Текст'))
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
                ->addIdentifier('title', null, array('label' => 'Заголовок'))
                ->add('rubric', null, array('label' => 'Рубрика'))
                ->add('enabled', null, array('label' => 'Показывать на сайте'));
        // ->add('commentsEnabled');
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('title')
                ->add('enabled')
                ->add('author');
    }

    /**
     * @param $queryBuilder
     * @param $alias
     * @param $field
     * @param $value
     *
     * @return void
     *
    public function getWithOpenCommentFilter($queryBuilder, $alias, $field, $value)
    {
    if (!$value) {
    return;
    }

    $queryBuilder->leftJoin(sprintf('%s.comments', $alias), 'c');
    $queryBuilder->andWhere('c.status = :status');
    $queryBuilder->setParameter('status', Comment::STATUS_MODERATE);
    }*/

    /**
     * @param \Knp\Menu\ItemInterface $menu
     * @param $action
     * @param null|\Sonata\AdminBundle\Admin\Admin $childAdmin
     *
     * @return void
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, Admin $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            $this->trans('Content Show'),
            array('uri' => $admin->generateUrl('show', array('id' => $id)))
        );

        /*   $menu->addChild(
            $this->trans('sidemenu.link_view_comments'),
            array('uri' => $admin->generateUrl('sonata.news.admin.comment.list', array('id' => $id)))
        );*/
    }

    public function setUserManager($userManager)
    {
        $this->userManager = $userManager;
    }

    public function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * @param \Sonata\FormatterBundle\Formatter\Pool $formatterPool
     *
     * @return void
     */
    public function setPoolFormatter(FormatterPool $formatterPool)
    {
        $this->formatterPool = $formatterPool;
    }

    /**
     * @return \Sonata\FormatterBundle\Formatter\Pool
     */
    public function getPoolFormatter()
    {
        return $this->formatterPool;
    }

    /**
     * @param ContentInterface $content
     */
    public function prePersist($content)
    {
        $content->setContentFormatter('raw');

         $content->setContent($this->getPoolFormatter()->transform($content->getContentFormatter(), $content->getRawContent()));
    }

    /**
     * @param ContentInterface $content
     */
    public function preUpdate($content)
    {
        $content->setContentFormatter('raw');
        $content->setContent($this->getPoolFormatter()->transform($content->getContentFormatter(), $content->getRawContent()));
    }
}
