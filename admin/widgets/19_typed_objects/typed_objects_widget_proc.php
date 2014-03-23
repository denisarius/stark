<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once "$path/_config.php";
	require_once "$_admin_widgets_path/common_widget_proc.php";

// -----------------------------------------------------------------------------
function typed_objects_get_detail_type($type_id, $obj_type)
{
	$types=typed_objects_get_object_description($obj_type);
	$types=$types['details'];
	foreach($types as $type)
		if ($type['id']==$type_id) return $type['type'];
	return '';
}
// -----------------------------------------------------------------------------
function typed_objects_get_object_detail($obj_type, $prop_id)
{
	$object_description=typed_objects_get_object_description($obj_type);
	if ($object_description===false) return false;
	$prop=false;
	foreach($object_description['details'] as $d)
		if ($d['id']==$prop_id) $prop=$d;
	return $prop;
}
//------------------------------------------------------------------------------
function typed_objects_get_object_html($object)
{
	global $_base_site_images_url, $_base_site_objects_images_url;

	$note=str_replace('</p>', '<br>', $object['note']);
	$note=substr(strip_tags($note, '<br>'), 0, 256);
	if ($object['visible']==1) $ch=' checked="checked"';
	else $ch='';
	if ($object['image']!='')
		$img="$_base_site_objects_images_url/{$object['image']}";
	else
		$img="$_base_site_images_url/no_object_image.png";
	$obj_name_js=str2js($object['name']);
	$html=<<<stop
<div class="typed_objects_object_node" id="typed_objects_list_node_{$object['id']}">
<div class="typed_objects_object_node_title" onClick="typed_objects_object_edit({$object['id']})">{$object['name']}</div>
<div class="typed_objects_object_node_image"><img src="$img" style="width:150px;"/></div>
<div class="typed_objects_object_node_note" >$note</div>
<br>
<input type="checkbox" id="typed_object_visible_{$object['id']}" onClick="typed_objects_toggle_visible({$object['id']})" $ch> Отображать на сайте
<hr>
<input type="button" class="admin_tool_button" value="Фотогалерея объекта" onClick="typed_objects_go_gallery({$object['id']})">
<input type="button" class="admin_tool_button" style="margin-left: 20px;" value="Переместить/скопировать объект" onClick="typed_objects_object_move_start({$object['id']})">
<input type="button" class="admin_tool_button" style="float:right" value="Удалить объект" onClick="typed_objects_delete_object({$object['id']}, '$obj_name_js')">
<br>
</div>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_get_objects_list($menu_item, $obj_type, $page)
{
	global $_cms_objects_types, $_cms_objects_table, $_cms_objects_admin_list_page_length;

	if ($obj_type=='')
	{
		// Не задан тип объектов
		if (count($_cms_objects_types)>1)
		{
			// Количество определенных объектов больше одного
			$html=<<<stop
<div class="typed_objects_object_type_selector">
Тип объектов:
<select id="typed_objects_object_type">
<option value="-1"></option>
stop;
			$ot=get_data_array('type, count(*) as cnt', $_cms_objects_table, "menu_item='$menu_item' group by type order by cnt desc limit 1");
			$ot=$ot['type'];
			foreach($_cms_objects_types as $obj_type)
			{
		        if ($obj_type['id']==$ot) $sl='selected="selected"';
				else $sl='';
				$html.=<<<stop
<option value="{$obj_type['id']}" $sl>{$obj_type['name']}</option>
stop;
			}
			$html.='</select></div>';
		}
		else	// Определен только один тип объектов
			$html.="<input type='hidden' id='typed_objects_object_type' value='{$_cms_objects_types[0]['id']}'>";
	}
	else	// Задан фиксированняй тип объектов
		$html.="<input type='hidden' id='typed_objects_object_type' value='$obj_type'>";
	$html.=<<<stop
<br><input type="button" value="Добавить объект" id="typed_objects_object_add" onClick="typed_objects_object_add()">
<input type="button" value="Сортировка объектов" id="typed_objects_object_sort" onClick="typed_objects_object_sort()" style="float:right;"><br>
stop;
	$start=$page*$_cms_objects_admin_list_page_length;
	$res=query("select SQL_CALC_FOUND_ROWS * from $_cms_objects_table where menu_item='$menu_item' order by sort, id desc limit $start, $_cms_objects_admin_list_page_length");
	$total=get_data('FOUND_ROWS()');
	$html.=get_admin_pager($total, $page, $_cms_objects_admin_list_page_length, 'typed_objects_show_objects_list_page');
	while ($r=mysql_fetch_assoc($res))
		$html.=typed_objects_get_object_html($r);
	$html.=get_admin_pager($total, $page, $_cms_objects_admin_list_page_length, 'typed_objects_show_objects_list_page');
	return array('html'=>$html, 'page'=>$page);
}
// -----------------------------------------------------------------------------
function typed_objects_get_edit_object_html($id, $type)
{
	global $_cms_objects_table, $_cms_objects_details, $_cms_text_parts, $_base_site_structured_text_images_path;
	global $_base_site_objects_images_path, $_base_site_objects_images_url, $_admin_root_url;

	if ($id==-1)
	{
		$obj_type=$type;
		$obj_name='';
        $obj_note='';
		$prop_html='';
		$obj_img='';
		$obj_img_src="$_admin_root_url/images/no_image_256.gif";
		$prop_html=typed_objects_get_object_propertis_edit_html($id, $type);
	}
	else
	{
		$obj=get_data_array('*', $_cms_objects_table, "id='$id'");
		$obj_type=$obj['type'];
		$obj_name=$obj['name'];
		$obj_note=$obj['note'];
		$obj_img=$obj['image'];
		if ($obj_img!='') $obj_img_src="$_base_site_objects_images_url/$obj_img";
		else $obj_img_src="$_admin_root_url/images/no_image_256.gif";
		$prop_html=typed_objects_get_object_propertis_edit_html($id, $obj['type']);
		copy_image_to_temp("$_base_site_objects_images_path/$obj_img");
// обработка всех параметров типа "структурированный тест"
// копируем изображения связанные с полями в temp
		$object_description=typed_objects_get_object_description($obj_type);
		foreach($object_description['details'] as $obj_prop)
			if($obj_prop['type']=='st')
			{
				$prop_id=get_data('id', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$res=query("select id, image from $_cms_text_parts where node='$prop_id' and type=1");
				while($r=mysql_fetch_assoc($res))
					copy_image_to_temp("$_base_site_structured_text_images_path/{$r['image']}");
			}
	}
	$object_description=typed_objects_get_object_description($obj_type);
	if (isset($object_description['sx']) && $object_description['sx']!='') $sx=$object_description['sx'];
	else $sx=$_cms_objects_image_sx;
	if (isset($object_description['sy']) && $object_description['sy']!='') $sy=$object_description['sy'];
	else $sy=$_cms_objects_image_sy;
	$html=<<<stop
<input type="hidden" id="typed_objects_edit_object_id" value="$id">
<input type="hidden" id="typed_objects_edit_object_sx" value="$sx">
<input type="hidden" id="typed_objects_edit_object_sy" value="$sy">
stop;

    if (isset($object_description['no_object_image']) && $object_description['no_object_image']==true)
		$html.='<input type="hidden" id="typed_objects_object_image" value="">';
    if (isset($object_description['no_object_description']) && $object_description['no_object_description']==true)
		$html.='<input type="hidden" id="typed_object_description" value="">';

	$html.=<<<stop
<table width='99%'>
<tr><td valign='top' width='120' class='typed_objects_edit_prop_need_title'>Название объекта</td>
<td><input type='text' id='typed_object_name' style='width:100%;' value='$obj_name'></td></tr>
stop;

    if (!isset($object_description['no_object_image']) || $object_description['no_object_image']!=true)
		$html.=<<<stop
<tr><td valign='top'>Изображение</td>
<td>
<input type="hidden" id="typed_objects_object_image" value="$obj_img">
<div class="typed_objects_edit_image_container">
<img id="typed_object_image_img" src="$obj_img_src" />
</div>
<div>
<div style=" float:left">
<div style="margin-bottom: 10px;"><input type="button" value="Загрузить изображение" onClick="typed_objects_object_edit_load_image()"></div>
<div><input type="button" value="Удалить изображение" onClick="typed_objects_object_edit_delete_image()"></div>
</div>
</div>
</td></tr>
stop;

    if (!isset($object_description['no_object_description']) || $object_description['no_object_description']!=true)
		$html.=<<<stop
<tr><td valign='top'>Описание объекта</td>
<td class="typed_object_description_container">
	<textarea id='typed_object_description' style='width:100%;' row='5'>$obj_note</textarea>
	<div class='typed_objects_text_edit_tool_panel' id='typed_objects_text_edit_tool_panel'>
		<input type='button' class='admin_tool_button objects_edit_tool_button' value='Вставить ссылку на документ' onClick='texts_edit_insert_document_link("texts_link_text_document_select")'>
		<input type='button' class='admin_tool_button objects_edit_tool_button' value='Вставить константу' onClick='texts_edit_insert_constant("texts_constant_select")'>
		<input type='button' class='admin_tool_button objects_edit_tool_button' value='Изменить тэг' onClick="texts_edit_edit_pseudotag()">
	</div>
</td></tr>
stop;

	$html.=<<<stop
$prop_html
</table>
<hr>
<input type='button' value='Сохранить данные' style='margin-right:10px;' onClick='typed_objects_object_edit_save()'>
<input type='button' value='Отменить' onClick='typed_objects_object_edit_cancel()'>
stop;

    if (!isset($object_description['no_object_description']) || $object_description['no_object_description']!=true)
		$html.=<<<stop
<script type="text/javascript">
    CKEDITOR.config.height= '200px';
	CKEDITOR.config.format_tags = 'p';
	CKEDITOR.config.baseFloatZIndex=100100;
	if (text_editor) CKEDITOR.remove(text_editor);
	text_editor=CKEDITOR.replace('typed_object_description');
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_get_object_description($type)
{
	global $_cms_objects_types;
	foreach($_cms_objects_types as $desc)
		if (strtolower($desc['id'])==strtolower($type)) return $desc;
	return false;
}
// -----------------------------------------------------------------------------
function typed_objects_get_object_propertis_edit_html($id, $type)
{
	global $_cms_objects_details, $_admin_js_url, $_cms_directories_data;

	$object_description=typed_objects_get_object_description($type);
	if ($object_description===false) return '';
	foreach($object_description['details'] as $obj_prop)
	{
        if(isset($obj_prop['noshow']) && $obj_prop['noshow']) continue;
		$obj_prop['type']=strtolower($obj_prop['type']);
		$need='';  $name_style='';
		if ($obj_prop['need'])
		{
			$need="data-need='true'";
			$name_style="class='typed_objects_edit_prop_need_title'";
		}
		if ($obj_prop['type']=='c')
			$html.="<tr><td valign='bottom' $name_style>{$obj_prop['name']}</td><td>";
		elseif ($obj_prop['type']=='t')
			$html.="<tr><td valign='top' $name_style>{$obj_prop['name']}</td><td>";
		else
			$html.="<tr><td valign='center' $name_style>{$obj_prop['name']}</td><td>";
        switch($obj_prop['type'])
		{
			case 's':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				if (!isset($obj_prop['readonly']) || !$obj_prop['readonly'])
					$html.="<input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='text' style='width:100%;' value='$v' $need>";
				else
					$html.="<span>$v</span><input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='hidden' value='$v'>";
				break;
			case 'd':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				if (!isset($obj_prop['readonly']) || !$obj_prop['readonly'])
					$html.="<input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='text' style='width:100%;' value='$v' data-type='d' $need>";
				else
					$html.="<span>$v</span><input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='hidden' value='$v' data-type='d'>";
				break;
			case 't':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$html.="<textarea id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' style='width:100%;' $need>$v</textarea>";
				break;
			case 'e':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$html.="<select id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' size='1' $need>";
				$opt=explode('|', $obj_prop['options']);
				sort($opt);
				if ($v=='') $s=" selected='selected'";
				$html.="<option value=''$sl></option>";
				foreach($opt as $o)
				{
					$sl='';
					if ($o==$v) $sl=" selected='selected'";
					$html.="<option value='{$o}'$sl>$o</option>";
				}
				$html.="</select>";
				break;
			case 'c':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$ch='';
				if ($v==1) $ch='checked';
				$html.="<input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='checkbox' class='prop_img_checkbox' value='1' $ch>";
				break;
			case 'do':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$html.="<select id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' size='1' $need>";
				$res=query("select * from $_cms_directories_data where dir='{$obj_prop['options']}'");
				if ($v=='') $s=" selected='selected'";
				$html.="<option value=''$sl></option>";
                while($r=mysql_fetch_assoc($res))
				{
					$sl='';
					if ($r['id']==$v) $sl=" selected='selected'";
					$html.="<option value='{$r['id']}'$sl>{$r['content']}</option>";
				}
				mysql_free_result($res);
				$html.="</select>";
				break;
			case 'dm':
                $resV=query("select value from $_cms_objects_details where node='$id' and typeId='{$obj_prop['id']}'");
				$val=array();
				$hval='';
				while($r=mysql_fetch_assoc($resV))
				{
					array_push($val, $r['value']);
					$hval.="{$r['value']}|";
				}
				$hval=substr($hval, 0, -1);
				mysql_free_result($resV);
				$html.=<<<stop
<input type="hidden" id="prop_{$obj_prop['id']}" name="{$obj_prop['id']}" value="$hval"><div class="typed_objects_property_dir_values" id="propt_{$obj_prop['id']}" onClick="typed_objects_property_dir_select($type, '{$obj_prop['id']}')">
stop;
				if (count($val))
				{
					foreach($val as $v)
					{
						$name=get_data('content', $_cms_directories_data, "id='$v'");
						$html.="$name, ";
					}
					$html=substr($html, 0, -2);
				}
				else
					$html.='выберите значения';
				$html.='</div>';
				break;
			case 'st':	// structured text
				global $_cms_text_parts;
				$prop_id=get_data('id', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$cnt=get_data('count(*)', $_cms_text_parts, "node='$prop_id' and type=1");
                $hval=typed_objects_get_structured_text_html_value($id, $obj_prop['id']);
				$html.=<<<stop
<input type="hidden" id="prop_{$obj_prop['id']}" name="{$obj_prop['id']}" value="$hval">
<div class="typed_objects_property_structured_text_values" id="propt_{$obj_prop['id']}" onClick="typed_objects_property_structured_text('$id', '{$obj_prop['id']}', '$type')">
stop;
				if ($cnt) $html.="количество фрагментов: $cnt ";
				$html.='(нажмите для редактирования)';
				$html.='</div>';
				break;
			case 'tb':	// table
				$cnt=get_data('count(*)', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
                $hval=typed_objects_get_table_html_value($id, $obj_prop['id']);
				$td=typed_objects_get_table_description($type, $obj_prop['id']);
				$ew=$td['width'];
                if ($ew=='') $ew=$td['columns_count']*150;
				if ($ew>1000) $ew=1000; if ($ew<300) $ew=300;
//				$hval='[тесто][200гр][сахар][50гр]';
				$html.=<<<stop
<input type="hidden" id="prop_{$obj_prop['id']}" name="{$obj_prop['id']}" value="$hval">
<input type="hidden" id="prop_editor_width_{$obj_prop['id']}" value="$ew">
<div class="typed_objects_property_table_values" id="propt_{$obj_prop['id']}" onClick="typed_objects_property_table('$id', '{$obj_prop['id']}', '$type')">
stop;
				if ($cnt) $html.="количество строк: $cnt ";
				$html.='(нажмите для редактирования)';
				$html.='</div>';
				break;
		}
		$html.='</td></tr>';
	}
	return $html;
}
// -----------------------------------------------------------------------------
// Получение параметров поля типа "таблица"
// -----------------------------------------------------------------------------
function typed_objects_get_table_description($obj_type, $prop_type)
{
	$prop=typed_objects_get_object_detail($obj_type, $prop_type);
	$desc=array();
	$desc['columns']=explode('|', $prop['columns']);
	$desc['columns_count']=count($desc['columns']);
	if(isset($prop['width']))
	{
		$desc['width']=$prop['width'];
		$desc['cellwidth']=($desc['width']-85)/$desc['columns_count']-18;
	}
	else
	{
		$desc['width']='';
		$desc['cellwidth']='';
	}
	return $desc;
}
// -----------------------------------------------------------------------------
// Генерация HTML кода для поля с данными поля типа талица
// -----------------------------------------------------------------------------
function typed_objects_get_table_html_value($obj_id, $prop_type)
{
	global $_cms_objects_details;

	$str='';
	$res=query("select * from $_cms_objects_details where node='$obj_id' and typeId='$prop_type' order by id");
	while($r=mysql_fetch_assoc($res))
	{
		$val=htmlspecialchars($r['value'], ENT_QUOTES, $html_charset);
		$str.="$val";
	}
	mysql_free_result($res);
	return $str;
}
// -----------------------------------------------------------------------------
// Генерация HTML кода для редактирования таблицы
// -----------------------------------------------------------------------------
function typed_objects_table_edit_get_html($id, $prop_type, $prop_value, $obj_type)
{
	$object_description=typed_objects_get_object_description($obj_type);
	$prop=typed_objects_get_object_detail($obj_type, $prop_type);
	$desc=typed_objects_get_table_description($obj_type, $prop_type);

	$html=<<<stop
<div class="typed_object_table_edit_container">
	<input type="hidden" id="typed_objects_table_edit_object_id" value="$id"/>
	<input type="hidden" id="typed_objects_table_edit_object_prop_type" value="$prop_type"/>
	<input type="hidden" id="typed_objects_table_edit_object_obj_type" value="$obj_type"/>
	<div class="typed_object_table_edit_caption">
stop;
	for($i=0; $i<$desc['columns_count']; $i++)
		$html.=<<<stop
<div class="hdr" style="width: {$desc['cellwidth']}px;">{$desc['columns'][$i]}</div>
stop;
	$html.='</div>';
	$cells=typed_objects_prop_explode_fragments_string($prop_value);
	$html.='<div id="typed_object_table_edit_nodes_sortable">';
	for($i=0; $i<count($cells); $i+=$desc['columns_count'])
		$html.=typed_objects_table_edit_get_row_html($i/$desc['columns_count'], $cells, $i, $desc, $desc['cellwidth'], true);
	$max_row=count($cells)/$desc['columns_count'];
	$html.=typed_objects_table_edit_get_row_html($max_row, array_fill(0, $desc['columns_count'], ''), 0, $desc, $desc['cellwidth'], false);
	$html.=<<<stop
	</div>
	<input type="hidden" id="typed_objects_table_edit_max_row" value="$max_row"/>
	<hr>
	<input type="button" class="_left" value="Сохранить" onClick="typed_objects_table_edit_save()"/>
	<input type="button" class="_right" value="Отмена" onClick="typed_objects_table_edit_cancel()"/>
</div>
<script type="text/javascript">
	typed_objects_table_edit_refresh_sortable();
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
// Генерация HTML кода для редактирования строки таблицы
// -----------------------------------------------------------------------------
function typed_objects_table_edit_get_row_html($row_id, $cells, $pos, $desc, $cellwidth, $can_delete)
{
	global $html_charset, $_admin_root_url;

	$row_idx=$row_id+1;
	$html=<<<stop
<div class="typed_object_table_edit_node" id="typed_object_table_edit_row_{$row_id}">
<div class="typed_object_table_edit_nod_number">$row_idx.</div>
stop;
    $empty=true;
	for($i=$pos; $i<$pos+$desc['columns_count']; $i++)
	{
		if ($cells[$i]!='') $empty=false;
		$cell=htmlspecialchars(strtr($cells[$i], array('\\['=>'[', '\\]'=>']')), ENT_QUOTES, $html_charset);
		$html.=<<<stop
<input class="typed_object_table_edit_node_cell" type="text" value="$cell" style="width: {$cellwidth}px;" onKeyUp="typed_object_table_edit_row_key_control($row_id)"/>
stop;
	}
    if(!$empty && $can_delete) $img_sl='';
	else $img_sl=' style="display: none;"';
	$html.=<<<stop
<img src="$_admin_root_url/images/delete_24.png" alt="" title="Удалить строку" onClick="typed_object_table_edit_row_delete($row_id)" $img_sl/>
</div>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
// Генерация HTML кода для поля с данными поля типа структурированный текст
// -----------------------------------------------------------------------------
function typed_objects_get_structured_text_html_value($obj_id, $obj_prop_id)
{
	global $html_charset, $_cms_objects_details, $_cms_text_parts;

	if ($obj_id==-1) return '';
	$prop_id=get_data('id', $_cms_objects_details, "node='$obj_id' and typeId='$obj_prop_id'");
	$res=query("select * from $_cms_text_parts where node='$prop_id' and type=1 order by sort");
	$val='';
	while($r=mysql_fetch_assoc($res))
	{
		$title=strtr($r['title'], array('['=>'\\[', ']'=>'\\]'));
		$title=htmlspecialchars($title, ENT_QUOTES, $html_charset);
		$content=strtr($r['content'], array('['=>'\\[', ']'=>'\\]'));
		$content=htmlspecialchars($content, ENT_QUOTES, $html_charset);
		$val.="[{$r['id']}][$title][{$r['image']}][$content]";
	}
	return $val;
}
// -----------------------------------------------------------------------------
// Разбор строки с описанием структурированного текста в массив.
// Элементы массива компонуются следующим образом
// для структурированного текста:
// 	+0 - id фрагмента
//	+1 - заголовок фрагмента
//	+2 - имя файла с изображением
//	+3 - текст фрагмента
// для таблицы:
//	+0 - значение колонки 1
//	+1 - значение колонки 2
//		...
//	+n - значение колонки n+1
// -----------------------------------------------------------------------------
function typed_objects_prop_explode_fragments_string($text)
{
	$str='';
	$len=strlen($text);
	$in_braces=true;
	$fragments=array();
	$fragmet_start=1;
    for($pos=1; $pos<$len; $pos++)
	{
		if ($in_braces)
		{
			if ($text[$pos]==']' && $text[$pos-1]!='\\')
			{
				$in_braces=false;
				$fr=substr($text, $fragmet_start, $pos-$fragmet_start);
				array_push($fragments, trim($fr));
			}
		}
		else
		{
			if ($text[$pos]=='[' && $text[$pos-1]!='\\')
			{
				$in_braces=true;
				$fragmet_start=$pos+1;
			}

		}
	}
	return $fragments;
}
// -----------------------------------------------------------------------------
// Генерация HTML кода для редактирования структурированного текста
// -----------------------------------------------------------------------------
function typed_objects_structured_text_edit_get_html($id, $prop_type, $text, $obj_type)
{
	$object_description=typed_objects_get_object_description($obj_type);
	$prop=typed_objects_get_object_detail($obj_type, $prop_type);
	if (isset($prop['sx']) && $prop['sx']!='') $sx=$prop['sx'];
	elseif (isset($object_description['sx']) && $object_description['sx']!='') $sx=$object_description['sx'];
	else $sx=$_cms_objects_image_sx;

	if (isset($prop['sy']) && $prop['sy']!='') $sy=$prop['sy'];
	if (isset($object_description['sy']) && $object_description['sy']!='') $text_sy=$object_description['sy'];
	else $sy=$_cms_objects_image_sy;

	$html=<<<stop
<div class="typed_object_structure_text_edit_container">
	<input type="hidden" id="typed_objects_structured_text_edit_object_id" value="$id"/>
	<input type="hidden" id="typed_objects_structured_text_edit_object_prop_type" value="$prop_type"/>
	<input type="hidden" id="typed_objects_structured_text_edit_object_obj_type" value="$obj_type"/>
	<input type="hidden" id="typed_objects_structured_text_edit_current_fragment_id" value=""/>
	<input type="hidden" id="typed_objects_edit_object_text_sx" value="$sx">
	<input type="hidden" id="typed_objects_edit_object_text_sy" value="$sy">
stop;
	$fragments=typed_objects_prop_explode_fragments_string($text);
	$html.='<div id="typed_object_structure_text_edit_nodes_sortable">';
	for($i=0; $i<count($fragments); $i+=4)
		$html.=typed_objects_structured_text_edit_get_fragment_html($fragments[$i], $fragments[$i+1], $fragments[$i+2], $fragments[$i+3], $obj_type);
	$html.=<<<stop
	</div>
	<input type="button" value="Добавить фрагмент" onClick="typed_objects_structure_text_add_fragment()" id="typed_objects_structure_text_add_fragment"/>
	<hr>
	<input type="button" class="_left" value="Сохранить" onClick="typed_objects_structure_text_edit_save()"/>
	<input type="button" class="_right" value="Отмена" onClick="typed_objects_structure_text_edit_cancel()"/>
</div>
<script type="text/javascript">
	typed_objects_structure_text_edit_refresh_sortable();
	$(".typed_object_structure_text_edit_node").show();
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_structured_text_edit_get_fragment_html($id, $title, $image, $content, $obj_type)
{
	global $_admin_uploader_url;

	$html=<<<stop
<div class="typed_object_structure_text_edit_node" id="fragment_id_{$id}">
<div class="typed_object_structure_text_edit_node_image_container">
	<h2>Изображение</h2>
	<input type="hidden" id="fragment_image_name_{$id}" value="$image" />
	<img src="$_admin_uploader_url/temp/$image" id="fragment_image_{$id}" alt="" />
	<input class="admin_tool_button" type="button" value="Загрузить" onClick="typed_objects_structure_text_edit_image_load('$id')"/>
	<input class="admin_tool_button" type="button" value="Удалить" onClick="typed_objects_structure_text_edit_image_clear('$id')"/>
</div>
<div class="typed_object_structure_text_edit_node_info_container">
	<div class="typed_object_structure_text_edit_node_info_title"><span>Заголовок:</span><input type="text" id="fragment_title_{$id}" value="$title"/></div>
	<div class="typed_object_structure_text_edit_node_info_text" name="fragment_content_{$id}" id="fragment_content_{$id}" onClick="typed_objects_structure_text_edit_fragment_edit_text('$id')">$content</div>
	<div class="typed_object_structure_text_edit_node_info_text_controls" id="fragment_text_controls_{$id}"></div>
</div>
<hr>
<input class="admin_tool_button _left" type="button" value="Удалить фрагмент" onClick="typed_objects_structure_text_edit_fragment_delete('$id')"/>
<br>
stop;

	$html.='</div>';
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_save_object_data($id, $obj_type, $menu_item, $name, $note, $img, $gallery, $props)
{
	global $_cms_objects_table, $_cms_objects_details, $_cms_text_parts, $html_charset;
	global $_admin_uploader_path, $_base_site_objects_images_path, $_base_site_structured_text_images_path;

	if ($gallery=='') $gallery=0;
	if ($id!=-1)
	{
		$obj_type=get_data('type', $_cms_objects_table, "id='$id'");
		$old_img=get_data('image', $_cms_objects_table, "id='$id'");
		if ($img!='')
		{
			if ($old_img!='')
				@unlink("$_base_site_objects_images_path/$old_img");
			$dest=create_unique_file_name($_base_site_objects_images_path, $img);
			rename("$_admin_uploader_path/temp/$img", $dest);
			$pp=pathinfo($dest);
			$img=$pp['basename'];
			query("update $_cms_objects_table set image='$img' where id='$id'");
		}
		else
		{
			if ($old_img!='')
			{
				@unlink("$_base_site_objects_images_path/$old_img");
				query("update $_cms_objects_table set image='' where id='$id'");
			}
		}
		query("update $_cms_objects_table set name='$name', note='$note' where id='$id'");

// обработка всех параметров типа "структурированный тест"
// удаляем записи в таблице $_cms_text_parts и связанные изображения
		$object_description=typed_objects_get_object_description($obj_type);
		foreach($object_description['details'] as $obj_prop)
			if($obj_prop['type']=='st')
			{
				$prop_id=get_data('id', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$res=query("select id, image from $_cms_text_parts where node='$prop_id' and type=1");
				while($r=mysql_fetch_assoc($res))
                	@unlink("$_base_site_structured_text_images_path/{$r['image']}");
				query("delete from $_cms_text_parts where node='$prop_id' and type=1");
			}
	}
	else
	{
		if ($img!='')
		{
			$dest=create_unique_file_name($_base_site_objects_images_path, $img);
			rename("$_admin_uploader_path/temp/$img", $dest);
			$pp=pathinfo($dest);
			$img=$pp['basename'];
		}
		query("insert into $_cms_objects_table (menu_item, type, name, note, date, visible, image, gallery) values ('$menu_item', '$obj_type', '$name', '$note', CURDATE(), 0, '$img', '$gallery')");
		$id=mysql_insert_id();
	}
	query("delete from $_cms_objects_details where node='$id' and type<>'i'");
   	parse_str($props, $details);
	foreach($details as $k=>$v)
	{
		$v=iconv ('utf-8', $html_charset, $v);
		$type=typed_objects_get_detail_type($k, $obj_type);
		if ($v!='' && $type!='')
		{
			switch($type)
			{
				case 'tb':
					$object_description=typed_objects_get_object_description($obj_type);
					$desc=typed_objects_get_table_description($obj_type, $k);
                    $fr=typed_objects_prop_explode_fragments_string($v);
					for($i=0; $i<count($fr); $i+=$desc['columns_count'])
					{
						$str='';
                        for($j=0; $j<$desc['columns_count']; $j++)
							$str.="[{$fr[$i+$j]}]";
						$str=mysql_escape_string($str);
						query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '$str')");
					}
					break;
				case 'st':
					query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '')");
					$node_id=mysql_insert_id();
                    $fr=typed_objects_prop_explode_fragments_string($v);
					for($i=0; $i<count($fr); $i+=4)
					{
						if ($fr[$i+2]!='')
						{
							$dest=create_unique_file_name("$_base_site_structured_text_images_path", $fr[$i+2]);
							$src="$_admin_uploader_path/temp/{$fr[$i+2]}";
							@rename($src, $dest);
							@unlink($src);
							$pp=pathinfo($dest);
                            $fr[$i+2]=$pp['basename'];
						}
						$title=mysql_escape_string(strtr($fr[$i+1], array('\\['=>'[', '\\]'=>']')));
						$content=mysql_escape_string(strtr($fr[$i+3], array('\\['=>'[', '\\]'=>']')));
						query("insert into $_cms_text_parts (type, node, date, title, image, content, sort, visible) values (1, '$node_id', CURDATE(), '$title', '{$fr[$i+2]}', '$content', '{$fr[$i]}', 1)");
					}
					break;
				case 'dm': // множественный выбор из справочника
	    			$ve=explode('|', $v);
					foreach($ve as $v)
						query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '$v')");
					break;
				default:
					query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '$v')");
					break;
			}
    	}
	}
}
// -----------------------------------------------------------------------------
function typed_objects_get_dir_values_html($type, $id, $vals)
{
	global $object_description, $_cms_directories_data;
	$object_description=typed_objects_get_object_description($type);
	$prop='';
	foreach($object_description['details'] as $d)
		if ($d['id']==$id) $prop=$d;
	if ($prop=='') return;
	$html=<<<stop
<h2>{$prop['name']}</h2>
stop;
	$vals=explode('|', $vals);
	$res=query("select * from $_cms_directories_data where dir='{$prop['options']}' order by content");
	while($r=mysql_fetch_assoc($res))
	{
		if (in_array($r['id'], $vals)) $ch='checked="checked"';
		else $ch='';
		$html.=<<<stop
<div class="typed_objects_dir_select_node">
<input type="checkbox" id="dir_m_{$r['id']}" name="dir_m_{$r['id']}" value="1" $ch/> {$r['content']}
</div>
stop;
	}
	mysql_free_result($res);
	$html.=<<<stop
<hr>
<input type="button" value="Сохранить" onClick="typed_objects_property_dir_save($type, '$id')"/>
<script type="text/javascript">
$("input[type=checkbox][id ^= 'dir_m_']").imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent: true});
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_dir_values_save($id, $props)
{
	global $_cms_directories_data;

   	parse_str($props, $values);
	$data='';
	$text='';
	foreach($values as $k=>$v)
	{
		$k=substr($k, 6);
		$data.="$k|";
		$val=get_data('content', $_cms_directories_data, "id='$k'");
		$text.="$val, ";
	}
	$data=substr($data, 0, -1);
	$text=substr($text, 0, -2);
	return serialize_data('data|text', $data, $text);
}
// -----------------------------------------------------------------------------
function typed_objects_get_sort_html($menu_item)
{
	global $_cms_objects_table;

	$res=query("select id, name from $_cms_objects_table where menu_item='$menu_item' order by sort");
	$html.='<div class="typed_object_object_sort_container"><ul class="typed_object_object_sort_list" id="typed_object_object_sort_list">';
	while($r=mysql_fetch_assoc($res))
	{
		$html.=<<<stop
<li id="obj_sort_{$r['id']}">{$r['name']}</li>
stop;
	}
	$html.=<<<stop
</ul></div>
<input type="button" value="Сохранить" onClick="typed_objects_sort_save()"/>
stop;
	mysql_free_result($res);
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_object_move($id, $menu, $copy_mode)
{
	global $_cms_objects_table, $_cms_objects_details, $_cms_gallery_table;
	global $_base_site_galleries_path, $_cms_gallery_data_table;

	if(!$copy_mode)
    	query("update $_cms_objects_table set menu_item='$menu' where id='$id'");
	else
	{
        $fields_list=mysql_get_fields_list($_cms_objects_table, 'id|menu_item');
		query("insert into $_cms_objects_table (menu_item, $fields_list) select '$menu' as menu_item, $fields_list from $_cms_objects_table where id='$id'");
		$new_id=mysql_insert_id();
        $fields_list=mysql_get_fields_list($_cms_objects_details, 'id|node');
        query("insert into $_cms_objects_details (node, $fields_list) select $new_id as node, $fields_list from $_cms_objects_details where node='$id'");
		$res=query("select * from $_cms_gallery_table where menu_item='$id' and link_type=1");
		while ($r=mysql_fetch_assoc($res))
		{
			$dest=create_unique_file_name($_base_site_galleries_path, $r['file']);
			$pp=pathinfo($dest);
			$dest_thumb="$_base_site_galleries_path/thumbs/{$pp['basename']}";
        	copy("$_base_site_galleries_path/{$r['file']}", $dest);
        	copy("$_base_site_galleries_path/thumbs/{$r['file']}", $dest_thumb);
			query("insert into $_cms_gallery_table (menu_item, file, title, comment, sort, visible, link_type) values ('$new_id', '{$pp['basename']}', '{$r['title']}', '{$r['comment']}', '{$r['sort']}', '{$r['visible']}', '{$r['link_type']}')");
		}
		mysql_free_result($res);
        $fields_list=mysql_get_fields_list($_cms_gallery_data_table, 'id|menu_item');
        query("insert into $_cms_gallery_data_table (menu_item, $fields_list) select $new_id as menu_item, $fields_list from $_cms_gallery_data_table where menu_item='$id'");
	}
}
// -----------------------------------------------------------------------------
function typed_objects_object_delete($id)
{
	global $_cms_objects_table, $_cms_objects_details, $_cms_text_parts, $_cms_gallery_table;
	global $_base_site_galleries_path, $_cms_gallery_data_table;

    $obj_type=get_data('type', $_cms_objects_table, "id='$id'");
	if ($obj_type!==false)
	{
// обработка всех параметров типа "структурированный тест"
// удаляем записи в таблице $_cms_text_parts и связанные изображения
		$object_description=typed_objects_get_object_description($obj_type);
		foreach($object_description['details'] as $obj_prop)
			if($obj_prop['type']=='st')
			{
				$prop_id=get_data('id', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$res=query("select id, image from $_cms_text_parts where node='$prop_id' and type=1");
				while($r=mysql_fetch_assoc($res))
	               	@unlink("$_base_site_structured_text_images_path/{$r['image']}");
				query("delete from $_cms_text_parts where node='$prop_id' and type=1");
			}
    }

	query("delete from $_cms_objects_details where node='$id'");
	query("delete from $_cms_objects_table where id='$id'");
	$res=query("select * from $_cms_gallery_table where menu_item='$id' and link_type=1");
	while($r=mysql_fetch_assoc($res))
	{
       	@unlink("$_base_site_galleries_path/{$r['file']}");
       	@unlink("$_base_site_galleries_path/thumbs/{$r['file']}");
	}
	mysql_free_result($res);
	query("delete from $_cms_gallery_table where menu_item='$id' and link_type=1");
	query("delete from $_cms_gallery_data_table where menu_item='$id' and link_type=1");
}
// -----------------------------------------------------------------------------
?>
