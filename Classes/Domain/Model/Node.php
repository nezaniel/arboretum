<?php
namespace Nezaniel\Arboretum\Domain\Model;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Algorithms;

/**
 * The Node domain model
 */
class Node
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Tree
     */
    protected $tree;

    /**
     * @var array
     */
    protected $incomingEdges = [];

    /**
     * @var array
     */
    protected $outgoingEdges = [];


    /**
     * Node constructor.
     * @param Tree $tree
     * @param string $type
     * @param string $identifier
     */
    public function __construct(Tree $tree = null, $type = 'unstructured', $identifier = null)
    {
        $this->tree = $tree;
        $this->type = $type;
        $this->identifier = $identifier ?: Algorithms::generateUUID();
    }


    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * @return array|Edge[]
     */
    public function getOutgoingEdges()
    {
        $outgoingEdges = [];
        foreach ($this->outgoingEdges as $treeIdentifier => $edges) {
            $outgoingEdges = array_merge($outgoingEdges, $edges);
        }

        return $outgoingEdges;
    }

    /**
     * @param Tree $tree
     * @return array|Edge[]
     */
    public function getOutgoingEdgesInTree(Tree $tree)
    {
        return $this->outgoingEdges[$tree->getIdentityHash()] ?? [];
    }

    /**
     * @return array|Edge[]
     */
    public function getIncomingEdges()
    {
        return $this->incomingEdges;
    }

    /**
     * @param Tree $tree
     * @return Edge|null
     */
    public function getIncomingEdgeInTree(Tree $tree)
    {
        return $this->incomingEdges[$tree->getIdentityHash()];
    }

    /**
     * @param Node $child
     * @param Tree $tree
     * @param string $position
     * @param string $name
     * @param bool $removed
     */
    public function createOutgoingEdge(Node $child, Tree $tree, $position = 'start', $name = null, $removed = false)
    {
        $newEdge = new Edge($this, $child, $tree, $position, $name, $removed);
        $this->outgoingEdges[$tree->getIdentityHash()][$name ?: $child->getIdentifier()] = $newEdge;
    }
}
