<?php
namespace sf\cache;

interface CacheInterface
{
	public function buildKey($key);
	
	public function get($key);
	
	public function exits($key);
	
	public function mget($keys);

	public function set($key, $value, $duration = 0);

	public function mset($items, $duration = 0);
    
    //判断key 是否存在 ，再设置值
	public function add($key, $value, $duration = 0);
    
    //判断key 是否存在，再设置值
	public function madd($items, $duration = 0);
    
    //删除key
	public function delete($key);
    
    //删除所有
    public function flush();


}
