<?php
	
	// Test file <tests.php>

	// Load class
	require_once './class.ytapi.php';

	$api = new YouTubeAPI();

	header("Content-Type: application/json");

	switch ($_GET["m"]) {
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
	}

?>