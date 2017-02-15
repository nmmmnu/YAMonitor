<?php
namespace monitor\sensors;

require_once __DIR__ . "/../interfaces/isensor.php";

class Redis implements ISensor{
	const CONN	= "connections";
	const MEM	= "memory";

	private $redis_host;
	private $redis_port;
	private $redis_timeout;

	function __construct($redis_host = "127.0.0.1", $redis_port = 6379, $redis_timeout = 1){
		$this->redis_host	= $redis_host;
		$this->redis_port	= $redis_port;
		$this->redis_timeout	= $redis_timeout;
	}

	function getAvailableOptions(){
		return [ self::CONN, self::MEM ];
	}

	function getValues(){
		try{
			$r = new \Redis();
			$r->connect($this->redis_host, $this->redis_port, $this->redis_timeout);

			$info = $r->info();

			return [
				self::CONN	=>	$info["connected_clients"]	,
				self::MEM	=>	self::mb_($info["used_memory"])	,
			];
		}catch(\RedisException $e){
			return [
				self::CONN	=>	0	,
				self::MEM	=>	0	,
			];
		}
	}

	private static function mb_($a){
		return (int) ($a / 1024 / 1024);
	}
}

