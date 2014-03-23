<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;

	require_once 'pmf_config.php';
	require_once "$path/_config.php";
	require_once "$_admin_pmEngine_path/pmAPI.php";
	require_once "$_admin_common_proc_path/variables.php";
	require_once "$_admin_common_proc_path/db.php";
    require_once 'pmfinder_proc.php';

	setlocale(LC_CTYPE, 'ru_RU.CP1251');
	setlocale(LC_COLLATE, 'ru_RU.CP1251');

	session_start();
	
	$link=connect_db();
	header("Content-Type: text/html; charset={$html_charset}");

	$section=pmImportVarsList('section', false);
	switch ($section)
	{
		case 'pmfGetFilesList':
			$path=pmImportVarsList('path', false);
			if (isset($path) && $path!='')
			{
				$_SESSION['pmf_current_folder']=$path;
				echo pmf_get_files_list_html($path);
			}
			break;
		case 'pmfImageProcess':
			$vars=pmImportVarsList('file|path', false);
			if (isset($vars['path']) && $vars['path']!='' && isset($vars['file']) && $vars['file']!='')
				echo pmf_image_process($vars['file'], $vars['path']);
			break;
		case 'pmfFolderCreate':
			$vars=pmImportVarsList('name|path', false);
			if (isset($vars['name']) && $vars['name']!='')
			{
				$vars['name']=iconv ('utf-8', $html_charset, $vars['name']);
				$res=pmf_folder_create($vars['path'], $vars['name']);
				if ($res['err']=='')
				{
					$dir_tree=pmf_get_tree_html($res['real_path']);
					$files=pmf_get_files_list_html($res['real_path']);
				}
				echo serialize_data('err|dir_tree|files|cur_path', $err, $dir_tree, $files, $res['real_path']);
			}
			else
				echo serialize_data('err|dir_tree|files|cur_path', 'Function arguments error', '', '', $pmf_root_path);
			break;
		case 'pmfFolderDeleteIsPossible':
			$path=pmImportVarsList('path', false);
			echo pmf_folder_delete_is_possible($path);
			break;
		case 'pmfFolderDelete':
			$path=pmImportVarsList('path', false);
			if (isset($path) && $path!='')
			{
				$err=pmf_folder_delete($path);
				if ($err=='')
				{
					$p=strrpos($path, '/');
					$path=substr($path, 0, $p);
					if (strlen($path)==strlen($pmf_root_path)) $path='';
					$dir_tree=pmf_get_tree_html($path);
					$files=pmf_get_files_list_html($path);
				}
				echo serialize_data('err|dir_tree|files', $err, $dir_tree, $files);
			}
			else
				echo serialize_data('err|dir_tree|files', 'Function arguments error', '', '');
			break;
		case 'pmfImageDelete':
			$file=pmImportVarsList('file', false);
			if (isset($file) && $file!='')
            	pmf_image_delete($file);
			break;

	}
	mysql_close($link);
?>