<?php
// +----------------------------------------------------------------------
// | 蓝斧LEAPFU [ 探索不止，步履不停 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024 https://www.leapfu.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed Cloud Printer SDK is open source under the MIT license
// +----------------------------------------------------------------------
// | Author: Leapfu  <leapfu@hotmail.com>
// +----------------------------------------------------------------------

namespace Leapfu\CloudPrinter\Cache;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * 简单文件缓存实现
 * 不依赖任何第三方缓存库，兼容性最好
 */
class FileCache implements CacheInterface
{
    protected string $cacheDir;
    protected string $namespace;

    public function __construct(string $cacheDir = '', string $namespace = 'cloud_printer')
    {
        $this->cacheDir = $cacheDir ?: sys_get_temp_dir() . '/cloud_printer_cache';
        $this->namespace = $namespace;
        
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $filename = $this->getFilename($key);
        
        if (!file_exists($filename)) {
            return $default;
        }

        $data = file_get_contents($filename);
        $cached = json_decode($data, true);

        if ($cached === null || !isset($cached['expires']) || !isset($cached['value'])) {
            return $default;
        }

        if ($cached['expires'] > 0 && time() > $cached['expires']) {
            unlink($filename);
            return $default;
        }

        return $cached['value'];
    }

    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
    {
        $filename = $this->getFilename($key);
        $expires = $this->calculateExpiry($ttl);

        $data = [
            'value' => $value,
            'expires' => $expires,
        ];

        return file_put_contents($filename, json_encode($data)) !== false;
    }

    public function delete(string $key): bool
    {
        $filename = $this->getFilename($key);
        
        if (file_exists($filename)) {
            return unlink($filename);
        }

        return true;
    }

    public function clear(): bool
    {
        $pattern = $this->cacheDir . '/' . $this->namespace . '_*';
        $files = glob($pattern);
        
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
    {
        $success = true;
        
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                $success = false;
            }
        }

        return $success;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        $success = true;
        
        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                $success = false;
            }
        }

        return $success;
    }

    public function has(string $key): bool
    {
        return $this->get($key, null) !== null;
    }

    protected function getFilename(string $key): string
    {
        $safeKey = preg_replace('/[^a-zA-Z0-9_-]/', '_', $key);
        return $this->cacheDir . '/' . $this->namespace . '_' . $safeKey . '.cache';
    }

    protected function calculateExpiry(null|int|\DateInterval $ttl): int
    {
        if ($ttl === null) {
            return 0; // 永不过期
        }

        if (is_int($ttl)) {
            return $ttl > 0 ? time() + $ttl : 0;
        }

        if ($ttl instanceof \DateInterval) {
            $now = new \DateTime();
            $expiry = $now->add($ttl);
            return $expiry->getTimestamp();
        }

        return 0;
    }
} 