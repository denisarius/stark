<?php
// -----------------------------------------------------------------------------
function pmf_get_tree_html($cur_dir)
{
	global $pmf_root_path, $pmf_self_url;

	if ($cur_dir=='') $cc=' pmf_folder_node_active';
	else $cc='';

	$html=<<<stop
<div id='pmf_folder_0' class='pmf_folder_node{$cc}' onClick='pmf_select_folder(0)' data='$pmf_root_path' data-status='/'>
<img src='$pmf_self_url/images/root_folder.png'>
</div>
stop;
	$cur_dir=substr($cur_dir, strlen($pmf_root_path)+1);
	$res=pmf_get_subtree('', 1, 1, $cur_dir);
	return $html.$res['html'];
}
// -----------------------------------------------------------------------------
function pmf_get_subtree($path, $level, $id, $cur_dir)
{
	global $pmf_root_path, $pmf_self_url;

	$html='';
	if ($path=='') $path='/';
	elseif ($path[0]!='/') $path="/$path";
	if ($path!='/') $path="$path/";
	$dir="{$pmf_root_path}{$path}";
	$d=opendir($dir);
	if ($d!==false)
	{
		while (false !== ($file = readdir($d)))
		{
			if ($file!='.' && $file!='..')
			{
				$fullname="{$dir}{$file}";
				if (is_dir($fullname))
				{
					$id++;
					$m=$level*20;
					if ("/$cur_dir"=="{$path}{$file}") $cc=' pmf_folder_node_active';
					else $cc='';
                    $virtual=get_data_array('virtual_name, virtual_path', '_vfs', "real_name='$file'");
	            	$html.=<<<stop
<div id='pmf_folder_$id' style='padding-left:{$m}px;' class='pmf_folder_node{$cc}' onClick='pmf_select_folder($id)' data='$fullname' data-status='{$virtual['virtual_path']}'>
	<img src='$pmf_self_url/images/folder.png'><span>{$virtual['virtual_name']}</span>
</div>
stop;
					$res=pmf_get_subtree("{$path}{$file}", $level+1, $id, $cur_dir);
					$html.=$res['html'];
					$id=$res['id'];
				}
			}
	    }
	}
	closedir($d);
	return array('html'=>$html, 'id'=>$id);
}
// -----------------------------------------------------------------------------
function pmf_get_image_html($id, $file, $fullname, $url, $sz, $ix, $iy)
{
	$virtual_name=get_data('virtual_name', '_vfs', "real_name='$file'");
	$fn=$virtual_name;
	if (strlen($fn)>26)
	{
		$pp=pathinfo($fn);
		$fn=substr($fn, 0, 12).' ... '.substr($pp['basename'], strlen($pp['basename'])-16);
	}
	$virtual_name=htmlspecialchars($virtual_name);
	$fn=htmlspecialchars($fn);
	return <<<stop
<div id='pmf_folder_item_$id' class='pmf_folder_item' onDblClick='pmf_select_item($id)' data='$fullname'
style='width: {$ix}px; height: {$iy}px;'>
	<div class="pmf_tools_layer">
		<div style="background-image: url('images/tools_icon_delete.png')" onClick="pmf_image_delete($id)"></div>
	</div>
	<img id='pmf_image_$id' src='$url' style="width: {$sz['x']}px; heght: {$sz['y']}px; margin-top: {$sz['margin_top']}px;" title="$virtual_name" />
<div class='pmf_image_name' id='pmf_file_name_{$id}' style='width: {$ix}px;'>$fn</div>
</div>
stop;
}
// -----------------------------------------------------------------------------
function pmf_get_files_list_html($path)
{
	global $pmf_root_path, $pmf_root_url, $pmf_allowed_extension, $pmf_thumb_size;

	$allowed_ext=explode('|', strtolower($pmf_allowed_extension));
	$html='';
	if ($path=='') $dir=$pmf_root_path;
	else $dir=$path;
	$d=opendir($dir);
	$id=1;
	$ix=$pmf_thumb_size+2;
	$iy=$pmf_thumb_size+20;
	$dir_content=array();
	if ($d!==false)
	{
		while (false !== ($file = readdir($d)))
		{
			$fullname="{$dir}/{$file}";
			if (is_file($fullname))
			{
               	$virtual_name=get_data('virtual_name', '_vfs', "real_name='$file'");
//				array_push($dir_content, $virtual_name=>$file);
				$dir_content[$file]=strtolower($virtual_name);
			}
	    }
        asort($dir_content);
		foreach($dir_content as $file=>$virtual_name)
		{
			$fullname="{$dir}/{$file}";
			$pp=pathinfo($fullname);
			if (in_array(strtolower($pp['extension']), $allowed_ext))
			{
				$url=substr($fullname, strlen($_SERVER['DOCUMENT_ROOT']));
				$sz=pmGetBoundedImageSize($fullname, $pmf_thumb_size, $pmf_thumb_size);
				$sz['margin_top']=$pmf_thumb_size-$sz['y']-1;
				$html.=pmf_get_image_html($id, $file, $fullname, $url, $sz, $ix, $iy);
				$id++;
			}
		}
	}
	closedir($d);
	return $html;
}
// -----------------------------------------------------------------------------
function pmf_image_process($file, $path)
{
	global $html_charset, $_admin_uploader_path;

	$fn="$_admin_uploader_path/$file";
	$file=iconv ('utf-8', $html_charset, $file);
	$pp=pathinfo($file);
	$file_uid=pmf_get_unique_file_node_id("$path", $pp['extension']);
	$dest="$path/$file_uid.{$pp['extension']}";
	@rename($fn, $dest);
    $parent_vp=get_data('virtual_path', '_vfs', "real_path='$path'");
	query("insert into _vfs (real_name, real_path, virtual_name, virtual_path) values ('$file_uid.{$pp['extension']}', '$dest', '$file', '$parent_vp/$file')");
	return pmf_get_files_list_html($path);
}
//------------------------------------------------------------------------------
// Возвращает уникальный ID ( => _vfs.real_name ) для файла или папки
// $path		- путь к фалу для которого создается ID
// $extension	- расширение имени файла
function pmf_get_unique_file_node_id($path, $extension)
{
	do {
        $unique=true;
		$uid=substr(md5(rand().$path), 5, 16);
		if (file_exists("$path/$uid.$extension")) $unique=false;
		if ($unique)
		{
			$i=get_data('id', '_vfs', "real_name='$uid.$extension'");
			if ($i!==false) $unique=false;
		}
	} while(!$unique);
	return $uid;
}
//------------------------------------------------------------------------------
// Создание папки
// $path - путь где создается папка
// $name - имя папки
function pmf_folder_create($path, $name)
{
	$dir_uid=pmf_get_unique_file_node_id($path, '');
	$original_path=$path;
	$path="$path/";
	$dir_path="{$path}{$dir_uid}";
	if (is_dir($dir_path))
	{
		$real_name=get_data('real_name', '_vfs', "real_path='$dir_path'");
		return array('err'=>'', 'real_name'=>$real_name, 'real_path'=>$dir_path);
	}
    $parent_vp=get_data('virtual_path', '_vfs', "real_path='$original_path'");
	if (!@mkdir($dir_path, 0777, true)) return array('err'=>"На удалось создать папку:<br>$name", 'real_name'=>$real_name);
	query("insert into _vfs (real_name, real_path, virtual_name, virtual_path) values ('$dir_uid', '$dir_path', '$name', '$parent_vp/$name')");
	return array('err'=>'', 'real_name'=>$dir_uid, 'real_path'=>$dir_path);
}
// -----------------------------------------------------------------------------
function pmf_get_files_count($path)
{
	$cnt=0;
	$d=opendir($path);
	if ($d!==false)
	{
		while (false !== ($file = readdir($d)))
			if ($file!='.' && $file!='..') $cnt++;
	}
	closedir($d);
	return $cnt;
}
// -----------------------------------------------------------------------------
function pmf_folder_delete_is_possible($path)
{
	$files_count=pmf_get_files_count($path);
	if ($files_count) return 0;
	return 1;
}
// -----------------------------------------------------------------------------
// Удаление папки
// $path - путь к папке которая удаляется
function pmf_folder_delete($path)
{
	if (!pmf_folder_delete_is_possible($path)) return 'Нельзя удалять непустые папки';
	if (!@rmdir($path)) return 'Не удалось удалить папку';
	query("delete from _vfs where real_path='$path'");
	return '';
}
// -----------------------------------------------------------------------------
function pmf_image_delete($file)
{
	if (!@unlink($file)) return;
	query("delete from _vfs where real_path='$file'");
}
// -----------------------------------------------------------------------------
?>