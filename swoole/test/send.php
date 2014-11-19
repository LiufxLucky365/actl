<?php
	// $client_1 = stream_socket_client('tcp://127.0.0.101:9501', $errno, $errstr, 30);
	// $client_2 = stream_socket_client('tcp://127.0.0.101:9502', $errno, $errstr, 30);
	// $res = fwrite($client_1, 'frame_1');
	// $res = fwrite($client_2, 'frame_2');
	// fclose($client_1);
	// die();

	// $client_2 = stream_socket_client('tcp://127.0.0.101:9502', $errno, $errstr, 30);

	$connNum = 10;
	$data = "123 ";
	$count = 0;
	$connList = array();

	$client = stream_socket_client('tcp://127.0.0.101:9501', $errno, $errstr, 30);

	while($count<$connNum){
		$res = fwrite($client, "$count ");
		$count++;
	}
	sleep(3);
	fclose($client);