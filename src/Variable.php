<?php

/**
 * @file
 * Utility library implementation.
 */

namespace EnvDereference;

/**
 * Class containing the static metods provided by the library.
 *
 * @author ndobromirov
 */
class Variable
{
    const DEFAULT_PREFIX = '#';

    public static function get($name, $prefix = self::DEFAULT_PREFIX)
    {
        $value = getenv($name);
        if (strpos($value, $prefix) === 0) {
            $value = getenv(substr($value, strlen($prefix)));
        }
        return $value;
    }

    public static function getRecursive($name, $prefix = self::DEFAULT_PREFIX)
    {
        $value = getenv($name);
        while (strpos($value, $prefix) === 0) {
            $value = getenv(substr($value, strlen($prefix)));
        }
        return $value;
    }
}
