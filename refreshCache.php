<?php

	// Cache refresh program <refreshCache.php>
	error_reporting(!E_ALL);
	header("Content-Type: application/json");

	// Load cache control program
	require_once dirname(__FILE__)."/libs/class.cache.php";

	require_once dirname(__FILE__)."/libs/class.sitestat.php";

	$cache = new Cache();
	$stat = new SiteStatistics();
	$time = (int)date("G") % 6;
	var_dump($cache->updateCache($time));
	if (date("i") == "00" or date("i") == "30") foreach ([CACHEMODE_VIDEO, CACHEMODE_CHANNEL, CACHEMODE_PLAYLIST, CACHEMODE_PLAYLISTCONTENTS] as $v) {
		var_dump($stat->saveStats($v));
	}
?>
