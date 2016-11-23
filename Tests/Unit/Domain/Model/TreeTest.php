<?php
namespace Nezaniel\Arboretum\Tests\Functional\Domain\Model;

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
            [$this->fallbackTree->getIdentityHash()]
        );
    }


    /**
     * @todo build complete graph covering all fallback combinations
     * @test
     */
    public function traverseReachesAllNodes()
    {
        $nodeIdentifier = 'nodeToBeFound';
        $childNode = new Arboretum\Model\Node($this->fallbackTree, null, $nodeIdentifier);
        $this->graph->getRootNode()->createOutgoingEdge($childNode, $this->fallbackTree);

        $foundNodeIdentifiers = [];
        $this->fallbackTree->traverse(function (Arboretum\Model\Node $node) use(&$foundNodeIdentifiers) {
            $foundNodeIdentifiers[] = $node->getIdentifier();
        });

        $this->assertContains(
            $nodeIdentifier,
            $foundNodeIdentifiers
        );
    }


    /**
     * @todo build complete graph covering all fallback combinations
     * @test
     */
    public function traverseReachesAllEdges()
    {
        $edgeName = 'edgeToBeFound';
        $childNode = new Arboretum\Model\Node($this->fallbackTree);
        $this->graph->getRootNode()->createOutgoingEdge($childNode, $this->fallbackTree, null, $edgeName);

        $foundEdgeNames = [];
        $this->fallbackTree->traverse(null, function (Arboretum\Model\Edge $edge) use(&$foundEdgeNames) {
            $foundEdgeNames[] = $edge->getName();
        });

        $this->assertContains(
            $edgeName,
            $foundEdgeNames
        );
    }
}
