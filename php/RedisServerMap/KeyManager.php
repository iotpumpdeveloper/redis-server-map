<?php 
namespace RedisServerMap;

class KeyManager
{
    private $key;
    private $redis;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function setRedisInstance($redis)
    {
        $this->redis = $redis;
    }

    public function getRedisInstance()
    {
        return $this->redis;
    }

    /**
     * set value to key
     */
    public function set($value)
    {
        $this->redis->set($this->key, $value);
    }

    /**
     * get the key value
     */
    public function value()
    {
        return $this->redis->get($this->key);
    }
}
