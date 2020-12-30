<?php

	// Note: index.php error parameter (Referrence: index.php @ docs/list.params.md)

	// Index page <index.php>
	error_reporting(E_ALL&&!E_NOTICE);

	// Load site constant definition file
	require_once dirname(__FILE__).'/libs/const.site.php';

	// Load site statistics class file
	require_once dirname(__FILE__).'/libs/class.sitestat.php';

	// Load functions file
	require_once dirname(__FILE__).'/libs/functions.util.php';

	$stat = new SiteStatistics();

	// --- [Page Setting] ---
	define('SITE_TITLE', 'YouTube Data Viewer 3');
	define('SITE_CURRENT_PAGE', PAGE_HOME);

?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<?php require_once dirname(__FILE__).'/includes/include.head.php';?>
	</head>
	<body>
		<?php require_once dirname(__FILE__).'/includes/include.header.php';?>
		<div class="container text-center">
			<h2 class="my-5 heading h1">Welcome to YouTube Data Viewer 3!</h2>
			<p class="my-3">
				YouTube Data Viewer 3へようこそ。<br>
				このサイトは、YouTubeのデータ、また統計データを「分かりやすく」お伝えするため開発されています。
			</p>
			<h2 class="my-5 heading h2">使ってみる</h2>
			<?php require_once dirname(__FILE__).'/includes/include.searchform.php'; ?>
			<h2 class="my-5 heading h3">統計情報</h2>
			<div class="bs-component">
				<ul class="nav nav-tabs">
					<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#chart-video">動画</a></li>
					<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#chart-channel">チャンネル</a></li>
					<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#chart-playlist">プレイリスト</a></li>
				</ul>
				<div class="tab-content mb-3">
					<div class="tab-pane fade show active" id="chart-video"><canvas id="chart_video"></canvas></div>
					<div class="tab-pane fade" id="chart-channel"><canvas id="chart_channel"></canvas></div>
					<div class="tab-pane fade" id="chart-playlist"><canvas id="chart_playlist"></canvas></div>
				</div>
			</div>
		</div>
		<?php require_once dirname(__FILE__).'/includes/include.footer.php'; ?>
	</body>
</html>
