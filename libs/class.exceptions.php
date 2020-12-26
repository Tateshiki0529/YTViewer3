<?php
	
	// Exception class file <class.exceptions.php>
	
	/**
	 * [EXCEPTIONS] 例外クラス群
	 *
	 * 例外クラス定義ファイル。
	 * エラーコードの定義: nmmll
	 * n = Primary reason
	 * m = Secondary reason
     * l = Sub secondary reason
	 *
	 * @access public
	 * @author Tateshiki0529 <info@ttsk3.net>
	 * @copyright 2020 Tateshiki Lab. All Rights Reserved.
	 * @category Except
	 * @package Exceptions
	**/

	/**
	 * [EXCEPTION] YouTube Data API利用不可の例外 (YTAPIUnavailableException)
	 *
	 * YouTube Data API v3 (以下、YouTubeAPI)の例外クラス。
	 * n = 1 (YTAPI error)
	 * m = 1 (Is unavailable)
	 *
	 * @access public
	 * @extends Exception
	**/
	class YTAPIUnavailableException extends Exception {
		/**
		 * [SETUP] コンストラクタ (__construct)
		 *
		 * 例外を発生させる。
		 *
		 * @access public
		 * @param string $message エラーメッセージ
		 * @throws YTAPIUnavailableException (Extend: Exception) (Error Code: 10101)
		 * @see Exception
		**/
		public function __construct($message) {
			parent::__construct($message." (Error 10101)", 10101);
		}
	}

	/**
	 * [EXCEPTION] データベース接続失敗の例外 (DBConnectException)
	 *
	 * データベースの例外クラス。
	 * n = 2 (DB error)
	 * m = 2 (Can't connect)
	 *
	 * @access public
	 * @extends Exception
	**/
	class DBConnectException extends Exception {
		/**
		 * [SETUP] コンストラクタ (__construct)
		 *
		 * 例外を発生させる。
		 *
		 * @access public
		 * @param string $message エラーメッセージ
		 * @throws DBConnectException (Extend: Exception) (Error Code: 20201)
		 * @see Exception
		**/
		public function __construct($message) {
			parent::__construct($message." (Error 20201)", 20201);
		}
	}

	/**
	 * [EXCEPTION] データベース重複の例外 (DBDataDuplicateException)
	 * 
	 * データベースの例外クラス。
	 * n = 2 (DB error)
	 * m = 3 (Is duplicated)
	 * l = 99 (Need admin to fix)
	 *
	 * @access public
	 * @extends Exception
	**/
	class DBDataDuplicateException extends Exception {
		/**
		 * [SETUP] コンストラクタ (__construct)
		 *
		 * 例外を発生させる。
		 *
		 * @access public
		 * @param string $message エラーメッセージ
		 * @throws DBDataDuplicateException (Extend: Exception) (Error Code: 20399)
		 * @see Exception
		**/
		public function __construct($message) {
			parent::__construct($message." (Error 20399)", 20399);
		}
	}

	/**
	 * [EXCEPTION] データベース取得エラーの例外 (DBDataRetrieveException)
	 * 
	 * データベースの例外クラス。
	 * n = 2 (DB error)
	 * m = 4 (Can't retrieve)
	 * l = 10 (Try again later or need admin to fix)
	 *
	 * @access public
	 * @extends Exception
	**/
	class DBDataRetrieveException extends Exception {
		/**
		 * [SETUP] コンストラクタ (__construct)
		 *
		 * 例外を発生させる。
		 *
		 * @access public
		 * @param string $message エラーメッセージ
		 * @throws DBDataRetrieveException (Extend: Exception) (Error Code: 20410)
		 * @see Exception
		**/
		public function __construct($message) {
			parent::__construct($message." (Error 20410)", 20410);
		}
	}

	/**
	 * [EXCEPTION] データベース更新エラーの例外 (DBDataUpdateException)
	 * 
	 * データベースの例外クラス。
	 * n = 2 (DB error)
	 * m = 5 (Can't update)
	 * l = 10 (Try again later or need admin to fix)
	 *
	 * @access public
	 * @extends Exception
	**/
	class DBDataUpdateException extends Exception {
		/**
		 * [SETUP] コンストラクタ (__construct)
		 *
		 * 例外を発生させる。
		 *
		 * @access public
		 * @param string $message エラーメッセージ
		 * @throws DBDataUpdateException (Extend: Exception) (Error Code: 20510)
		 * @see Exception
		**/
		public function __construct($message) {
			parent::__construct($message." (Error 20510)", 20510);
		}
	}

	/**
	 * [EXCEPTION] データベース追加エラーの例外 (DBDataInsertException)
	 * 
	 * データベースの例外クラス。
	 * n = 2 (DB error)
	 * m = 6 (Can't insert)
	 * l = 10 (Try again later or need admin to fix)
	 *
	 * @access public
	 * @extends Exception
	**/
	class DBDataInsertException extends Exception {
		/**
		 * [SETUP] コンストラクタ (__construct)
		 *
		 * 例外を発生させる。
		 *
		 * @access public
		 * @param string $message エラーメッセージ
		 * @throws DBDataInsertException (Extend: Exception) (Error Code: 20610)
		 * @see Exception
		**/
		public function __construct($message) {
			parent::__construct($message." (Error 20610)", 20610);
		}
	}