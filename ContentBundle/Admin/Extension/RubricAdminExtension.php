<?php
namespace Iphp\ContentBundle\Admin\Extension;


use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Form\FormMapper;

class RubricAdminExtension extends AdminExtension
{


    public function configureFormFields(FormMapper $form)
    {

     /*   $form->with('Материал')
             ->add('contents', 'sonata_type_collection',
            array('label' => 'Content'),
            array('edit' => 'list'))
                ->end();*/

    }
}