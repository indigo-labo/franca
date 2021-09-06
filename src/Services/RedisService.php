<?php

namespace IndigoLabo\Franca\Services;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    private $redis = null;
    private $connection = null;
    private $expire = 86400;

    public function __construct($connection = 'default')
    {
        $this->connection = $connection;
        $this->redis = Redis::connection($this->connection);
    }

    public function expire($expire) {
        $this->expire = $expire;
    }

    public function set($key, $value) {
        $this->redis->set($key, serialize($value));
        $this->redis->expire($key, $this->expire);
    }

    public function get($key) {
        $value = $this->redis->get($key);
        return $value ? unserialize($this->redis->get($key)) : null;
    }
}
