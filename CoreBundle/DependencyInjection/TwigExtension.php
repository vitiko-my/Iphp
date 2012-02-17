<?php

namespace Iphp\CoreBundle\DependencyInjection;


class TwigExtension extends \Twig_Extension
{
    protected $twigEnviroment;

    protected $rubricManager;

    public function __construct(
        \Twig_Environment $twigEnviroment,
        \Iphp\CoreBundle\Model\RubricFactory $rubricManager)
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

            //Вынести в ContentBundle
            'cpath' => new \Twig_Function_Method($this, 'getContentPath'),
        );
    }


    public function getRubricPath($rubric)
    {
        return $this->rubricManager->generatePath($rubric);
    }


    public function getContentPath($content)
    {
       return $this->rubricManager->generatePath($content->getRubric()).$content->getSlug();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'iphpp';
    }
}


class TemplateHelper
{
    protected $rubricManager;


    public function __construct(\Iphp\CoreBundle\Model\RubricFactory $rubricManager)
    {


        //Напрямую инжектировать в сервис request нельзя т.к. он может быть неактивным

        $this->rubricManager = $rubricManager;
    }

    public function getRubric()
    {
        // return 'Rubrica '.$this->request->get ('_rubric');


        return $this->rubricManager->getCurrent();
    }
}
