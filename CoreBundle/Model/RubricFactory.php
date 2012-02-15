<?php


namespace Iphp\CoreBundle\Model;

class RubricFactory
{
    protected $em;
    protected $request;


    public function __construct(\Doctrine\ORM\EntityManager $em,  \Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
      $this->em = $em;
      $this->request = $container->hasScope('request') && $container->isScopeActive('request') ?
                $container->get('request') : null;
    }


    public function getByPath ($rubricPath)
    {
        return $this->getRepository()->findOneByFullPath ($rubricPath);
    }


    protected function getRepository()
    {
        return $this->em->getRepository('ApplicationIphpCoreBundle:Rubric');
    }

    public function getCurrent()
    {
        if (!$this->request && !$this->request->get('_rubric')) return null;
        return $this->getByPath($this->request->get('_rubric'));
    }




}