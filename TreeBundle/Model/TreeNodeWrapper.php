<?php

namespace Iphp\TreeBundle\Model;

use Iphp\TreeBundle\Model\TreeNodeInterface;


class  TreeNodeWrapper implements TreeNodeWrapperInterface
{
    protected $node;
    protected $nodeClass;

    protected $parent;
    protected $children;

    static protected $nodeMethodsCache = array();

    function __construct(TreeNodeInterface $node)
    {
        $this->node = $node;
        $this->nodeClass = get_class($node);


        if (!isset(self::$nodeMethodsCache[$this->nodeClass])) {
            self::$nodeMethodsCache[$this->nodeClass] = array_change_key_case(array_flip(get_class_methods($this->node)));
        }


    }


    public function getNode()
    {
       return $this->node;
    }


    function __call($method, $args)
    {

        if (!method_exists($this->node, $method))
        {
         $lcMethod = strtolower($method);
         if (isset(self::$nodeMethodsCache[$this->nodeClass]['get' . $lcMethod]))
             $method = 'get' . $method;
         elseif (isset(self::$nodeMethodsCache[$this->nodeClass]['is' . $lcMethod]))
             $method = 'is' . $method;
        }

        /*if (method_exists($this->node,$method))*/
        return call_user_func_array(array($this->node, $method), $args);
        /*       else throw new \BadMethodCallException('Method '.$method.' not found');*/
    }



    public function addChild (TreeNodeWrapper  $wrappedNode)
    {
       $wrappedNode->setParent($this);
       $this->children[] =  $wrappedNode;

    }


    function setParent (TreeNodeWrapper  $wrappedNode)
    {
        $this->parent = $wrappedNode;
    }

    public function hasChildren()
    {
        return $this->children ? true : false;
    }

    public function children()
    {
       return $this->children;
    }

    public function parent()
    {
       return $this->parent;
    }

    public function parents()
    {

    }

    public function __toString()
    {
        return (string) $this->node;
    }


}