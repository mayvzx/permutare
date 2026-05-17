<?php

spl_autoload_register(function (string $class): void {
    $prefixes = [
        'App\\' => BASE_PATH . '/app/',
        'Core\\' => BASE_PATH . '/core/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (str_starts_with($class, $prefix)) {
            $relativeClass = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (is_file($file)) {
                require $file;
            }
        }
    }
});

foreach (glob(BASE_PATH . '/app/Helpers/*.php') as $helperFile) {
    require_once $helperFile;
}
