<?php
namespace Nezaniel\Arboretum\Tests\Unit\Domain\Model;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */
use Nezaniel\Arboretum\Domain as Arboretum;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Tests\UnitTestCase;

/**
 * Test cases for the tree
 */
class TreeTest extends UnitTestCase
{
    /**
     * @var Arboretum\Model\Graph
     */
    protected $graph;

    /**
     * @var Arboretum\Model\Tree
     */
    protected $fallbackTree;

    /**
     * @var Arboretum\Model\Tree
     */
    protected $fallingBackTree;


    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->graph = new Arboretum\Model\Graph();

        $this->fallbackTree = $this->graph->createTree(
            [
                'workspace' => 'live',
                'site' => 'neos.io',
                'dimensions' => [
                    'language' => 'en',
                    'market' => 'Europe'
                ]
            ]
        );

        $this->fallingBackTree = $this->graph->createTree(
            [
                'workspace' => 'live',
                'site' => 'neos.io',
                'dimensions' => [
                    'language' => 'sjn',
                    'market' => 'Hîthundor'
                ]
            ],
            $this->fallbackTree
        );

        $nodeA1 = new Arboretum\Model\Node($this->fallbackTree, null, 'A1');
        $this->graph->getRootNode()->createOutgoingEdge($nodeA1, $this->fallbackTree);

        $nodeB1 = new Arboretum\Model\Node($this->fallbackTree, null, 'B1');
        $nodeA1->createOutgoingEdge($nodeB1, $this->fallbackTree);

        $nodeC1 = new Arboretum\Model\Node($this->fallbackTree, null, 'C1');
        $nodeA1->createOutgoingEdge($nodeC1, $this->fallbackTree);

        $nodeA2 = new Arboretum\Model\Node($this->fallingBackTree, null, 'A2');
        $this->graph->getRootNode()->createOutgoingEdge($nodeA2, $this->fallingBackTree);

        $nodeB2 = new Arboretum\Model\Node($this->fallingBackTree, null, 'B2');
        $nodeA2->createOutgoingEdge($nodeB2, $this->fallingBackTree);

        $nodeA2->createOutgoingEdge($nodeC1, $this->fallingBackTree);

        $nodeD2 = new Arboretum\Model\Node($this->fallingBackTree, null, 'D2');
        $nodeA2->createOutgoingEdge($nodeD2, $this->fallingBackTree);
    }


    /**
     * @test
     */
    public function traverseReachesAllNodesInFallbackTreeAndOnlyThem()
    {
        $foundNodeIdentifiers = [];
        $this->fallbackTree->traverse(function (Arboretum\Model\Node $node) use(&$foundNodeIdentifiers) {
            if ($node->getType() !== 'root') {
                $foundNodeIdentifiers[] = $node->getIdentifier();
            }
        });

        $this->assertEquals(
            ['A1', 'B1', 'C1'],
            $foundNodeIdentifiers
        );
    }

    /**
     * @test
     */
    public function traverseReachesAllNodesInFallingBackTreeAndOnlyThem()
    {
        $foundNodeIdentifiers = [];
        $this->fallingBackTree->traverse(function (Arboretum\Model\Node $node) use(&$foundNodeIdentifiers) {
            if ($node->getType() !== 'root') {
                $foundNodeIdentifiers[] = $node->getIdentifier();
            }
        });

        $this->assertEquals(
            ['A2', 'B2', 'C1', 'D2'],
            $foundNodeIdentifiers
        );
    }
}
