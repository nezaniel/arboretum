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
        $this->registerNode($this->rootNode);
    }

    /**
     * @param Node $node
     * @return void
     */
    public function registerNode(Node $node)
    {
        $this->nodeRegistry[$node->getIdentifierForGraph()] = $node;
    }

    /**
     * @param Tree $tree
     * @param string $nodeIdentifier
     * @return Node
     */
    public function getNode(Tree $tree, $nodeIdentifier)
    {
        return $this->nodeRegistry[$nodeIdentifier . '@' . $tree->getIdentityHash()] ?? null;
    }

    /**
     * @return array|Node[]
     */
    public function getNodes()
    {
        return $this->nodeRegistry;
    }

    /**
     * @param Tree $tree
     * @return void
     */
    public function registerTree(Tree $tree)
    {
        $this->treeRegistry[$tree->getIdentityHash()] = $tree;
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

    /**
     * @return Tree
     */
    public function getFirstFallbackRootTree()
    {
        $tree = $this->treeRegistry[array_keys($this->treeRegistry)[0]];
        while ($tree->getFallback()) {
            $tree = $tree->getFallback();
        }

        return $tree;
    }

    /**
     * @param Tree $fallbackTree
     * @param callable $treeAction
     * @return void
     */
    public function traverseVariantTrees(Tree $fallbackTree = null, callable $treeAction)
    {
        if (!$fallbackTree) {
            $fallbackTree = $this->getFirstFallbackRootTree();
        }

        $continue = $treeAction($fallbackTree);
        if ($continue !== false) {
            foreach ($fallbackTree->getVariants() as $variantTree) {
                $this->traverseVariantTrees($variantTree, $treeAction);
            }
        }
    }


    /**
     * @param callable $nodeAction
     * @param callable $edgeAction
     * @return void
     */
    public function traverse(callable $nodeAction = null, callable $edgeAction = null)
    {
        $visitedNodes = [];
        $this->traverseNode($this->getRootNode(), $nodeAction, $edgeAction, $visitedNodes);
    }

    /**
     * @param Node $node
     * @param callable $nodeAction
     * @param callable $edgeAction
     * @param array $visitedNodes
     * @return void
     */
    protected function traverseNode(Node $node, callable $nodeAction = null, callable $edgeAction = null, array& $visitedNodes = [])
    {
        if (isset($visitedNodes[$node->getIdentifierForGraph()])) {
            return;
        }

        $continue = $nodeAction ? $nodeAction($node) : true;
        $visitedNodes[$node->getIdentifierForGraph()] = true;
        if ($continue !== false) {
            foreach ($node->getOutgoingEdges() as $edge) {
                $this->traverseEdge($edge, $edgeAction, $nodeAction, $visitedNodes);
            }
        }
    }

    /**
     * @param Edge $edge
     * @param callable $edgeAction
     * @param callable $nodeAction
     * @param array $visitedNodes
     * @return void
     */
    protected function traverseEdge(Edge $edge, callable $edgeAction = null, callable $nodeAction = null, array& $visitedNodes = [])
    {
        $continue = $edgeAction ? $edgeAction($edge) : true;
        if ($continue !== false) {
            $this->traverseNode($edge->getChild(), $nodeAction, $edgeAction, $visitedNodes);
        }
    }
}
