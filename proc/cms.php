<?php
//------------------------------------------------------------------------------
function cms_get_dir_value($id)
{
	global $_cms_directories_data;
	return get_data('content', $_cms_directories_data, "id='$id'");
}
//------------------------------------------------------------------------------
function cms_get_object_description($type)
{
	global $_cms_objects_types;

	foreach($_cms_objects_types as $desc)
		if (strtolower($desc['id'])==strtolower($type)) return $desc;
	return false;
}
//------------------------------------------------------------------------------
function cms_get_object_detail($type, $id)
{
	$object_description=cms_get_object_description($type);
	if ($object_description===false) return false;
	$prop='';
	foreach($object_description['details'] as $d)
		if ($d['id']==$id) $prop=$d;
	if ($prop=='') return false;
	return $prop;
}
//------------------------------------------------------------------------------
function cms_get_objects_details($objType, $id, $type, $count=1, $indexes=false)
{
	global $_cms_objects_details;

	$descr=cms_get_object_detail($objType, $type);
	if ($descr===false) return '';
	if ($count==1)
	{
		$val=get_data_array('value, id', $_cms_objects_details, "node='$id' and typeId='$type' order by id");
		if (!$indexes && $val!==false) $val['value']=cms_get_object_detail_value($descr, $val['value'], $val['id']);
		return $val['value'];
	}
	else
	{
		$details=array();
	    $res=query("select value, id from $_cms_objects_details where node='$id' and typeId='$type' order by id");
		while($r=mysql_fetch_assoc($res))
		{
            switch($descr['type'])
			{
            	case 'dm':
				case 'tb':
				case 'st':
					if (!$indexes) $r['value']=cms_get_object_detail_value($descr, $r['value'], $r['id']);
					array_push($details, $r['value']);
					break;
				default:
					$inds=explode('|', $r['value']);
					foreach($inds as $idx)
					{
						if (!$indexes) $idx=cms_get_object_detail_value($descr, $idx);
						array_push($details, $idx);
					}
					break;
			}
		}
		mysql_free_result($res);
		return $details;
	}
}
//------------------------------------------------------------------------------
function cms_set_objects_details($objType, $id, $type, $val)
{
	global $_cms_objects_details;

	$descr=cms_get_object_detail($objType, $type);
	if ($descr===false) return '';
	query("delete from $_cms_objects_details where node='$id' and typeId='$type'");
	if (!is_array($val))
		query("insert into $_cms_objects_details (node, typeId, type, value) values ($id, '$type', '{$descr['type']}', '$val')");
	else
	{
		foreach($val as $v)
			query("insert into $_cms_objects_details (node, typeId, type, value) values ($id, '$type', '{$descr['type']}', '$v')");
	}
}
//------------------------------------------------------------------------------
function cms_get_object_detail_value($descr, $val, $id=0)
{
	global $_cms_directories_data;

    switch($descr['type'])
	{
		case 'st':
			return cms_get_object_details_structured_text($id);
		case 'tb':
			return cms_get_object_detail_table_row_array($val);
		case 'dm':
		case 'do':
			return get_data('content', $_cms_directories_data, "id='$val'");
		default:
			return $val;
	}
}
//------------------------------------------------------------------------------
// Сбор фрагментов структурированного текста в массив
function cms_get_object_details_structured_text($id)
{
	global $_cms_text_parts;

	$id=(int)$id;
	$res=query("select * from $_cms_text_parts where node='$id' and visible=1 order by sort, id");
	$text=array();
	while($r=mysql_fetch_assoc($res))
		array_push($text, array('title'=>$r['title'], 'image'=>$r['image'], 'text'=>$r['content'], 'date'=>$r['date']));
	mysql_free_result($res);
	return $text;
}
//------------------------------------------------------------------------------
// Разбор строки таблицы в массив
function cms_get_object_detail_table_row_array($text)
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
//------------------------------------------------------------------------------
// Генерация HTML кода для элемента SELECT на основе справочника
// dir_id		- ID справочника
// id			- ID HTML элемента (<select name="<id>" id="<id>">)
// class		- CSS класс для элемента SELECT
// first_empty	- добавление первого пустого элемента в селект
// init			- ID выбранной строчки
function cms_get_dir_select($dir_id, $id, $class, $first_empty, $init=-1)
{
	global $_cms_directories_data;

	$html="<select id='$id'";
	if ($class!='') $html.=" class='$class'";
	$html.='>';
	if ($first_empty) $html.="<option value='-1'></option>";
    $res=query("select id, content from $_cms_directories_data where dir='$dir_id' order by id");
	while($r=mysql_fetch_assoc($res))
	{
		$sl='';
		if ($r['id']==$init) $sl=" selected='selected'";
		$html.="<option value='{$r['id']}'$sl>{$r['content']}</option>";
	}
	mysql_free_result($res);
	$html.='</select>';
	return $html;
}
//------------------------------------------------------------------------------
// Генерация HTML кода для множественного выбора на основе справочника
// dir_id		- ID справочника
// id			- ID HTML элемента (<select name="<id>" id="<id>">)
// class		- CSS класс для элемента SELECT
// init			- список выбранных ID в виде <id>|<id>|<id>
function cms_get_dir_multiselect($dir_id, $id, $class, $init='')
{
	global $html_charset, $_cms_directories_data;

	$html='<div';
	if ($class!='') $html.=" class='$class'";
	$html.='>';
	$init=explode('|', $init);
    $res=query("select id, content from $_cms_directories_data where dir='$dir_id' order by id");
	while($r=mysql_fetch_assoc($res))
	{
		$sl='';
		if (in_array($r['id'], $init)) $sl=' checked="checked"';
		$html.=<<<stop
<div><input type="checkbox" id="$id" name="$id" value="{$r['id']}"$sl /><label for="$id_{$r['id']}">{$r['content']}</label></div>
stop;
	}
	mysql_free_result($res);
	$html.='</div>';
	return $html;
}
//------------------------------------------------------------------------------
// Генерация HTML кода для элемента SELECT на основе описания объекта
// $obj_type	- тип объекта
// $obj_prop	- название свойства объекта
// id			- ID HTML элемента (<select name="<id>" id="<id>">)
// class		- CSS класс для элемента SELECT
// first_empty	- добавление первого пустого элемента в селект
// init			- ID выбранной строчки
function cms_get_object_enum_select($obj_type, $obj_prop, $id, $class, $first_empty, $init='')
{
	global $html_charset, $_cms_objects_types;

	$html="<select id='$id'";
	if ($class!='') $html.=" class='$class'";
	$html.='>';
	if ($first_empty) $html.="<option value='-1'></option>";
    $prop=cms_get_object_detail($obj_type, $obj_prop);
	$val=explode('|', $prop['options']);
	foreach($val as $v)
	{
		$sl='';
		if ($v==$init) $sl=" selected='selected'";
		$v=htmlspecialchars($v, ENT_QUOTES,$html_charset);
		$html.="<option value='$v'$sl>$v</option>";
	}
	$html.='</select>';
	return $html;
}
//------------------------------------------------------------------------------
function get_url_menu_id($url)
{
	global $_cms_menus_table, $_cms_menus_items_table;

	$menu=false;
	$resM=query("select * from $_cms_menus_table");
	while ($rm=mysql_fetch_assoc($resM))
	{
		$res=query("select * from $_cms_menus_items_table where menu={$rm['id']}");
		while ($r=mysql_fetch_assoc($res))
		{
			if ($r['url']==$url || $r['url']=="$url.html")
				$menu=array('id'=>$rm['id'], 'item'=>$r[id]);
		}
		mysql_free_result($res);
	}
	mysql_free_result($resM);
	return $menu;
}
//------------------------------------------------------------------------------
function cms_shop_delete_good($id)
{
    global $_cms_tree_node_table, $_cms_tree_node_details, $_cms_goods_images_path;
	query("delete from $_cms_tree_node_table where id='$id'");
	$res=query("select * from $_cms_tree_node_details where node='$id' and type='i'");
	while($r=mysql_fetch_assoc($res))
	{
		$file="$_cms_goods_images_path/{$r['value']}";
		$thumb_file="$_cms_goods_images_path/thumbs/{$r['value']}";
		@unlink($file);
		@unlink($thumb_file);
	}
	mysql_free_result($res);
	query("delete from $_cms_tree_node_details where node='$id'");
}
//------------------------------------------------------------------------------
function cms_delete_order($id)
{
	query("delete from orders_goods where `order`='$id'");
	query("delete from orders where id='$id'");
}
//------------------------------------------------------------------------------
?>