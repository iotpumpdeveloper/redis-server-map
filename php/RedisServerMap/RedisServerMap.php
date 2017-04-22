<?php
namespace RedisServerMap;

use KeyDistributor;

class RedisServerMap
{
    private $weightedNodeMap;
    private $keyDistributor;
    private $redisInstances;
    private $keyManagers;

    public function __construct($serverMap)
    {
        $this->serverMap = $serverMap;
        $this->weightedNodeMap = new KeyDistributor\WeightedNodeMap($serverMap);
        $this->weightedNodeMap->syncSlotWithNodes();
        $this->redisInstances = [];
        $this->keyManagers = [];
    }

    public function getServerNameForKey($key)
    {
        $serverName = $this->weightedNodeMap->getNodeForKey($key); 
        return $serverName;
    }

    public function getRedisInstanceForServer($serverName)
    {
        if ( !isset($this->redisInstances[$serverName]) ) {
            $redis = new \Redis();
            $redis->connect(
                $this->serverMap[$serverName]['host'], 
                $this->serverMap[$serverName]['port']
            );
            $this->redisInstances[$serverName] = $redis;
        }
        return $this->redisInstances[$serverName];
    }

    /**
     * get redis instance for a specific key
     */
    public function getRedisInstanceForKey($key)
    {
        $serverName = $this->getServerNameForKey($key);
        return $this->getRedisInstanceForServer($serverName);
    }

    public function getKeyManagerFor($key)
    {
        $redis = $this->getRedisInstanceForKey($key);
        if ( !isset($this->keyManagers[$key]) ) {
            $keyManager = new KeyManager($key);
            $keyManager->setRedisInstance($redis);
            $this->keyManagers[$key] = $keyManager;
        }
        return $this->keyManagers[$key];
    }
}
