<?php

namespace Iphp\CoreBundle\Twig;

use Iphp\CoreBundle\Manager\RubricManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

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

    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface;
     */
    protected  $securityContext;

    public function __construct(\Twig_Environment $twigEnviroment,
                                RubricManager $rubricManager,
                                SecurityContextInterface $securityContext)
    {
        $this->twigEnviroment = $twigEnviroment;
        $this->rubricManager = $rubricManager;
        $this->securityContext = $securityContext;

        $twigEnviroment->addGlobal('iphp', new TemplateHelper($rubricManager));
    }

    public function getFunctions()
    {
        return array(
            // 'strtr' => new \Twig_Filter_Function('strtr'),

            'sonata_block_by_name' => new \Twig_Function_Method($this, 'sonataBlockByName'),

            'entitypath' => new \Twig_Function_Method($this, 'getEntityUrl'),
            'inlineedit' => new \Twig_Function_Method($this, 'getInlineEditStr', array('is_safe' => array('html'))),


            //Заменить entitypath
            'rpath' => new \Twig_Function_Method($this, 'getRubricPath'),
            'cpath' => new \Twig_Function_Method($this, 'getContentPath'),

        );
    }


    public function getRubricPath($rubric)
    {
        return $this->rubricManager->generatePath($rubric);
    }

    public function sonataBlockByName($blockName)
    {
        return 'Ищем ' . $blockName;
    }


    public function getContentPath($content)
    {
        return $this->rubricManager->generatePath($content->getRubric()) . $content->getSlug();
    }

    public function getEntityUrl($entity, $arg1 = null, $arg2 = null, $arg3 = null)
    {

        /*        $args = func_get_args();

        print_r ($args);
        print '--';
        exit();*/

        if (!method_exists($entity, 'getSitePath')) {
            return 'method ' . get_class($entity) . '->getSitePath() not defined';
            throw new \Exception ('method ' . get_class($entity) . '->getSitePath() not defined');
        }


        return $this->rubricManager->getBaseUrl() . $entity->getSitePath($arg1, $arg2, $arg3);
    }


    public function getInlineEditStr($entity)
    {
        return $this->securityContext->isGranted(array ('ROLE_ADMIN'/*,'ROLE_SUPER_ADMIN'*/)) ?
        '<a href="#" onClick="return inlineEdit (\'' . addslashes(get_class($entity)) . '\',' . $entity->getId() .
                ')">edit</a>' : '';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'iphpp';
    }


/*    public function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }*/
}



