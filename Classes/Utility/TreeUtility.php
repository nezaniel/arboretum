<?php
namespace Nezaniel\Arboretum\Utility;

/*
 * This file is part of the Nezaniel.Arboretum package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Utility\Arrays;

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
