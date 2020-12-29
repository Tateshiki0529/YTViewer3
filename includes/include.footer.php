<footer id="footer">
	<div class="container">
		<div class="hidden-lg hidden-md text-justify no-padding-no-margin">
			<div class="col-12 no-padding">
				<span class="text-muted small"></span>
				<hr class="primary">
			</div>
			<div class="col-12 text-center">
				<span class="text-muted small">
					© Copyright 2016-<script type="text/javascript">document.write(new Date().getFullYear());</script> <a href="https://lab.ttsk3.net/" id="MobileCompanyLink" title="Tateshiki Lab." rel="index,follow" target="_blank">Tateshiki Lab.</a> All Rights Reserved.
				</span>
			</div>
		</div>
	</div>
</footer>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="./js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
<?php

	foreach ($stat->loadStats(CACHEMODE_VIDEO) as $k => $v) {
		if ($k == "save_date") foreach ($v as $v2) {
			$video["save_date"][] = "\"".$v2."\"";
		} elseif ($k == "search_count") foreach ($v as $v2) {
			$video["search_count"][] = $v2;
		} elseif ($k == "cache_count") foreach ($v as $v2) {
			$video["cache_count"][] = $v2;
		}
	}
	$sc_v = $video["search_count"];
	$cc_v = $video["cache_count"];
	foreach ($stat->loadStats(CACHEMODE_CHANNEL) as $k => $v) {
		if ($k == "save_date") foreach ($v as $v2) {
			$channel["save_date"][] = "\"".$v2."\"";
		} elseif ($k == "search_count") foreach ($v as $v2) {
			$channel["search_count"][] = $v2;
		} elseif ($k == "cache_count") foreach ($v as $v2) {
			$channel["cache_count"][] = $v2;
		}
	}
	$sc_c = $channel["search_count"];
	$cc_c = $channel["cache_count"];

	foreach ($stat->loadStats(CACHEMODE_PLAYLIST) as $k => $v) {
		if ($k == "save_date") foreach ($v as $v2) {
			$playlist["save_date"][] = "\"".$v2."\"";
		} elseif ($k == "search_count") foreach ($v as $v2) {
			$playlist["search_count"][] = $v2;
		} elseif ($k == "cache_count") foreach ($v as $v2) {
			$playlist["cache_count"][] = $v2;
		}
	}
	$sc_p = $playlist["search_count"];
	$cc_p = $playlist["cache_count"];

	rsort($sc_v);
	rsort($cc_v);
	$max_sc_v = $sc_v[0];
	$max_cc_v = $cc_v[0];
	if ($max_sc_v >= $max_cc_v) {$max_v = $max_sc_v;} else {$max_v = $max_cc_v;}
	if ($max_v < 10) $max_v = 10;
	rsort($sc_c);
	rsort($cc_c);
	$max_sc_c = $sc_c[0];
	$max_cc_c = $cc_c[0];
	if ($max_sc_c >= $max_cc_c) {$max_c = $max_sc_c;} else {$max_c = $max_cc_c;}
	if ($max_c < 10) $max_c = 10;
	rsort($sc_p);
	rsort($cc_p);
	$max_sc_p = $sc_p[0];
	$max_cc_p = $cc_p[0];
	if ($max_sc_p >= $max_cc_p) {$max_p = $max_sc_p;} else {$max_p = $max_cc_p;}
	if ($max_p < 10) $max_p = 10;

?>
<script type="text/javascript">
	var ctx_video = document.getElementById("chart_video").getContext("2d");
	ctx_video.canvas.height = 150;
	var chart_video = new Chart(ctx_video, {
		type: "line",
		data: {
			labels: [<?=implode(",", $video["save_date"]);?>],
			datasets: [{
				label: "検索回数 (回)",
				borderColor: "rgb(0, 255, 127)",
				backgroundColor: "rgba(0, 255, 127, 0.5)",
				fill: true,
				data: [<?=implode(",", $video["search_count"]);?>]
			},{
				label: "キャッシュ保存数 (個)",
				borderColor: "rgb(255, 215, 0)",
				backgroundColor: "rgba(255, 215, 0, 0.5)",
				fill: true,
				data: [<?=implode(",", $video["cache_count"]);?>]
			}]
		},
		options: {
			title: {
				display: true,
				text: "動画統計情報の推移"
			},
			scales: {
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: "取得時間"
					}
				}],
				yAxes: [{
										scaleLabel: {
												display: true,
												labelString: '回数'
										},
										ticks: {
												min: 0,
						stepSize: <?=selectStep($max_v);?>,
						max: <?=$max_v;?>
										}
								}]
			},
			responsive: true,
			maintainAspectRatio: true
		}
	});
</script>

<script type="text/javascript">
	var ctx_channel = document.getElementById("chart_channel").getContext("2d");
	ctx_channel.canvas.height = 150;
	var chart_channel = new Chart(ctx_channel, {
		type: "line",
		data: {
			labels: [<?=implode(",", $channel["save_date"]);?>],
			datasets: [{
				label: "検索回数 (回)",
				borderColor: "rgb(0, 255, 127)",
				backgroundColor: "rgba(0, 255, 127, 0.5)",
				fill: true,
				data: [<?=implode(",", $channel["search_count"]);?>]
			},{
				label: "キャッシュ保存数 (個)",
				borderColor: "rgb(255, 215, 0)",
				backgroundColor: "rgba(255, 215, 0, 0.5)",
				fill: true,
				data: [<?=implode(",", $channel["cache_count"]);?>]
			}]
		},
		options: {
			title: {
				display: true,
				text: "チャンネル統計情報の推移"
			},
			scales: {
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: "取得時間"
					}
				}],
				yAxes: [{
										scaleLabel: {
												display: true,
												labelString: '回数'
										},
										ticks: {
												min: 0,
						stepSize: <?=selectStep($max_c);?>,
						max: <?=$max_c;?>
										}
								}]
			},
			responsive: true,
			maintainAspectRatio: true
		}
	});
</script>

<script type="text/javascript">
	var ctx_playlist = document.getElementById("chart_playlist").getContext("2d");
	ctx_playlist.canvas.height = 150;
	var chart_playlist = new Chart(ctx_playlist, {
		type: "line",
		data: {
			labels: [<?=implode(",", $playlist["save_date"]);?>],
			datasets: [{
				label: "検索回数 (回)",
				borderColor: "rgb(0, 255, 127)",
				backgroundColor: "rgba(0, 255, 127, 0.5)",
				fill: true,
				data: [<?=implode(",", $playlist["search_count"]);?>]
			},{
				label: "キャッシュ保存数 (個)",
				borderColor: "rgb(255, 215, 0)",
				backgroundColor: "rgba(255, 215, 0, 0.5)",
				fill: true,
				data: [<?=implode(",", $playlist["cache_count"]);?>]
			}]
		},
		options: {
			title: {
				display: true,
				text: "プレイリスト統計情報の推移"
			},
			scales: {
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: "取得時間"
					}
				}],
				yAxes: [{
										scaleLabel: {
												display: true,
												labelString: '回数'
										},
										ticks: {
												min: 0,
						stepSize: <?=selectStep($max_p);?>,
						max: <?=$max_p;?>
										}
								}]
			},
			responsive: true,
			maintainAspectRatio: true
		}
	});
</script>

<script type="text/javascript">
		$('.bs-component [data-toggle="popover"]').popover();
		$('.bs-component [data-toggle="tooltip"]').tooltip();
</script>
