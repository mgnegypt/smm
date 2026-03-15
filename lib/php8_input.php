<?php

if (!function_exists('input_safe_array')) {
    /**
     * Normalize mixed input to an array for PHP 8-safe processing.
     */
    function input_safe_array($source): array
    {
        return is_array($source) ? $source : [];
    }
}

if (!function_exists('input_string')) {
    /**
     * Return trimmed string input from an array key.
     */
    function input_string(array $source, string $key, string $default = ''): string
    {
        if (!array_key_exists($key, $source) || $source[$key] === null) {
            return $default;
        }

        if (is_scalar($source[$key])) {
            return trim((string) $source[$key]);
        }

        return $default;
    }
}

if (!function_exists('input_array')) {
    /**
     * Return nested array input from an array key.
     */
    function input_array(array $source, string $key): array
    {
        if (!array_key_exists($key, $source) || !is_array($source[$key])) {
            return [];
        }

        return $source[$key];
    }
}

if (!function_exists('input_int')) {
    /**
     * Return integer value from input when valid; otherwise default.
     */
    function input_int(array $source, string $key, int $default = 0): int
    {
        if (!array_key_exists($key, $source)) {
            return $default;
        }

        $value = filter_var($source[$key], FILTER_VALIDATE_INT);

        return $value === false ? $default : (int) $value;
    }
}
