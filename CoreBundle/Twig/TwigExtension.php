<?php

namespace Iphp\CoreBundle\Twig;

use Iphp\CoreBundle\Manager\RubricManager;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $twigEnviroment;

    /**
     * @var \Iphp\CoreBundle\Manager\RubricManager
     */
    protected $rubricManager;

    public function __construct(\Twig_Environment $twigEnviroment,  RubricManager $rubricManager)
    {
        $this->twigEnviroment = $twigEnviroment;
        $this->rubricManager = $rubricManager;
        $twigEnviroment->addGlobal('iphp', new TemplateHelper($rubricManager));
    }

    public function getFunctions()
    {
        return array(
            // 'strtr' => new \Twig_Filter_Function('strtr'),
            'rpath' => new \Twig_Function_Method($this, 'getRubricPath'),
            'sonata_block_by_name' => new \Twig_Function_Method($this, 'sonataBlockByName'),


            //Вынести в ContentBundle
            'cpath' => new \Twig_Function_Method($this, 'getContentPath'),
        );
    }


    public function getRubricPath($rubric)
    {
        return $this->rubricManager->generatePath($rubric);
    }

    public function sonataBlockByName ($blockName)
    {
       return 'Ищем '.$blockName;
    }


    public function getContentPath($content)
    {
        return $this->rubricManager->generatePath($content->getRubric()) . $content->getSlug();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'iphpp';
    }
}



