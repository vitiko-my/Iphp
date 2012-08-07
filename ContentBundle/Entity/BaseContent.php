<?php

namespace  Iphp\ContentBundle\Entity;


use Iphp\ContentBundle\Model\Content as ModelContent;

abstract class BaseContent extends ModelContent
{
    public function __construct()
    {
       $this->images    = new \Doctrine\Common\Collections\ArrayCollection;
       $this->files   = new \Doctrine\Common\Collections\ArrayCollection;
       // $this->comments = new \Doctrine\Common\Collections\ArrayCollection;
    }
}