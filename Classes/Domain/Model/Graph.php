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
    protected $trees;


    /**
     * Graph constructor.
     * @param array|Tree[] $trees
     */
    public function __construct(array $trees = [])
    {
        $this->rootNode = new Node(null, 'root');
        foreach ($trees as $tree) {
            $this->trees[$tree->getIdentityHash()] = $tree;
        }
    }


    /**
     * @param array $identityComponents
     * @param array $fallback
     * @return Tree
     * @todo spawn edges along fallback tree
     */
    public function createTree(array $identityComponents, array $fallback = [])
    {
        $tree = new Tree($this, $identityComponents, $fallback);
        $this->trees[$tree->getIdentityHash()] = $tree;
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
