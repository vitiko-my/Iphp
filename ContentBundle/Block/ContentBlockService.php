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

    /**
     * @return \Iphp\ContentBundle\Entity\BaseContentRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('ApplicationIphpContentBundle:Content');
    }


    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    /**
     * @param array
     */
    protected function getContents(\Closure $prepareQueryBuilder)
    {
        return $this->getRepository()->createQuery('c', $prepareQueryBuilder)->getResult();
    }


}
