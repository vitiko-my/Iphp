<?php


namespace  Iphp\CoreBundle\Entity;


use Iphp\CoreBundle\Model\Rubric as ModelRubric;

abstract class BaseRubric extends ModelRubric
{
    public function __construct()
    {
      // $this->images    = new \Doctrine\Common\Collections\ArrayCollection;
       // $this->comments = new \Doctrine\Common\Collections\ArrayCollection;
    }
}