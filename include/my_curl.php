<?php

function my_curl($url, $timeout = 2){
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,			$url		);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,	true		);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,	$timeout	);
	curl_setopt($ch,CURLOPT_TIMEOUT,		$timeout	);

	$response = curl_exec($ch);

	curl_close($ch);

	return $response;
}

