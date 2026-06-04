<?php

if (!function_exists('t')) {
    function t(string $key, string $lang = null): string
    {
        $lang = $lang ?: (session('lang', 'en'));
        $translations = require __DIR__ . '/../../resources/lang/' . $lang . '.php';
        return $translations[$key] ?? $key;
    }
}

if (!function_exists('__t')) {
    function __t(string $key, string $lang = null): string
    {
        return t($key, $lang);
    }
}
