<?php

namespace Iphp\TreeBundle\Model;


interface TreeNodeWrapperInterface
{
    public function hasChildren();
    public function children();
    public function parent();
    public function parents();


}