<header>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand" href="./index.php">
				YouTube Data Viewer 3
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbar">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item<?=(defined("SITE_CURRENT_PAGE") and SITE_CURRENT_PAGE == PAGE_HOME)?" active":"";?>"><a class="nav-link" href="<?=(defined("SITE_URL_HOME"))?SITE_URL_HOME:"#";?>">Home</a></li>
				</ul>
			</div>
		</div>
	</nav>
</header>
