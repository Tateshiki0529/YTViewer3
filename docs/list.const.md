# 定数一覧  
---
YouTube Data Viewer(以下、本サイト)で使われている定数は以下の通り。  

## 現在使用中の定数
| カテゴリ   | 定数名                     | 定数値                                                                               | 説明                                                   | 定義ファイル名       |
|:----------:|:--------------------------:|:------------------------------------------------------------------------------------:|:------------------------------------------------------:|:--------------------:|
| Database   | DB_DSN                     | \(非公開\)                                                                           | PDOインスタンス用のDSN\(Data Source Name\)             | libs/common.php      |
| ⇓         | DB_USER                    | \(非公開\)                                                                           | データベースユーザー名                                 | ⇓                   |
| ⇓         | DB_PASS                    | \(非公開\)                                                                           | データベースパスワード                                 | ⇓                   |
| ⇓         | DB_SETTINGS                | `[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]` | データベース接続時の設定                               | ⇓                   |
| YouTubeAPI | YTAPI_KEYS                 | \(非公開\)                                                                           | YouTube Data APIの接続用キー                           | ⇓                   |
| CacheMode  | CACHEMODE_VIDEO            | `1`                                                                                  | モード指定定数。動画モードを指定する。                 | libs/const.cache.php |
| ⇓         | CACHEMODE_CHANNEL          | `2`                                                                                  | モード指定定数。チャンネルモードを指定する。           | ⇓                   |
| ⇓         | CACHEMODE_PLAYLIST         | `3`                                                                                  | モード指定定数。再生リストモードを指定する。           | ⇓                   |
| ⇓         | CACHEMODE_PLAYLISTCONTENTS | `4`                                                                                  | モード指定定数。再生リストコンテンツモードを指定する。 | ⇓                   |

## 現在は使用していない定数
| カテゴリ   | 定数名                      | 定数値     | 説明                    | 定義ファイル名  |
|:----------:|:---------------------------:|:----------:|:-----------------------:|:---------------:|
| TwitterAPI | TWITTER_CONSUMER_KEY        | \(未設定\) | Twitterを操作するキー。 | libs/common.php |
| ⇓         | TWITTER_CONSUMER_SECRET     | \(未設定\) | 同上。                  | ⇓              |
| ⇓         | TWITTER_ACCESS_TOKEN        | \(未設定\) | 同上。                  | ⇓              |
| ⇓         | TWITTER_ACCESS_TOKEN_SECRET | \(未設定\) | 同上。                  | ⇓              |