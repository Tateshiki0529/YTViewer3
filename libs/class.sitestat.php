<?php

	// Site statistics class file <class.sitestat.php>

	// Load cache controller class file
	require_once dirname(__FILE__).'/class.cache.php';

	/**
	 * [STATISTICS] サイト統計クラス (SiteStatistics)
	 *
	 * サイトの統計情報を取得するクラス。
	 *
	 * @access public
	 * @author Tateshiki0529 <lab@ttsk3.net>
	 * @copyright 2020 Tateshiki Lab. All Rights Reserved.
	 * @category Status
	 * @package Statistics
	**/
	class SiteStatistics {
		private $cache;

		/**
		 * [SETUP] コンストラクタ (__construct)
		 *
		 * インスタンスの生成とキャッシュオブジェクトの生成を行う。
		 *
		 * @access public
		**/
		public function __construct() {
			$this->cache = new Cache();
		}

		/**
		 * [GET] サイト統計情報取得 (loadStats)
		 *
		 * サイト統計情報を取得する。
		 *
		 * @access public
		 * @param int $mode キャッシュ読み込みモード (Referrence: docs/list.const.md)
		 * @return array $result 統計情報
		**/
		public function loadStats($mode) {
			$fn = $this->selectFile($mode);
			return json_decode(file_get_contents($fn["statsFile"]), true);
		}

		/**
		 * [GET] サイト統計情報保存 (saveStats)
		 *
		 * サイト統計情報を保存する。
		 *
		 * @access public
		 * @param int $mode キャッシュ読み込みモード (Referrence: docs/list.const.md)
		 * @return array $result 統計情報
		 * @return boolean false 失敗時
		**/
		public function saveStats($mode) {
			$fn = $this->selectFile($mode);
			if ($fn === false) return false;
			$file = json_decode(file_get_contents($fn["statsFile"]), true);
			foreach (array_keys($file) as $k) {
				array_shift($file[$k]);
				switch ($k) {
					case 'search_count':
						$file[$k][] = (int)file_get_contents($fn["searchCountFile"]);
						$fp = fopen($fn["searchCountFile"], "w");
						fputs($fp, "0");
						fclose($fp);
						break;
					case 'cache_count':
						$file[$k][] = $this->cache->countCache($mode);
						break;
					case 'save_date':
						$file[$k][] = date("H:i");
						break;
				}
			}
			$fp = fopen($fn["statsFile"], "w");
			fputs($fp, json_encode($file, JSON_PRETTY_PRINT));
			return fclose($fp);
		}

		/**
		 * [RESET] 統計情報リセット (resetStats)
		 *
		 * サイト統計情報をリセットする。
		 *
		 * @access public
		 * @param int $mode キャッシュ読み込みモード (Referrence: docs/list.const.md)
		 * @return boolean $result 処理の結果
		**/
		public function resetStats($mode) {
			$fn = $this->selectFile($mode);
			$arrayKeys = ["search_count", "cache_count", "save_date"];
			foreach ($arrayKeys as $k) {
				foreach (range(0,22) as $i) {
					$blank[$k][] = 0;
				}
			}
			$fp = fopen($fn["searchCountFile"], "w");
			fputs($fp, "0");
			fclose($fp);
			$fp = fopen($fn["statsFile"], "w");
			fputs($fp, json_encode($blank, JSON_PRETTY_PRINT));
			fclose($fp);
			return true;
		}

		/**
		 * [SELECT] ファイル名選択 (selectFile)
		 *
		 * ファイル名をモードから選択する。
		 *
		 * @access private
		 * @param int $mode キャッシュ読み込みモード (Referrence: docs/list.const.md)
		 * @return string $filename ファイル名
		 * @return boolean false モードが存在しない時
		**/
		private function selectFile($mode) {
			$root = DOC_ROOT;
			switch ($mode) {
				case CACHEMODE_VIDEO:
					return ["statsFile" => $root."/stats/video.json", "searchCountFile" => $root."/stats/search.video.txt"];
					break;
				case CACHEMODE_CHANNEL:
					return ["statsFile" => $root."/stats/channel.json", "searchCountFile" => $root."/stats/search.channel.txt"];
					break;
				case CACHEMODE_PLAYLIST:
					return ["statsFile" => $root."/stats/playlist.json", "searchCountFile" => $root."/stats/search.playlist.txt"];
					break;
				case CACHEMODE_PLAYLISTCONTENTS:
					return ["statsFile" => $root."/stats/pcontents.json", "searchCountFile" => $root."/stats/search.pcontents.txt"];
					break;
				default:
					return false;
					break;
			}
		}
	}
?>
