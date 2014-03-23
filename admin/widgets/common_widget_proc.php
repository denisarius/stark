<?php
//------------------------------------------------------------------------------
function common_get_menus_list($func)
{
	global $_cms_menus_table;
	$res=query("select * from $_cms_menus_table order by name");
	if (!mysql_num_rows($res))
		$html='n/a';
	else
	{
		while ($r=mysql_fetch_assoc($res))
		{
			$name=strtr($r['name'], array("\""=>"&quot;", "\\"=>"\\\\", "'" => "\\'"));
			$html.=<<<stop
<span class="cms_common_menu_selector_item" onClick="$func({$r['id']}, '$name')">{$r['name']}</span><br>
stop;
		}
	}
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
function common_get_menu_items_list($menu, $func)
{
	$html=common_get_menu_items_list_one_level($menu, $func, 0, '');
	return $html;
}
//------------------------------------------------------------------------------
function common_get_menu_items_list_one_level($menu, $func, $parent, $prefix)
{
	global $_cms_menus_items_table;
	$html='<ul class="cms_menu_items_select_list">';
	$res=query("select * from $_cms_menus_items_table where menu='$menu' and parent=$parent order by sort, id");
	while($r=mysql_fetch_assoc($res))
	{
		$name=str_replace('"', '\"', $r['name']);
   		$html.= <<<stop
<li><span onClick='$func({$r['id']}, "$name");'>{$r['name']}</span></li>
stop;
    	$html.=common_get_menu_items_list_one_level($menu, $func, $r['id'], "$prefix&nbsp;nbsp;");
	}
	mysql_free_result($res);
	$html.='</ul>';
	if ($html=='<ul></ul>') $html='';
    return $html;
}
/*------------------------------------------------------------------------------
// генерация SELECT для выбора основного раздела
// menu_id		- ID выбранного корневого меню
// menu_items	- список ID разрешенных разделов
// func			- функция вызываемая при выборе раздела
//----------------------------------------------------------------------------*/
function common_menu_item_selector_get_menus($menu_id, $menu_items, $func)
{
	global $_cms_menus_table;
	$html=<<<stop
<select onChange="common_menu_item_select_menu_changed('$func')" id="common_menu_item_selector_menu"><option value="-1"></option>
stop;
	$res=query("select * from $_cms_menus_table order by name");
	while ($r=mysql_fetch_assoc($res))
	{
		if ($r['id']==$menu_id) $sl='selected="selected"';
		else $sl='';
		$html.="<option value='{$r['id']}' $sl>{$r['name']}</option>";
	}
	mysql_free_result($res);
	$html.='</select>';
	return $html;
}
/*------------------------------------------------------------------------------
// генерация HTML подразделов для заданного раздела
// menu_id		- id корневог меню по которому формируется список подразделов
// menu_items	- список ID разрешенных разделов
// func			- функция вызываемая при выборе раздела
//----------------------------------------------------------------------------*/
function common_get_menu_item_selector_items_html($menu_id, $menu_items, $func)
{
	global $_cms_menus_table;

	if ($menu_id==-1)
		$html='<h2>Выберите основной раздел</h2>';
	else
	{
		$menu_id=get_data_array('id, name', $_cms_menus_table, "id='$menu_id'");
		if ($menu_id===false) return '';
		$menu_name=strtr($menu_id['name'], array("\""=>"&quot;", "\\"=>"\\\\", "'" => "\\'"));
		$html='<div class="common_menu_item_select_items_container">';
        $html.=common_get_menu_item_selector_items_html_level($menu_id, $menu_name, 0, $func, 0);
		$html.='</div>';
	}
	return $html;
}
/*------------------------------------------------------------------------------
// генерация HTML для одного уровня меню
// menu		- id корневого меню
// parent	- id родительского элемента
// func		- функция вызываемая при выборе раздела
// margin	- отступ слева для текущего уровня меню
//----------------------------------------------------------------------------*/
function common_get_menu_item_selector_items_html_level($menu, $menu_name, $parent, $func, $margin)
{
	global $_cms_menus_items_table;

	$res=query("select id, name from $_cms_menus_items_table where menu='{$menu['id']}' and parent='$parent' order by sort, id");
	while ($r=mysql_fetch_assoc($res))
	{
		$name=strtr($r['name'], array("\""=>"&quot;", "\\"=>"\\\\", "'" => "\\'"));
		$html.=<<<stop
<div onClick="$func({$menu['id']}, {$r['id']}, '$menu_name', '$name')" style="margin-left:{$margin}px">{$r['name']}</div>
stop;
		$html.=common_get_menu_item_selector_items_html_level($menu, $menu_name, $r['id'], $func, $margin+20);
	}
	mysql_free_result($res);
	return $html;
}
/*------------------------------------------------------------------------------
// включение в список пунктов меню ID всех родительских пунктов меню
// $menu_items	- массив id пунктов меню
//----------------------------------------------------------------------------*/
function get_expand_menu_items_list($menu_items)
{
	global $_cms_menus_items_table;

	$cnt=count($menu_items);
	$mi=implode(',', $menu_items);
	$res=query("select distinct parent from $_cms_menus_items_table where id in ($mi) and visible=1");
	while($r=mysql_fetch_assoc($res))
		if ($r['parent']) array_push($menu_items, $r['parent']);
	mysql_free_result($res);
    $menu_items=array_unique($menu_items);
	if (count($menu_items)>$cnt) $menu_items=get_expand_menu_items_list($menu_items);
	return $menu_items;
}
/*------------------------------------------------------------------------------
// генерация HTML для диалога выбора разделов
// menu_id	- id изначально выбранного раздела. Если -1 то раздел не выбирается
// func		- функция вызываемая при выборе раздела
//				func(menu_id, menu_item_id, menu_name, menu_item_name)
//	  				menu_id 		- id корневого раздела
//	   				menu_item_id	- id подраздела
//					menu_name		- название корневого раздела
//					menu_item_name	- название подраздела
// height	- максимальная высота блока подразделов
//----------------------------------------------------------------------------*/
function common_get_menu_item_selector_html($menu_id, $menu_items, $func, $height)
{
	global $_admin_menu_selector_tree;

	if ($_admin_menu_selector_tree)
	{
		global $_cms_menus_table;

		$menu_items_expand=get_expand_menu_items_list(explode(',', $menu_items));
		$menu_items_expand=implode(',', $menu_items_expand);
	    $html='';
		$res=query("select id, name from $_cms_menus_table order by id");
		while($r=mysql_fetch_assoc($res))
		{
			$m_html=common_get_menu_item_selector_items_level_html($r['id'], $r['name'], $menu_items_expand, $menu_items, $func, 0);
			if ($m_html!='') $html.= <<<stop
<h2>{$r['name']}</h2>
$m_html
stop;
		}
		mysql_free_result($res);
		if ($html=='') $html='Не созданы разделы или они не связанны с объектами.';
		$html=<<<stop
<div class="common_menu_item_select_tree_container" style="max-height:{$height}px;">
$html
</div>
stop;
	}
	else
	{
		$menu_html=common_menu_item_selector_get_menus($menu_id, $menu_items, $func);
		$items_html=common_get_menu_item_selector_items_html($menu_id, $menu_items, $func);
		$html=<<<stop
<div class="common_menu_item_select_container">
$menu_html
<div class="common_menu_item_selector_items_container" id="menu_item_selector_items_container" style="max-height:{$height}px;">
$items_html
</div>
</div>
stop;
	}
	return $html;
}
//------------------------------------------------------------------------------
function common_get_menu_item_selector_items_level_html($menu_id, $menu_name, $menu_items, $menu_items_src, $func, $parent)
{
	global $_cms_menus_items_table;

	$html='';
	if ($menu_items=='' || $menu_items=='-1')
    	$q="select * from $_cms_menus_items_table where menu='$menu_id' and visible=1 and parent='$parent' order by sort, id";
	else
	{
    	$q="select * from $_cms_menus_items_table where menu='$menu_id' and visible=1 and parent='$parent' and id in ($menu_items) order by sort, id";
		$menu_items_array=explode(',', $menu_items_src);
	}
	$res=query($q);
	while($r=mysql_fetch_assoc($res))
	{
		$m_name=str2js($menu_name);
		$mi_name=str2js($r['name']);
		if ($menu_items!='' && $menu_items!='-1' && !in_array($r['id'], $menu_items_array))
			$html.= <<<stop
<div style="cursor:default;">{$r['name']}
stop;
		else
			$html.= <<<stop
<div class="common_section_selector_item" onClick="$func($menu_id, {$r['id']}, '$m_name', '$mi_name')">{$r['name']}
stop;
		$html.=common_get_menu_item_selector_items_level_html($menu_id, $menu_name, $menu_items, $menu_items_src, $func, $r['id']);
		$html.='</div>';
	}
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
// Генерация HTML кода для селектора раздела в виджетах
//------------------------------------------------------------------------------
function common_get_menu_item_selector($menu_item_id, $title, $func)
{
	global $_cms_menus_table, $_cms_menus_items_table;

// Задан раздел к которому привязан виджет и раздел только один
	if ($menu_item_id!='-1' && $menu_item_id!='' && strpos($menu_item_id, ',')===false)
	{
		$menu_name=get_data('name', $_cms_menus_items_table, "id='$menu_item_id'");
		if ($menu_name!==false)
			// Раздел к которому привязаны объекты существует.
			// Запрещаем выбор раздела.
			return <<<stop
<div class="cms_menu_item_selector">$menu_name</div>
<input type="hidden" id="common_menu_item_id_init" value="$menu_item_id"/>
stop;
		else
			// Раздел отсутствует. Злобно ругаемся.
			return <<<stop
<div class="cms_menu_item_selector">Отсутствует раздел заданный в настройках виджета. Пожалуйста обратитесь к администратору сайта. (ID=$menu_item_id)</div>
stop;
	}

// Выбор раздела разрешен (пункт меню не задан или их несколько)

	return <<<stop
<div id="widget_menu_item_selector_container">
<div id="widget_menu_item_selector" onClick="common_menu_item_select('Выберите раздел', '$func', '', '$menu_item_id')" class="cms_menu_item_selector">Выберите раздел</div>
</div>
stop;

/*
	$cnt=get_data('count(*)', $_cms_menus_table);
	if ($cnt<2)
	{
		$cnt_i=get_data('count(*)', $_cms_menus_items_table);
		if (!$cnt_i)
		{
			echo <<<stop
<div class="cms_menu_item_selector">Основной раздел не содержит ни одного подраздела. Сначала нужно создать хотя бы один подраздел в основном <a href="menus.php">разделе</a></div>
stop;
			return;
		}
		$menu=get_data_array('id, name', $_cms_menus_table);
		$selector=<<<stop
<input type="hidden" value="{$menu['id']}" id="objects_menu_id">
<div id="objects_menu_selector" class="cms_menu_selector">{$menu['name']}</div>
stop;
		if (!$menu_id) $menu_name='Выберите подраздел';
		else $menu_name=get_data('name', $_cms_menus_items_table, "id='$menu_id'");
		$selector.=<<<stop
<div id="objects_menu_item_selector_container">
<br><div id="objects_menu_item_selector" onClick="common_menu_item_select('Выберите подраздел', 'typed_objects_menu_item_select_change', '{$menu['id']}')" class="cms_menu_item_selector">Выберите подраздел</div>
</div>
stop;
	}
	else
	{
		if ($menu_id) $menu_item=get_data_array('*', $_cms_menus_items_table, "id='$menu_id'");
		else $menu_item=false;
		if (!$menu_id || $menu_item===false)
			$selector=<<<stop
<input type="hidden" value="0" id="objects_menu_id">
<div id="objects_menu_selector" onClick="objects_menu_select()" class="cms_menu_selector">Выберите раздел</div>
<div id="objects_menu_item_selector_container"></div>
stop;
		else
		{
			$menu=get_data_array('*', $_cms_menus_table, "id='{$menu_item['menu']}'");
			$selector=<<<stop
<input type="hidden" value="{$menu['id']}" id="objects_menu_id">
<div id="objects_menu_selector" onClick="objects_menu_select()" class="cms_menu_selector">{$menu['name']}</div>
<div id="objects_menu_item_selector_container">
<br><div id="objects_menu_item_selector" onClick="objects_menu_item_select({$menu['id']})" class="cms_menu_item_selector">{$menu_item['name']}</div>
</div>
stop;
		}
	}
    return $selector;
*/
}
//------------------------------------------------------------------------------
function common_get_menus_map($func)
{
	global $_cms_menus_table, $_cms_menus_items_table;
	$html='';
	$res=query("select id, name from $_cms_menus_table order by id");
	while($r=mysql_fetch_assoc($res))
	{
		$html.=<<<stop
<p>{$r['name']}</p>
stop;
		$html.=common_get_submap($r['id'], 0, $func);
	}
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
function common_get_submap($menu, $parent, $func)
{
	global $_cms_menus_items_table;

	$html='<ul>';
	$res=query("select * from $_cms_menus_items_table where menu='$menu' and parent=$parent order by sort, id");
	while($r=mysql_fetch_assoc($res))
	{
   		$html.= <<<stop
<li><span style="cursor: pointer;" onClick="$func({$r['id']});">{$r['name']}</span></li>
stop;
    	$html.=common_get_submap($menu, $r['id'], $func);
		$i++;
	}
	mysql_free_result($res);
	$html.='</ul>';
	if ($html=='<ul></ul>') $html='';
    return $html;
}
//------------------------------------------------------------------------------
// Обрабатка загруженного файла с изображением
// Изображение уменьшается до даких размеров что бы оно вписалось в прямоугольник $sx:$sy
// Если $max=true то расчет ведется по наименьшей стороне. Так что бы изображение полностью накрывало
// заданный прямоугольник превышая его размер по наибольшей стороне
// Результирующее изображение помещяетс в $_admin_uploader_path/temp
// При настройках по умолчанию (/admin/uploader/uploads/temp)
//------------------------------------------------------------------------------
function common_temp_image_process($file, $sx, $sy, $max=0, $quality=0)
{
	global $_admin_uploader_path, $_admin_uploader_url;

	$pp=pathinfo($file);
	$ext=strtolower($pp['extension']);
	$fn="$_admin_uploader_path/$file";
	switch($ext)
	{
		case 'jpg':
		case 'jpeg':
			$im = imagecreatefromjpeg($fn);
			break;
		case 'png':
			$im = imagecreatefrompng($fn);
			break;
	}
	$dest=create_unique_file_name("$_admin_uploader_path/temp", $file);
    image_resize($im, $sx, $sy, $dest, $ext, $max, $quality);
    unlink($fn);
	delete_old_temp_files();
	$pp=pathinfo($dest);
	$dest=$pp['basename'];
	query("insert into _temp_files (file, created) values ('$dest', CURDATE())");
	return "$_admin_uploader_url/temp/$dest";
}
//------------------------------------------------------------------------------
function common_temp_file_process($file)
{
	global $_admin_uploader_path, $html_charset;

	$pp=pathinfo($file);
	$ext=strtolower($pp['extension']);
	$file8=str_replace('..', '', iconv ('utf-8', $html_charset,  $file));
//	$file8=$file;
	$fn="$_admin_uploader_path/$file";
	$dest="$_admin_uploader_path/temp/$file8";
	@unlink($dest);
	rename($fn, $dest);
	delete_old_temp_files();
    $file8=mysql_real_escape_string($file8);
	query("insert into _temp_files (file, created) values ('$file8', CURDATE())");
	return $file8;
}
//------------------------------------------------------------------------------
function common_temp_file_delete($file)
{
	global $_admin_uploader_path, $html_charset;
	$file8=iconv ('utf-8', $html_charset,  $file);
	$fn=str_replace('..', '', "$_admin_uploader_path/temp/$file8");
	@unlink($fn);
    $file8=mysql_real_escape_string($file8);
	query("delete from _temp_files where file='$file8'");
}
//------------------------------------------------------------------------------
function delete_old_temp_files()
{
	global $_admin_uploader_path;

	$days_to_store=2; // количество дней начиная с которого удаляются устаревшие изображения
	$res=query("select id, file from _temp_files where created<DATE_SUB(CURDATE(), INTERVAL $days_to_store DAY)");
	while($r=mysql_fetch_assoc($res))
		@unlink("$_admin_uploader_path/temp/{$r['file']}");
	mysql_free_result($res);
	query("delete from _temp_files where created<DATE_SUB(CURDATE(), INTERVAL $days_to_store DAY)");
}
//------------------------------------------------------------------------------
function common_get_link_text_list($func)
{
	global $_cms_texts_table, $_cms_menus_items_table;

	$html='';
	$res=query("select id, signature, title, menu_item from $_cms_texts_table order by menu_item, id");
	$menu_item=-1;
	while($r=mysql_fetch_assoc($res))
	{
		if($r['menu_item']!=$menu_item)
		{
			$menu_name=get_data('name', $_cms_menus_items_table, "id='{$r['menu_item']}'");
			$html.="<div class='common_link_text_list_menu_item'>$menu_name</div>";
			$menu_item=$r['menu_item'];
		}
		$f_title=strtr($r['title'], array("\""=>"&quot;", "\\"=>"\\\\", "'" => "\\'"));
		$html.=<<<stop
<div class="common_link_text_list_text" onClick="$func('{$r['signature']}', '$f_title', {$r['id']})">{$r['title']}</div>
stop;
	}
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
function common_get_link_menu_html($func)
{
	global $_cms_menus_table;

	$html='';
	$res=query("select * from $_cms_menus_table order by name");
	if (mysql_num_rows($res))
	{
		$html.="<select id='texts_link_menu_menu_list' class='common_selector_link_menu_menu_list' onChange='texts_link_menu_menu_selected(\"$func\")'>";
		$menu=-1;
		while($r=mysql_fetch_assoc($res))
		{
			if ($menu==-1)
			{
				$html.="<option value='{$r['id']}' selected>{$r['name']}</option>";
				$menu=$r['id'];
				$menu_name=$r['name'];
			}
			else
				$html.="<option value='{$r['id']}'>{$r['name']}</option>";
		}
		$html.='</select>';
		$html.='<div id="common_link_menu_menu_item_block">'.common_get_link_menu_items_list_html($menu, $menu_name, 0, '', $func).'</div>';
	}
	else
		$html="<h2>Не созданно ни одного раздела</h2>";
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
function common_get_link_menu_items_list_html($menu, $menu_name, $parent, $prefix, $func)
{
	global $_cms_menus_items_table;

	$html='';
	$res=query("select * from $_cms_menus_items_table where menu='$menu' and parent='$parent' order by sort, id");
	while ($r=mysql_fetch_assoc($res))
	{
		$name=strtr($r['name'], array("\""=>"&quot;", "\\"=>"\\\\", "'" => "\\'"));
		$m_name=strtr($menu_name, array("\""=>"&quot;", "\\"=>"\\\\", "'" => "\\'"));
		$html.=<<<stop
<div class="texts_selector_link_menu_list_text" onClick="$func('{$r['id']}', '$m_name', '$name')">$prefix{$r['name']}</div>
stop;
		$html.=common_get_link_menu_items_list_html($menu, $menu_name, $r['id'], $prefix.'&nbsp;&nbsp;', $func);
	}
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
function common_get_documents_list_html($func)
{
	global $_cms_documents_table;

	$res=query("select * from $_cms_documents_table order by name");
	$html='<div class="documents_select_container">';
	if (mysql_num_rows($res))
	{
		while($r=mysql_fetch_assoc($res))
		{
			$js_name=strtr($r['name'], array("\""=>"&quot;", "\\"=>"\\\\", "'" => "\\'"));
			$html.=<<<stop
<div onClick="$func({$r['id']}, '$js_name')">
<span>{$r['name']}</span>
<span>{$r['real_file']}</span>
</div>
stop;
		}
	}
	else
		$html="Не загружен ни один документ.";
	mysql_free_result($res);
	$html.='</div>';
	return $html;

}
//------------------------------------------------------------------------------
function common_get_constants_list_html($func)
{
	global $_cms_constants_table;

	$res=query("select * from $_cms_constants_table");
	$html='<div class="constants_select_container">';
	if (mysql_num_rows($res))
	{
		while($r=mysql_fetch_assoc($res))
		{
			$cn=addslashes($r['name']);
			$html.=<<<stop
<div onClick="$func('$cn')">
<span>{$r['name']}</span>
<span>{$r['value']}</span>
</div>
stop;
		}
	}
	else
		$html="Не определена ни одна константа.";
	mysql_free_result($res);
	$html.='</div>';
	return $html;

}
//------------------------------------------------------------------------------
function common_get_tag_edit_html($str, $pos)
{
	$tag=common_get_pseudotag_from_string($str, $pos);
	$t=pmExplodePseudoTag($tag['tag']);
	$html='';
	switch($t['tag'])
	{
		case 'link':
			$html=common_get_tag_edit_link_html($t, $tag['start'], $tag['end']);
			break;
		case 'const':
			$html=common_get_tag_edit_const_html($t, $tag['start'], $tag['end']);
			break;
	}
	if ($html=='') $html='<h2>Ошибка обработки данных тэга.</h2>';
	return $html;
}
//------------------------------------------------------------------------------
function common_get_tag_edit_link_html($tag, $start, $end)
{
	global $_cms_texts_table, $_cms_menus_table, $_cms_menus_items_table, $_cms_documents_table;

	$html=<<<stop
<script type="text/javascript">
texts_edit_tag_text_select($start, $end);
</script>
stop;
	switch (strtolower($tag['args'][0]))
	{
		case 'text':
			$signature=strtoupper($tag['args'][1]);
			if ($signature=='') return '';
			$signature=mysql_real_escape_string($signature);
			$text_name=get_data('title', $_cms_texts_table, "signature='$signature'");
			$html.=<<<stop
<input type="hidden" id="texts_tag_edit_text_signature" value="$signature">
<b><u>Тип тэга: Ссылка на текст</u></b><br><br>
<b>Текст</b><br>
<div id="texts_tag_edit_text_name">$text_name</div>
<input type='button' class='admin_tool_button' value='Изменить' onClick="texts_tag_edit_link_text_select_signature()"><br><br>
<b>Текст ссылки</b>
<input type='text' id='texts_tag_edit_link_text' value='{$tag['args'][2]}' style='width: 90%'><br><br>
<b>Заголовок ссылки (&lt;a title&gt;)</b>
<input type='text' id='texts_tag_edit_link_title' value='{$tag['args'][3]}' style='width: 90%'>
<hr noshade size="1"><br>
<input type='button' class='admin_tool_button admin_tool_ok_button' value='Записать' onClick="texts_tag_edit_link_text_save()">
<input type='button' class='admin_tool_button admin_tool_cancel_button' value='Отменить' onClick="admin_info_close()">
stop;
			break;
		case 'menu':
			$menu_item_id=$tag['args'][1];
			if ($menu_item_id=='') return '';
			$menu_item_id=mysql_real_escape_string($menu_item_id);
			$menu_id=get_data('menu', $_cms_menus_items_table, "id='$menu_item_id'");
			if ($menu_id===false) return '';
			$menu_name=get_data('name', $_cms_menus_table, "id='$menu_id'");
			if ($menu_name===false) return '';
			$menu_item_name=get_data('name', $_cms_menus_items_table, "id='$menu_item_id'");
			$html.=<<<stop
<input type="hidden" id="texts_tag_edit_menu_item_id" value="$menu_item_id">
<b><u>Тип тэга: Ссылка на подраздел</u></b><br><br>
<div><b>Раздел:</b> <span id="texts_tag_edit_menu_name">$menu_name</span></div>
<div><b>Подраздел:</b> <span id="texts_tag_edit_menu_item_name">$menu_item_name</span></div><br>
<input type='button' class='admin_tool_button' value='Изменить' onClick="texts_tag_edit_link_menu_item_select()"><br><br>
<b>Текст ссылки</b>
<input type='text' id='texts_tag_edit_link_text' value='{$tag['args'][2]}' style='width: 90%'><br><br>
<b>Заголовок ссылки (&lt;a title&gt;)</b>
<input type='text' id='texts_tag_edit_link_title' value='{$tag['args'][3]}' style='width: 90%'>
<hr noshade size="1"><br>
<input type='button' class='admin_tool_button admin_tool_ok_button' value='Записать' onClick="texts_tag_edit_link_menu_save()">
<input type='button' class='admin_tool_button admin_tool_cancel_button' value='Отменить' onClick="admin_info_close()">
stop;
			break;
		case 'document':
			$id=$tag['args'][1];
			if ($id=='') return '';
			$id=mysql_real_escape_string($id);
			$doc_name=get_data('name', $_cms_documents_table, "id='$id'");
			if ($doc_name===false) return '';
			$html.=<<<stop
<input type="hidden" id="texts_tag_edit_document_id" value="$id">
<b><u>Тип тэга: Ссылка на документ</u></b><br><br>
<b>Документ</b><br>
<div id="texts_tag_edit_document_name">$doc_name</div>
<input type='button' class='admin_tool_button' value='Изменить' onClick="texts_tag_edit_link_document_select()"><br><br>
<b>Текст ссылки</b>
<input type='text' id='texts_tag_edit_link_text' value='{$tag['args'][2]}' style='width: 90%'><br><br>
<b>Заголовок ссылки (&lt;a title&gt;)</b>
<input type='text' id='texts_tag_edit_link_title' value='{$tag['args'][3]}' style='width: 90%'>
<hr noshade size="1"><br>
<input type='button' class='admin_tool_button admin_tool_ok_button' value='Записать' onClick="texts_tag_edit_link_document_save()">
<input type='button' class='admin_tool_button admin_tool_cancel_button' value='Отменить' onClick="admin_info_close()">
stop;
			break;
	}
	return $html;
}
//------------------------------------------------------------------------------
function common_get_tag_edit_const_html($tag, $start, $end)
{
	global $_cms_constants_table;

	$html=<<<stop
<script type="text/javascript">
texts_edit_tag_text_select($start, $end);
</script>
stop;
	$name=$tag['args'][0];
	if ($name=='') return '';
			$html.=<<<stop
<b><u>Тип тэга: Константа</u></b><br><br>
<b>Имя константы</b><br>
<div id="texts_tag_edit_text_name">$name</div>
<input type='button' class='admin_tool_button' value='Изменить' onClick="texts_tag_edit_const_select_const()"><br><br>
<hr noshade size="1"><br>
<input type='button' class='admin_tool_button admin_tool_ok_button' value='Записать' onClick="texts_tag_edit_const_save()">
<input type='button' class='admin_tool_button admin_tool_cancel_button' value='Отменить' onClick="admin_info_close()">
stop;
	return $html;
}
//------------------------------------------------------------------------------
function common_get_pseudotag_from_string($str, $pos)
{
	if (strlen($str)<5) return '';
	if ($pos>strlen($str)-3) $pos=strlen($str)-3;
	while($pos>=0)
	{
        if($str[$pos]=='{' && $str[$pos+1]=='@' && $str[$pos+2]=='@') break;
		$pos--;
	}
    if($str[$pos]!='{' || $str[$pos+1]!='@' || $str[$pos+2]!='@') return '';
	$inQuotes=false;
	$i=$pos+1;
	while($i<strlen($str))
	{
		if (!$inQuotes)
		{
			if ($str[$i]=='"' || $str[$i]=='\'') {$inQuotes=true; $quoteChar=$str[$i]; }
			if ($str[$i]=='}') break;
		}
		else
			if ($str[$i]==$quoteChar) $inQuotes=false;
		$i++;
	}
	if ($str[$i]!='}') return '';
	$tag_text=trim(substr($str, $pos+3, $i-$pos-3));
	return array('tag'=>$tag_text, 'start'=>$pos, 'end'=>$i+1);
}
//------------------------------------------------------------------------------
function common_find_text_by_signature_from_string($str, $pos)
{
	$tag=common_get_pseudotag_from_string($str, $pos);
	$signature=common_get_signature_from_link_text($tag);
	return $signature;
}
//------------------------------------------------------------------------------
function common_get_signature_from_link_text($tag)
{
	global $_cms_texts_table;
	if ($pos=strpos($tag, '(')===false) return '';
	$t=pmExplodePseudoTag($tag);
	$signature=strtoupper($t['args'][1]);
	if ($signature=='') return '';
	$signature=mysql_real_escape_string($signature);
	return get_data('title', $_cms_texts_table, "signature='$signature'");
}
//------------------------------------------------------------------------------
// Генерация HTML кода для редактирования прицепленного документа
function common_get_attachment_edit_html($id, $func)
{
	global $html_charset, $_admin_uploader_path;

	$attachment=pmGetAttachment($id);
	if ($attachment===false) { $attachment['name']=''; $attachment['document']=''; }
	else
	{
		$file=iconv ($html_charset, 'utf-8', $attachment['document']);
    	copy($attachment['real_path'], "$_admin_uploader_path/$file");
	}
	$attachment['name']=pmAntiXSSVar($attachment['name'], $html_charset);
	$html=<<<stop
<div class="common_attachment_edit_container">
	<div class="hdr">Имя документа:</div>
	<div class="field">
		<input type="text" id="common_attachment_edit_name" value="{$attachment['name']}"/>
	</div>

	<div class="hdr">Имя файла:</div>
	<div class="field">
		<div id="common_attachment_edit_document">{$attachment['document']}</div>
	</div>

	<div id='common_attachment_button_holder'></div>
	<div id='common_attachment_progress_block'></div>
	<input type="hidden" id="common_attachment_edit_id" value="$id"/>
	<hr>
	<input type="button" value="Записать" onClick="common_attachment_save($func)"/>
	<input type="button" class="_right" value="Отмена" onClick="admin_info_close()"/>
</div>
stop;
	return $html;
}
//------------------------------------------------------------------------------
// Запись изменений / добавление прицепленного документа
// attachment_id	- ID старой записи в таблице прикрепленных документов или '' если записи не было
// attachment_file	- имя загруженного файла или '' если файла не было (в этом случае старый документ удаляется)
// attachment_name	- название прикрепляемого документа
function common_attachment_save($attachment_id, $attachment_file, $attachment_name)
{
	global $_base_site_attachments_path, $_admin_uploader_path, $html_charset;

	if ($attachment_file=='') return $attachment_id;
	if ($attachment_id=='')
	{
		// ничего не было приклеплено до этого момента
		$dest=create_unique_file_name($_base_site_attachments_path, $attachment_file);
		rename("$_admin_uploader_path/$attachment_file", $dest);
		$attachment_file=iconv ('utf-8', $html_charset, $attachment_file);
		query("insert into _attachments (name, document, real_path) values ('$attachment_name', '$attachment_file', '$dest')");
		$attachment_id=mysql_insert_id();
	}
	else
	{  	// файл уже был прикреплен
		$attachment=pmGetAttachment($attachment_id);
		@unlink("{$attachment['real_path']}");	// удаляем старый файл в любом случае
		$dest=create_unique_file_name($_base_site_attachments_path, $attachment_file);
		rename("$_admin_uploader_path/$attachment_file", $dest);
		$attachment_file=iconv ('utf-8', $html_charset, $attachment_file);
        query("update _attachments set name='$attachment_name', document='$attachment_file', real_path='$dest' where id='$attachment_id'");
	}
	return $attachment_id;
}
//------------------------------------------------------------------------------
function common_attachment_delete($attachment_id)
{
	$attachment=pmGetAttachment($attachment_id);
	print_r($attachment);
	if ($attachment===false) return;
	@unlink("{$attachment['real_path']}");	// удаляем старый файл в любом случае
	query("delete from _attachments where id='$attachment_id'");
}
//------------------------------------------------------------------------------
?>