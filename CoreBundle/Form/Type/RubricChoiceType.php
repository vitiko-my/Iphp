<?php
namespace Iphp\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Doctrine\ORM\EntityManager;

use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

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


    public function getDefaultOptions(array $options)
    {

        return array(
            'empty_value' => '',
            'choice_list' => new  EntityChoiceList (
                $this->em,
                'Application\Iphp\CoreBundle\Entity\Rubric',
                'titleLevelIndented',
                new ORMQueryBuilderLoader (
                    $this->em->getRepository('ApplicationIphpCoreBundle:Rubric')->createQueryBuilder('r')
                            ->orderBy('r.left'))));

    }

    public function getParent(array $options)
    {
        return 'choice';
    }

    public function getName()
    {
        return 'rubricchoice';
    }
}
