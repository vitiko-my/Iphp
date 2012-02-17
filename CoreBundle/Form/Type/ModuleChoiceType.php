<?php
namespace Iphp\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Iphp\CoreBundle\Module\ModuleManager;

class ModuleChoiceType extends AbstractType
{

    /**
     * @var Iphp\CoreBundle\Module\ModuleManager
     */
    protected $moduleManager;

    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }


    public function getDefaultOptions(array $options)
    {
        $moduleOptions = array();
        foreach ($this->moduleManager->modules() as $module)
        {
            $moduleOptions[get_class($module)] = $module->getName();
        }

        //print_r ($moduleOptions);

        return  array ('choices' => $moduleOptions);
    }

    public function getParent(array $options)
    {
        return 'choice';
    }

    public function getName()
    {
        return 'modulechoice';
    }
}