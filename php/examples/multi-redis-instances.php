<?php
require_once __DIR__.'/../../vendor/autoload.php';

$map = [
    's1' => [
        'weight' => 4,
        'host' => '127.0.0.1',
        'port' => 6379
    ],
    's2' => [
        'weight' => 4,
        'host' => '127.0.0.1',
        'port' => 6379
    ],
    's3' => [
        'weight' => 6,
        'host' => '127.0.0.1',
        'port' => 6379
    ]
];

$redisServerMap = new RedisServerMap\RedisServerMap($map);

$key = "superjim";

$keyManager = $redisServerMap->getKeyManagerFor($key);
$keyManager->setKeyValue("2");
print $keyManager->getKeyValue()."\n";
