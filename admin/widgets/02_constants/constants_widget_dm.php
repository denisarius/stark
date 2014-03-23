<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once 'constants_widget_proc.php';
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
	require_once "$_admin_common_proc_path/main.php";
	require_once "$_admin_proc_path/main.php";
//	require_once "$_admin_proc_path/design.php";

	if (!isset($section) || $section=='') exit;
	$link=connect_db();


//******************************************************************************
//
// Блок процедур для работы с константами
//
//******************************************************************************
	switch ($section)
	{
		case 'constantsConstAddSave':
			importVars('name|val', true);
			if (isset($name) && $name!='')
			{
            	$name=iconv ('utf-8', $html_charset, $name);
            	$val=iconv ('utf-8', $html_charset, $val);
				$id=get_data('name', $_cms_constants_table, "name='$name'");
				if ($id===false)
				{
					query("insert into $_cms_constants_table (name, value) values ('$name', '$val')");
					echo constants_get_list_html();
				}
			}
			break;
		case 'constantsConstEditHtml':
			importVars('id', true);
			if (isset($id) && $id!='')
			{
				$name=get_data('name', $_cms_constants_table, "id='$id'");
				echo serialize_data('html|name', str_replace("\r\n", ' ', constants_get_constant_edit_block_html($id)), $name);

			}
//			echo constants_get_constant_edit_block_html($id);
			break;
		case 'constantsConstEditSave':
			importVars('name|val', true);
			if (isset($name) && $name!='')
			{
            	$name=iconv ('utf-8', $html_charset, $name);
            	$val=iconv ('utf-8', $html_charset, $val);
				$id=get_data('id', $_cms_constants_table, "name='$name'");
				if ($id!==false)
				{
					query("update $_cms_constants_table set value='$val' where name='$name'");
					echo constants_get_list_item_html($id);
				}
			}
			break;
		case 'constantsConstEditDelete':
			importVars('id', true);
			if (isset($id) && $id!='')
			{
				$i=get_data('id', $_cms_constants_table, "id='$id'");
				if ($i!==false)
				{
					query("delete from $_cms_constants_table where id='$id'");
					echo 'ok';
				}
			}
			break;
	}
//------------------------------------------------------------------------------
?>