<?php


namespace Iphp\ContentBundle\Block;

use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;

//use Sonata\PageBundle\Model\PageInterface;
//use Sonata\PageBundle\Generator\Mustache;


abstract class ContentBlockService extends BaseBlockService
{

    protected $em;

    protected function getRepository()
    {
       return $this->em->getRepository ('ApplicationIphpContentBundle:Content');
    }


    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    /**
     * @param string $alias
     * @return \Iphp\ContentBundle\Entity\ContentQueryBuilder
     */
    protected function createQueryBuilder ($alias = 'c')
    {
      return $this->getRepository()->createQueryBuilder ($alias);
    }
}
