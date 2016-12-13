<?php
namespace Nezaniel\Arboretum\Tests\Unit\Domain\Model;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */
use Nezaniel\Arboretum\Domain as Arboretum;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Tests\UnitTestCase;

/**
 * Test cases for edges
 */
class EdgeTest extends UnitTestCase
{
    /**
     * @var Arboretum\Model\Tree
     */
    protected $tree;

    /**
     * @var Arboretum\Model\Node
     */
    protected $grandParentNode;

    /**
     * @var Arboretum\Model\Node
     */
    protected $parentNode;

    /**
     * @var Arboretum\Model\Node
     */
    protected $childNode;


    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $graph = new Arboretum\Model\Graph();
        $this->tree = new Arboretum\Model\Tree($graph, []);

        $this->grandParentNode = new Arboretum\Model\Node($this->tree);
        $this->parentNode = new Arboretum\Model\Node($this->tree);
        $this->childNode = new Arboretum\Model\Node($this->tree);
    }

    /**
     * @test
     * @dataProvider accessRolesProvider
     * @param array $parentRoles
     * @param array $childRoles
     * @param array $expectedRoles
     */
    public function mergeStructurePropertiesWithParentComputesStrictestRoles(array $parentRoles, array $childRoles, array $expectedRoles)
    {
        $this->tree->connectNodes($this->grandParentNode, $this->parentNode, null, null, ['accessRoles' => $parentRoles]);
        $edge = $this->tree->connectNodes($this->parentNode, $this->childNode, null, null, ['accessRoles' => $childRoles]);
        $edge->mergeStructurePropertiesWithParent();

        $this->assertEquals(array_values($expectedRoles), array_values($edge->getProperty('accessRoles')));
    }

    /**
     * @return array
     */
    public function accessRolesProvider()
    {
        return [
            [['A', 'B'], ['A', 'B'], ['A', 'B']],
            [['A', 'B'], ['B'], ['B']],
            [['B'], ['A', 'B'], ['B']],
            [['A'], ['B'], []],
            [[], [], []]
        ];
    }

    /**
     * @test
     * @dataProvider hiddenBeforeProvider
     * @param \DateTime|null $parentHiddenBefore
     * @param \DateTime|null $childHiddenBefore
     * @param \DateTime|null $expectedHiddenBefore
     */
    public function mergeStructurePropertiesWithParentComputesStrictestHiddenBefore(\DateTime $parentHiddenBefore = null, \DateTime $childHiddenBefore = null, \DateTime $expectedHiddenBefore = null)
    {
        $this->tree->connectNodes($this->grandParentNode, $this->parentNode, null, null, ['hiddenBeforeDateTime' => $parentHiddenBefore]);
        $edge = $this->tree->connectNodes($this->parentNode, $this->childNode, null, null, ['hiddenBeforeDateTime' => $childHiddenBefore]);
        $edge->mergeStructurePropertiesWithParent();

        $this->assertEquals($expectedHiddenBefore, $edge->getProperty('hiddenBeforeDateTime'));
    }

    /**
     * @return array
     */
    public function hiddenBeforeProvider()
    {
        $earlierDateTime = new \DateTime('@1481283000');
        $laterDateTime = new \DateTime('@1481284000');

        return [
            [$laterDateTime, $earlierDateTime, $laterDateTime],
            [$earlierDateTime, $laterDateTime, $laterDateTime],
            [$earlierDateTime, $earlierDateTime, $earlierDateTime],
            [null, $earlierDateTime, $earlierDateTime],
            [$earlierDateTime, null, $earlierDateTime],
            [null, null, null],
        ];
    }

    /**
     * @test
     * @dataProvider hiddenAfterProvider
     * @param \DateTime|null $parentHiddenAfter
     * @param \DateTime|null $childHiddenAfter
     * @param \DateTime|null $expectedHiddenAfter
     */
    public function mergeStructurePropertiesWithParentComputesStrictestHiddenAfter(\DateTime $parentHiddenAfter = null, \DateTime $childHiddenAfter = null, \DateTime $expectedHiddenAfter = null)
    {
        $this->tree->connectNodes($this->grandParentNode, $this->parentNode, null, null, ['hiddenAfterDateTime' => $parentHiddenAfter]);
        $edge = $this->tree->connectNodes($this->parentNode, $this->childNode, null, null, ['hiddenAfterDateTime' => $childHiddenAfter]);
        $edge->mergeStructurePropertiesWithParent();

        $this->assertEquals($expectedHiddenAfter, $edge->getProperty('hiddenAfterDateTime'));
    }

    /**
     * @return array
     */
    public function hiddenAfterProvider()
    {
        $earlierDateTime = new \DateTime('@1481283000');
        $laterDateTime = new \DateTime('@1481284000');

        return [
            [$laterDateTime, $earlierDateTime, $earlierDateTime],
            [$earlierDateTime, $laterDateTime, $earlierDateTime],
            [$earlierDateTime, $earlierDateTime, $earlierDateTime],
            [null, $earlierDateTime, $earlierDateTime],
            [$earlierDateTime, null, $earlierDateTime],
            [null, null, null],
        ];
    }

    /**
     * @test
     * @dataProvider hiddenProvider
     * @param bool $parentHidden
     * @param bool $childHidden
     * @param bool $expectedHidden
     */
    public function mergeStructurePropertiesWithParentComputesStrictestHidden(bool $parentHidden, bool $childHidden, bool $expectedHidden)
    {
        $this->tree->connectNodes($this->grandParentNode, $this->parentNode, null, null, ['hidden' => $parentHidden]);
        $edge = $this->tree->connectNodes($this->parentNode, $this->childNode, null, null, ['hidden' => $childHidden]);
        $edge->mergeStructurePropertiesWithParent();

        $this->assertEquals($expectedHidden, $edge->getProperty('hidden'));
    }

    /**
     * @return array
     */
    public function hiddenProvider()
    {
        return [
            [true, false, true],
            [false, true, true],
            [true, true, true],
            [false, false, false]
        ];
    }

    /**
     * @test
     * @dataProvider hiddenInIndexProvider
     * @param bool $parentHiddenInIndex
     * @param bool $childHiddenInIndex
     * @param bool $expectedHiddenInIndex
     */
    public function mergeStructurePropertiesWithParentComputesStrictestHiddenInIndex(bool $parentHiddenInIndex, bool $childHiddenInIndex, bool $expectedHiddenInIndex)
    {
        $this->tree->connectNodes($this->grandParentNode, $this->parentNode, null, null, ['hiddenInIndex' => $parentHiddenInIndex]);
        $edge = $this->tree->connectNodes($this->parentNode, $this->childNode, null, null, ['hiddenInIndex' => $childHiddenInIndex]);
        $edge->mergeStructurePropertiesWithParent();

        $this->assertEquals($expectedHiddenInIndex, $edge->getProperty('hiddenInIndex'));
    }

    /**
     * @return array
     */
    public function hiddenInIndexProvider()
    {
        return [
            [true, false, true],
            [false, true, true],
            [true, true, true],
            [false, false, false]
        ];
    }
}
