<?php
namespace App\Core;

class Cache
{
    private static string $cacheDir = __DIR__ . '/../../cache/';
    private static int $defaultTtl = 3600; // Default TTL in seconds (1 hour)

    /**
     * Get data from cache.
     * @param string $key
     * @return mixed|null
     */
    public static function get(string $key)
    {
        $cacheFile = self::getCacheFilePath($key);

        if (!file_exists($cacheFile)) {
            return null;
        }

        $content = file_get_contents($cacheFile);
        $data = unserialize($content);

        if (isset($data['expires_at']) && $data['expires_at'] < time()) {
            // Cache expired, delete file
            unlink($cacheFile);
            return null;
        }

        return $data['value'] ?? null;
    }

    /**
     * Set data to cache.
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl Time to live in seconds. If null, uses default.
     * @return bool
     */
    public static function set(string $key, $value, ?int $ttl = null): bool
    {
        $cacheFile = self::getCacheFilePath($key);
        $ttl = $ttl ?? self::$defaultTtl;

        $data = [
            'value' => $value,
            'expires_at' => time() + $ttl,
        ];

        // Ensure cache directory exists
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }

        return file_put_contents($cacheFile, serialize($data)) !== false;
    }

    /**
     * Clear a specific item from cache.
     * @param string $key
     * @return bool
     */
    public static function forget(string $key): bool
    {
        $cacheFile = self::getCacheFilePath($key);
        if (file_exists($cacheFile)) {
            return unlink($cacheFile);
        }
        return true;
    }

    /**
     * Get the full path for a cache key.
     * @param string $key
     * @return string
     */
    private static function getCacheFilePath(string $key): string
    {
        // Sanitize key to be a valid filename
        $filename = md5($key);
        return self::$cacheDir . $filename . '.cache';
    }

    /**
     * Clear all items from cache.
     * @return bool
     */
    public static function clearAll(): bool
    {
        $files = glob(self::$cacheDir . '*.cache');
        if ($files === false) {
            return false; // Error reading directory
        }

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }
}
