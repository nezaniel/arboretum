<?php
namespace Nezaniel\Arboretum\Domain\Aggregate;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */
use Neos\Cqrs\Domain\EventSourcedAggregateRootInterface;
use Neos\Cqrs\Domain\RepositoryInterface;
use Nezaniel\Arboretum\Domain as Arboretum;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Algorithms;

/**
 * The graph repository
 *
 * @Flow\Scope("singleton")
 */
class GraphRepository implements RepositoryInterface
{
    /**
     * @var Arboretum\Aggregate\Graph
     */
    protected $graph;


    /**
     * GraphRepository constructor.
     */
    public function __construct()
    {
        $this->graph = new Arboretum\Aggregate\Graph(Algorithms::generateUUID());
    }


    /**
     * @param string $identifier
     * @return Arboretum\Aggregate\Graph
     */
    public function get(string $identifier) : Arboretum\Aggregate\Graph
    {
        return $this->graph;
    }

    /**
     * @param EventSourcedAggregateRootInterface|Arboretum\Aggregate\Graph $graph
     * @return void
     */
    public function save(EventSourcedAggregateRootInterface $graph)
    {
        $this->graph = $graph;
    }
}
