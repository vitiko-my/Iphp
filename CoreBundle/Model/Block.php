<?php


namespace Iphp\CoreBundle\Model;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Model\Block as BaseBlock;

use Iphp\CoreBundle\Model\RubricInterface;

abstract class Block extends BaseBlock
{
    protected $rubric;

    protected $title;

    /**
     * Add children
     *
     * @param \Sonata\BlockBundle\Model\BlockInterface $child
     */
    public function addChildren(BlockInterface $child)
    {
        $this->children[] = $child;

        $child->setParent($this);
        $child->setRubric($this->getRubric());
    }

    /**
     * Set rubric
     *
     * @param Iphp\CoreBundle\Model\RubricInterface $rubric
     */
    public function setRubric(RubricInterface $rubric= null)
    {
        $this->rubric = $rubric;
    }

    /**
     * Get rubric
     *
     * @return Iphp\CoreBundle\Model\RubricInterface $rubric
     */
    public function getRubric()
    {
        return $this->rubric;
    }

    public function disableChildrenLazyLoading()
    {
        if (is_object($this->children)) {
            $this->children->setInitialized(true);
        }
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }
}