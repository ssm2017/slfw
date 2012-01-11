#!/usr/bin/env php
<?php

if (!empty($_SERVER['REQUEST_METHOD'])) {
  print "This script must only be run from the command line";
  die();
}

$debug = 0;

function http_post($server, $url, $data) {
  global $debug;

	$urlencoded = '';
	while (list($key,$value) = each($data)) {
		$urlencoded .= urlencode($key) . "=" . urlencode($value) . "&";
  }
	$urlencoded = substr($urlencoded, 0, -1);	

	$content_length = strlen($urlencoded);

  $headers .= "POST $url HTTP/1.0\n";
  $headers .= "Host: $server\n";
  $headers .= "Referer: $referrer\n";
  $headers .= "Accept: */*\n";
  $headers .= "Content-type: application/x-www-form-urlencoded\n";
  $headers .= "Connection: close\n";
	$headers .= "Cache-Control: no-cache\n";

  $headers .= "User-Agent: Second Life LSL/1.13.3(3) (http://secondlife.com)\n";

  // Second Life headers. Adjust those to your needs
  $headers .= "X-SecondLife-Shard: Testing\n";
  $headers .= "X-SecondLife-Object-Key: 8ed95fa8-b026-880e-c16c-f4eb3de3c624\n";
  $headers .= "X-SecondLife-Object-Name: Secondlife Test Object\n";
  $headers .= "X-SecondLife-Owner-Key: 24ac45f5-5253-417c-9e6e-3R544ffcb13f\n";
  $headers .= "X-SecondLife-Owner-Name: Someone Omega\n";
  $headers .= "X-SecondLife-Region: Urdu (236800, 280320)\n";
  $headers .= "X-SecondLife-Local-Position: (99.5225, 195.0857, 241.33728)\n";
  $headers .= "X-SecondLife-Local_Rotation: (0.000000, 0.000000, 0.373000, 0.927831)\n";
  $headers .= "X-SecondLife-Local_Velocity: (-0.000000, -0.000000, -0.188637)\n";

  $headers .= "Content-length: $content_length\n";
  $headers .= "\n";

  $request .= $urlencoded."\n";

	$fp = fsockopen($server, 80, $errno, $errstr);
	if (!$fp) {
		print "http_post(): fsockopen() failed: server $server. Error=$errno: $errstr\n";
		return "SOCKERR";
	}

	if ($debug) {
		print "http_post(): fputs headers=$headers\n";
  }

	fputs($fp, $headers);

	if ($debug) {
		print "http_post(): fputs request=$request\n";
  }

	fputs($fp, $request);
	
	$ret = "";
	while (!feof($fp)) {
		$str = fgets($fp, 4096);
	  print "$str";
		$ret .= $str;
	}
        
	if ($debug) {
		print "http_post(): ret: $ret\n";
	}

	fclose($fp);
	
	return $ret;
}

$host = $argv[1];
$url  = $argv[2];

if (!$host) {
  $host = 'localhost';
}

if (!$url) {
  $url = '/secondlife';
}

$post_data = array (
  'app' => 'sltest',
  'cmd' => 'hello',
  'arg' => '',
  );

return http_post($host, $url, $post_data);


