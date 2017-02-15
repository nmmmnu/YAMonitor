<?php
namespace monitor\sensors;

require_once __DIR__ . "/../interfaces/isensor.php";

class LA implements ISensor{
	const CPU	= "CPU";
	const LA1	= "LA1";
	const LA2	= "LA2";
	const LA3	= "LA3";

	const FILE_LA	= "/proc/loadavg";
	const FILE_CPU	= "/proc/cpuinfo";

	function getAvailableOptions(){
		return [ self::CPU, self::LA1, self::LA2, self::LA3 ];
	}

	function getValues(){
		list($la1, $la2, $la3) = self::getLA_();

		return [
			self::CPU	=>	self::format_( self::getCPUCount_() ),
			self::LA1	=>	self::format_($la1)	,
			self::LA2	=>	self::format_($la2)	,
			self::LA3	=>	self::format_($la3)	,
		];
	}

	private static function getLA_(){
		$txt = file_get_contents(self::FILE_LA);
		list($la1, $la2, $la3, $_) = explode(" ", $txt, 4);
		return [ $la1, $la2, $la3 ];
	}

	private static $cpuCount = false;

	private static function getCPUCount_(){
		if (self::$cpuCount)
			return self::$cpuCount;


		$cpuinfo = file_get_contents(self::FILE_CPU);
		preg_match_all('/^processor/m', $cpuinfo, $matches);
		self::$cpuCount = count($matches[0]);

		return self::$cpuCount;
	}

	private static function format_($a){
		return sprintf("%.2f", $a);
	}
}

