<?php

	// Test file <tests.php>

	// Load class
	require_once './class.ytapi.php';
	require_once './class.cache.php';

	$api = new YouTubeAPI();
	$cache = new Cache();

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

	echo json_encode($cache->updateCache($_GET["circle"]), JSON_PRETTY_PRINT);

?>
