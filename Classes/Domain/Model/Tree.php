<?php
namespace Nezaniel\Arboretum\Domain\Model;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */

use Nezaniel\Arboretum\Domain as Arboretum;
use Nezaniel\Arboretum\Utility\TreeUtility;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * The Tree domain model
 */
class Tree
{
    /**
     * @var Graph
     */
    protected $graph;

    /**
     * @var string
     */
    protected $identityHash;

    /**
     * Identity components of this Tree
     *
     * @var array<mixed>
     */
    protected $identityComponents = [];

    /**
     * The direct fallback tree
     *
     * @var Tree
     */
    protected $fallback;

    /**
     * The directly falling back trees
     *
     * @var array|Tree[]
     */
    protected $fallingBack = [];


    /**
     * Tree constructor.
     * @param Graph $graph
     * @param array $identityComponents
     * @param Tree $fallback
     */
    public function __construct(Graph $graph, array $identityComponents, Tree $fallback = null)
    {
        $this->graph = $graph;
        $this->identityComponents = $identityComponents;
        $this->identityHash = TreeUtility::hashIdentityComponents($identityComponents);

        if ($fallback) {
            $this->fallback = $fallback;
            $fallback->addFallingBack($this);
        }
    }

    /**
     * @param Graph $graph
     * @return void
     */
    public function setGraph(Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * @return string
     */
    public function getIdentityHash()
    {
        return $this->identityHash;
    }

    /**
     * @return array
     */
    public function getIdentityComponents()
    {
        return $this->identityComponents;
    }

    /**
     * @return Tree
     */
    public function getFallback()
    {
        return $this->fallback;
    }

    /**
     * @return array|Tree[]
     */
    public function getFallingBack()
    {
        return $this->fallingBack;
    }

    /**
     * @param Tree $tree
     */
    public function addFallingBack(Tree $tree)
    {
        $this->fallingBack[$tree->getIdentityHash()] = $tree;
    }

    /**
     * @param Node $parent
     * @param Node $child
     * @param string $position
     * @param string $name
     * @return Edge
     */
    public function connectNodes(Node $parent, Node $child, $position = 'start', $name = null)
    {
        $edge = new Edge($parent, $child, $this, $position, $name);
        $parent->registerOutgoingEdge($edge);
        $child->registerIncomingEdge($edge);

        return $edge;
    }

    /**
     * @param Edge $edge
     * @return void
     */
    public function disconnectNodes(Edge $edge)
    {
        $edge->getParent()->deregisterOutgoingEdge($edge);
        $edge->getChild()->deregisterIncomingEdge($edge);
        unset($edge);
    }

    /**
     * @param callable $nodeAction
     * @param callable $edgeAction
     * @return void
     */
    public function traverse(callable $nodeAction = null, callable $edgeAction = null)
    {
        $this->traverseNode($this->graph->getRootNode(), $nodeAction, $edgeAction);
    }

    /**
     * @param Node $node
     * @param callable $nodeAction
     * @param callable $edgeAction
     * @return void
     */
    protected function traverseNode(Node $node, callable $nodeAction = null, callable $edgeAction = null)
    {
        if ($nodeAction) {
            $continue = $nodeAction($node);
        } else {
            $continue = true;
        }
        if ($continue !== false) {
            foreach ($node->getOutgoingEdgesInTree($this) as $edge) {
                $this->traverseEdge($edge, $edgeAction, $nodeAction);
            }
        }
    }

    /**
     * @param Edge $edge
     * @param callable $edgeAction
     * @param callable $nodeAction
     * @return void
     */
    protected function traverseEdge(Edge $edge, callable $edgeAction = null, callable $nodeAction = null)
    {
        if ($edgeAction) {
            $continue = $edgeAction($edge);
        } else {
            $continue = true;
        }
        if ($continue !== false) {
            $this->traverseNode($edge->getChild(), $nodeAction, $edgeAction);
        }
    }
}
