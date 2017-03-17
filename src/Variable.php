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

    /**
     * Simple and fast linear dereferencer.
     *
     * @param string $name
     *   Name of env var to dereference.
     * @param string $default
     *   Value to use, when the requested variable is not found.
     * @param string $prefix
     *   Defauts to #.
     *
     * @return string|bool
     *   string - Extracted value, when present.
     *   bool - false when the env variable is not set.
     */
    public static function get($name, $default = false, $prefix = self::DEFAULT_PREFIX)
    {
        $value = self::getWithDefault($name, $default);
        if (strpos($value, $prefix) === 0) {
            $value = getenv(substr($value, strlen($prefix)));
        }
        return $value;
    }

    /**
     * Simple and fast recursive linear dereferencer.
     *
     * @param string $name
     *   Name of env var to dereference.
     * @param string $default
     *   Value to use, when the requested variable is not found.
     * @param string $prefix
     *   Defauts to #.
     *
     * @return string|bool
     *   string - Extracted value, when present.
     *   bool - false when the env variable is not set.
     */
    public static function getRecursive($name, $default = false, $prefix = self::DEFAULT_PREFIX)
    {
        $value = self::getWithDefault($name, $default);
        while (strpos($value, $prefix) === 0) {
            $value = getenv(substr($value, strlen($prefix)));
        }
        return $value;
    }

    /**
     * Multy-way variable dereferencing utility.
     *
     * @param type $name
     * @param string $default
     *   Value to use, when the requested variable is not found.
     * @param type $prefix
     *
     * @return type
     */
    public static function getEmbedded($name, $default = false, $prefix = self::DEFAULT_PREFIX)
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
        return $value;
    }

    /**
     * Multy-way recursive variable dereferencing utility.
     *
     * @param type $name
     * @param string $default
     *   Value to use, when the requested variable is not found.
     * @param type $prefix
     *
     * @return type
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
        return $value;
    }

    private static function getWithDefault($name, $default)
    {
        return ($value = getenv($name)) !== false ? $value : $default;
    }
}
