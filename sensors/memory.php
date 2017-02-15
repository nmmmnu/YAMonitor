<?php
namespace monitor\sensors;

require_once __DIR__ . "/../interfaces/isensor.php";

class Memory implements ISensor{
	const MEM_TOTAL		= "mem_total";
	const MEM_FREE		= "mem_free";
	const MEM_FREE_PROC	= "mem_free_proc";
	const MEM_USED		= "mem_used";

	const MEM_BUFFERS	= "mem_buffers";
	const MEM_CACHED	= "mem_cached";

	const SWAP_TOTAL	= "swap_total";
	const SWAP_FREE		= "swap_free";
	const SWAP_FREE_PROC	= "swap_free_proc";
	const SWAP_USED		= "swap_used";

	const FILE		= "/proc/meminfo";

	function getAvailableOptions(){
		return [
			self::MEM_TOTAL, self::MEM_FREE, self::MEM_FREE_PROC, self::MEM_USED,

			self::MEM_BUFFERS, self::MEM_CACHED,

			self::SWAP_TOTAL, self::SWAP_FREE, self::SWAP_FREE_PROC, self::SWAP_USED,
		];
	}

	function getValues(){
		$data = self::getMemory_();

		$data[self::MEM_USED ] = $data[self::MEM_TOTAL] - $data[self::MEM_FREE]
					- $data[self::MEM_BUFFERS ] - $data[self::MEM_CACHED ];

		$data[self::SWAP_USED] = $data[self::SWAP_TOTAL] - $data[self::SWAP_FREE];

		$data[self::MEM_FREE_PROC ] = self::percent_($data[self::MEM_FREE ], $data[self::MEM_USED] );
		$data[self::SWAP_FREE_PROC] = self::percent_($data[self::SWAP_FREE], $data[self::SWAP_USED]);

		ksort($data);

		return $data;
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
			case "cached"		: $k = self::MEM_CACHED		; break;
			case "swaptotal"	: $k = self::SWAP_TOTAL	        ; break;
			case "swapfree"		: $k = self::SWAP_FREE		; break;
			}

			if (! $k)
				continue;

			$data[$k] = (int) trim($value);
		}

		return $data;
	}

	private static function percent_($free, $total){
		$proc  = 100 * $free / $total;

		return sprintf("%.2f", $proc);
	}
}

