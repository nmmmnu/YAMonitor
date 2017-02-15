<?php
namespace monitor;

require_once __DIR__ . "/../interfaces/isensor.php";

class Monitor{
	private $id;
	private $isensor;

	function __construct($id, sensors\ISensor $isensor){
		$this->id		= $id;
		$this->isensor		= $isensor;
	}

	function getID(){
		return $this->id;
	}

	function get(){
		return $this->isensor->getValues();
	}

}

