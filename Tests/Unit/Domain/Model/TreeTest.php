<?php
namespace Nezaniel\Arboretum\Tests\Unit\Domain\Model;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */
use Nezaniel\Arboretum\Domain as Arboretum;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Tests\UnitTestCase;

/**
 * Test cases for trees
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

        $this->fallbackTree = new Arboretum\Model\Tree(
            $this->graph,
            [
                'workspace' => 'live',
                'site' => 'neos.io',
                'dimensions' => [
                    'language' => 'en',
                    'market' => 'Europe'
                ]
            ]
        );

        $this->fallingBackTree = new Arboretum\Model\Tree(
            $this->graph,
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
        $this->fallbackTree->connectNodes($this->graph->getRootNode(), $nodeA1);

        $nodeB1 = new Arboretum\Model\Node($this->fallbackTree, null, 'B1');
        $this->fallbackTree->connectNodes($nodeA1, $nodeB1);

        $nodeC1 = new Arboretum\Model\Node($this->fallbackTree, null, 'C1');
        $this->fallbackTree->connectNodes($nodeA1, $nodeC1);


        $nodeA2 = new Arboretum\Model\Node($this->fallingBackTree, null, 'A2');
        $this->fallingBackTree->connectNodes($this->graph->getRootNode(), $nodeA2);

        $nodeB2 = new Arboretum\Model\Node($this->fallingBackTree, null, 'B2');
        $this->fallingBackTree->connectNodes($nodeA2, $nodeB2);

        $this->fallingBackTree->connectNodes($nodeA2, $nodeC1);

        $nodeD2 = new Arboretum\Model\Node($this->fallingBackTree, null, 'D2');
        $this->fallingBackTree->connectNodes($nodeA2, $nodeD2);
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
