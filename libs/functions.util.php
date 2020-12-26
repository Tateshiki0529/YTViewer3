<?php
	// Function file <functions.util.php>

	/**
	 * [FUNCTIONS] 関数個別定義ファイル
	 *
	 * クラス化するまでもない関数を定義する。
	 *
	 * @access public
	 * @author Tateshiki0529 <info@ttsk3.net>
	 * @copyright 2020 Tateshiki Lab. All Rights Reserved.
	 * @category Function
	 * @package Functions
	**/

	/**
	 * [GET] 外部データをcURLで取得する関数 (file_cget_contents)
	 *
	 * file_get_contentsをcURLで行う代替関数。
	 *
	 * @access public
	 * @param string $address 取得先のURL
	 * @param (array $options オプション)
	 * @return string $result 取得先のデータ
	 * @todo $optionsパラメータ未実装
	 * @deprecated
	 * @link http://blazechariot.wp.xdomain.jp/%E7%84%A1%E6%96%99%E3%83%AC%E3%83%B3%E3%82%BF%E3%83%AB%E3%82%B5%E3%83%BC%E3%83%90%E3%83%BCxdomain%E3%81%A7file_get_contents%E3%81%8C%E5%88%A9%E7%94%A8%E3%81%A7%E3%81%8D%E3%81%AA%E3%81%84%E6%99%82
	**/
	function file_cget_contents($address, $options = null) {
		$ch = curl_init(); // 初期化
		curl_setopt( $ch, CURLOPT_URL, $address ); // URLの設定
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // 出力内容を受け取る設定
		curl_setopt( $ch, CURLOPT_REFERER, "https://ytv3.ml/");
		$result = curl_exec( $ch ); // データの取得
		curl_close($ch); // cURLのクローズ

		return $result;
	}
?>
