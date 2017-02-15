<?php
namespace monitor\storage;

require_once __DIR__ . "/../interfaces/istorage.php";

class BlackHole implements IStorage{
	function store($timestamp, $value){
		// yep, nothing here...
	}
}
