<?php
// -----------------------------------------------------------------------------
function goods_get_good_description($type)
{
	global $_cms_good_types;
	foreach($_cms_good_types as $gt)
		if ($gt['id']==$type) return $gt;
	return false;
}
// -----------------------------------------------------------------------------
function goods_get_detail_type($good_types, $typeId)
{
	foreach($good_types as $type)
		if ($type['id']==$typeId) return $type['type'];
	return '';
}
// -----------------------------------------------------------------------------
function goods_get_detail_description($good_types, $typeId)
{
	foreach($good_types as $type)
		if ($type['id']==$typeId) return $type;
	return '';
}
// -----------------------------------------------------------------------------
function goods_get_category_list_html($menu, $func, $parent)
{
	global $_cms_menus_items_table;
	$str='';
	$res=query("select * from $_cms_menus_items_table where menu='$menu' and parent=$parent");
	while($r=mysql_fetch_assoc($res))
	{
		$cnt=get_data('count(*)', $_cms_menus_items_table, "parent={$r['id']}");
		if ($cnt)
			$str.="{$r['name']}<br>";
		else
		{
			$name=str_replace('"', '\"', $r['name']);
			$str.="<span style='text-decoration: underline; cursor: pointer;' onClick='$func({$r['id']}, \"$name\")'>{$r['name']}</span><br>";
		}
		$subtree=goods_get_category_list_html($menu, $func, $r['id']);
		if ($subtree!='') $str.="<blockquote>$subtree</blockquote>";
	}
	mysql_free_result($res);
	return $str;
}
//------------------------------------------------------------------------------
function goods_get_filter_sql($signature, $name, $visible, $props)
{
	global $html_charset, $_cms_tree_node_details, $_cms_good_types;

	$fl=''; $sub_fl='';
	if ($signature!='') $fl.="code like '%$signature%' and ";
	if ($name!='') $fl.="name like '%$name%' and ";
	if ($visible!='') $fl.="visible='$visible' and ";
    if ($props!='')
	{
		$sub_query="union distinct select node from $_cms_tree_node_details where ";
		parse_str($props, $details);
		foreach($details as $k=>$v)
		{
			$v=iconv ('utf-8', $html_charset, $v);
			if ($v!='')
			{
				if ($sub_fl=='') $sub_fl="id in (select distinct node from $_cms_tree_node_details where ";
				$k=substr($k, 18);
				$eq='=';
				if (substr($k, -4)=='_min') {$eq='>='; $k=substr($k, 0, -4);}
				if (substr($k, -4)=='_max') {$eq='<='; $k=substr($k, 0, -4);}
				$dd=goods_get_detail_description($_cms_good_types[0]['details'], $k);
				if ($dd['type']=='dm') // множественная выборка из справочника
				{
                	$rv=explode('|', $v);
					foreach($rv as $v)
						$sub_fl.="typeId='$k' and value='$v' $sub_query";
					$sub_fl=substr($sub_fl, 0, -strlen($sub_query));
				}
				elseif ($dd['type']=='do' || $dd['type']=='oo') // одиночная выборка из справочника
					$sub_fl.="typeId='$k' and value{$eq}'$v' ";
				elseif ($dd['type']=='d')
					$sub_fl.="typeId='$k' and value{$eq}{$v} ";
				elseif ($dd['type']=='s')
					$sub_fl.="typeId='$k' and value like '%{$v}%' ";
				else
					$sub_fl.="typeId='$k' and value{$eq}'$v' ";
				$sub_fl.=$sub_query;
			}
		}
		if ($sub_fl!='') $sub_fl=substr($sub_fl, 0, -strlen($sub_query)).')';
	}
	if ($fl!='' && $sub_fl=='') $fl=substr($fl, 0, -5);
	if ($sub_fl!='') $fl="$fl $sub_fl";
	return trim($fl);
}
//------------------------------------------------------------------------------
function get_property_filter_html($type)
{
	global $_cms_good_types, $_cms_directories_data, $_cms_objects_table;

	$p_list=array();
	foreach($_cms_good_types as $gt)
		if ($gt['id']==$type)
		{
			foreach($gt['details'] as $pr)
				if (!array_key_exists ($pr['id'], $p_list) && array_key_exists('filter', $pr) && $pr['filter'])
					$p_list[$pr['id']]=$pr;
		}

	$html='';
	foreach($p_list as $good_type)
	{
		if ($good_type['type']=='i') continue;
        $good_type['type']=strtolower($good_type['type']);
		$html.="<tr><td>{$good_type['name']}";
        switch($good_type['type'])
		{
			case 's':
			case 't':
				$html.=<<<stop
 содержит</td><td>
<input id='goods_filter_prop_{$good_type['id']}' name='goods_filter_prop_{$good_type['id']}'type='text' data-type='{$good_type['type']}' />
stop;
				break;
			case 'd':
				$html.=<<<stop
 в интервале</td><td>
от <input id='goods_filter_prop_{$good_type['id']}_min' name='goods_filter_prop_{$good_type['id']}_min' type='text' style='width: 80px; text-align:center; margin: 0px 10px;' data-type='{$good_type['type']}'/>
до <input id='goods_filter_prop_{$good_type['id']}_max' name='goods_filter_prop_{$good_type['id']}_max' type='text' style='width: 80px; text-align:center; margin: 0px 10px;' data-type='{$good_type['type']}'/>
stop;
				break;
			case 'e':
				$html.=<<<stop
</td><td>
<select id='goods_filter_prop_{$good_type['id']}' name='goods_filter_prop_{$good_type['id']}'>
<option value=''></option>
stop;
				$opt=explode('|', $good_type['options']);
				sort($opt);
				foreach($opt as $o)
					$html.="<option value='{$o}'>$o</option>";
				$html.="</select>";
				break;
			case 'c':
				$html.=<<<stop
</td><td>
<select id='goods_filter_prop_{$good_type['id']}' name='goods_filter_prop_{$good_type['id']}'>
<option value=""></option>
<option value="1">Да</option>
<option value="0">Нет</option>
</select>
stop;
				break;
			case 'do':	// одиночное значение из справочника
				$html.=<<<stop
</td><td>
<select id='goods_filter_prop_{$good_type['id']}' name='goods_filter_prop_{$good_type['id']}'>
<option value=''></option>
stop;
				$res=query("select * from $_cms_directories_data where dir='{$good_type['options']}'");
                while($r=mysql_fetch_assoc($res))
					$html.="<option value='{$r['id']}'>{$r['content']}</option>";
				mysql_free_result($res);
				$html.="</select>";
				break;
			case 'dm':	// выбор нескольких значений из справочника
				$html.=<<<stop
</td><td>
<input type="hidden" id="goods_filter_prop_{$good_type['id']}" name="goods_filter_prop_{$good_type['id']}" value=""><div class="goods_property_dir_values" id="goods_filter_propt_{$good_type['id']}" onClick="goods_filter_property_dir_select('{$good_type['id']}')">выберите значения</div>
stop;
				break;
			case 'oo':	// одиночное значение из объектов
				$html.=<<<stop
</td><td>
<select id='goods_filter_prop_{$good_type['id']}' name='goods_filter_prop_{$good_type['id']}'>
<option value=''></option>
stop;
				$res=query("select * from $_cms_objects_table where type='{$good_type['options']}' order by name");
                while($r=mysql_fetch_assoc($res))
					$html.="<option value='{$r['id']}'>{$r['name']}</option>";
				mysql_free_result($res);
				$html.="</select>";
				break;
		}
		$html.='</td></tr>';
	}
	return $html;
}
//------------------------------------------------------------------------------
function goods_get_filter_html($type)
{
	if ($type!=-1) $prop_filter=get_property_filter_html($type);
	else $prop_filter='';
	$html=<<<stop
<div class="goods_filter_container" id="goods_filter_container">
<input type="hidden" id="goods_filter_sql" value="" />
<input type="hidden" id="goods_filter_props_sql" value="" />
<h3 onClick="goods_filter_toggle()"><img id="goods_filter_icon" src="images/goods_filter.png" />Фильтр товаров</h3>
<div id="goods_filter_data">
<table>
<tr>
<td>Артикул начинается с</td>
<td><input type="text" id="goods_filter_signature"/></td>
</tr>
<tr>
<td>Название содержит</td>
<td><input type="text" id="goods_filter_name"/></td>
</tr>
<tr>
<td>Отображается на сайте</td>
<td><select id="goods_filter_visible"><option value=""></option><option value="1">Да</option><option value="0">Нет</option></select></td>
</tr>
$prop_filter
</table>
<hr />
<input type="button" class="admin_tool_button" value="Установить фильтр" onClick="goods_filter_set()" />
<input type="button" class="admin_tool_button" value="Снять фильтр" onClick="goods_filter_reset()" />
</div>
</div>
stop;
	return $html;
}
//------------------------------------------------------------------------------
function goods_good_type_selector_html($menu_item, $obj_type)
{
	global $_cms_good_types, $_cms_tree_node_table;

	if (count($_cms_good_types)>1)
	{
		$html=<<<stop
<div class="goods_good_type_selector">
Тип товаров:
<select id="goods_good_type" onChange="goods_good_type_change()">
<option value="-1"></option>
stop;
		if($obj_type==-1)
		{
			$ot=get_data_array('type, count(*) as cnt', $_cms_tree_node_table, "parent='$menu_item' group by type order by cnt desc limit 1");
			$ot=$ot['type'];
		}
		else
			$ot=$obj_type;
		foreach($_cms_good_types as $good_type)
		{
	        if ($good_type['id']==$ot) $sl='selected="selected"';
			else $sl='';
			$html.=<<<stop
<option value="{$good_type['id']}" $sl>{$good_type['name']}</option>
stop;
		}
		$html.='</select></div>';
	}
	else
        $html=<<<stop
<input type="hidden" id="goods_good_type" value="{$_cms_good_types[0]['id']}" />
stop;
	return array('html'=>$html, 'type'=>$ot);
}
//------------------------------------------------------------------------------
function goods_get_list_frame_html($menu, $category, $page, $filter, $obj_type)
{
	global $_cms_tree_node_table, $_cms_goods_admin_list_page_length;

	$filter=stripcslashes($filter);
	$html=<<<stop
<br><input type="button" value="Добавить новый товар" onClick="goods_add_good()"><br><br>
stop;
	$start=$page*$_cms_goods_admin_list_page_length;
	if ($filter!='') $filter="and $filter";
	if ($obj_type==-1)
		$sql="select SQL_CALC_FOUND_ROWS id, name, code, visible from $_cms_tree_node_table where menu='$menu' and parent='$category' $filter order by sort, id desc limit $start, $_cms_goods_admin_list_page_length";
	else
		$sql="select SQL_CALC_FOUND_ROWS id, name, code, visible from $_cms_tree_node_table where menu='$menu' and parent='$category' and type='$obj_type' $filter order by sort, id desc limit $start, $_cms_goods_admin_list_page_length";
	$res=query($sql);
	$total=get_data('FOUND_ROWS()');
	if ($total)
	{
		$html.=get_admin_pager($total, $page, $_cms_goods_admin_list_page_length, 'goods_show_list');
		while ($r=mysql_fetch_assoc($res))
			$html.=goods_data_block($r);
		$html.=get_admin_pager($total, $page, $_cms_goods_admin_list_page_length, 'goods_show_list');
	}
	mysql_free_result($res);
	return array('html'=>$html, 'sql'=>$sql);
//	return $html;
}
//------------------------------------------------------------------------------
function goods_good_data_block_indiv($good)
{
    global $_admin_good_title_suffix, $_cms_tree_node_details;

	$suffix='';
	if (isset($_admin_good_title_suffix) && $_admin_good_title_suffix!='');
	{
		$fl=explode('|', $_admin_good_title_suffix);
		foreach($fl as $f)
		{
			$f=mysql_real_escape_string($f);
			$val=get_data('value', $_cms_tree_node_details, "node='{$good['id']}' and typeId='$f'");
			if ($val!==false) $suffix.="$val, ";
		}
		if (strlen($suffix)) $suffix=' ['.substr($suffix, 0, -2).']';
	}
	if ($good['visible']) $ch=' checked ';
	else $ch='';
	$html=<<<stop
<span class="cms_goods_list_item_name" onClick="goods_good_edit({$good['id']})">{$good['name']} ({$good['code']})$suffix</span><br>
<div id="goods_item_block_{$good['id']}">
<div style="float:left;"><input type="checkbox" id="goods_visible_{$good['id']}" class="img_checkbox" $ch onClick="goods_good_visible_toggle({$good['id']})">Включать в каталог</div>
<input type="button" style="float:right;" class="admin_tool_button" value="Удалить товар" onClick="goods_delete_good({$good['id']})">
<input type="button" style="float:right;margin-right:20px" class="admin_tool_button" value="Перенести в другой раздел" onClick="goods_move_good({$good['id']})"><br>
</div>
stop;
	return $html;
}
//------------------------------------------------------------------------------
function goods_data_block($good)
{
	$html=<<<stop
<div class="cms_goods_list_item" id="goods_list_item_{$good['id']}">
stop;
	$html.=goods_good_data_block_indiv($good);
	$html.='</div>';
	return $html;
}
//------------------------------------------------------------------------------
// генерация HTML кода формы добавления нового товара
// obj_type	- тип добовляемого товара
//------------------------------------------------------------------------------
function goods_get_good_add_html($obj_type)
{
	$good_type=goods_get_good_description($obj_type);
    $obj_type_name=$good_type['name'];
	$html=<<<stop
<input type="hidden" value="$obj_type" id="goods_good_type" />
<table width="99%">
<tr><td style="width: 120px;"><b>Тип товара</b></td>
<td><u>$obj_type_name</u></td></tr>
<tr><td>Название товара</td>
<td><input type="text" id="good_name" style="width:100%;"></td></tr>
<tr><td>Артикул</td>
<td><input type="text" id="good_code" style="width:130px;">
<input type="button" onClick="goods_create_good_code()" value="Сгенерировать" class="admin_tool_button" style="margin:0px 0px 3px 10px;"></td></tr>
</table><br>
<input type="button" value="Добавить товар" style="margin-right:10px;" onClick="goods_save_new_good()">
stop;
	return $html;
}
//------------------------------------------------------------------------------
// генерация HTML кода формы редактирования товара
// id	- ID редактируемого товара
//------------------------------------------------------------------------------
function goods_get_good_edit_html($id, $max_height)
{
	global $_cms_tree_node_table, $_cms_good_types, $_cms_tree_node_details, $_cms_directories, $_cms_directories_data, $_admin_js_url;
	global $_cms_objects_table;

	if ((int)$max_height==0) $max_height=600;
	$good=get_data_array('*', $_cms_tree_node_table, "id='$id'");
	if($good===false) { echo ''; exit(); }
	$type=$good['type'];
	$html=<<<stop
<div id="goods_good_edit_container" style="max-height: {$max_height}px; overflow-y: scroll;">
<table width="99%">
<tr><td style="width: 120px;">Артикул</td>
<td><input type="text" id="good_code" style="width:130px;" value="{$good['code']}"></td></tr>
<tr><td valign="top">Название товара</td>
<td><input type="text" id="good_name" style="width:100%;" value="{$good['name']}"></td></tr>
<tr><td valign="top">Описание товара</td>
<td><textarea id="good_description" style="width:100%;" row="5">{$good['note']}</textarea></td></tr>
stop;
	$good_description=goods_get_good_description($good['type']);
	foreach($good_description['details'] as $good_type)
	{
        $good_type['type']=strtolower($good_type['type']);
		if ($good_type['type']=='c')
			$html.="<tr><td valign='bottom'>{$good_type['name']}</td><td>";
		elseif ($good_type['type']=='i' || $good_type['type']=='t')
			$html.="<tr><td valign='top'>{$good_type['name']}</td><td>";
		else
			$html.="<tr><td valign='center'>{$good_type['name']}</td><td>";
		$need='';
		if ($good_type['need']) $need="data-need='true'";
        switch($good_type['type'])
		{
			case 's':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<input id='prop_{$good_type['id']}' name='{$good_type['id']}' type='text' style='width:100%;' value='$v' $need>";
				break;
			case 'd':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<input id='prop_{$good_type['id']}' name='{$good_type['id']}' type='text' style='width:100%;' value='$v' data-type='d' $need>";
				break;
			case 't':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<textarea id='prop_{$good_type['id']}' name='{$good_type['id']}' style='width:100%;' $need>$v</textarea>";
				break;
			case 'e':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<select id='prop_{$good_type['id']}' name='{$good_type['id']}' size='1' $need>";
				$opt=explode('|', $good_type['options']);
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
			case 'i':
				$html.="<div class='admin_input' style='width:100%;' id='good_images_box_$id'>";
                $html.=goods_images_block_html($id, $good_type);
				$html.="</div>";
				break;
			case 'c':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$ch='';
				if ($v==1) $ch='checked';
				$html.="<input id='prop_{$good_type['id']}' name='{$good_type['id']}' type='checkbox' class='prop_img_checkbox' value='1' $ch>";
				break;
			case 'do':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<select id='prop_{$good_type['id']}' name='{$good_type['id']}' size='1' $need>";
				$res=query("select * from $_cms_directories_data where dir='{$good_type['options']}'");
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
                $resV=query("select value from $_cms_tree_node_details where node='$id' and typeId='{$good_type['id']}'");
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
<input type="hidden" id="prop_{$good_type['id']}" name="{$good_type['id']}" value="$hval"><div class="goods_property_dir_values" id="propt_{$good_type['id']}" onClick="goods_property_dir_select({$good['type']}, '{$good_type['id']}')">
stop;
				if (count($val))
				{
					foreach($val as $v)
					{
						$name=get_data('content', $_cms_directories_data, "id='$v'");
//						$p=strpos($name, ':::');
//						if ($p!==false) $name=trim(substr($name, $p+3));
						$html.="$name, ";
					}
					$html=substr($html, 0, -2);
				}
				else
					$html.='выберите значения';
				$html.='</div>';
				break;
			case 'oo':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<select id='prop_{$good_type['id']}' name='{$good_type['id']}' size='1' $need>";
				$res=query("select * from $_cms_objects_table where type='{$good_type['options']}' order by name");
				if ($v=='') $s=" selected='selected'";
				$html.="<option value=''$sl></option>";
                while($r=mysql_fetch_assoc($res))
				{
					$sl='';
					if ($r['id']==$v) $sl=" selected='selected'";
					$html.="<option value='{$r['id']}'$sl>{$r['name']}</option>";
				}
				mysql_free_result($res);
				$html.="</select>";
				break;
			case 'ff':
				$vals=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.=<<<stop
<input type="hidden" id="prop_{$good_type['id']}" name="{$good_type['id']}" value="$vals"><div class="goods_property_dir_values" id="propt_{$good_type['id']}" onClick="goods_property_features_select({$good['type']}, '{$good_type['id']}')">
stop;
				$dirs_val=parse_good_features($vals);
				if (count($dirs_val))
				{
					foreach($dirs_val as $dir_id=>$dir)
					{
						$name=get_data('name', $_cms_directories, "id='$dir_id'");
						$html.="$name, ";
					}
					$html=substr($html, 0, -2);
				}
				else
					$html.='выберите значения';
				$html.='</div>';
				break;
		}
		$html.='</td></tr>';
	}
//	$html=str_replace("\r\n", '', $html);
	$html.=<<<stop
</table><br>
</div>
<input type="button" value="Сохранить изменения" onClick="goods_save_good_edit($id)">
<script type="text/javascript">
    CKEDITOR.config.height= '200px';
	CKEDITOR.config.format_tags = 'p';
	if (text_editor) CKEDITOR.remove(text_editor);
	text_editor=CKEDITOR.replace('good_description');
	text_editor.on( 'instanceReady', function( e ){ admin_info_center() } );
</script>
stop;
	return $html;
}
//------------------------------------------------------------------------------
// Удалить
/*
function goods_get_good_details_block_html($id, $type)
{
	global $_cms_good_types, $_cms_tree_node_details, $_cms_directories, $_cms_directories_data, $_admin_js_url;
	global $_cms_objects_table;

	$html='<tr><td colspan=\'2\'><h3>Характеристики товара</h3></td></tr>';
	$good_description=goods_get_good_description($type);
	foreach($good_description['details'] as $good_type)
	{
        $good_type['type']=strtolower($good_type['type']);
		if ($good_type['type']=='c')
			$html.="<tr><td valign='bottom'>{$good_type['name']}</td><td>";
		elseif ($good_type['type']=='i' || $good_type['type']=='t')
			$html.="<tr><td valign='top'>{$good_type['name']}</td><td>";
		else
			$html.="<tr><td valign='center'>{$good_type['name']}</td><td>";
		$need='';
		if ($good_type['need']) $need="data-need='true'";
        switch($good_type['type'])
		{
			case 's':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<input id='prop_{$good_type['id']}' name='{$good_type['id']}' type='text' style='width:100%;' value='$v' $need>";
				break;
			case 'd':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<input id='prop_{$good_type['id']}' name='{$good_type['id']}' type='text' style='width:100%;' value='$v' data-type='d' $need>";
				break;
			case 't':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<textarea id='prop_{$good_type['id']}' name='{$good_type['id']}' style='width:100%;' $need>$v</textarea>";
				break;
			case 'e':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<select id='prop_{$good_type['id']}' name='{$good_type['id']}' size='1' $need>";
				$opt=explode('|', $good_type['options']);
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
			case 'i':
				$html.="<div class='admin_input' style='width:100%;' id='good_images_box_$id'>";
                $html.=goods_images_block_html($id, $good_type);
				$html.="</div>";
				break;
			case 'c':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$ch='';
				if ($v==1) $ch='checked';
				$html.="<input id='prop_{$good_type['id']}' name='{$good_type['id']}' type='checkbox' class='prop_img_checkbox' value='1' $ch>";
				break;
			case 'do':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<select id='prop_{$good_type['id']}' name='{$good_type['id']}' size='1' $need>";
				$res=query("select * from $_cms_directories_data where dir='{$good_type['options']}'");
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
                $resV=query("select value from $_cms_tree_node_details where node='$id' and typeId='{$good_type['id']}'");
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
<input type="hidden" id="prop_{$good_type['id']}" name="{$good_type['id']}" value="$hval"><div class="goods_property_dir_values" id="propt_{$good_type['id']}" onClick="goods_property_dir_select($type, '{$good_type['id']}')">
stop;
				if (count($val))
				{
					foreach($val as $v)
					{
						$name=get_data('content', $_cms_directories_data, "id='$v'");
//						$p=strpos($name, ':::');
//						if ($p!==false) $name=trim(substr($name, $p+3));
						$html.="$name, ";
					}
					$html=substr($html, 0, -2);
				}
				else
					$html.='выберите значения';
				$html.='</div>';
				break;
			case 'oo':
                $v=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.="<select id='prop_{$good_type['id']}' name='{$good_type['id']}' size='1' $need>";
				$res=query("select * from $_cms_objects_table where type='{$good_type['options']}' order by name");
				if ($v=='') $s=" selected='selected'";
				$html.="<option value=''$sl></option>";
                while($r=mysql_fetch_assoc($res))
				{
					$sl='';
					if ($r['id']==$v) $sl=" selected='selected'";
					$html.="<option value='{$r['id']}'$sl>{$r['name']}</option>";
				}
				mysql_free_result($res);
				$html.="</select>";
				break;
			case 'ff':
				$vals=get_data('value', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
				$html.=<<<stop
<input type="hidden" id="prop_{$good_type['id']}" name="{$good_type['id']}" value="$vals"><div class="goods_property_dir_values" id="propt_{$good_type['id']}" onClick="goods_property_features_select($type, '{$good_type['id']}')">
stop;
				$dirs_val=parse_good_features($vals);
				if (count($dirs_val))
				{
					foreach($dirs_val as $dir_id=>$dir)
					{
						$name=get_data('name', $_cms_directories, "id='$dir_id'");
						$html.="$name, ";
					}
					$html=substr($html, 0, -2);
				}
				else
					$html.='выберите значения';
				$html.='</div>';
				break;
		}
		$html.='</td></tr>';
	}
	$html=str_replace("\r\n", '', $html);
	return $html;
}
*/
//------------------------------------------------------------------------------
function goods_images_block_html($id, $good_type)
{
	global $_cms_tree_node_details, $_admin_js_url;
	$html='';
	$cnt=get_data('count(*)', $_cms_tree_node_details, "node='$id' and typeId='{$good_type['id']}'");
	$html.="<input type='hidden' id='goods_good_images_counter' value='$cnt'><span style='font-size:10px; color: #f55;'>* Будьте внимательны. Операции связанные с картинками нельзя отменить!!!</span><br>";
	$html.="<table>";
	if ($cnt)
	{
		$res=query("select * from $_cms_tree_node_details where node='$id' and typeId='{$good_type['id']}' order by id");
		$cc=0;
		while ($r=mysql_fetch_assoc($res))
		{
			if (!$cc) $html.='<tr>';
			$html.="<td valign='bottom' align='center' class='admin_input' id='good_image_cell_{$r['id']}'><a class='thumbnail' href='/get_image.php?type=i&f={$r['value']}'><img src='/get_image.php?type=t&f={$r['value']}'></a><br><input type='button' class='admin_tool_button' value='Удалить' onClick='goods_good_delete_image($id, {$r['id']}, \"{$good_type['id']}\")'></td>";
			$cc++;
			if ($cc==3)
			{
				$cc=0;
				$html.='</tr>';
			}
		}
		if ($cc) $html.=str_repeat('<td>&nbsp;</td>', 3-$cc).'</tr>';
	}

	$html.=<<<stop
</table>
<script type="text/javascript">
$(document).ready(function(){
	$('a.thumbnail').colorbox();
});
</script>
stop;
	if ($cnt<$good_type['limit'])
		$html.=<<<stop
<input type="hidden" id="typeId_{$id}" value="{$good_type['id']}">
<input type="hidden" id="limit_{$id}" value="{$good_type['limit']}">
<input type="button" value="Загрузить изображение" onClick="goods_edit_load_image()">
stop;
	else
		$html.=<<<stop
<span style='font-size:10px; color: #f55;'>Загружено максимальное число картинок для этого товара.</span>
stop;
	return $html;
}
//------------------------------------------------------------------------------
function goods_get_dir_values_html($type, $id, $vals, $func)
{
	global $_cms_good_types, $_cms_directories_data;

	$gt=goods_get_good_description($type);
	$prop=goods_get_detail_description($gt['details'], $id);

	if ($prop=='') return;
	$html=<<<stop
<h2>{$prop['name']}</h2>
stop;
	$vals=explode('|', $vals);
	$res=query("select * from $_cms_directories_data where dir='{$prop['options']}' order by content");
	$section='';
	while($r=mysql_fetch_assoc($res))
	{
		if (in_array($r['id'], $vals)) $ch='checked="checked"';
		else $ch='';
		$p=strpos($r['content'], ':::');
		if ($p!==false)
		{
			$s=substr($r['content'], 0, $p);
			if ($section!=$s)
			{
				$html.="<div class='goods_dir_select_section'>$s</div>";
				$section=$s;
			}
			$r['content']=trim(substr($r['content'], $p+3));
		}
		elseif ($section!='')
		{
			$section='';
			$html.='<div class="goods_dir_select_section_null"></div>';
		}
		$html.=<<<stop
<div class="goods_dir_select_node">
<input type="checkbox" id="dir_m_{$r['id']}" name="dir_m_{$r['id']}" value="1" $ch/> {$r['content']}
</div>
stop;
	}
	mysql_free_result($res);
	$html.=<<<stop
<hr>
<input type="button" value="Сохранить" onClick="$func('$id')"/>
<script type="text/javascript">
$("input[type=checkbox][id ^= 'dir_m_']").imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent: true});
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function goods_dir_values_save($id, $props)
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
//		$p=strpos($val, ':::');
//		if ($p!==false) $val=trim(substr($val, $p+3));
		$text.="$val, ";
	}
	$data=substr($data, 0, -1);
	$text=substr($text, 0, -2);
	if ($text=='') $text='выберите значения';
	return serialize_data('data|text', $data, $text);
}
// -----------------------------------------------------------------------------
function goods_get_features_values_html($type, $id, $vals, $func)
{
	global $_cms_good_types, $_cms_shop_directories, $_cms_directories_data;

	$gt=goods_get_good_description($type);
	$prop=goods_get_detail_description($gt['details'], $id);

	if ($prop=='') return;
	$html='';
	$dirs=explode(',', $prop['options']);
	$dirs_val=parse_good_features($vals);

	foreach($dirs as $dir)
	{
		$dir_id=trim($dir);
		$dir_name=get_data('name', $_cms_shop_directories, "id='$dir_id'");
		$html.="<h2>$dir_name</h2>";

		$res=query("select * from $_cms_directories_data where dir='$dir_id' order by content");
		while($r=mysql_fetch_assoc($res))
		{
			if (array_key_exists($dir_id, $dirs_val) && array_key_exists($r['id'], $dirs_val[$dir_id])) { $ch='checked="checked"'; $pv=$dirs_val[$dir_id][$r['id']]; }
			else { $ch=''; $pv=''; }
			$html.=<<<stop
<div class="goods_dir_select_node">
<input type="checkbox" id="dir_fc_{$r['id']}" name="dir_fc_{$r['id']}" value="1" $ch/> <input type="text" id="dir_fv_{$r['id']}" name="dir_fv_{$r['id']}" value="$pv"/> {$r['content']}
</div>
stop;
		}
		mysql_free_result($res);
	}

	$html.=<<<stop
<hr>
<input type="button" value="Сохранить" onClick="$func('$id')"/>
<script type="text/javascript">
$("input[type=checkbox][id ^= 'dir_fc_']").imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent: false});
$("input[type=checkbox][id ^= 'dir_fv_']").imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent: false});
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function goods_features_values_save($id, $props_c, $props_v)
{
	global $_cms_shop_directories, $_cms_directories_data;

   	parse_str($props_c, $flags);
   	parse_str($props_v, $values);
	$data='';
	$text='';
	$dirs_val=array();
	foreach($flags as $k=>$v)
	{
        $id=substr($k, 7);
		$dir_id=get_data('dir', $_cms_directories_data, "id='$id'");
        if (array_key_exists("dir_fv_$id", $values) && $values["dir_fv_$id"]!='') $pv=$values["dir_fv_$id"];
		else $pv='0';
		$dirs_val[$dir_id][$id]=$pv;
	}

	foreach($dirs_val as $dir_id=>$dir)
	{
		$text.=get_data('name', $_cms_shop_directories, "id='$dir_id'").', ';
		$data.="|$dir_id";
        foreach($dir as $d=>$v)
			$data.="[$d,$v]";
	}
	$data=substr($data, 1);
	$text=substr($text, 0, -2);
	if ($text=='') $text='выберите значения';
	return serialize_data('data|text', $data, $text);
}
// -----------------------------------------------------------------------------
?>
