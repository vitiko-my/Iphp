<?php

namespace Iphp\TreeBundle\Model;

use Iphp\TreeBundle\Model\TreeNodeWrapper;

class  TreeNodeIterator implements \Iterator
{
    protected $nodes = array();
    protected $position = 0;

    /**
     * Текущий уровень нод в итераторе
     * @integer $level
     */
    protected $level = null;

    function __construct($nodes = null)
    {
        $this->nodes = $nodes ? $this->prepareNodes($nodes) : array();
    }

    protected function prepareNodes($nodes)
    {


        $nodeByLevel = array();
        foreach ($nodes as $node)
        {
            // print '--' . $node.' - '.$node->getLevel();

            //if ($this->level === null || $node->getLevel() < $this->level) $this->level = $node->getLevel();

            $wrappedNode = new TreeNodeWrapper($node);
            $nodeByLevel[$node->getLevel()][$node->getId()] = $wrappedNode;
        }

        $levels = array_keys($nodeByLevel);
        sort($levels);

        $this->level = $levels[0];
        //$nodesNextLevel =  isset($levels[1]) ?


        if (sizeof($levels) == 1) return array_values($nodeByLevel[$this->level]);


        foreach ($nodeByLevel as $level => $nodesById)
        {
            foreach ($nodesById as $nodeId => $node)
            {
                 $parentLevel = $node->getLevel()-1;
                 $parentId = $node->getParentId();


                if (isset($nodeByLevel[$parentLevel][$parentId])) $nodeByLevel[$parentLevel][$parentId]->addChild($node);
               // print '<br>'.$node.' '.$parentId;
            }
        }
       // print_r($levels);


        //print '-->' . $this->level;


        return array_values($nodeByLevel[$this->level]);

    }

    function rewind()
    {
        $this->position = 0;
    }

    function current()
    {
        return $this->nodes[$this->position];
    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        ++$this->position;
    }

    function valid()
    {
        return isset($this->nodes[$this->position]);
    }


    function count()
    {
        return sizeof($this->nodes);
    }

    function getLevel()
    {
        return $this->level;
    }
}