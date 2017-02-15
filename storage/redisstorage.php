<?php
namespace monitor\storage;

require_once __DIR__ . "/../interfaces/istorage.php";

class RedisStorage implements IStorage{
	const MKEY_DELIMITER = ".";

	private $prefix;

	private $redis;

	private $redis_host;
	private $redis_port;
	private $redis_timeout;

	function __construct($prefix, $redis_host = "127.0.0.1", $redis_port = 6379, $redis_timeout = 1){
		$this->prefix		= $prefix;

		$this->redis_host	= $redis_host;
		$this->redis_port	= $redis_port;
		$this->redis_timeout	= $redis_timeout;

		$this->redis = new \Redis();
		$this->redis->connect($this->redis_host, $this->redis_port, $this->redis_timeout);

	}

	function store($timestamp, $value){
		// add uniqueness to the record...
		$value["_"] = $timestamp;
print_r($value);

		$key	= $this->prefix;
		$score	= $timestamp;
		$value	= json_encode($value);

		$this->redis->zadd($key, $score, $value);
	}
}

