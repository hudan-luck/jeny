<?php
namespace sf\cache;

class FileCache implements CacheInterface
{
    public $cachePath;

    public function buildKey($key)
    {
        if (!is_string($key)) {
            $key = json_encode($key);
        }
        return md5($key);
    }

    public function getCachePath($key)
    {
        $key = $this->buildKey($key);
        $cacheFile = $this->cachePath. $key;
        return $cacheFile;
    }

    public function get($key)
    {
        $cacheFile = $this->getCachePath($key);
        if (@filemtime($cacheFile) > time()) {
            //return unserialize(@file_get_contents($cacheFile));
            return @file_get_contents($cacheFile);
        } else {
            return false;
        }
    }
    
    public function exits($key)
    {
        $cacheFile = $this->getCachePath($key);
        return @filemtime($cacheFile) > time();
    }
    
    public function mget($keys)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[] = $this->get($key);
        }
        return $result;
    }
     
    public function set($key, $value, $duration = 0)
    {
        $cacheFile = $this->getCachePath($key);
        //$value = serialize($value);
        if (file_put_contents($cacheFile, $value, LOCK_EX) !== false) {
            if ($duration <= 0) {
                $duration = 31536000;
            }
            return touch($cacheFile, $duration + time());
        } else {
            return false;
        }
    }

    public function mset($items, $duration = 0)
    {
        $fileKeys = [];
        foreach ($items as $key => $val) {
            if ($this->set($key, $val, $duration) === false) {
                $filedKeys[] = $key;
            }
        }
        return $fileKeys;
    } 

    public function add($key, $value, $duration = 0)
    {
        if (!$this->exits($key)) {
            return $this->set($key, $value, $duration);
        } else {
            return false;
        }
    }
    
    public function madd($items, $duration = 0)
    {
        $faileKeys = [];
        foreach ($items as $key => $val) {
            if ($this->add($key, $val, $duration) === false) {
                $faileKeys[] = $key;
            }
        }               
        return $faileKeys;
    }
    
    public function delete($key)
    {
        $key = $this->builKey($key);
        $cacheFile = $this->cachePath. $key;
        unlink($cacheFile);
    }
    
    public function flush()
    {
        // 打开cache文件所在目录
        $dir = @dir($this->cachePath);
        // 列出目录中的所有文件
        while (($file = $dir->read()) !== false) {
            if ($file !== '.' && $file !== '..') {
               unlink($this->cachePath . $file);
            }
        }
        $dir->close();
    }
}
