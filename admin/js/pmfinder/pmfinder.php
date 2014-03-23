<?php
	if (isset($_REQUEST['_SESSION'])) die('Global error. Engine stopped.');

	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;

	session_start();
	require_once "$path/_config.php";
    require_once "$pmPath/pmMain.php";
	require_once "$_admin_common_proc_path/db.php";
    require_once 'pmf_config.php';
    require_once 'pmfinder_proc.php';

	$link=connect_db();

	pmImportVars('CKEditorFuncNum');

    // инициализируем путь к корню картинок в таблице виртуальных имен
	$id=get_data('id', '_vfs', "real_path='$pmf_root_path'");
	if ($id===false)
	{
//		$p=strrpos($pmf_root_path, '/');
//		$parent_path=substr($pmf_root_path, 0, $p);
//		$dir_uid=pmf_get_unique_file_node_id($parent_path, '');
		query("insert into _vfs (real_name, real_path, virtual_name, virtual_path) values ('', '$pmf_root_path', '', '')");
	}

	if (isset($_SESSION['pmf_current_folder'])) $cur_folder=$_SESSION['pmf_current_folder'];
	else $cur_folder='';
	$dir=pmf_get_tree_html($cur_folder);
	$files=pmf_get_files_list_html($cur_folder);
	echo <<<stop
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$html_charset}">
<link href="pmfinder.css" rel="stylesheet" type="text/css">
<link href="$_admin_css_url/admin.css" rel="stylesheet" type="text/css">
<link href="$_base_site_css_url/jquery-ui/jquery-ui.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="pmfinder.js"></script>
<script type="text/javascript" src="$_admin_js_url/_admin.js"></script>
<script type="text/javascript" src="$_admin_root_url/uploader/swfupload.js"></script>

<script type="text/javascript">
	$(window).resize(_pmf_window_resize);
	$(document).ready(function(){
    	_pmf_window_resize();
	});
</script>
</head>
<body>
<input type='hidden' id='CKEditorFuncNum' value='$CKEditorFuncNum'>
<input type='hidden' id='cPath' value='$pmf_root_path'>
<div id="pmf_frame_left" class="pmf_frame_left">
	<div id= "pmf_left_frame_content" class="pmf_left_frame_content">
$dir
	</div>
</div>
<div id="pmf_frame_thumbnails" class="pmf_frame_thumbnails">
	<div class="pmf_toolbar_container">
		<div id="pmf_toolbar" class="pmf_toolbar">
			<img src="images/folder_add.png" class="admin_image_button" onClick="pmf_folder_add()" title="Создать папку">
			<img src="images/folder_delete.png" class="admin_image_button" onClick="pmf_folder_delete()" title="Удалить папку">
			<div class="separator"></div>
			<img src="images/file_upload.png" class="admin_image_button" onClick="pmf_image_upload()" title="Добавить файл">
		</div>
		<div class="pmf_toolbar_path" id="pmf_toolbar_path"></div>
	</div>
	<div id="pmf_thumbnails_frame_content" class="pmf_thumbnails_frame_content">
$files
	</div>
	<div id="pmf_status" class="pmf_status"></div>
</div>
</body>
</html>
stop;

	mysql_close($link);
?>
