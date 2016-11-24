<?php
namespace Nezaniel\Arboretum\Domain\Model;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */
use TYPO3\Flow\Annotations as Flow;

/**
 * The graph domain model
 * Represents the complete directed node graph spanning multiple trees
 */
class Graph
{
    /**
     * @var Node
     */
    protected $rootNode;

    /**
     * The array of root level trees
     * @var array|Tree[]
     */
    protected $rootLevelTrees;

    /**
     * @var array|Node[]
     */
    protected $nodeRegistry;


    /**
     * Graph constructor.
     */
    public function __construct()
    {
        $this->rootNode = new Node(null, 'root');
    }

    /**
     * @param Node $node
     * @return void
     */
    public function registerNode(Node $node)
    {
        $this->nodeRegistry[$node->getTree()->getIdentityHash()][$node->getIdentifier()] = $node;
    }

    /**
     * @param array $identityComponents
     * @param Tree $fallback
     * @return Tree
     */
    public function createTree(array $identityComponents, Tree $fallback = null)
    {
        $tree = new Tree($this, $identityComponents, $fallback);
        if ($fallback) {
            $fallback->traverse(null, function (Edge $edge) use ($tree) {
                $tree->connectNodes($edge->getParent(), $edge->getChild());
            });
        } else {
            $this->rootLevelTrees[$tree->getIdentityHash()] = $tree;
        }

        return $tree;
    }

    /**
     * @return Node
     */
    public function getRootNode()
    {
        return $this->rootNode;
    }
}
