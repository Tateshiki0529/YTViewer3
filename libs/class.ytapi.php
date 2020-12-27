<?php

	// YouTube Data API controller <class.ytapi.php>

	// Load config program
	require_once dirname(__FILE__)."/common.php";

	// Load exceptions class file
	require_once dirname(__FILE__)."/class.exceptions.php";

	// Load utilities program
	require_once dirname(__FILE__).'/functions.util.php';

	// Load cache class file
	require_once dirname(__FILE__)."/class.cache.php";

	// Main class
	/**
	 * [API] YouTube Data API コントロールクラス (YouTubeAPI)
	 *
	 * YouTube Data API v3(以下、YouTubeAPI)の情報を取得するクラス。
	 *
	 * @access public
	 * @author Tateshiki0529 <info@ttsk3.net>
	 * @copyright 2020 Tateshiki Lab. All Rights Reserved.
	 * @category Get
	 * @package Controller
	**/
	class YouTubeAPI {
		private $url = "https://www.googleapis.com/youtube/v3";
		private $cache;
		private $key;
		private $createCacheObject;

		/**
		 * [SETUP] コンストラクタ (__construct)
		 *
		 * APIキーの選択とキャッシュクラスの生成を行う。
		 *
		 * @access public
		 * @param boolean $createCacheObject キャッシュオブジェクトを作成するか
		 * @throws YTAPIUnavailableException 利用できるAPIキーが存在しないときに発生する。
		 * @throws DBConnectException データベースの接続に失敗したときに発生する。
		 * @see YTAPIUnavailableException, DBConnectException (Referrence: class.exceptions.php)
		**/
		public function __construct($createCacheObject = true) {
			// DB Connect
			if ($createCacheObject) $this->cache = new Cache();
			// Check API Key
			$this->createCacheObject = $createCacheObject;
			foreach (YTAPI_KEYS as $v) {
				$base_url = $this->url."/i18nRegions?";
				$params = http_build_query([
					"part" => "snippet",
					"hl" => "ja",
					"key" => $v
				]);
				$access_url = $base_url.$params;
				$result = json_decode(file_get_contents($access_url), true);
				if (!isset($result["error"])) {
					$this->key = $v;
				}
			}

			if (!isset($this->key)) {
				throw new YTAPIUnavailableException("利用できるAPIキーが見つかりませんでした。");
			}
		}

		/**
		 * [GET] カスタムリクエスト (get)
		 *
		 * パラメータを指定してデータを取得する。
		 *
		 * @access public
		 * @param string $endpoint アクセス先を指定 (e.g. /videos, /channels)
		 * @param string $target ターゲットID
		 * @param array $part 取得するリソースプロパティ (e.g. ["id", "snippet"])
		 * @param (array $option オプション)
		 * @return array $result 取得データ
		**/
		public function get($endpoint, $target, $part, $option = null) {
			// In develop
			return false;

			/*$base_url = $this->url.$endpoint."?";
			$params = http_build_query([
				"part" => "snippet,contentDetails,id,liveStreamingDetails,player,statistics,status",
				"fields" => "items(id,snippet(publishedAt,channelId,title,description,thumbnails(default,high),channelTitle,categoryId),contentDetails(duration),liveStreamingDetails,player,statistics,status)",
				"id" => $id,
				"hl" => "ja",
				"key" => $this->key
			]);
			$data = file_get_contents($base_url.$params);
			$result = json_decode($data, true)["items"];
			if ($result == null) return false;
			return $result;*/
		}

		/**
		 * [GET] 動画情報取得 (getVideo)
		 *
		 * 動画情報を取得する。
		 *
		 * @access public
		 * @param string $id 動画ID
		 * @param (array $options オプション)
		 * @return array $return 動画データ
		 * @return boolean false 取得失敗時
		 * @see file_cget_contents (データ取得関数) (Referrence: functions.util.php)
		 * @see convert8601_datetime, convert8601_duration (ISO-8601変換関数) (Referrence: class.ytapi.php)
		 * @todo $optionsパラメータ未実装
		 * @deprecated file_cget_contents (Referrence: functions.util.php) (利用する意味がなくなったため)
		**/
		public function getVideo($id, $options = null) {
			if ($this->createCacheObject) {
				$cacheData = $this->cache->loadCache(CACHEMODE_VIDEO, $id);
				if ($cacheData !== false) {
					$result = $cacheData["cacheData"];
					$cacheDetails = [
						"useCache" => true,
						"lastCached" => $cacheData["lastCached"],
						"isCached" => null
					];
				} else {
					$base_url = $this->url."/videos?";
					$params = http_build_query([
						"part" => "snippet,contentDetails,id,liveStreamingDetails,player,statistics,status",
						"fields" => "items(id,snippet(publishedAt,channelId,title,description,thumbnails(default,high),channelTitle,categoryId),contentDetails(duration),liveStreamingDetails,player,statistics,status)",
						"id" => $id,
						"hl" => "ja",
						"key" => $this->key
					]);
					$data = file_get_contents($base_url.$params);
					$result = json_decode($data, true)["items"][0];
					if ($result == null) return false;
					$result["snippet"]["publishedAt"] = $this->convert8601_datetime($result["snippet"]["publishedAt"]);
					$result["contentDetails"]["duration"] = $this->convert8601_duration($result["contentDetails"]["duration"]);
					if (isset($result["liveStreamingDetails"])) {
						$result["liveStreamingDetails"]["actualStartTime"] = $this->convert8601_datetime($result["liveStreamingDetails"]["actualStartTime"]);
						$result["liveStreamingDetails"]["actualEndTime"] = $this->convert8601_datetime($result["liveStreamingDetails"]["actualEndTime"]);
						$result["liveStreamingDetails"]["scheduledStartTime"] = $this->convert8601_datetime($result["liveStreamingDetails"]["scheduledStartTime"]);
					}
					$cacheSaved = $this->cache->saveCache(CACHEMODE_VIDEO, $result);
					$cacheDetails = [
						"useCache" => false,
						"lastCached" => time(),
						"isCached" => $cacheSaved
					];
				}
			} else {
				$base_url = $this->url."/videos?";
				$params = http_build_query([
					"part" => "snippet,contentDetails,id,liveStreamingDetails,player,statistics,status",
					"fields" => "items(id,snippet(publishedAt,channelId,title,description,thumbnails(default,high),channelTitle,categoryId),contentDetails(duration),liveStreamingDetails,player,statistics,status)",
					"id" => $id,
					"hl" => "ja",
					"key" => $this->key
				]);
				$data = file_get_contents($base_url.$params);
				$result = json_decode($data, true)["items"][0];
				if ($result == null) return false;
				$result["snippet"]["publishedAt"] = $this->convert8601_datetime($result["snippet"]["publishedAt"]);
				$result["contentDetails"]["duration"] = $this->convert8601_duration($result["contentDetails"]["duration"]);
				if (isset($result["liveStreamingDetails"])) {
					$result["liveStreamingDetails"]["actualStartTime"] = $this->convert8601_datetime($result["liveStreamingDetails"]["actualStartTime"]);
					$result["liveStreamingDetails"]["actualEndTime"] = $this->convert8601_datetime($result["liveStreamingDetails"]["actualEndTime"]);
					$result["liveStreamingDetails"]["scheduledStartTime"] = $this->convert8601_datetime($result["liveStreamingDetails"]["scheduledStartTime"]);
				}
				$cacheDetails = [
					"useCache" => false,
					"lastCached" => time(),
					"isCached" => null
				];
			}
			$return["data"] = $result;
			$return["cacheDetails"] = $cacheDetails;
			return $return;
		}

		/**
		 * [GET] チャンネル情報取得 (getChannel)
		 *
		 * チャンネル情報を取得する。
		 *
		 * @access public
		 * @param string $id チャンネルID
		 * @param (array $option オプション)
		 * @return array $result チャンネルデータ
		 * @return boolean false 取得失敗時
		 * @todo $optionパラメータ未実装
		**/
		public function getChannel($id, $option = null) {
			if ($this->createCacheObject) {
				$cacheData = $this->cache->loadCache(CACHEMODE_CHANNEL, $id);
				if ($cacheData !== false) {
					$result = $cacheData["cacheData"];
					$cacheDetails = [
						"useCache" => true,
						"lastCached" => $cacheData["lastCached"],
						"isCached" => null
					];
				} else {
					$base_url = $this->url."/channels?";
					$params = http_build_query([
						"part" => "id,snippet,contentDetails,statistics",
						"fields" => "items(id,snippet(title,description,publishedAt,thumbnails(default,high)),contentDetails,statistics)",
						"hl" => "ja",
						"id" => $id,
						"key" => $this->key
					]);
					$data = file_get_contents($base_url.$params);
					$result = json_decode($data, true)["items"][0];
					if ($result == null) return false;
					$result["snippet"]["publishedAt"] = $this->convert8601_datetime($result["snippet"]["publishedAt"]);
					$cacheSaved = $this->cache->saveCache(CACHEMODE_CHANNEL, $result);
					$cacheDetails = [
						"useCache" => false,
						"lastCached" => time(),
						"isCached" => $cacheSaved
					];
				}
			} else {
				$base_url = $this->url."/channels?";
				$params = http_build_query([
					"part" => "id,snippet,contentDetails,statistics",
					"fields" => "items(id,snippet(title,description,publishedAt,thumbnails(default,high)),contentDetails,statistics)",
					"hl" => "ja",
					"id" => $id,
					"key" => $this->key
				]);
				$data = file_get_contents($base_url.$params);
				$result = json_decode($data, true)["items"][0];
				if ($result == null) return false;
				$result["snippet"]["publishedAt"] = $this->convert8601_datetime($result["snippet"]["publishedAt"]);
				$cacheDetails = [
					"useCache" => false,
					"lastCached" => time(),
					"isCached" => null
				];
			}
			$return["data"] = $result;
			$return["cacheDetails"] = $cacheDetails;
			return $return;
		}

		/**
		 * [GET] プレイリスト情報取得 (getPlaylist)
		 *
		 * プレイリスト情報を取得する。
		 *
		 * @access public
		 * @param string $id プレイリストID
		 * @param (array $option オプション)
		 * @return array $result プレイリストデータ
		 * @return boolean false 取得失敗時
		 * @todo $optionパラメータ未実装
		**/
		public function getPlaylist($id, $option = null) {
			if ($this->createCacheObject){
				$cacheData = $this->cache->loadCache(CACHEMODE_PLAYLIST, $id);
				if ($cacheData !== false) {
					$result = $cacheData["cacheData"];
					$cacheDetails = [
						"useCache" => true,
						"lastCached" => $cacheData["lastCached"],
						"isCached" => null
					];
				} else {
					$base_url = $this->url."/playlists?";
					$params = http_build_query([
						"part" => "id,snippet,status",
						"fields" => "items(id,snippet(title,channelId,channelTitle,description,publishedAt,thumbnails(default,high)),status)",
						"hl" => "ja",
						"id" => $id,
						"key" => $this->key
					]);
					$data = file_get_contents($base_url.$params);
					$result = json_decode($data, true)["items"][0];
					if ($result == null) return false;
					$result["snippet"]["publishedAt"] = $this->convert8601_datetime($result["snippet"]["publishedAt"]);
					$cacheDetails = [
						"useCache" => false,
						"lastCached" => time(),
						"isCached" => null
					];
				}
			} else {
				$base_url = $this->url."/playlists?";
				$params = http_build_query([
					"part" => "id,snippet,status",
					"fields" => "items(id,snippet(title,channelId,channelTitle,description,publishedAt,thumbnails(default,high)),status)",
					"hl" => "ja",
					"id" => $id,
					"key" => $this->key
				]);
				$data = file_get_contents($base_url.$params);
				$result = json_decode($data, true)["items"][0];
				if ($result == null) return false;
				$result["snippet"]["publishedAt"] = $this->convert8601_datetime($result["snippet"]["publishedAt"]);
				$cacheSaved = $this->cache->saveCache(CACHEMODE_PLAYLIST, $result);
				$cacheDetails = [
					"useCache" => false,
					"lastCached" => time(),
					"isCached" => $cacheSaved
				];
			}
			$return["data"] = $result;
			$return["cacheDetails"] = $cacheDetails;
			return $return;
		}

		/**
		 * [GET] プレイリスト動画情報取得 (getPlaylistContents)
		 *
		 * プレイリスト動画情報を取得する。
		 *
		 * @access public
		 * @param string $id プレイリストID
		 * @param (array $option オプション)
		 * @return array $result プレイリストデータ
		 * @return boolean false 取得失敗時
		 * @todo $optionパラメータ未実装
		**/
		public function getPlaylistContents($id, $option = null) {
			if (!$this->createCacheObject) throw new YTAPINoCacheObjectException("キャッシュオブジェクトが作成されていないため、処理を継続できません。");
			$cacheData = $this->cache->loadCache(CACHEMODE_PLAYLISTCONTENTS, $id);
			if ($cacheData !== false) {
				$result = $cacheData["cacheData"];
				$cacheDetails = [
					"useCache" => true,
					"lastCached" => $cacheData["lastCached"],
					"isCached" => null
				];
			} else {
				$base_url = $this->url."/playlistItems?";
				$params = http_build_query([
					"part" => "id,snippet,contentDetails",
					"fields" => "nextPageToken,items(id,snippet(title,channelTitle,channelId,position),contentDetails),pageInfo",
					"hl" => "ja",
					"playlistId" => $id,
					"maxResults" => 25,
					"key" => $this->key
				]);
				$data = file_get_contents($base_url.$params);
				$result = json_decode($data, true);
				#var_dump($result);
				$token = $result["nextPageToken"];
				$result = $result["items"];
				if ($result == null) return false;
				while (isset($token)) {
					$params = http_build_query([
						"part" => "id,snippet,contentDetails",
						"fields" => "nextPageToken,items(id,snippet(title,channelTitle,channelId,position),contentDetails),pageInfo",
						"hl" => "ja",
						"playlistId" => $id,
						"key" => $this->key,
						"maxResults" => 25,
						"pageToken" => $token
					]);
					unset($token);
					$data2 = file_get_contents($base_url.$params);
					$result2 = json_decode($data2, true);
					$token = $result2["nextPageToken"];
					foreach ($result2["items"] as $v) $result[] = $v;
				}
				$count = 0;
				$cacheSaved = $this->cache->saveCache(CACHEMODE_PLAYLISTCONTENTS, $result, $id);
				$cacheDetails = [
					"useCache" => false,
					"lastCached" => time(),
					"isCached" => $cacheSaved
				];
			}
			$return["data"] = $result;
			foreach (range(0, count($return["data"]) - 2) as $v) {
				if (isset($return["data"][$v]["contentDetails"]["videoId"], $return["data"][0]["contentDetails"]["videoPublishedAt"])) {
					$return["data"][$v]["snippet"]["id"] = $return["data"][$v]["contentDetails"]["videoId"];
					$return["data"][$v]["snippet"]["publishedAt"] = $this->convert8601_datetime($return["data"][$v]["contentDetails"]["videoPublishedAt"]);
					unset($return["data"][$v]["contentDetails"]);
				}
			}
			$return["cacheDetails"] = $cacheDetails;
			return $return;
		}

		// --- ↑ Public | Private ↓ ---

		/**
		 * [PROCESS] ISO-8601の変換 (convert8601_datetime)
		 *
		 * YouTubeAPIから返されるISO-8691形式の日時をY/m/d H:i:s形式の日時に変換する。
		 *
		 * @access private
		 * @param string $date ISO-8601形式の日時 (タイムゾーン: UTC <+0000>)
		 * @return string $datetime Y/m/d H:i:s形式の日時 (タイムゾーン: Asia/Tokyo <+0900>)
		 * @see DateTimeクラス
		**/
		private function convert8601_datetime($date) {
			$date = str_replace("Z", "", $date);
			$converted = DateTime::createFromFormat("Y-m-d\TH:i:s", $date);
			#var_dump(DateTime::getLastErrors());
			return date("Y/m/d H:i:s", strtotime($converted->format("Y/m/d H:i:s")." +9 hours"));
		}

		/**
		 * [PROCESS] ISO-8601の変換 (convert8601_duration)
		 *
		 * YouTubeAPIから返されるISO-8691形式の期間をH:i:s形式の期間に変換する。
		 *
		 * @access private
		 * @param string $duration ISO-8601形式の期間
		 * @return string $time H:i:s形式の期間
		 * @see DateIntervalクラス
		**/
		private function convert8601_duration($duration) {
			$converted = new DateInterval($duration);
			return $converted->format("%H:%I:%S");
		}

	}
