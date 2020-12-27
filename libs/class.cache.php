<?php

	// Cache controller <class.cache.php>

	// Load config program
	require_once dirname(__FILE__)."/common.php";

	// Load exceptions class file
	require_once dirname(__FILE__)."/class.exceptions.php";

	// Load utilities program
	require_once dirname(__FILE__).'/functions.util.php';

	// Load constant definition file
	require_once dirname(__FILE__).'/const.cache.php';

	// Load YTAPI control class program
	require_once dirname(__FILE__).'/class.ytapi.php';

	/**
	 * [DB] キャッシュコントロールクラス
	 *
	 * DBを取り扱い、キャッシュの管理をするクラス。
	 *
	 * @access public
	 * @author Tateshiki0529 <info@ttsk3.net>
	 * @copyright 2020 Tateshiki Lab. All Rights Reserved.
	 * @category Save
	 * @package Controller
	**/
	class Cache {
		private $dsn = DB_DSN;
		private $user = DB_USER;
		private $pass = DB_PASS;
		private $option = DB_SETTINGS;
		private $pdo;
		private $api;

		/**
		 * [SETUP] コンストラクタ (__construct)
		 *
		 * PDOオブジェクトの準備をする。
		 *
		 * @access public
		 * @throws DBConnectException
		**/
		public function __construct() {
			try {
				$this->pdo = new PDO(
					$this->dsn,
					$this->user,
					$this->pass,
					$this->option
				);
				$this->api = new YouTubeAPI(false);
			} catch (PDOException $e) {
				throw new DBConnectException("データベースへの接続に失敗しました。[".$e->getMessage()."]");
			}
		}

		/**
		 * [INSERT, UPDATE] キャッシュ保存 (saveCache)
		 *
		 * キャッシュを保存する。
		 *
		 * @access public
		 * @param int $mode キャッシュ保存モード (Referrence: const.cache.php)
		 * @param array $data YouTubeAPIクラスのget系関数が返すデータ
		 * @param string ($id 別途IDを指定するモードのみ使用)
		 * @return boolean $result キャッシュの保存結果
		 * @throws DBDataDuplicateException データベースのデータの重複時に発生
		 * @see getVideo, getChannel, getPlaylist, getPlaylistContents (各種データ取得関数) (Referrence: class.ytapi.php)
		 * @see DBDataDuplicateException (データ重複エラー) (Referrence: class.exceptions.php)
		 * @deprecated DBDataInsertException, DBDataRetrieveException, DBDataUpdateException (DB操作時エラー) (Referrence: class.exceptions.php)
		**/
		public function saveCache($mode, $data, $id = null) {
			$nowTime = time();
			$select = $this->selectDB($mode);
			if ($mode == CACHEMODE_PLAYLISTCONTENTS) {$idValue = $id;} else {$idValue = $data["id"];}
			if ($select === false) return false;
			try {
				$stmt = $this->pdo->prepare("SELECT * FROM `{$select["tableName"]}` WHERE `{$select["idName"]}` = :id;");
				$stmt->bindParam(":id", $idValue, PDO::PARAM_STR);
				if (!$stmt->execute()) {
					#throw new DBDataRetrieveException("データの取得に失敗しました。時間をおいて再度やり直してください。それでも治らない場合、管理人へご連絡ください。");
					return false;
				}
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (count($result) == 1) {
					if (($nowTime - $result[0]["lastCached"]) > 180) {
						$stmt = $this->pdo->prepare("UPDATE `{$select["tableName"]}` SET `cacheData` = :data, `lastCached` = :last WHERE `{$select["idName"]}` = :id;");
						$encoded = serialize($data);
						$stmt->bindParam(":data", $encoded, PDO::PARAM_STR);
						$stmt->bindValue(":last", $nowTime, PDO::PARAM_INT);
						$stmt->bindParam(":id", $idValue, PDO::PARAM_STR);
						if (!$stmt->execute()) {
							#throw new DBDataUpdateException("データの更新に失敗しました。");
							return false;
						} else {
							return true;
						}
					}
				} elseif (count($result) == 0) {
					$stmt = $this->pdo->query("SELECT * FROM `{$select["tableName"]}` ORDER BY `serialNo` ASC;");
					$tempData = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$lastData = $tempData[count($tempData) - 1];
					$nextCircle = $lastData["updateCircle"] + 1;
					if ($nextCircle >= 6) $nextCircle = 0;
					$stmt = $this->pdo->prepare("INSERT INTO `{$select["tableName"]}` (`{$select["idName"]}`, `cacheData`, `lastCached`, `updateCircle`) VALUES (:id, :data, :last, :circle);");
					$encoded = serialize($data);
					$stmt->bindParam(":data", $encoded, PDO::PARAM_STR);
					$stmt->bindValue(":last", $nowTime, PDO::PARAM_INT);
					$stmt->bindParam(":id", $idValue, PDO::PARAM_STR);
					$stmt->bindValue(":circle", $nextCircle, PDO::PARAM_INT);
					if (!$stmt->execute()) {
						#throw new DBDataInsertException("データの追加に失敗しました。");
						return false;
					} else {
						return true;
					}
				} else {
					throw new DBDataDuplicateException("データの重複が確認されました。管理人へご連絡ください。(ID: {$idValue}) (Mode: {$mode})");
				}
			} catch (PDOException $e) {
				return false;
			}
		}

		/**
		 * [SELECT] キャッシュ読み込み (loadCache)
		 *
		 * キャッシュを読み込む。
		 *
		 * @access public
		 * @param int $mode キャッシュ読み込みモード (Referrence: docs/list.const.md)
		 * @param string $id 各種データのID
		 * @return array $result キャッシュに保存されているデータ
		 * @return boolean false データが保存されていない、または期限切れの場合
		 * @throws DBDataDuplicateException データベースのデータの重複時に発生
		 * @see DBDataDuplicateException (データ重複エラー) (Referrence: class.exceptions.php)
		**/
		public function loadCache($mode, $id) {
			$nowTime = time();
			$idValue = $id;
			$select = $this->selectDB($mode);
			if ($select === false) return false;
			try {
				$stmt = $this->pdo->prepare("SELECT * FROM `{$select["tableName"]}` WHERE `{$select["idName"]}` = :id;");
				$stmt->bindParam(":id", $id, PDO::PARAM_STR);
				if (!$stmt->execute()) {
					throw new DBDataRetrieveException("データの取得に失敗しました。時間をおいて再度やり直してください。それでも治らない場合、管理人へご連絡ください。");
				}
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (count($result) == 1) {
					if (($nowTime - $result[0]["lastCached"]) > 180) return false;
					$result[0]["cacheData"] = unserialize($result[0]["cacheData"]);
					return $result[0];
				} elseif (count($result) == 0) {
					return false;
				} else {
					throw new DBDataDuplicateException("データの重複が確認されました。管理人へご連絡ください。(ID: {$idValue}) (Mode: {$mode})");
				}
			} catch (PDOException $e) {
				return false;
			}
		}

		/**
		 * [REFRESH] キャッシュデータの更新 (updateCache)
		 *
		 * キャッシュの更新を行う。
		 *
		 * @access public
		 * @param int $circle アップデートサークル
		 * @return boolean $result アップデート結果
		 * @see アップデート周期のリスト (Referrence: list.updatecircle.md)
		**/
		public function updateCache($circle) {
			$availableMode = [
				CACHEMODE_VIDEO,
				CACHEMODE_CHANNEL,
				CACHEMODE_PLAYLIST
			];
			try {
				foreach ($availableMode as $v) {
					$select = $this->selectDB($v);
					$stmt = $this->pdo->prepare("SELECT `{$select["idName"]}` FROM {$select["tableName"]} WHERE `updateCircle` = :circle;");
					$stmt->bindParam(":circle", $circle, PDO::PARAM_STR);
					if (!$stmt->execute()) return false;
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$idList[$select["idName"]] = [];
					foreach ($result as $v2) {
						$idList[$select["idName"]][] = $v2[$select["idName"]];
					}
				}
				foreach($idList as $k => $v) {
					if ($k == "videoId") foreach ($v as $v2) $videoData[$v2] = $this->api->getVideo($v2);
					if ($k == "channelId") foreach ($v as $v2) $channelData[$v2] = $this->api->getChannel($v2);
					if ($k == "playlistId") foreach ($v as $v2) $playlistData[$v2] = $this->api->getPlaylist($v2);
				}

				#var_dump($videoData);
				foreach ($videoData as $k => $v) {
					if (!$this->saveCache(CACHEMODE_VIDEO, $v["data"])) return false;
				}
				foreach ($channelData as $k => $v) {
					if (!$this->saveCache(CACHEMODE_CHANNEL, $v["data"])) return false;
				}
				foreach ($playlistData as $k => $v) {
					if (!$this->saveCache(CACHEMODE_PLAYLIST, $v["data"])) return false;
				}
				return true;
			} catch (PDOException $e) {
				return false;
			}
		}

		/**
		 * [SELECT] モードの選別とデータベース選択関数 (selectDB)
		 *
		 * 入力されたモードによってDB文字列を返す。
		 *
		 * @access private
		 * @param int $mode キャッシュモード (Referrence: docs/list.const.md)
		 * @return array $result DB文字列
		 * @return boolean false キャッシュモードの判別に失敗したとき
		 * @see 定数一覧 (Referrence: docs/list.const.md)
		**/
		private function selectDB($mode) {
			switch ($mode) {
				case CACHEMODE_VIDEO:
					$tableName = "video_cache";
					$idName = "videoId";
					break;
				case CACHEMODE_CHANNEL:
					$tableName = "channel_cache";
					$idName = "channelId";
					break;
				case CACHEMODE_PLAYLIST:
					$tableName = "playlist_cache";
					$idName = "playlistId";
					break;
				case CACHEMODE_PLAYLISTCONTENTS:
					$tableName = "pcontents_cache";
					$idName = "playlistId";
					break;
				default:
					return false;
					break;
			}
			return ["tableName" => $tableName, "idName" => $idName];
		}
	}
?>
