<?php

spl_autoload_register(function ($class_name) {
    $prefix = 'TestOnly\\';
    $base_dir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class_name, $len) !== 0) return;

    $file = $base_dir . str_replace('\\', '/', substr($class_name, $len)) . '.php';

    if (file_exists($file)) require $file;
});