<?php
namespace Nezaniel\Arboretum\Application\Service;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */

use Nezaniel\Arboretum\Application\Command\CreateNode;
use Nezaniel\Arboretum\Domain as Arboretum;
use TYPO3\Flow\Annotations as Flow;

/**
 * The CreateNode command
 */
class NodeCommandHandler
{
    /**
     * @Flow\Inject
     * @var Arboretum\Aggregate\GraphRepository
     */
    protected $graphRepository;


    /**
     * @param CreateNode $command
     * @return void
     */
    public function handleCreateNode(CreateNode $command)
    {
        $graph = $this->graphRepository->get('');
        $graph->recordThat(new Arboretum\Aggregate\Event\NodeWasCreated(
            $command->getIdentifier(),
            $command->getType(),
            $command->getTreeIdentifier(),
            $command->getParentIdentifier(),
            $command->getParentTreeIdentifier(),
            $command->getPosition(),
            $command->getName()
        ));
    }
}
