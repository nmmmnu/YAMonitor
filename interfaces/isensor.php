<?php
namespace monitor\sensors;

interface ISensor{
	function getAvailableOptions();
	function getValues();
}


