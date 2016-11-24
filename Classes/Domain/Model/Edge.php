<?php
namespace Nezaniel\Arboretum\Domain\Model;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * The Edge domain model
 */
class Edge
{
    /**
     * @var Node
     */
    protected $parent;

    /**
     * @var Node
     */
    protected $child;

    /**
     * @var Tree
     */
    protected $tree;

    /**
     * @var string
     */
    protected $position;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $removed = false;


    /**
     * Edge constructor.
     * @param Node $parent
     * @param Node $child
     * @param Tree $tree
     * @param string $position
     * @param string $name
     * @param bool $removed
     */
    public function __construct(Node $parent, Node $child, Tree $tree, $position = 'start', $name = null, $removed = false)
    {
        $this->parent = $parent;
        $this->child = $child;
        $this->tree = $tree;
        $this->position = $position;
        $this->name = $name;
        $this->removed = $removed;
    }


    /**
     * @return Tree
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * @param Tree $tree
     */
    public function setTree($tree)
    {
        $this->tree = $tree;
    }

    /**
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Node $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Node
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param Node $child
     * @todo update registration in tree
     */
    public function setChild($child)
    {
        $this->child = $child;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @todo update registration in tree
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function isRemoved()
    {
        return $this->removed;
    }

    /**
     * @param boolean $removed
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;
    }
}
