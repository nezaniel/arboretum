<?php
namespace Nezaniel\Arboretum\Command;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */

use Nezaniel\Arboretum\Application\Command\CreateNode;
use Nezaniel\Arboretum\Application\Service\NodeCommandHandler;
use Nezaniel\Arboretum\Domain as Arboretum;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;
use TYPO3\Flow\Utility\Files;

/**
 * The performance test command controller
 */
class PerformanceTestCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var Arboretum\Aggregate\GraphRepository
     */
    protected $graphRepository;

    /**
     * @Flow\Inject
     * @var NodeCommandHandler
     */
    protected $nodeCommandHandler;


    /**
     * @param int $trees
     * @param int $nodes
     * @param int $iterations
     * @return void
     */
    public function runCommand($trees = 1, $nodes = 100, $iterations = 10)
    {
        $baseMemoryUsage = memory_get_usage(true);
        $totalTimeSpent = 0;
        for ($i = 0; $i < $iterations; $i++) {
            $totalTimeSpent += $this->runIteration($trees, $nodes);
        }
        \TYPO3\Flow\var_dump(Files::bytesToSizeString(memory_get_peak_usage(true) - $baseMemoryUsage), 'Maximum additional memory usage');
        \TYPO3\Flow\var_dump(round($totalTimeSpent / $iterations), 'Average time spent per iteration (ms)');
    }

    /**
     * @todo distribute nodes into hierarchical structure
     * @todo evaluate impact of and implement fallback edges
     *
     * @param $trees
     * @param $nodes
     * @return float
     */
    protected function runIteration($trees, $nodes)
    {
        $time = microtime(true);

        $graph = $this->graphRepository->get('');
        for ($t = 0; $t < $trees; $t++) {
            $tree = new Arboretum\Model\Tree($graph, ['number' => $t]);

            for ($n = 0; $n < $nodes; $n++) {
                $command = new CreateNode('wat', $tree->getIdentityHash(), 'root', $tree->getIdentityHash());
                $this->nodeCommandHandler->handleCreateNode($command);
            }
        }

        return round((microtime(true) - $time) * 1000);
    }
}
