<?php

if (!function_exists('t')) {
    function t(string $key, string $lang = null): string
    {
        static $cache = [];
        $lang = $lang ?: (session('lang', 'en'));
        if (!isset($cache[$lang])) {
            $cache[$lang] = require __DIR__ . '/../../resources/lang/' . $lang . '.php';
        }
        return $cache[$lang][$key] ?? $key;
    }
}

if (!function_exists('__t')) {
    function __t(string $key, string $lang = null): string
    {
        return t($key, $lang);
    }
}

if (!function_exists('getAccessibilityMode')) {
    function getAccessibilityMode(): string
    {
        return session('accessibility_mode', 'normal');
    }
}

if (!function_exists('isHighContrast')) {
    function isHighContrast(): bool
    {
        return session('high_contrast', false);
    }
}

if (!function_exists('getTextSizeClass')) {
    function getTextSizeClass(): string
    {
        $size = session('text_size', 'medium');
        return match ($size) {
            'small' => 'text-sm',
            'large' => 'text-lg',
            default => '',
        };
    }
}

if (!function_exists('hasDisability')) {
    function hasDisability(string $type): bool
    {
        $types = session('disability_type', []);
        return in_array($type, $types);
    }
}

if (!function_exists('getAccessibilityBodyClass')) {
    function getAccessibilityBodyClass(): string
    {
        $classes = [];
        $mode = getAccessibilityMode();
        if ($mode !== 'normal') {
            $classes[] = 'accessibility-mode-' . $mode;
        }
        if (isHighContrast()) {
            $classes[] = 'high-contrast';
        }
        $size = getTextSizeClass();
        if ($size) {
            $classes[] = $size;
        }
        return implode(' ', $classes);
    }
}
