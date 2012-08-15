<?php
/**
 * Created by Vitiko
 * Date: 08.08.12
 * Time: 16:13
 */
namespace Iphp\FileStoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\CreationException;
use Symfony\Component\Form\FormView;


use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

use Iphp\FileStoreBundle\Form\DataTransformer\FileDataTransformer;

class FileType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //Нельзя добавлять трансформер только для файла, т.к. для трансформирования файла
        // нужно знать знасение delete checkbox

        /*$builder->add($builder->create('file', 'file')->addModelTransformer(new FileDataTransformer()))
                 ->add('delete', 'checkbox')->addModelTransformer(new FileDataTransformer());*/


        $builder->add('file', 'file')
                ->add('delete', 'checkbox', array('label' => 'Удалить'))
                ->addViewTransformer(new FileDataTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'field';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'iphp_file';
    }
}
