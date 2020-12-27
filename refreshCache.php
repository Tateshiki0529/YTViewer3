<?php

	// Cache refresh program <refreshCache.php>
	error_reporting(!E_ALL);

	// Load cache control program
	require_once dirname(__FILE__)."/libs/class.cache.php";

	$cache = new Cache();
	$time = (int)date("G") % 6;
	var_dump($cache->updateCache($time));
?>
