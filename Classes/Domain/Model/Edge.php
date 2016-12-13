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
     * @var array
     */
    protected $properties = [];


    /**
     * Edge constructor.
     * @param Node $parent
     * @param Node $child
     * @param Tree $tree
     * @param string $position
     * @param string $name
     * @param array $properties
     */
    public function __construct(Node $parent, Node $child, Tree $tree, $position = 'start', $name = null, array $properties = [])
    {
        $this->parent = $parent;
        $this->child = $child;
        $this->tree = $tree;
        $this->position = $position;
        $this->name = $name;
        $this->properties = $properties;
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
     * @return string
     */
    public function getNameForGraph()
    {
        return $this->getName() . '@' . $this->getTree()->getIdentityHash();
    }

    /**
     * @return array
     */
    public function getProperties() : array
    {
        return $this->properties;
    }

    /**
     * @param $propertyName
     * @return mixed|null
     */
    public function getProperty($propertyName)
    {
        return $this->properties[$propertyName] ?? null;
    }

    /**
     * @param $propertyName
     * @param $propertyValue
     * @return void
     */
    public function setProperty($propertyName, $propertyValue)
    {
        $this->properties[$propertyName] = $propertyValue;
    }

    /**
     * @return Edge|null
     */
    public function getParentEdge()
    {
        return $this->getParent()->getIncomingEdgeInTree($this->tree);
    }

    /**
     * @return void
     */
    public function mergeStructurePropertiesWithParent()
    {
        if (!$this->getParentEdge()) {
            return;
        }
        $this->properties['accessRoles'] = array_intersect($this->getProperty('accessRoles') ?: [], $this->getParentEdge()->getProperty('accessRoles') ?: []);
        $this->properties['hidden'] = $this->getProperty('hidden') || $this->getParentEdge()->getProperty('hidden');
        if ($this->getProperty('hiddenBeforeDateTime')) {
            if ($this->getParentEdge()->getProperty('hiddenBeforeDateTime')) {
                $this->properties['hiddenBeforeDateTime'] = max($this->getProperty('hiddenBeforeDateTime'), $this->getParentEdge()->getProperty('hiddenBeforeDateTime'));
            } else {
                $this->properties['hiddenBeforeDateTime'] = $this->getProperty('hiddenBeforeDateTime');
            }
        } else {
            $this->properties['hiddenBeforeDateTime'] = $this->getParentEdge()->getProperty('hiddenBeforeDateTime');
        }
        if ($this->getProperty('hiddenAfterDateTime')) {
            if ($this->getParentEdge()->getProperty('hiddenAfterDateTime')) {
                $this->properties['hiddenAfterDateTime'] = min($this->getProperty('hiddenAfterDateTime'), $this->getParentEdge()->getProperty('hiddenAfterDateTime'));
            } else {
                $this->properties['hiddenAfterDateTime'] = $this->getProperty('hiddenAfterDateTime');
            }
        } else {
            $this->properties['hiddenAfterDateTime'] = $this->getParentEdge()->getProperty('hiddenAfterDateTime');
        }
        $this->properties['hiddenInIndex'] = $this->getProperty('hiddenInIndex') || $this->getParentEdge()->getProperty('hiddenInIndex');
    }
}
