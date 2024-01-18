<?php

use App\Framework\Facade\Config;

/**
 * Retrieves the value associated with the given key from the configuration.
 *
 * @param string $key The key to retrieve the configuration value for.
 *
 * @return mixed The value associated with the given key in the configuration.
 */
function config(string $key): mixed
{
    return Config::load($key);
}

/**
 * Returns the complete URL for a given path or the base URL if no path is provided.
 *
 * @param string $path (optional) The path to be appended to the base URL.
 *
 * @return string The complete URL.
 */
function url(string $path = ''): string
{
    $url = $_ENV['APP_URL'];
    if (!empty($path)) {
        $url = trim($url, '/') . '/' . $path;
    }
    return $url;
}

/**
 * Get the full path of a file or directory within the project.
 *
 * @param string $path (optional) The path of the file or directory. Defaults to an empty string.
 *
 * @return string The full path of the file or directory.
 */
function project_path(string $path = ''): string
{
    return realpath(PROJECT_PATH . '/' . $path);
}


/**
 * Returns the absolute path to the source directory or concatenates it with the provided path.
 *
 * @param string $path (optional) The path to concatenate with the source directory. Default is an empty string.
 *
 * @return string The absolute path to the source directory or the concatenated path.
 */
function source_path(string $path = ''): string
{
    if (empty($path)) {
        return realpath(PROJECT_PATH . '/src');
    }

    return realpath(PROJECT_PATH . '/src') . DIRECTORY_SEPARATOR . $path;
}


/**
 * Returns the path to a route file or the base route directory if no file is specified.
 *
 * @param string $file The name of the route file (optional).
 *
 * @return mixed The path to the specified route file or the base route directory.
 */
function route_path(string $file): mixed
{
    if (empty($file)) {
        return realpath(PROJECT_PATH . '/routes/');
    }

    return realpath(PROJECT_PATH . '/routes/') . DIRECTORY_SEPARATOR . $file;
}

/**
 * Returns the absolute path to a configuration file or directory.
 *
 * If a relative path is provided, it will be appended to the default configuration path.
 * If an empty string is provided, the default configuration path will be returned.
 * The returned path will be an absolute path.
 *
 * @param string $path The relative path to the configuration file or directory.
 *
 * @return string The absolute path to the configuration file or directory.
 */
function config_path(string $path = ''): string
{
    return realpath(PROJECT_PATH . '/config/' . $path);
}

/**
 * Returns the full path of a view file.
 * If the optional $path parameter is empty, the method will return the real path of the "resources/views" directory.
 * If the $path parameter is provided, the method will append it to the real path of the "resources/views" directory.
 *
 * @param string $path (optional) The path to append to the directory path. Default is an empty string.
 *
 * @return string The full path of the view file or directory.
 */
function view_path(string $path = ''): string
{
    if (empty($path)) {
        return realpath(PROJECT_PATH . '/resources/views');
    }

    return realpath(PROJECT_PATH . '/resources/views') . DIRECTORY_SEPARATOR . $path;
}

/**
 * Returns the absolute path to the HTTP directory.
 *
 * @param string $path Optional. The specific path to append to the HTTP directory. Defaults to an empty string.
 *
 * @return string The absolute path to the HTTP directory.
 */
function http_path(string $path = ''): string
{
    if (empty($path)) {
        return realpath(PROJECT_PATH . '/src/Http/');
    }

    return realpath(PROJECT_PATH . '/src/Http/') . DIRECTORY_SEPARATOR . $path;
}

/**
 * Returns the absolute path to the cache directory or a file within it.
 *
 * @param string $path (optional) The path to a file within the cache directory. Defaults to an empty string.
 *
 * @return string The absolute path to the cache directory or the absolute path to the specified file within the cache directory.
 */
function cache_path(string $path = ''): string
{
    return realpath(PROJECT_PATH . '/var/cache/' . $path);
}

/**
 * Generate the log file path.
 *
 * @param string $path (optional) The path to append to the log directory.
 *
 * @return string The absolute path to the log file.
 */
function log_path(string $path = ''): string
{
    return realpath(PROJECT_PATH . '/var/logs/') . DIRECTORY_SEPARATOR . $path;
}
