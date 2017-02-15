<?php
namespace monitor;

require_once __DIR__ . "/../interfaces/istorage.php";

require_once __DIR__ . "/monitor.php";

class Monitoring{
	const KEY_DELIMITER = '.';

	private $monitors;

	private $istorage;

	function __construct(storage\IStorage $istorage){
		$this->istorage = $istorage;
	}

	function add($id, sensors\ISensor $isensor){
		$this->monitors[ $id ] = new Monitor($id, $isensor);
	}

	function clear(){
		$this->monitors = [];
	}

	function process(){
		$timestamp = time();

		$data = [];

		foreach($this->monitors as $monitor){
			$id = $monitor->getID();

			foreach($monitor->get() as $key => $val)
				$data[$id . self::KEY_DELIMITER . $key] = $val;
		}

		$this->istorage->store($timestamp, $data);
	}
}

