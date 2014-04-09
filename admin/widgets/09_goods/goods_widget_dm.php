<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;

	require_once 'goods_widget_proc.php';
	require_once "$path/_config.php";
	require_once "$_admin_common_proc_path/variables.php";
	require_once "$_admin_common_proc_path/cms.php";
	require_once "$_admin_common_proc_path/logs.php";
	if (file_exists("$_admin_common_proc_path/user.php")) require_once "$_admin_common_proc_path/user.php";
	if (file_exists("$_admin_common_proc_path/shop.php")) require_once "$_admin_common_proc_path/shop.php";
	require_once "$_admin_common_proc_path/main.php";
	require_once "$_admin_pmEngine_path/pmMain.php";
	require_once "$_admin_pmEngine_path/pmAPI.php";

	importVars('section', false);
	if(!isset($section) || $section=='') exit;

	header("Content-Type: text/html; charset={$html_charset}");

	require_once "$_admin_common_proc_path/db.php";
	require_once "$_admin_proc_path/main.php";
	require_once "$_admin_proc_path/common_design.php";
	require_once "$_admin_widgets_path/common_widget_proc.php";

	if (!isset($section) || $section=='') exit;
	$link=connect_db();
//******************************************************************************
//
// Блок процедур для работы с товарами
//
//******************************************************************************
	switch ($section)
	{
		// Создание дерева категорий для выбора рабочей
		case 'goodsGetCategoriesMenuList':
			importVars('id|func', true);
			if (isset($id) && $id!='')
			echo "<p><b>Выбрать можно только ту категорию которая не имеет подкатегорий.</b></p>";
			$list=goods_get_category_list_html($id, $func, 0);
			echo $list;
			break;
		// Генерация фрейма со списком товаров в категории
		case 'goodsGetGoodsList':
			importVars('menu|category|page|filter|obj_type', true);
			$filter=iconv ('utf-8', $html_charset, $filter);
			if (isset($menu) && $menu!='' && isset($category) && $category!='' && isset($page) && $page!='')
			{
				$type_selector=goods_good_type_selector_html($category, $obj_type);
				$filter_html=goods_get_filter_html($type_selector['type']);
//				$goods_list_html=goods_get_list_frame_html($menu, $category, $page, $filter, $type_selector['type']);
//				echo serialize_data('type|filter|list', $type_selector['html'], $filter_html, $goods_list_html);
				$goods_list=goods_get_list_frame_html($menu, $category, $page, $filter, $type_selector['type']);
				echo serialize_data('type|filter|list|sql', $type_selector['html'], $filter_html, $goods_list['html'], $goods_list['sql']);
			}
			break;
		// Генерация HTML кода для добавления товара
		case 'goodsGetGoodAddHTML':
			importVars('obj_type', true);
			if (!isset($obj_type) || $obj_type=='') return '';
            echo goods_get_good_add_html($obj_type);
			break;
		// Генерация артикула товара
		case 'goodsCreateGoodCode':
			importVars('name', false);
			if (isset($name) && $name!='')
			{
				$name=str2url(iconv ('utf-8', $html_charset, $name));
				$max=get_data('max(id)', $_cms_tree_node_table)+1;
				echo metaphone($name, 5).$max;
			}
			break;
		// Проверка возможности добавления товара
		case 'goodsGoodCanBeAdd':
			importVars('name|code', true);
			if (isset($name) && $name!='' && isset($code) && $code!='')
			{
				$name=iconv ('utf-8', $html_charset, $name);
				$code=iconv ('utf-8', $html_charset, $code);
				$res=query("select id from $_cms_tree_node_table where code='$code'");
				if (mysql_num_rows($res)) echo 'no';
				else echo 'yes';
				mysql_free_result($res);
			}
			else
				echo 'no';
			break;
		// Добавление товара
		case 'goodsGoodAdd':
			importVars('name|menu|category|code|type', true);
			if (isset($name) && $name!='' && isset($menu) && $menu!='' && isset($category) && $category!='' && isset($code) && $code!='' && isset($type) && $type!='')
			{
				$name=iconv ('utf-8', $html_charset, $name);
				$code=iconv ('utf-8', $html_charset, $code);
				query("insert into $_cms_tree_node_table (type, name, note, menu, parent, code, date, visible) values ('$type', '$name', '', '$menu', '$category', '$code', CURDATE(), 0)");
			}
			break;
		// Удаление товара
		case 'goodsGoodDelete':
			importVars('id', true);
			if (isset($id) && $id!='') cms_shop_delete_good($id);
			break;
		// Переключение отображения товара в каталоге на сайте
		case 'goodsSetGoodVisible':
			importVars('id|visible', true);
			if (isset($id) && $id!='' && isset($visible) && $visible!='')
				query("update $_cms_tree_node_table set visible='$visible' where id='$id'");
			break;
		// Выбор данных для построения HTML блока редактирования товара
		// Удалить
/*
		case 'goodsGetGoodData':
			importVars('id', true);
			if (isset($id) && $id!='')
			{
                $r=get_data_array('*', $_cms_tree_node_table, "id='$id'");
				if($r===false) { echo ''; exit(); }
				$detailsBlock=goods_get_good_details_block_html($r['id'], $r['type']);
				$r['note']=str_replace("\n", '{@n@}', $r['note']);
				echo serialize_data('id|name|code|parent|note|detailsBlock', $r['id'], $r['name'], $r['code'], $r['parent'], $r['note'], $detailsBlock);
			}
			break;
*/
		// Генерация HTML блока для редактирования товара
		case 'goodsGetGoodEditHtml':
			importVars('id|mh', true);
			if (isset($id) && $id!='')
				echo goods_get_good_edit_html($id, $mh);
			break;
		// Получение название товара по его ID
		case 'goodsGetGoodName':
			importVars('id', true);
			if (isset($id) && $id!='') echo get_data('name', $_cms_tree_node_table, "id='$id'");
			break;
		case 'goodsGetGoodDataBlock':
			importVars('id', true);
			if (isset($id) && $id!='') echo goods_good_data_block_indiv(get_data_array('*', $_cms_tree_node_table, "id='$id'"));
			break;
		case 'goodsGoodEditCanBeSave':
			importVars('id|name|code', true);
			$name=iconv ('utf-8', $html_charset, $name);
			$code=iconv ('utf-8', $html_charset, $code);
			if (isset($id) && $id!='' && isset($name) && $name!='' && isset($code) && $code!='')
			{
				$res=query("select id from $_cms_tree_node_table where code='$code' and id<>'$id'");
				if (mysql_num_rows($res))
					echo 'no';
				else
					echo '';
				mysql_free_result($res);
            }
			else
				echo 'no';
			break;
		case 'goodsSaveGoodData':
			importVars('id|name|code|note', true);
			$name=iconv ('utf-8', $html_charset, $name);
			$note=iconv ('utf-8', $html_charset, $note);
			$code=iconv ('utf-8', $html_charset, $code);
			importVars('props', false);
			if (isset($id) && $id!='')
			{
                if ($name!='') query("update $_cms_tree_node_table set name='$name' where id='$id'");
                $gt=get_data('type', $_cms_tree_node_table, "id='$id'");
				$gt=goods_get_good_description($gt);
                query("update $_cms_tree_node_table set note='$note', code='$code' where id='$id'");
				query("delete from $_cms_tree_node_details where node='$id' and type<>'i'");
            	parse_str($props, $details);
				foreach($details as $k=>$v)
				{
					$v=iconv ('utf-8', $html_charset, $v);
					$type=goods_get_detail_type($gt['details'], $k);
					if ($type=='c' && $v=='')
						query("insert into $_cms_tree_node_details (node, typeId, type, value) values ('$id', '$k', '$type', '0')");
					if ($v!='' && $type!='')
					{
			            if ($type=='dm')
						{
				   			$ve=explode('|', $v);
							foreach($ve as $v)
								query("insert into $_cms_tree_node_details (node, typeId, type, value) values ('$id', '$k', '$type', '$v')");
						}
						else
							query("insert into $_cms_tree_node_details (node, typeId, type, value) values ('$id', '$k', '$type', '$v')");
					}
    			}
			}
			break;
		case 'goodsGetImagesBlock':
			importVars('id|typeId', true);
			if (isset($id) && $id!='' && isset($typeId) && $typeId!='')
			{
                $type=get_data('type', $_cms_tree_node_table, "id='$id'");
				$gt=goods_get_good_description($type);
				$goods_type=goods_get_detail_description($gt['details'], $typeId);
	            echo goods_images_block_html($id, $goods_type);
			}
			break;
		// Удаление изображения из БД и с диска
		case 'goodsDeleteImage':
			importVars('id|imageId|typeId', true);
			if (isset($id) && $id!='' && isset($imageId) && $imageId!='' && isset($typeId) && $typeId!='')
			{
				$file=get_data('value', $_cms_tree_node_details, "id='$imageId'");
				query("delete from $_cms_tree_node_details where id='$imageId'");
				$server_file="{$_SERVER['DOCUMENT_ROOT']}$_cms_goods_images_url/$file";
				$server_thumb_file="{$_SERVER['DOCUMENT_ROOT']}$_cms_goods_images_url/thumbs/$file";
				@unlink($server_file);
				@unlink($server_thumb_file);
                $type=get_data('type', $_cms_tree_node_table, "id='$id'");
				$gt=goods_get_good_description($type);
				$goods_type=goods_get_detail_description($gt['details'], $typeId);
	            echo goods_images_block_html($id, $goods_type);
			}
			break;
		case 'goodsUploadedImageProcess':
			importVars('id|file|typeId', true);
			if (!isset($file) || $file=='' || !isset($id) || $id=='' || !isset($typeId) || $typeId=='') exit;
			$dest=create_unique_file_name($_cms_goods_images_path, $file);
			@rename("$_admin_uploader_path/$file", $dest);
			$pp=pathinfo($dest);
            delete_temp_image("$_admin_uploader_path/$file");
			create_thumbnail($dest, "$_cms_goods_images_path/thumbs/{$pp['basename']}", $pp['extension'], $_cms_goods_images_size_x, $_cms_goods_images_size_y, $_cms_goods_images_thumbnail_size);
			query("insert into $_cms_tree_node_details (node, typeId, type, value) values ('$id', '$typeId', 'i', '{$pp['basename']}')");
			$type=get_data('type', $_cms_tree_node_table, "id='$id'");
			$gt=goods_get_good_description($type);
			$goods_type=goods_get_detail_description($gt['details'], $typeId);
            echo goods_images_block_html($id, $goods_type);
			break;
		// Генерация HTML кода для выбора множественных вхождений из справочника
		case 'goodsGetDirValuesHtml':
			importVars('type|id|vals|func', true);
			if (!isset($type) || $type=='' || !isset($id) || $id=='' || !isset($func) || $func=='') return;
			echo goods_get_dir_values_html($type, $id, $vals, $func);
			break;
		// Сохранение данных выбранных из справочника
		case 'goodsDirValuesSave':
			importVars('id|props', true);
			if (!isset($id) || $id=='') return;
			echo goods_dir_values_save($id, $props);
			break;
		// Генерация HTML кода для выбора характеристик товара
		case 'goodsGetFeaturesHtml':
			importVars('type|id|vals|func', true);
			if (!isset($type) || $type=='' || !isset($id) || $id=='' || !isset($func) || $func=='') return;
			echo goods_get_features_values_html($type, $id, $vals, $func);
			break;
		// Сохранение выбранных характеристик товара
		case 'goodsFeaturesValuesSave':
			importVars('id|props_c|props_v', true);
			if (!isset($id) || $id=='') return;
			echo goods_features_values_save($id, $props_c, $props_v);
			break;
		// генерация SQL условия для фильтра товаров
		case 'goodsGetFilterSql':
			importVars('signature|name|visible|props', true);
			if (!isset($signature)) $signature='';
			$signature=iconv ('utf-8', $html_charset, $signature);
			if (!isset($name)) $name='';
			$name=iconv ('utf-8', $html_charset, $name);
			if (!isset($visible)) $visible='';
			if (!isset($props)) $props='';
			if ($signature=='' && $name=='' && $visible=='' && $props=='') return;
			echo goods_get_filter_sql($signature, $name, $visible, $props);
			break;
		// перемещение товара в другой раздел
		case 'goodsGoodMove':
			importVars('id|menu_item', true);
			if (!isset($id) || $id=='' || !isset($menu_item) || $menu_item=='') return;
			$menu=get_data('menu', $_cms_menus_items_table, "id='$menu_item'");
			query("update $_cms_tree_node_table set menu='$menu', parent='$menu_item' where id='$id'");
			break;
	}
?>