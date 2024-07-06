<?php

if (!function_exists('boxpacker')) {
    function boxpacker()
    {
        return app(\Jmursuadev\BoxPacker\BoxPacker::class);
    }
}

if (!function_exists('boxpacker_public_path')) {
    function boxpacker_public_path($path = '')
    {
        return __DIR__ . '/public/' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
