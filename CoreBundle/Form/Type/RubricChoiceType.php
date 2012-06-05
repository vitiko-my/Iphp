<?php
namespace Iphp\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Doctrine\ORM\EntityManager;

use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

use Symfony\Component\Form\Exception\TransformationFailedException;


class RubricChoiceType extends AbstractType
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function getDefaultOptions()
    {


        $entityChoiceList = new  EntityChoiceList (
            $this->em,
            'Application\Iphp\CoreBundle\Entity\Rubric',
            'titleLevelIndented',
            new ORMQueryBuilderLoader (
                $this->em->getRepository('ApplicationIphpCoreBundle:Rubric')->createQueryBuilder('r')
                        ->orderBy('r.left')));

        if (isset($options['transform_to_id']) && $options['transform_to_id'])
        {
         $choices = $entityChoiceList->getChoices();


         //
         foreach ( $choices as $key => $choice)
         if (is_object($choice)) $choices[$key] = $choice->getTitleLevelIndented();
       /*     print sizeof($choices);

            print_r (array_keys($choices));
            print get_class($choices[1]);
            exit();*/
         $choiceList = new SimpleChoiceList ($choices);
        }
        else $choiceList = $entityChoiceList;


        return array(
            'transform_to_id' => false,
            'empty_value' => '',
            'choice_list' => $choiceList
        );

    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'rubricchoice';
    }


}

