<?php

namespace Iphp\CoreBundle\Twig;

use \Iphp\CoreBundle\Routing\EntityRouter;
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
     * @var  \Iphp\CoreBundle\Routing\EntityRouter;
     */
    protected $entityRouter;

    public function __construct(\Twig_Environment $twigEnviroment,
                                 RubricManager $rubricManager,
                                 EntityRouter $entityRouter)
    {
        $this->twigEnviroment = $twigEnviroment;
        $this->rubricManager = $rubricManager;
        $this->entityRouter = $entityRouter;

        $twigEnviroment->addGlobal('iphp', new TemplateHelper($rubricManager));
    }

    public function getFunctions()
    {
        return array(
            'sonata_block_by_name' => new \Twig_Function_Method($this, 'sonataBlockByName'),

            'entitypath' => new \Twig_Function_Method($this, 'getEntityPath'),
            'inlineedit' => new \Twig_Function_Method($this, 'getInlineEditStr', array('is_safe' => array('html'))),
            'rpath' => new \Twig_Function_Method($this, 'getRubricPath'),

            //Заменены entitypath

            //'cpath' => new \Twig_Function_Method($this, 'getContentPath'),

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


    public function getEntityPath($entity, $arg1 = null, $arg2 = null, $arg3 = null)
    {

        /*        $args = func_get_args();

        print_r ($args);
        print '--';
        exit();*/

        if (!method_exists($entity, 'getSitePath')) {
            return 'method ' . get_class($entity) . '->getSitePath() not defined';
            throw new \Exception ('method ' . get_class($entity) . '->getSitePath() not defined');
        }




      //  print get_class ($entity);

        $methodData = new \ReflectionMethod($entity, 'getSitePath');
        $parameters =   $methodData->getParameters();

       // print  sizeof($parameters);


        $args = array ( $arg1, $arg2, $arg3);
        if (sizeof($parameters) > 0 && $parameters[0]->getClass() &&
                $parameters[0]->getClass()->isInstance ($this->entityRouter))
        {
           array_unshift($args,$this->entityRouter);
        }


        return /*$this->rubricManager->getBaseUrl() .*/
                call_user_func_array(array ($entity, 'getSitePath'),   $args );


    }


    public function getInlineEditStr($entity)
    {
        return $this->securityContext->isGranted(array('ROLE_ADMIN' /*,'ROLE_SUPER_ADMIN'*/)) ?
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

}



