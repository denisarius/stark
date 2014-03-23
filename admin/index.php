<?php
	require_once '_config.php';
	require_once "$_admin_common_proc_path/db.php";
	require_once "$_admin_common_proc_path/variables.php";
	require_once "$_admin_proc_path/common_design.php";
	require_once "$_admin_proc_path/main.php";
	require_once "$_admin_proc_path/db.php";

	require_once "$_admin_proc_path/widgets_core.php";

	global $widgets;
	
	session_start();
	$link=connect_db(false);
	if ($link===false) show_db_global_error();

	$widgets=new TWidgets();
	foreach($widgets->get_widgets() as $widget)
	{
		$fn="$_admin_widgets_path/{$widget['path']}/{$widget['id']}_widget.php";
		if (file_exists($fn))
		require_once "$fn";
		$wc='T'.ucfirst($widget['id']);
		$obj=new $wc;
		$widgets->set_widget_object($widget['id'], $obj);
	}

	$js_tags=$widgets->get_js_includes_tags();
	$css_tags=$widgets->get_css_includes_tags();
	$head=<<<stop
<link href="$_admin_css_url/colorbox.css" rel="stylesheet" type="text/css">
<link href="$_admin_js_url/jcrop/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css">
<link href="$_admin_widgets_url/common.css" rel="stylesheet" type="text/css">
<link href="$_base_site_css_url/jquery-ui/jquery-ui.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<!-- script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="$_scripts_libs_url/libs/js/string.format/string.format.js"></script>
<script type="text/javascript" src="$_admin_js_url/_admin.js"></script>
<script type="text/javascript" src="$_base_site_js_url/jquery.imagecbox.js"></script>
$js_tags
$css_tags
stop;

    show_admin_workframe_start($head);
	show_admin_top($widgets->get_current_section_name());
	$widgets->show_main_menu();
	$widgets->show_content();
	show_admin_workframe_end();

	mysql_close($link);
?>
