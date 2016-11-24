<?php
namespace Nezaniel\Arboretum\Application\Command;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Algorithms;

/**
 * The CreateNode command
 */
final class CreateNode
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $treeIdentifier;

    /**
     * @var string
     */
    protected $parentIdentifier;

    /**
     * @var string
     */
    protected $parentTreeIdentifier;

    /**
     * @var string
     */
    protected $position;

    /**
     * @var string
     */
    protected $name;


    /**
     * NodeCreated constructor.
     * @param string $type
     * @param string $treeIdentifier
     * @param string $parentIdentifier
     * @param string $parentTreeIdentifier
     * @param string $position
     * @param string $name
     */
    public function __construct($type, $treeIdentifier, $parentIdentifier, $parentTreeIdentifier, $position = 'start', $name = '')
    {
        $this->identifier = Algorithms::generateUUID();
        $this->type = $type;
        $this->treeIdentifier = $treeIdentifier;
        $this->parentIdentifier = $parentIdentifier;
        $this->parentTreeIdentifier = $parentTreeIdentifier;
        $this->position = $position;
        $this->name = $name;
    }


    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTreeIdentifier()
    {
        return $this->treeIdentifier;
    }

    /**
     * @return string
     */
    public function getParentIdentifier()
    {
        return $this->parentIdentifier;
    }

    /**
     * @return string
     */
    public function getParentTreeIdentifier()
    {
        return $this->parentTreeIdentifier;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
