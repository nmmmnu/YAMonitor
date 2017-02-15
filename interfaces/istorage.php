<?php
namespace monitor\storage;

interface IStorage{
	function store($timestamp, $value);
}


