<?php

$current_page = wCMS::slugify(wCMS::page('title'));

function alterAdmin($args) {
    if(!wCMS::$loggedIn) return $args;

    $doc = new DOMDocument();
    $doc->loadHTML($args[0]);

    $label = $doc->createElement("p");
    $label->setAttribute("class", "subTitle");
    $label->nodeValue = "Header height (in %)";

    $doc->getElementById("currentPage")->insertBefore($label, $doc->getElementById("currentPage")->lastChild);

    $wrapper = $doc->createElement("div");
    $wrapper->setAttribute("class", "change");

    $input = $doc->createElement("div");
    $input->setAttribute("class", "editText");
    $input->setAttribute("data-target", "pages");
    $input->setAttribute("id", "themeHeaderHeight");
    $input->nodeValue = isset(wCMS::get('pages', wCMS::slugify(wCMS::page('title')))->themeHeaderHeight) ? wCMS::get('pages', wCMS::slugify(wCMS::page('title')))->themeHeaderHeight : "100vh";

    $wrapper->appendChild($input);

    $doc->getElementById("currentPage")->insertBefore($wrapper, $doc->getElementById("currentPage")->lastChild);

    $args[0] = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());
    return $args;
}
wCMS::addListener('settings', 'alterAdmin');

$page_image = wCMS::asset('images/default.jpg');

// Check if image for this page exisis
$files = glob("files/*");
foreach($files as $file) {
	$filename = pathinfo($file, PATHINFO_FILENAME);
	if($filename == $current_page) {
		$page_image = $file;
		break;
	}
}


function getEditableArea($name, $default) {
	$block = wCMS::slugify(wCMS::page('title')) . "_" . $name;

	// Check if the newEditableArea area is already exists, if not, create it
	$value = null;
	if (empty(wCMS::get('blocks',$block))) {
		wCMS::set('blocks',$block, 'content', $default);
	} else {
		$value = wCMS::get('blocks',$block,'content');
	}

	if (wCMS::$loggedIn) {
        if($value == null) return "<script>history.go(0)</script>";

		return wCMS::block($block);
	}

	// If not logged in, return block in non-editable mode
	return $value;
}

$height = isset(wCMS::get('pages', $current_page)->themeHeaderHeight) ? wCMS::get('pages', $current_page)->themeHeaderHeight : "100";

$heading = getEditableArea("heading", wCMS::page('title'));
$subtitle = getEditableArea("subtitle", wCMS::page('description'));

if($current_page == 'login')
	$subtitle = wCMS::page('content');

?>
<!--
	WonderCMS 2.* Parallax Theme
	by Stephan Stanisic
-->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=wCMS::get('config','siteTitle')?> - <?=wCMS::page('title')?></title>
	<meta name="description" content="<?=wCMS::page('description')?>">
	<meta name="keywords" content="<?=wCMS::page('keywords')?>">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="<?=wCMS::asset('css/style.css')?>">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400&display=swap" rel="stylesheet">
	<?=wCMS::css()?>
	<?php /* Translate php to some javascript variables and css rules. Please don't replicate this. */ ?>
	<script>
	var page = <?=json_encode($current_page)?>;
	var heading = <?=json_encode($heading)?>;
	var subtitle = <?=json_encode($subtitle)?>;
	var image = <?=json_encode($page_image)?>;
	var height = <?=json_encode($height)?>;
	</script>
	<style> .parallax { height: <?=$height?>vh; } </style>
</head>
<body>
	<?=wCMS::alerts()?>
	<?=wCMS::settings()?>

	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=wCMS::url()?>">
					<?=wCMS::get('config','siteTitle')?>
				</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      			<ul class="nav navbar-nav navbar-right">
					<?=wCMS::menu()?>
				</ul>
			</div>
		</div>
	</nav>

    <header class="parallax-wrapper">
        <div class="parallax" style='background-image: url(<?=json_encode($page_image)?>);'>
            <div class="inner">
                <h1><?= $heading ?></h1>
                <?= wCMS::$loggedIn ? $subtitle : "<p>$subtitle</p>" ?>
            </div>
			<a href="#content" class="scrolly">Read more<br>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M119.5 262.9L3.5 145.1c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0L128 223.3l100.4-102.2c4.7-4.7 12.3-4.7 17 0l7.1 7.1c4.7 4.7 4.7 12.3 0 17L136.5 263c-4.7 4.6-12.3 4.6-17-.1zm17 128l116-117.8c4.7-4.7 4.7-12.3 0-17l-7.1-7.1c-4.7-4.7-12.3-4.7-17 0L128 351.3 27.6 249.1c-4.7-4.7-12.3-4.7-17 0l-7.1 7.1c-4.7 4.7-4.7 12.3 0 17l116 117.8c4.7 4.6 12.3 4.6 17-.1z" fill="#ffffff"/></svg></a>
        </div>
    </header>

	<div class="container" id="content">
		<div class="row">
			<div class="col-lg-12 text-center padding40">
				<?=wCMS::page('content')?>
			</div>
		</div>
	</div>

	<div class="container-fluid CTA">
			<div class="text-center padding40">
				<?=wCMS::block('subside')?>

			</div>
	</div>

	<footer class="container-fluid">
		<div class="text-center padding20">
			<br><br>
			<?=wCMS::footer()?>
			<br><br><br>
		</div>
	</footer>

	<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
	<?=wCMS::js()?>
    <script src="<?=wCMS::asset('js/script.js')?>"></script>
</body>
</html>
