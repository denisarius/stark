<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once 'menus_widget_proc.php';
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

	if (!isset($section) || $section=='') exit;
	$link=connect_db();

//******************************************************************************
//
// Блок процедур для работы с меню
//
//******************************************************************************
	switch ($section)
	{
		// Генерация фрейма со списком меню
		case 'menusGetMenusList':
			importVars('func', true);
			$list=menus_get_menus_list($func);
			echo $list;
			break;
		// Генерация фрейма со списком пунктов меню
		case 'menusGetMenuItemsList':
			importVars('func|id', true);
			$list=menus_get_menu_items_list($id, $func);
			echo $list;
			break;
		// Добавление меню
		case 'menusAddMenu':
			importVars('name', true);
			$name=iconv ('utf-8', $html_charset, $name);
			$res=query("select id from $_cms_menus_table where name='$name'");
			if (mysql_num_rows($res))
				echo serialize_data('status|menu_id|menu_name', 'no', '', '');
			else
			{
				query("insert into $_cms_menus_table (name) values ('$name')");
				$id=mysql_insert_id();
				echo serialize_data('status|menu_id|menu_name', 'ok', $id, $name);
			}
			mysql_free_result($res);
			break;
		// Удаление меню
		case 'menusDeleteMenu':
			importVars('id', true);
			if (!isset($id) || $id=='')
				echo 'no';
			else
			{
				$res=query("select id from $_cms_texts_table where menu_item in (select id from $_cms_menus_items_table where menu='$id')");
				if (mysql_num_rows($res))
					echo 'texts';
				else
				{
					query("delete from $_cms_menus_items_table where menu='$id'");
					query("delete from $_cms_menus_table where id='$id'");
					echo 'ok';
				}
				mysql_free_result($res);
			}
			break;
		// Добавление пункта меню
		case 'menusAddMenuItem':
			importVars('menu|current|parent|name', true);
			if (!isset($name) || $name=='' || !isset($menu) || $menu=='')
				{ $status='error'; $list_html=''; $current_id=$current;}
			else
			{
				$name=iconv ('utf-8', $html_charset, $name);
				$max=get_data('max(sort)', $_cms_menus_items_table, "menu='$menu' and parent='$parent'")+1;
				query("insert into $_cms_menus_items_table (name, url, parent, menu, sort, visible) values ('$name', '', '$parent', '$menu', '$max', 1)");
				$status='ok';
				$current_id=mysql_insert_id();
				$list_html=str_replace("\r\n", ' ', menus_get_menus_items_group($menu, 0, $current, ''));
			}
			echo serialize_data('status|current_id|list_html', $status, $current_id, $list_html);
			break;
		// Генерация HTML кода фрейма с деревом пунктов меню
		case 'menusGetMenuItemList':
			importVars('id', true);
			if (isset($id) && $id!='')
				echo str_replace("\r\n", '', menus_get_menus_items_group($id, 0, -1, ''));
			break;
		// Возвращает данные для пункта меню по id
		case 'menusMenuItemGetData':
			importVars('id', true);
			if (isset($id) && $id!='')
			{
				$r=get_data_array('*', $_cms_menus_items_table, "id='$id'");
				if($r===false) { echo ''; exit(); }
				$subtree=menus_get_menu_subtree($id);
				$buttons=menus_get_menu_item_buttons($id);
				echo serialize_data('id|name|parent|subtree|url|tag|visible|buttons', $r['id'], $r['name'], $r['parent'], $subtree, $r['url'], $r['tag'], $r['visible'], $buttons);
			}
			break;
        // Сохранение параметров пункта меню по id
		case 'menusMenuItemSaveData':
			importVars('id|name|url|tag|visible', true);
			$name=iconv ('utf-8', $html_charset, $name);
			$tag=iconv ('utf-8', $html_charset, $tag);
			if (!isset($visible) || $visible=='') $visible=0;
			if (isset($id) && $id!='' && isset($name) && name!='')
				query("update $_cms_menus_items_table set name='$name', url='$url', tag='$tag', visible='$visible' where id='$id'");
			echo get_data('name', $_cms_menus_items_table, "id='$id'");
			break;
		// Определение возможности удаления пункта меню.
		// Yes - возвращается только если в этом пункте нет подменю и связанных текстов
		case 'menusMenuItemCanDelete':
			importVars('id', true);
			if (isset($id) && $id!='')
			{
				$res=query("select id from $_cms_menus_items_table where parent='$id'");
				if(mysql_num_rows($res))
					echo 'no';
				else
				{
					mysql_free_result($res);
					$res=query("select id from $_cms_texts_table where menu_item='$id'");
					$total=mysql_num_rows($res);
					mysql_free_result($res);
					if ($total) echo 'no';
					else echo 'yes';
				}
			}
			else
				echo 'no';
			break;
		// Удаление пункта меню (на всякий случай вместе со связанными текстами)
		case 'menusMenuItemDelete':
			importVars('id|menu', true);
			if (isset($id) && $id!='' && isset($menu) && $menu!='')
			{
				$status='ok';
				query("delete from $_cms_texts_table where menu_item='$id'");
				query("delete from $_cms_menus_items_table where id='$id'");
				$current_id=get_data('id', $_cms_menus_items_table, 'true limit 1');
				$list_html=str_replace("\r\n", ' ', menus_get_menus_items_group($menu, 0, $current_id, ''));
			}
			else
				$status='error';
			echo serialize_data('status|current_id|list_html', $status, $current_id, $list_html);
			break;
		// Изменение сортировки пунктов меню
		case 'menusMenuItemSotrChange':
			importVars('menu|sort|current', true);
			if (!isset($current) || $current=='') $current=-1;
			if (isset($menu) && $menu!='' && isset($sort) && $sort!='')
			{
				menus_change_menu_items_sort($sort, $menu);
				echo str_replace("\r\n", '', menus_get_menus_items_group($menu, 0, $current, ''));
			}
			break;
		// Генерация HTML блока для редактирование названия меню
		case 'menusMenuNameCahangeGetHTML':
			importVars('id', true);
			if (isset($id) && $id!='')
				echo menus_get_menu_edit_name_html($id);
			break;
		// Запись названия меню
		case 'menusMenuNameChangeSave':
			importVars('id|name', true);
			$name=iconv ('utf-8', $html_charset, $name);
			if (isset($id) && $id!='' && isset($name) && $name!='')
				query("update $_cms_menus_table set name='$name' where id='$id'");
			break;
	}
//------------------------------------------------------------------------------
?>
