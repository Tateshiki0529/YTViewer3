<?php

	// Test file <tests.php>

	// Load classes
	require_once './class.ytapi.php';
	require_once './class.cache.php';
	require_once './class.sitestat.php';

	$api = new YouTubeAPI();
	$cache = new Cache();
	$stat = new SiteStatistics();

	header("Content-Type: application/json");

	/*switch ($_GET["m"]) {
		case 'video':
			var_dump($api->getVideo($_GET["id"]));
			break;
		case 'channel':
			var_dump($api->getChannel($_GET["id"]));
			break;
		case 'playlist':
			var_dump($api->getPlaylist($_GET["id"]));
			break;
		case 'playlistcontents':
			var_dump($api->getPlaylistContents($_GET["id"]));
			break;
		default:
			# code...
			break;
	}*/

	#var_dump($stat->resetStats(CACHEMODE_VIDEO));
	var_dump($_SERVER["DOCUMENT_ROOT"]);

?>
