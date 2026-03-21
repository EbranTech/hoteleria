<?php
if (!function_exists('LoadEnvFile')) {

    function LoadEnvFile(string $path): void {

        if (!is_file($path) || !is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line === '' || $line[0] === '#') {
                continue;
            }

            [$name, $value] = array_pad(explode('=', $line, 2), 2, '');

            $name = trim($name);

            if ($name === '') {
                continue;
            }

            $value = trim($value);
            $value = trim($value, "\"'");

            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

if (!defined('APP_ENV_LOADED')) {
    LoadEnvFile(__DIR__ . '/../.env');
    define('APP_ENV_LOADED', true);
}
?>