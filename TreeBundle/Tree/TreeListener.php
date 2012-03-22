<?php

namespace Iphp\TreeBundle\Tree;


use  Gedmo\Tree\TreeListener as BaseTreeListener;

use Doctrine\Common\EventArgs,
Gedmo\Mapping\MappedEventSubscriber,
Doctrine\Common\Persistence\ObjectManager;


class TreeListener extends BaseTreeListener
{

    private $myStrategies = array();


    private $myStrategyInstances = array();


    public function getStrategy(ObjectManager $om, $class)
    {
        if (!isset($this->myStrategies[$class])) {
            $config = $this->getConfiguration($om, $class);
            if (!$config) {
                throw new \Gedmo\Exception\UnexpectedValueException("Tree object class: {$class} must have tree metadata at this point");
            }
            $managerName = 'UnsupportedManager';
            if ($om instanceof \Doctrine\ORM\EntityManager) {
                $managerName = 'ORM';
            } elseif ($om instanceof \Doctrine\ODM\MongoDB\DocumentManager) {
                $managerName = 'ODM';
            }
            if (!isset($this->strategyInstances[$config['strategy']])) {
                $strategyClass =   '\\Iphp\\TreeBundle\\Tree\\Strategy\\'  . ucfirst($config['strategy']);
                if (!class_exists($strategyClass)) {
                    throw new \Gedmo\Exception\InvalidArgumentException($managerName . " TreeListener does not support tree type: {$config['strategy']}");
                }
                $this->myStrategyInstances[$config['strategy']] = new $strategyClass($this);
            }
            $this->myStrategies[$class] = $config['strategy'];
        }
        return $this->myStrategyInstances[$this->myStrategies[$class]];
    }

    protected function getStrategiesUsedForObjects(array $classes)
    {
        $strategies = array();
        foreach ($classes as $name => $opt) {
            if (isset($this->myStrategies[$name]) && !isset($strategies[$this->myStrategies[$name]])) {
                $strategies[$this->myStrategies[$name]] = $this->myStrategyInstances[$this->myStrategies[$name]];
            }                $strategies[$this->myStrategies[$name]] = $this->myStrategyInstances[$this->myStrategies[$name]];

        }
        return $strategies;
    }
}
