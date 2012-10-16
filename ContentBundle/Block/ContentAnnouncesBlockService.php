<?php


namespace Iphp\ContentBundle\Block;

use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;


//use Sonata\PageBundle\Model\PageInterface;
//use Sonata\PageBundle\Generator\Mustache;


class ContentAnnouncesBlockService extends ContentBlockService
{
    /**
     * {@inheritdoc}
     */
    public function execute(BlockInterface $block, Response $response = null)
    {
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());


        $response = $this->renderResponse('IphpContentBundle:Block:content_announces.html.twig', array(
            'block' => $block,
            'settings' => $settings,
            'contents' => $this->prepareContents($settings)
        ), $response);

        return $response;
    }


    public function prepareContents(array $settings)
    {
        return $this->getContents(function ($qb) use ($settings)
        {
            $qb->fromRubric($settings['rubric_id'])
                ->withSubrubrics((bool)$settings['withSubrubrics'])
                ->orderBy('c.date', 'DESC');

            if ($settings['entriesNum']) $qb->setMaxResults($settings['entriesNum']);
        });
    }


    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        // TODO: Implement validateBlock() method.
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {

        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'label' => 'Рубрика',
            'keys' => array(
                array('rubric_id', 'rubricchoice', array(
                    'transform_to_id' => true,
                    'attr' => array('class' => 'label_hidden'),
                    'required' => false)),


                array('withSubrubrics', 'checkbox', array('label' => 'С подрубриками', 'required' => false)),
                array('entriesNum', 'integer', array('label' => 'Количество записей', 'required' => false)),
                array('title', 'text', array('label' => 'Заголовок блока', 'required' => false))
            )
        ));

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Контент - список анонсов';
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultSettings()
    {
        return array(

        );
    }
}
