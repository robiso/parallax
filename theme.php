<?php

global $Wcms;

$page_image = $Wcms->asset('images/default.jpg');
$heading = $Wcms->page('title');

$height = 100;
$subtitle = "";
$type = "";
$readMore = "";

if($Wcms->currentPage == $Wcms->get('config', 'login')) {
	$subtitle = $Wcms->page('content');
} else {
	// Check if image for this page exisis
	if(isset($Wcms->get("pages", $Wcms->currentPage)->background) && $Wcms->get("pages", $Wcms->currentPage)->background != "") {
		$page_image = "data/files/" . $Wcms->get("pages", $Wcms->currentPage)->background;
	}

	$height = isset($Wcms->get('pages', $Wcms->currentPage)->themeHeaderHeight) ? $Wcms->get('pages', $Wcms->currentPage)->themeHeaderHeight : 100;

	// $heading = getEditableArea("heading", $Wcms->page('title'));
	// $subtitle = getEditableArea("subtitle", "");

	$type = isset($Wcms->get('pages', $Wcms->currentPage)->parallax) ? $Wcms->get('pages', $Wcms->currentPage)->parallax : "dual";
	$readMore = isset($Wcms->get('pages', $Wcms->currentPage)->readMoreText) ? $Wcms->get('pages', $Wcms->currentPage)->readMoreText : "Read more";
}

?>
<!DOCTYPE html>
<!--
	WonderCMS 3.0.0 Parallax Theme
	by Stephan Stanisic
-->
<html lang="en">
<head>
	
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$Wcms->get('config','siteTitle')?> - <?=$Wcms->page('title')?></title>
	<meta name="description" content="<?=$Wcms->page('description')?>">
	<meta name="keywords" content="<?=$Wcms->page('keywords')?>">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400&display=swap" rel="stylesheet">

	<!-- Admin CSS -->
	<?= $Wcms->css() ?>

	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?= $Wcms->asset('css/style.css') ?>">

	<?php /* Translate php to some javascript variables and css rules. Please don't replicate this. */ ?>
	<script>var page=<?=json_encode($Wcms->currentPage)?>,heading=<?=json_encode($heading)?>,subtitle=<?=json_encode($subtitle)?>,image=<?=json_encode($page_image)?>,height=<?=json_encode($height)?>,type=<?=json_encode($type)?>,loggedIn=<?=json_encode($Wcms->loggedIn)?>;</script>
	<style>.parallax{height:<?=htmlentities($height)?>vh;}</style>
</head>
<body>
		<?= $Wcms->settings() ?>
		<?= $Wcms->alerts() ?>

	<nav class="navbar navbar-default<?php if($height == 0)echo " background"; if(!$Wcms->loggedIn && $height == 0)echo " sticky no-animation"; ?>">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=$Wcms->url()?>">
					<?=$Wcms->get('config','siteTitle')?>
				</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	  			<ul class="nav navbar-nav navbar-right">
					<?=$Wcms->menu()?>
				</ul>
			</div>
		</div>
	</nav>

	<?php if($height != 0): ?>

	<header class="parallax-wrapper">
		<div class="parallax" style='background-image: url(<?=json_encode($page_image)?>);'>
			<div class="inner">
				<h1><?= $heading ?></h1>
				<?= $Wcms->loggedIn ? $subtitle : "<p>$subtitle</p>" ?>
			</div>
			<?php if($readMore): ?>
			<a href="#content" class="scrolly"><?=$readMore?><br>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M119.5 262.9L3.5 145.1c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0L128 223.3l100.4-102.2c4.7-4.7 12.3-4.7 17 0l7.1 7.1c4.7 4.7 4.7 12.3 0 17L136.5 263c-4.7 4.6-12.3 4.6-17-.1zm17 128l116-117.8c4.7-4.7 4.7-12.3 0-17l-7.1-7.1c-4.7-4.7-12.3-4.7-17 0L128 351.3 27.6 249.1c-4.7-4.7-12.3-4.7-17 0l-7.1 7.1c-4.7 4.7-4.7 12.3 0 17l116 117.8c4.7 4.6 12.3 4.6 17-.1z" fill="#ffffff"/></svg></a>
			<?php endif; ?>
		</div>
	</header>

	<?php endif; ?>

	<?php if($Wcms->currentPage != $Wcms->get('config', 'login')): ?>

	<div class="container" id="content">
		<div class="row">
			<div class="col-lg-12 text-center padding40">
				<?=$Wcms->page('content')?>
			</div>
		</div>
	</div>

	<div class="container-fluid CTA">
			<div class="text-center padding40">
				<?=$Wcms->block('subside')?>
			</div>
	</div>

	<?php else: ?>

	<style>.parallax .scrolly { display: none }</style>

	<?php endif; ?>

	<footer class="container-fluid">
		<div class="text-center padding20">
			<br><br>
			<?=$Wcms->footer()?>
			<br><br><br>
		</div>
	</footer>

	<?php
		if(!$Wcms->loggedIn) {
			echo "<script src='https://code.jquery.com/jquery-1.12.4.min.js' integrity='sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ' crossorigin='anonymous'></script>";
		}
	?>
	
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
	<?=$Wcms->js()?>

	<script src="<?=$Wcms->asset('js/script.js')?>"></script>

	<?php /* Add some overwrites for pages with different parallax types. */ ?>
	<?php if($type == "scroll") echo "<style>.parallax{background-attachment:fixed;}</style>"; ?>
	<?php if($height == 100) echo "<style>.parallax{padding-top:0;}</style>"; ?>
</body>
</html>
