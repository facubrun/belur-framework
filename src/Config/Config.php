<?php

namespace Belur\Config;

class Config {
    private static array $config = [];

    public static function load(string $path) {
        foreach (glob($path . '/*.php') as $file) {
            $key = basename($file, '.php');
            self::$config[$key] = require_once $file;
        }
    }

    public static function get(string $configuration, $default = null) {
        $keys = explode('.', $configuration);
        $finalKey = array_pop($keys);
        $array = self::$config;

        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return $default;
            } else {
                $array = $array[$key];
            }
        }
        return $array[$finalKey] ?? $default;
    }
}
