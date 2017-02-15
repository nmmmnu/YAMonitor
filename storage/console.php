<?php
namespace monitor\storage;

require_once __DIR__ . "/../interfaces/istorage.php";

class Console implements IStorage{
	function store($timestamp, $value){
		$date = date("Y-m-d H:i:s", $timestamp);

		foreach($value as $key => $val)
			printf("[ %s ] %-32s = %s\n", $date, $key, $val);
	}
}

