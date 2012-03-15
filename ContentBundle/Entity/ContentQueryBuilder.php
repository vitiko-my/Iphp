<?php
namespace Iphp\ContentBundle\Entity;

use Doctrine\ORM\QueryBuilder;

class ContentQueryBuilder extends QueryBuilder
{

    /**
     * @var \Application\Iphp\CoreBundle\Entity\Rubric
     */
    protected $fromRubric = null;


    /**
     * @var integer
     */
    protected $fromRubricId;

    function fromRubric($rubric)
    {
        if (is_int($rubric)) {
            $this->fromRubricId = $rubric;
        }
        else if ($rubric instanceof \Application\Iphp\CoreBundle\Entity\Rubric) {
            $this->fromRubric = $rubric;

        }
        else throw new \InvalidArgumentException (
            'Method ContentQueryBuilder::fromRubric accepts integer or \Application\Iphp\CoreBundle\Entity\Rubric object');

        $this->orWhere('c.rubric = :fromRubric')->setParameter('fromRubric', $rubric);


        return $this;
    }


    function withSubrubrics($withSubrubrics = true)
    {

        if (!$withSubrubrics) return;

        $this->prepareFromRubric();

        if ($this->fromRubric) {
           $children = $this->getRubricRepository()->children($this->fromRubric);

            foreach ($children as $pos => $child)
            $this->orWhere ('c.rubric = :fromChild'.$pos)->setParameter('fromChild'.$pos, $child);
        }

        return $this;
    }



    function whereSlug ($slug)
    {
      $this->andWhere('c.slug = :slug')->setParameter('slug',$slug);
      return $this;
    }




    protected function prepareFromRubric()
    {
        if ($this->fromRubric || !$this->fromRubricId) return;

        $this->fromRubric =  $this->getRubricRepository()->find($this->fromRubricId);
    }


    protected function getRubricRepository()
    {
        return $this->getEntityManager()->getRepository('ApplicationIphpCoreBundle:Rubric');
    }
}