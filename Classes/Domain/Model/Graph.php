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
     * @var array|Tree[]
     */
    protected $treeRegistry;

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
     * @param Tree $tree
     * @param string $nodeIdentifier
     * @return Node
     */
    public function getNode(Tree $tree, $nodeIdentifier)
    {
        return $this->nodeRegistry[$tree->getIdentityHash()][$nodeIdentifier] ?? null;
    }

    /**
     * @param Tree $tree
     * @return void
     */
    public function registerTree(Tree $tree)
    {
        $this->treeRegistry[$tree->getIdentityHash()] = $tree;
        $this->nodeRegistry[$tree->getIdentityHash()]['root'] = $this->rootNode;
    }

    /**
     * @param string $treeIdentifier
     * @return Tree|null
     */
    public function getTree($treeIdentifier)
    {
        return $this->treeRegistry[$treeIdentifier] ?? null;
    }

    /**
     * @return Tree[]
     */
    public function getTrees()
    {
        return $this->treeRegistry;
    }

    /**
     * @return Node
     */
    public function getRootNode()
    {
        return $this->rootNode;
    }

}
