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
     * Fallback trees
     *
     * @var array
     */
    protected $fallback;


    /**
     * Tree constructor.
     * @param Graph $graph
     * @param array $identityComponents
     * @param array $fallback
     */
    public function __construct(Graph $graph, array $identityComponents, array $fallback = [])
    {
        $this->graph = $graph;
        $this->identityComponents = $identityComponents;
        $this->identityHash = TreeUtility::hashIdentityComponents($identityComponents);
        $this->fallback = $fallback;
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
     * @return array
     */
    public function getFallback()
    {
        return $this->fallback;
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
