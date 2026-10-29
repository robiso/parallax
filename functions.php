<?php
global $Wcms;

/**
 * The current page's settings, read without writing: $Wcms->get('pages', ...) creates and saves a key it does not find
 * (an empty page on a 404, a top-level copy of a subpage's slug). getCurrentPageData() walks the page tree and returns
 * null on a 404, but exists only since core 3.2.0, so older cores get the next best read instead of a fatal (3.2.6 broke them).
 */
function parallaxPageData() {
    global $Wcms;
    if (method_exists($Wcms, 'getCurrentPageData')) {
        return $Wcms->getCurrentPageData() ?? new stdClass();
    }
    if (isset($Wcms->currentPageExists) && !$Wcms->currentPageExists) { // 3.1.x: no write on a 404
        return new stdClass();
    }
    return $Wcms->get('pages', $Wcms->currentPage); // 3.0.x: the 3.2.4 read
}

function getEditableArea($name, $default) {
    global $Wcms;
	$block = $Wcms->currentPage . "_" . $name;

	$value = null;
	if (empty($Wcms->get('blocks',$block))) {
		$Wcms->set('blocks',$block, 'content', $default);
	} else {
		$value = $Wcms->get('blocks',$block,'content');
	}
	if ($Wcms->loggedIn) {
        // Do a not so nice reload to make `$Wcms->block($block)` return any content.
        if($value === null) return "<script>history.go(0)</script>";

		return $Wcms->block($block);
	}

	// If not logged in, return block in non-editable mode
	return $value;
}

function alterAdmin($args) {
    global $Wcms;

    if(!$Wcms->loggedIn) return $args;

    $doc = new DOMDocument();
    @$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $args[0]);

    /* Input element for header height */

    $label = $doc->createElement("p");
    $label->setAttribute("class", "subTitle");
    $label->nodeValue = "Header height (in %, set to 0 to hide header)";

    $doc->getElementById("currentPage")->insertBefore($label, $doc->getElementById("currentPage")->lastChild->previousSibling->previousSibling);

    $wrapper = $doc->createElement("div");
    $wrapper->setAttribute("class", "change");

    $input = $doc->createElement("div");
    $input->setAttribute("class", "editText");
    $input->setAttribute("data-target", "pages");
    $input->setAttribute("id", "themeHeaderHeight");
    $input->nodeValue = isset(parallaxPageData()->themeHeaderHeight) ? parallaxPageData()->themeHeaderHeight : "100";

    $wrapper->appendChild($input);

    $doc->getElementById("currentPage")->insertBefore($wrapper, $doc->getElementById("currentPage")->lastChild->previousSibling->previousSibling);

    /* Enable / disable / edit the read more text */

    $label = $doc->createElement("p");
    $label->setAttribute("class", "subTitle");
    $label->nodeValue = "Read more text (leave empty to hide)";

    $doc->getElementById("currentPage")->insertBefore($label, $doc->getElementById("currentPage")->lastChild->previousSibling->previousSibling);

    $wrapper = $doc->createElement("div");
    $wrapper->setAttribute("class", "change");

    $input = $doc->createElement("div");
    $input->setAttribute("class", "editText");
    $input->setAttribute("data-target", "pages");
    $input->setAttribute("id", "readMoreText");
    $input->nodeValue = isset(parallaxPageData()->readMoreText) ? parallaxPageData()->readMoreText : "Read more";

    $wrapper->appendChild($input);

    $doc->getElementById("currentPage")->insertBefore($wrapper, $doc->getElementById("currentPage")->lastChild->previousSibling->previousSibling);

    /* Input element for background */

    $label = $doc->createElement("p");
    $label->setAttribute("class", "subTitle");
    $label->nodeValue = "Background image";

    $doc->getElementById("currentPage")->insertBefore($label, $doc->getElementById("currentPage")->lastChild->previousSibling->previousSibling);

	$form_group = $doc->createElement("div");
    $form_group->setAttribute("class", "form-group");

    $wrapper = $doc->createElement("div");
    $wrapper->setAttribute("class", "change");

    $input = $doc->createElement("select");
    $input->setAttribute("class", "form-control");
    $input->setAttribute("onchange", "fieldSaveParallax('background',this.value,'pages');");
    $input->setAttribute("name", "backgroundSelect");

	$option = $doc->createElement("option");
	$option->setAttribute("value", "");
	$option->nodeValue = "Theme default";
	$input->appendChild($option);

	$files = glob($Wcms->filesPath . "/*");
	foreach($files as $file) {
		if(!in_array(getimagesize($file)[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) continue;

		$file = basename($file);

		$option = $doc->createElement("option");
	    $option->setAttribute("value", $file);
		$option->nodeValue = $file;

		if(isset(parallaxPageData()->background) && parallaxPageData()->background == $file)
			$option->setAttribute("selected", "selected");

		$input->appendChild($option);
	}

    $wrapper->appendChild($input);
    $form_group->appendChild($wrapper);

    $doc->getElementById("currentPage")->insertBefore($form_group, $doc->getElementById("currentPage")->lastChild->previousSibling->previousSibling);

    /* Input element for parallax type */

    $label = $doc->createElement("p");
    $label->setAttribute("class", "subTitle");
    $label->nodeValue = "Parallax type";

    $doc->getElementById("currentPage")->insertBefore($label, $doc->getElementById("currentPage")->lastChild->previousSibling->previousSibling);

	$form_group = $doc->createElement("div");
    $form_group->setAttribute("class", "form-group");

    $wrapper = $doc->createElement("div");
    $wrapper->setAttribute("class", "change");

    $input = $doc->createElement("select");
    $input->setAttribute("class", "form-control");
    $input->setAttribute("onchange", "fieldSaveParallax('parallax',this.value,'pages');");
    $input->setAttribute("name", "parallaxSelect");

    $types = ["Dual", "Parallax", "Inverse", "Scroll", "None"];
    foreach($types as $type) {
    	$option = $doc->createElement("option");
    	$option->setAttribute("value", strtolower($type));
    	$option->nodeValue = $type;

        if(isset(parallaxPageData()->parallax) && parallaxPageData()->parallax == strtolower($type))
			$option->setAttribute("selected", "selected");

    	$input->appendChild($option);
    }

    $wrapper->appendChild($input);
    $form_group->appendChild($wrapper);

    $doc->getElementById("currentPage")->insertBefore($form_group, $doc->getElementById("currentPage")->lastChild->previousSibling->previousSibling);

    $args[0] = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());
    return $args;
}
$Wcms->addListener('settings', 'alterAdmin');

?>
