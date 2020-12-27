<?php

	// Common config file <common.php>
	// Copied original file "common.php"

	// --- [YouTube Data API v3] ---
	define("YTAPI_KEYS", ["--- SOME KEY #1 ---", "--- SOME KEY #2 ---", "--- SOME KEY #3 ---"]);

	// --- [Twitter API] ---
	define("TWITTER_CONSUMER_KEY", "");
	define("TWITTER_CONSUMER_SECRET", "");
	define("TWITTER_ACCESS_TOKEN", "");
	define("TWITTER_ACCESS_TOKEN_SECRET", "");

	// --- [ytv3.ml Database] ---
	define("DB_DSN", "--- DATABASE DSN ---");
	define("DB_USER", "--- DATABASE USERNAME ---");
	define("DB_PASS", "--- DATABASE PASSWORD ---");
	define("DB_SETTINGS", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]);
