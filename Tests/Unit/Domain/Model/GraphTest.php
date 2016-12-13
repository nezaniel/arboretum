<?php
namespace Nezaniel\Arboretum\Tests\Unit\Domain\Model;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */
use Nezaniel\Arboretum\Domain as Arboretum;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Tests\UnitTestCase;

/**
 * Test cases for the graph
 */
class GraphTest extends UnitTestCase
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

        $nodeA1 = new Arboretum\Model\Node($this->fallbackTree, null, 'A1');
        $this->fallbackTree->connectNodes($this->graph->getRootNode(), $nodeA1);

        $nodeB1 = new Arboretum\Model\Node($this->fallbackTree, null, 'B1');
        $this->fallbackTree->connectNodes($nodeA1, $nodeB1);

        $nodeC1 = new Arboretum\Model\Node($this->fallbackTree, null, 'C1');
        $this->fallbackTree->connectNodes($nodeA1, $nodeC1);
    }


    /**
     * @test
     */
    public function createTreeWithFallbackSpawnsFallbackEdges()
    {
        $variantTree = new Arboretum\Model\Tree(
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

        $foundNodeIdentifiers = [];
        $variantTree->traverse(function (Arboretum\Model\Node $node) use(&$foundNodeIdentifiers) {
            if ($node->getType() !== 'root') {
                $foundNodeIdentifiers[] = $node->getIdentifier();
            }
        });

        $this->assertEquals(
            ['A1', 'B1', 'C1'],
            $foundNodeIdentifiers
        );
    }
}
