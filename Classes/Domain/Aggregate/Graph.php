<?php
namespace Nezaniel\Arboretum\Domain\Aggregate;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */
use Neos\Cqrs\Domain\AbstractEventSourcedAggregateRoot;
use Nezaniel\Arboretum\Domain as Arboretum;
use TYPO3\Flow\Annotations as Flow;

/**
 * The graph domain model
 * Represents the complete directed node graph spanning multiple trees
 */
class Graph extends AbstractEventSourcedAggregateRoot
{
    /**
     * @var Arboretum\Model\Node
     */
    protected $rootNode;

    /**
     * @var array|Arboretum\Model\Tree[]
     */
    protected $treeRegistry;

    /**
     * @var array|Arboretum\Model\Node[]
     */
    protected $nodeRegistry;


    /**
     * Graph constructor.
     * @param string $identifier
     */
    public function __construct(string $identifier)
    {
        parent::__construct($identifier);
        $this->rootNode = new Arboretum\Model\Node(null, 'root');
    }

    /**
     * @param Arboretum\Model\Node $node
     * @return void
     */
    public function registerNode(Arboretum\Model\Node $node)
    {
        $this->nodeRegistry[$node->getTree()->getIdentityHash()][$node->getIdentifier()] = $node;
    }

    /**
     * @param Arboretum\Model\Tree $tree
     * @param string $nodeIdentifier
     * @return Arboretum\Model\Node
     */
    public function getNode(Arboretum\Model\Tree $tree, $nodeIdentifier)
    {
        return $this->nodeRegistry[$tree->getIdentityHash()][$nodeIdentifier] ?? null;
    }

    /**
     * @param Arboretum\Model\Tree $tree
     * @return void
     */
    public function registerTree(Arboretum\Model\Tree $tree)
    {
        $this->treeRegistry[$tree->getIdentityHash()] = $tree;
        $this->nodeRegistry[$tree->getIdentityHash()]['root'] = $this->rootNode;
    }

    /**
     * @param string $treeIdentifier
     * @return Arboretum\Model\Tree|null
     */
    public function getTree($treeIdentifier)
    {
        return $this->treeRegistry[$treeIdentifier] ?? null;
    }

    /**
     * @return Arboretum\Model\Node
     */
    public function getRootNode()
    {
        return $this->rootNode;
    }

    /**
     * @param Event\NodeWasCreated $event
     * @return void
     */
    public function whenNodeWasCreated(Arboretum\Aggregate\Event\NodeWasCreated $event)
    {
        $tree = $this->getTree($event->getTreeIdentifier());
        $parentTree = $this->getTree($event->getParentTreeIdentifier());
        $node = new Arboretum\Model\Node($tree, $event->getType(), $event->getIdentifier());
        $parentNode = $this->getNode($parentTree, $event->getParentIdentifier());
        $tree->connectNodes($parentNode, $node, $event->getPosition(), $event->getName());
    }
}
