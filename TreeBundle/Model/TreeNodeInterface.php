<?php

namespace Iphp\TreeBundle\Model;


interface TreeNodeInterface
{


    /**
     * Get lft
     *
     * @return integer
     */
    public function getLeft();

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRight();

    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot();


    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLevel();


    public function setParent(TreeNodeInterface $parent = null);

    public function getParent();


    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren();

    /**
     * Add children
     *
     * @param Iphp\TreeBundle\Model\TreeNodeInterface $children
     */
    public function addChildren(TreeNodeInterface $children);
}