<?php
namespace monitor\storage;

require_once __DIR__ . "/../interfaces/istorage.php";

class Federated implements IStorage{
	private $istorage1;
	private $istorage2;
	private $istorage3;
	private $istorage4;

	function __construct(IStorage $istorage1, IStorage $istorage2 = NULL, IStorage $istorage3 = NULL, IStorage $istorage4 = NULL){
		$this->istorage1 = $istorage1;
		$this->istorage2 = $istorage2;
		$this->istorage3 = $istorage3;
		$this->istorage4 = $istorage4;
	}

	function store($timestamp, $value){
		self::store_($this->istorage1, $timestamp, $value);
		self::store_($this->istorage2, $timestamp, $value);
		self::store_($this->istorage3, $timestamp, $value);
		self::store_($this->istorage4, $timestamp, $value);
	}

	private static function store_(/* IStorage */ $istorage, $timestamp, $value){
		if ($istorage)
			$istorage->store($timestamp, $value);
	}
}

