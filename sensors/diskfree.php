<?php
namespace monitor\sensors;

require_once __DIR__ . "/../interfaces/isensor.php";

class DiskFree implements ISensor{
	const DISK_TOTAL	= "disk_total";
	const DISK_FREE		= "disk_free";
	const DISK_FREE_PROC	= "disk_free_proc";
	const DISK_USED		= "disk_used";

	private $path;

	function __construct($path){
		$this->path = $path;
	}

	function getAvailableOptions(){
		return [ self::DISK_TOTAL, self::DISK_FREE, self::DISK_FREE_PROC, self::DISK_USED ];
	}

	function getValues(){
		$total = disk_total_space($this->path);
		$free  = disk_free_space($this->path);
		$proc  = self::percent_($free, $total);
		$used  = $total - $free;

		return [
			self::DISK_TOTAL	=>	self::mb_($total)	,
			self::DISK_FREE		=>	self::mb_($free)	,
			self::DISK_FREE_PROC	=>	$proc			,
			self::DISK_USED		=>	self::mb_($used)	,
		];
	}

	private static function mb_($a){
		return (int) ($a / 1024 / 1024);
	}

	private static function percent_($free, $total){
		$proc  = 100 * $free / $total;

		return sprintf("%.2f", $proc);
	}
}

