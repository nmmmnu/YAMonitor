<?php
namespace monitor\sensors;

require_once __DIR__ . "/../interfaces/isensor.php";

class Memory implements ISensor{
	const MEM_TOTAL		= "mem_total";
	const MEM_FREE		= "mem_free";
	const MEM_BUFFERS	= "mem_buffers";
	const SWAP_TOTAL	= "swap_total";
	const SWAP_FREE		= "swap_free";

	const FILE		= "/proc/meminfo";

	function getAvailableOptions(){
		return [ self::MEM_TOTAL, self::MEM_FREE, self::SWAP_TOTAL, self::SWAP_FREE ];
	}

	function getValues(){
		return self::getMemory_();
	}

	static function getMemory_(){
		$txt = file_get_contents(self::FILE);

		$data = [];

		foreach(explode("\n", $txt) as $line){
			@list($key, $value) = explode(":", $line);

			$key = strtolower(trim($key));

			$k = false;

			switch($key){
			case "memtotal"		: $k = self::MEM_TOTAL		; break;
			case "memfree"		: $k = self::MEM_FREE		; break;
			case "buffers"		: $k = self::MEM_BUFFERS	; break;
			case "swaptotal"	: $k = self::SWAP_TOTAL	        ; break;
			case "swapfree"		: $k = self::SWAP_FREE		; break;
			}

			if (! $k)
				continue;

			$data[$k] = (int) trim($value);
		}

		return $data;
	}
}

