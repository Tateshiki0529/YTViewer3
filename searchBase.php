<?php

	// Search base program <searchBase.php>
	
	$input = $_POST["url"];
	$input = "https://www.youtube.com/user/0214mex";

	if (!isset($input)) {
		header("Location: index.php");
		exit();
	}

	if (!filter_var($input, FILTER_VALIDATE_URL)) {
		header("Location: index.php?serr=1"); // Invalid URL
		exit();
	}

	$urlData = parse_url($input);
	if (preg_match("/(www\.)?youtu(be\.com|\.be)/", $urlData["host"]) !== 1) {
		header("Location: index.php?serr=2"); // Is not YouTube URL
		exit();
	}

	parse_str($urlData["query"], $queries);
	$mode = [
		"video" => false,
		"playlist" => false,
		"channel" => false
	];
	if (isset($queries["v"])) $mode["video"] = true;
	if (isset($queries["list"])) $mode["playlist"] = true;
	if (strpos($urlData["path"], "/channel") !== false or strpos($urlData["path"], "/user") !== false) $mode["channel"] = true;

	if ($mode["video"]) {
		$videoId = $queries["v"];
		$query = [
			"mode" => "video",
			"id" => $videoId
		];
		if ($mode["playlist"]) {
			$query["mode"] .= ",playlist";
			$query["id"] .= ",".$queries["list"];
		}
		header("Location: validate.php?".http_build_query($query));
		exit();
	}
	if ($mode["playlist"]) {
		$playlistId = $queries["list"];
		$query = [
			"mode" => "playlist",
			"id" => $playlistId
		];
		header("Location: validate.php?".http_build_query($query));
		exit();
	}
	if ($mode["channel"]) {
		if (strpos($urlData["path"], "/user") !== false) {
			$isUserId = true;
		} else {
			$isUserId = false;
		}
		$channelId = explode("/", $urlData["path"])[2];
		$query = [
			"mode" => "channel",
			"id" => $channelId,
			"isUserId" => $isUserId
		];
		header("Location: validate.php?".http_build_query($query));
		exit();
	}
?>