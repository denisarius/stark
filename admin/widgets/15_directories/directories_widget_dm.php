<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once 'directories_widget_proc.php';
	require_once "$path/_config.php";
	require_once "$_admin_common_proc_path/variables.php";
	require_once "$_admin_common_proc_path/cms.php";
	require_once "$_admin_common_proc_path/logs.php";
	if (file_exists("$_admin_common_proc_path/user.php")) require_once "$_admin_common_proc_path/user.php";
	require_once "$_admin_common_proc_path/main.php";
	require_once "$_admin_pmEngine_path/pmMain.php";
	require_once "$_admin_pmEngine_path/pmAPI.php";

	importVars('section', false);
	if(!isset($section) || $section=='') exit;

	header("Content-Type: text/html; charset={$html_charset}");

	require_once "$_admin_common_proc_path/db.php";
	require_once "$_admin_proc_path/main.php";
	require_once "$_admin_proc_path/common_design.php";

	if (!isset($section) || $section=='') exit;
	$link=connect_db();

//******************************************************************************
//
// Блок процедур для работы с новостями
//
//******************************************************************************
	switch ($section)
	{
		case 'dirsGetEditDirHtml':
			importVars('id', true);
			if (isset($id) && $id!='') echo dirs_get_edit_dir_html($id);
			break;
        case 'dirsDirDataSave':
			importVars('id|name', true);
			if (isset($id) && $id!='' && isset($name) && $name!='')
			{
				$name=mysql_safe(iconv ('utf-8', $html_charset, $name));
				echo dirs_save_dir_data($id, $name);
			}
			else
				echo serialize_data('error|dirs|dir_content', 'Ошибка добавления справочника', '', '');
			break;
		case 'dirsGetDirContent':
			importVars('id', true);
			if (isset($id)) echo dirs_get_dir_list_html($id);
			break;
		case 'dirsGetEditValueHtml':
			importVars('dir_id|val_id', true);
			if (isset($dir_id) && $dir_id!='') echo dirs_get_edit_value_html($dir_id, $val_id);
			break;
		case 'dirsDirValueSave':
			importVars('dir_id|val_id|val|menu_id', true);
			if (isset($dir_id) && $dir_id!='' && isset($val_id) && $val_id!='' && isset($val) && $val!='')
			{
				$val=mysql_safe(iconv ('utf-8', $html_charset, $val));
				echo dirs_save_dir_value($dir_id, $val_id, $val, $menu_id);
			}
			else
				echo serialize_data('error|node', 'Ошибка записи значения', '');
			break;
		case 'dirsDirValueAddList':
			importVars('dir_id|vals', true);
			if (isset($dir_id) && $dir_id!='' && isset($vals) && $vals!='') dirs_add_items_list($dir_id, $vals);
			echo dirs_get_dir_list_html($dir_id);
			break;
		case 'dirsDirValueDelete':
			importVars('val_id', true);
			if (isset($val_id) && $val_id!='')
				query("delete from $_cms_directories_data where id='$val_id'");
			break;
	}
?>
