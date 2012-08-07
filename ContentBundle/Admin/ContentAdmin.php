<?php


namespace Iphp\ContentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

//use Sonata\FormatterBundle\Formatter\Pool as FormatterPool;

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

/*    function configure()
    {
        $this->configurationPool->getAdminByAdminCode('iphp.core.admin.rubric')
                ->addExtension( new RubricAdminExtension);
    }*/


    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
        // ->add('author')
                ->add('enabled', null, array('label' => 'Показывать на сайте'))
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
                ->add('title', null, array('label' => 'Заголовок'))
                ->add('enabled', null, array('required' => false, 'label' => 'Показывать на сайте'))
                ->add('slug')

                ->add('rubric', 'rubricchoice')


                ->add('author', 'sonata_type_model_list', array('required' => false)/*, array('edit' => 'list')*/)


                ->add('date', 'genemu_jquerydate', array(
            'required' => false, 'widget' => 'single_text'))
                ->add('abstract', null, array('label' => 'Анонс'))
        /*      ->add('contentFormatter', 'sonata_formatter_type_selector',
        array(
            'source' => 'rawContent',
            'target' => 'content',
            'label' => 'Форматирование'
        ))*/
                ->add('content', 'genemu_tinymce', array('label' => 'Текст'))
               ->add('image', 'sonata_type_model_list', array('required' => false),
            array(/*'edit' => 'list',*/ 'link_parameters' => array('context' => 'contentimage')))


                ->add('files', 'sonata_type_collection',
              array('label' => 'Файлы', 'by_reference' => false),
              array(
                  'edit' => 'inline',
               //   'sortable' => 'pos',
                  'inline' => 'table',
              ))

         /*       ->add('image', 'sonata_media_type', array(
                  'provider' => 'sonata.media.provider.image',
                   'context' => 'contentimage'))*/
         /*       ->add('images', 'sonata_type_collection', array(),
            array('edit' => 'list',  'link_parameters' => array('context' => 'contentimage'),
                                                                        'inline' => 'table'  ))*/
                ->end() // ->with('Options', array('collapsed' => true))
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
                ->add('date')
                ->add('image', 'text', array(
            'template' => 'IphpCoreBundle::image_preview.html.twig'
        ))
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
                ->add('rubric', null, array(), null, array(
            'property' => 'TitleLevelIndented',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er)
            {
                return $er->createQueryBuilder('r')
                        ->orderBy('r.left', 'ASC');
            }
        ))
                ->add('title')
                ->add('enabled')
           //     ->add('date')// ->add('author')
        ;
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
        // $content->setContentFormatter('raw');

        //    $content->setContent($this->getPoolFormatter()->transform($content->getContentFormatter(), $content->getRawContent()));
    }

    /**
     * @param ContentInterface $content
     */
    public function preUpdate($content)
    {
        //  $content->setContentFormatter('raw');
        //  $content->setContent($this->getPoolFormatter()->transform($content->getContentFormatter(), $content->getRawContent()));
    }
}
