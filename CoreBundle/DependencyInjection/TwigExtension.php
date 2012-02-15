<?php

namespace Iphp\CoreBundle\DependencyInjection;


class TwigExtension extends \Twig_Extension
{

    public function __construct(
        \Twig_Environment $twigEnviroment,
        \Iphp\CoreBundle\Model\RubricFactory $rubricFactory)
    {
        $twigEnviroment->addGlobal('iphp', new TemplateHelper( $rubricFactory));
    }

    public function getFunctions()
    {
        return array(
            // 'strtr' => new \Twig_Filter_Function('strtr'),
        );
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
    protected $rubricFactory;


    public function __construct(  \Iphp\CoreBundle\Model\RubricFactory $rubricFactory)
    {


        //Напрямую инжектировать в сервис request нельзя т.к. он может быть неактивным

        $this->rubricFactory = $rubricFactory;
    }

    public function getRubric()
    {
        // return 'Rubrica '.$this->request->get ('_rubric');


        return $this->rubricFactory->getCurrent();
    }
}
