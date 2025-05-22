<?php
spl_autoload_register(function ($class) {
    $prefix = 'PhpOffice\\PhpSpreadsheet\\';
    $base_dir = __DIR__ . '/PhpSpreadsheet/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

spl_autoload_register(function ($class) {
    if (strpos($class, 'Psr\\SimpleCache\\') === 0) {
        $path = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($path)) {
            require $path;
        }
    }
});

spl_autoload_register(function ($class) {
    $prefix = 'Composer\\Pcre\\';
    if (strpos($class, $prefix) === 0) {
        $file = __DIR__ . '/Composer/Pcre/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

spl_autoload_register(function ($class) {
    $prefix = 'ZipStream\\';
    $base_dir = __DIR__ . '/lib/ZipStream/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    
    // Maneja el caso especial ZipStream\ZipStream (sin subcarpeta)
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Si el archivo no existe, intenta cargarlo desde el directorio base (para ZipStream.php)
    if (!file_exists($file)) {
        $file = $base_dir . $relative_class . '.php';
    }

    if (file_exists($file)) {
        require $file;
    }
});

spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'MyCLabs\\Enum\\')) {
        $path = __DIR__ . '/lib/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($path)) {
            require $path;
        }
    }
});