<?php

/**
 * @file
 * Utility library implementation.
 */

namespace EnvDereference;

/**
 * Class containing the static methods provided by the library.
 *
 * @author ndobromirov
 */
class Variable
{
    const DEFAULT_PREFIX = '#';

    /**
     * Simple wrapper utility around PHP's getenv function.
     *
     * @param string $name Name of environment variable to fetch.
     * @param mixed $default Value to use, when the requested variable is not found.
     *
     * @return mixed
     *   string - Extracted value, when present.
     *   mixed - $default when the env variable is not set.
     */
    public static function getWithDefault($name, $default = null)
    {
        return ($value = getenv($name)) !== false ? $value : $default;
    }

    /**
     * Simple and fast linear de-referencer.
     *
     * @param string $name Name of environment variable to dereference.
     * @param mixed $default Value to use, when the requested variable is not found.
     * @param string $prefix Defaults to Variable::DEFAULT_PREFIX.
     *
     * @return mixed
     *   string - Extracted value, when present.
     *   mixed - $default when the env variable is not set.
     */
    public static function get($name, $default = null, $prefix = self::DEFAULT_PREFIX)
    {
        $value = self::getWithDefault($name, $default);
        if (strpos($value, $prefix) === 0) {
            $value = getenv(substr($value, strlen($prefix)));
        }
        return $value ?: $default;
    }

    /**
     * Simple and fast recursive linear de-referencer.
     *
     * @param string $name Name of environment variable to dereference.
     * @param mixed $default Value to use, when the requested variable is not found.
     * @param string $prefix Defaults to Variable::DEFAULT_PREFIX.
     *
     * @return mixed
     *   string - Extracted value, when present.
     *   mixed - $default when the env variable is not set.
     */
    public static function getRecursive($name, $default = null, $prefix = self::DEFAULT_PREFIX)
    {
        $value = self::getWithDefault($name, $default);
        while (strpos($value, $prefix) === 0) {
            $value = getenv(substr($value, strlen($prefix)));
        }
        return $value ?: $default;
    }

    /**
     * Multi-way variable de-referencing utility.
     *
     * @param string $name Name of environment variable to dereference.
     * @param mixed $default Value to use, when the requested variable is not found.
     * @param string $prefix Defaults to Variable::DEFAULT_PREFIX.
     *
     * @return mixed
     *   string - Extracted value, when present.
     *   mixed - $default when the env variable is not set.
     */
    public static function getEmbedded($name, $default = null, $prefix = self::DEFAULT_PREFIX)
    {
        $matches = [];
        $value = self::getWithDefault($name, $default);
        $pattern = '/' . preg_quote($prefix, '/') . '([A-Z0-9_]+)/';
        if (false !== $value && preg_match_all($pattern, $value, $matches)) {
            $replacements = [];
            list ($placeholders, $names) = $matches;
            foreach ($placeholders as $index => $placeholder) {
                $replacements[$placeholder] = getenv($names[$index]);
            }
            $value = strtr($value, $replacements);
        }
        return $value ?: $default;
    }

    /**
     * Multi-way recursive variable de-referencing utility.
     *
     * @param string $name Name of environment variable to dereference.
     * @param mixed $default Value to use, when the requested variable is not found.
     * @param string $prefix Defaults to Variable::DEFAULT_PREFIX.
     *
     * @return mixed
     *   string - Extracted value, when present.
     *   mixed - $default when the env variable is not set.
     */
    public static function getEmbeddedRecursive($name, $default = false, $prefix = self::DEFAULT_PREFIX)
    {
        $matches = [];
        $value = self::getWithDefault($name, $default);
        $pattern = '/' . preg_quote($prefix, '/') . '([A-Z0-9_]+)/';

        while (false !== $value && preg_match_all($pattern, $value, $matches)) {
            $map = [];
            list ($keys, $names) = $matches;
            foreach ($keys as $i => $key) {
                $map[$key] = ($newValue = getenv($names[$i])) !== false ? $newValue : '';
            }
            $value = strtr($value, $map);
        }

        return $value ?: $default;
    }
}
