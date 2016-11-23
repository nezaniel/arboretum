<?php
namespace Nezaniel\Arboretum\Utility;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Arrays;

/**
 * The Tree utility library
 */
class TreeUtility
{
    /**
     * @param array $identifierComponents
     * @return string
     */
    public static function hashIdentityComponents(array $identifierComponents)
    {
        Arrays::sortKeysRecursively($identifierComponents);

        return md5(json_encode($identifierComponents));
    }
}
