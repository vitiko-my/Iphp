<?php



namespace Iphp\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\CacheBundle\Cache\CacheManagerInterface;

use Sonata\BlockBundle\Block\BlockServiceManagerInterface;

class BlockAdmin extends Admin
{
    protected $parentAssociationMapping = 'rubric';


    /**
     * @var \Sonata\BlockBundle\Block\BlockServiceManager
     */
    protected $blockManager;

    protected $cacheManager;

    protected $inValidate = false;

    /**
     * @param \Sonata\AdminBundle\Route\RouteCollection $collection
     * @return void
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('savePosition', 'save-position');
        $collection->add('view', $this->getRouterIdParameter() . '/view');
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('title')
                ->addIdentifier('type')
                ->add('rubric')
                ->add('position')
                ->add('enabled')
                ->add('updatedAt');

    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('enabled')
                ->add('type')
                ->add('rubric' /* ,null, array ('field_type' => 'rubricchoice') */);
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $block = $this->getSubject();


        $formMapper

                ->add('title')
                ->add('type', 'sonata_block_service_choice' )
                ->add('enabled')
                ->add('rubric','rubricchoice')
                ->add('position');

        if ($block->getType()) {

            $service = $this->blockManager->getBlockService($block);

            if ($block->getId() > 0) {
                $service->buildEditForm($formMapper, $block);
            } else {
                $service->buildCreateForm($formMapper, $block);
            }


        }
    }

    /**
     * @param \Sonata\AdminBundle\Validator\ErrorElement $errorElement
     * @param \Sonata\BlockBundle\Model\BlockInterface $block
     */
    public function validate(ErrorElement $errorElement, $block)
    {
        if ($this->inValidate) {
            return;
        }

        // As block can be nested, we only need to validate the main block, no the children
        $this->inValidate = true;
        $this->blockManager->validateBlock($errorElement, $block);
        $this->inValidate = false;
    }

    /**
     * @param $id
     * @return object
     */
    public function getObject($id)
    {
        $subject = parent::getObject($id);

        if ($subject) {
            $service = $this->blockManager->getBlockService($subject);
            $subject->setSettings(array_merge($service->getDefaultSettings(), $subject->getSettings()));

            $service->load($subject);
        }

        return $subject;
    }

    public function preUpdate($object)
    {
        // fix weird bug with setter object not being call
        $object->setChildren($object->getChildren());
        //  $object->getPage()->setEdited(true);

        $this->blockManager->getBlockService($object)->preUpdate($object);
    }

    public function postUpdate($object)
    {
        $service = $this->blockManager->getBlockService($object);

        $this->cacheManager->invalidate($service->getCacheKeys($object));
    }

    public function prePersist($object)
    {
        $this->blockManager->getBlockService($object)->prePersist($object);

        // $object->getPage()->setEdited(true);

        // fix weird bug with setter object not being call
        $object->setChildren($object->getChildren());
    }

    public function postPersist($object)
    {
        $service = $this->blockManager->getBlockService($object);

        $this->cacheManager->invalidate($service->getCacheKeys($object));
    }

    public function setBlockManager(BlockServiceManagerInterface $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    public function setCacheManager(CacheManagerInterface $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }
}