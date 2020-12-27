<?php

	// Cache refresh program <refreshCache.php>

	// Load cache control program
	require_once dirname(__FILE__)."/libs/class.cache.php";

	$cache = new Cache();
	$time = (int)date("G") % 6;
	echo $cache->updateCache($time);

?>
