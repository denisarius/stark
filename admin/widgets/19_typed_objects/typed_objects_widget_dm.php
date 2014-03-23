<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once 'typed_objects_widget_proc.php';
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
// Блок процедур для работы с объектами
//
//******************************************************************************
	switch ($section)
	{
		// Генерация списка объектов
		case 'typed_objectsGetObjectsList':
			importVars('menu_item|obj_type|page', true);
			if (!isset($menu_item) || $menu_item=='') return;
			if (!isset($obj_type)) $obj_type=='';
			if (!isset($page) || $page=='') $page=0;
			$list=typed_objects_get_objects_list($menu_item, $obj_type, $page);
			$list['html']=str_replace("\r\n", '',  $list['html']);
			$list['html']=str_replace("\n", '',  $list['html']);
			echo serialize_data('page|html', $list['page'], $list['html']);
			break;
		// Генерация блока HTML кода для редактирования объекта
		case 'typed_objectsGetObjectEditHtml':
			importVars('id|type', true);
			if (!isset($id) || $id=='') $id=-1;
			if (!isset($type) || $type=='') return;
			echo typed_objects_get_edit_object_html($id, $type);
			break;
		// Проверка возможности записи данных объекта
		case 'typed_objectsEditCanBeSave':
			importVars('id|name|menu_item', true);
			$name=iconv ('utf-8', $html_charset, $name);
			$i=get_data('id', $_cms_objects_table, "id!='$id' and menu_item='$menu_item' and name='$name'");
			if ($i!==false) echo "Объект с таким название уже существует.";
			break;
		// Запись данных объекта
		case 'typed_objectsSaveObjectData':
			importVars('menu_item|id|type|name|note|img|gallery', true);
			importVars('props', false);
			$name=iconv ('utf-8', $html_charset, $name);
			$note=iconv ('utf-8', $html_charset, $note);
			$props=iconv ('utf-8', $html_charset, $props);
			if (isset($id) && $id!='' && isset($menu_item) && $menu_item!='')
				typed_objects_save_object_data($id, $type, $menu_item, $name, $note, $img, $gallery, $props);
			break;
		// Удаление объекта
		case 'typed_objectsDeleteObject':
			importVars('id', true);
			if (isset($id) && $id!='') typed_objects_object_delete($id);
			break;
		// Переключение флажка отображения объекта на сайте
		case 'typed_objectSetObjectVisible':
			importVars('id|visible', true);
			if (!isset($id) || $id=='' || !isset($visible) || $visible=='') return;
			query("update $_cms_objects_table set visible='$visible' where id='$id'");
			$vis=get_data('visible', $_cms_objects_table, "id='$id'");
            if ($vis!==false) echo $vis;
			break;
		// Генерация HTML кода для выбора множественных вхождений из справочника
		case 'typed_objectsGetDirValuesHtml':
			importVars('id|type|vals', true);
			if (!isset($type) || $type=='' || !isset($id) || $id=='') return;
			echo typed_objects_get_dir_values_html($type, $id, $vals);
			break;
		// Сохранение данных выбранных из справочника
		case 'typed_objectsDirValuesSave':
			importVars('id|props', true);
			if (!isset($id) || $id=='') return;
			echo typed_objects_dir_values_save($id, $props);
			break;
		// Генерация HTML кода для сортировки объектов
		case 'typed_objectsGetSortHtml':
			importVars('menu_item', true);
			if (!isset($menu_item) || $menu_item=='') return;
			echo typed_objects_get_sort_html($menu_item);
			break;
		// Сохранение порядка сортировки объектов
		case 'typed_objectsSortSave':
			importVars('menu_item|sort', true);
			if (!isset($menu_item) || $menu_item=='' || !isset($sort) || $sort=='') return;
			$sort=substr($sort, 0, -1);
			$sort=explode('|', $sort);
			$s=0;
			foreach($sort as $id)
			{
				query("update $_cms_objects_table set sort='$s' where id='$id'");
				$s++;
			}
			$list=typed_objects_get_objects_list($menu_item, 0);
			echo serialize_data('page|html', $list['page'], $list['html']);
			break;
		// перемещение объекта в другой раздел
  		case 'typed_objectsMoveObject':
			importVars('id|menu|copy', true);
			if (!isset($menu) || $menu=='' || !isset($id) || $id=='') return;
			typed_objects_object_move($id, $menu, $copy);
			break;
		case 'typed_objectsStructuredTextEditGetHTML':
			importVars('id|prop_type|obj_type', true);
			importVars('prop_value', false);
			$prop_value=iconv ('utf-8', $html_charset, $prop_value);
			echo typed_objects_structured_text_edit_get_html($id, $prop_type, $prop_value, $obj_type);
			break;
		case 'typed_objectsStructuredTextEditFragmentGetHTML':
			importVars('id', true);
			echo typed_objects_structured_text_edit_get_fragment_html($id, '', '', '', '');
			break;
		case 'typed_objectsTableEditGetHTML':
			importVars('id|prop_type|obj_type', true);
			importVars('prop_value', false);
			$prop_value=iconv ('utf-8', $html_charset, $prop_value);
			echo typed_objects_table_edit_get_html($id, $prop_type, $prop_value, $obj_type);
			break;
		case 'typed_objectsTableEditGetEmptyRowHTML':
			importVars('row_id|prop_type|obj_type', true);
//			$object_description=typed_objects_get_object_description($obj_type);
//			$prop=typed_objects_get_object_detail($obj_type, $prop_type);
			$desc=typed_objects_get_table_description($obj_type, $prop_type);
			echo typed_objects_table_edit_get_row_html($row_id, array_fill(0, $desc['columns_count'], ''), 0, $desc, $desc['cellwidth'], false);
			break;
	}
?>