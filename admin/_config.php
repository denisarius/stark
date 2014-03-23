<?php
	$pmAdminVersion=array(1, 0, 1);

	require_once "{$_SERVER['DOCUMENT_ROOT']}/_config.php";
	if (($p=strrpos(__FILE__ , '/'))===false) $p=strrpos(__FILE__ , '\\');
	require_once substr(__FILE__, 0, $p).'/_config_db.php';
	require_once '_config_widgets.php';

$_admin_check_db_once=false;

$_admin_root_path="$_base_site_root_path/admin";
$_admin_widgets_path="$_admin_root_path/widgets";

$_admin_root_url="$_base_site_root_url/admin";
$_admin_widgets_url="$_admin_root_url/widgets";

$_admin_css_url="$_admin_root_url/css";
$_admin_js_url="$_admin_root_url/js";
$_admin_uploader_url="$_admin_root_url/uploader/uploads";

$_admin_common_proc_path="$_base_site_root_path/proc";
$_admin_pmEngine_path="$_base_site_root_path/pmEngine";
$_admin_proc_path="$_admin_root_path/proc";
$_admin_uploader_path="$_admin_root_path/uploader/uploads";

$_admin_backup_path="{$_SERVER['DOCUMENT_ROOT']}/_backups";
$_admin_backup_url="/_backups";

$_admin_messages_signature=<<<stop
<br><br>С уважением,<br>
Администрация сайта<br>
stop;

$_cms_texts_admin_list_page_length=20;
$_cms_customs_admin_list_page_length=20;
$_cms_customers_admin_list_page_length=20;
$_cms_users_admin_list_page_length=20;
$_cms_objects_admin_list_page_length=20;
$_cms_admin_walls_list_page_length=20;

$_cms_images_jpeg_quality=85;

$_admin_menu_selector_tree=true;
?>