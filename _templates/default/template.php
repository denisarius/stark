<?php
global $_languages, $language, $_scripts_libs_url, $pagePath, $html_charset;
global $_base_site_js_url, $_base_site_css_url;

require_once pmIncludePath('design.php');

$language = $_languages[0];

echo <<<stop
<!Doctype HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$html_charset}">
<!-- link href="$_base_site_css_url/jquery-ui/jquery-ui.css" type="text/css" rel="stylesheet" -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script -->
<!-- script type="text/javascript" src="$_base_site_js_url/jquery.imagecbox.js"></script -->
<script type="text/javascript" src="$_base_site_js_url/core.js"></script>
<script type="text/javascript" src="$_base_site_js_url/main.js"></script>
<script type="text/javascript" src="$_base_site_js_url/script.js"></script>
</head>
<body class="yscroll">
<@gadget_header>
<@gadget_top_menu>
<div class="content_container">
<@gadget_submenu>
	<div class="content_container_center">
stop;
require "$pmTemplatesPath/include/gadget_lights_classes.php";
global $searchFilter;
$searchFilter = new SearchFilter();
switch ($pagePath[0])
{
	case 'order':
		echo '<@gadget_content_header><@gadget_content_menu><@gadget_content11>';
		break;
	case 'ceil_solutions':
		echo '<@gadget_content_header><@gadget_content_menu><@gadget_ceil_solutions><@gadget_managers>';
		break;
	case 'lights':
		echo '<@gadget_lights>';
		break;
	case 'light-details':
		echo '<@gadget_lights_details>';
		break;
	case 'search':
		echo '<@gadget_search>';
		break;

	// типовой макет
	default:
		echo '<@gadget_content_header><@gadget_content_menu><@gadget_content>';
		break;
}

echo <<<stop
	</div>
</div>
<@gadget_footer>
</body>
</html>
stop;
//------------------------------------------------------------------------------
?>
