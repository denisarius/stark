<?php
//------------------------------------------------------------------------------
function shop_get_goods_details($id, $type, $count=1, $indexes=false)
{
	global $_cms_tree_node_details;
	if ($count==1)
	{
		$val=get_data_array('value, type', $_cms_tree_node_details, "node='$id' and typeId='$type' order by id");
		if (!$indexes) $val['value']=shop_get_good_detail_value($val);
		return $val['value'];
	}
	else
	{
		$details=array();
	    $res=query("select value, type from $_cms_tree_node_details where node='$id' and typeId='$type' order by id");
		while($r=mysql_fetch_assoc($res))
		{
			if (!$indexes) $r['value']=shop_get_good_detail_value($r);
			array_push($details, $r['value']);
		}
		mysql_free_result($res);
		return $details;
	}
}
//------------------------------------------------------------------------------
function shop_get_good_detail_value($val)
{
	global $_cms_directories_data, $_cms_objects_table;

	switch($val['type'])
	{
		default: return $val['value'];
		case 'dm':
		case 'do':
			return get_data('content', $_cms_directories_data, "id='{$val['value']}'");
		case 'oo':
			return get_data('name', $_cms_objects_table, "id='{$val['value']}'");
	}
}
//------------------------------------------------------------------------------
function shop_get_goods_parent_condition($parent)
{
	$list=shop_get_child_nodes($parent);
	if (strlen($list)) $list=substr($list, 0, -2);
	return "parent in ($list)";
}
//------------------------------------------------------------------------------
function get_expanded_section($current)
{
	global $_cms_menus_items_table, $_shop_menu_id;

	if ($current==-1) return array();
	if ($current)
	{
		$item['parent']=$current;
		do {
		$item=get_data_array('*', $_cms_menus_items_table, "id='{$item['parent']}'");
		} while($item['parent']!=0);
		return array_unique(array_merge(shop_get_child_nodes_list($item['id']), array($item['id'])));
	}
	else
	{
		$list=array();
		$res=query("select id from $_cms_menus_items_table where menu=$_shop_menu_id and parent=0");
	    while($r=mysql_fetch_assoc($res))
			array_push($list, $r['id']);
		return $list;
	}
}
//------------------------------------------------------------------------------
function shop_get_child_nodes_list($id)
{
	global $_cms_menus_items_table;

	$list=array();
	$res=query("select id from $_cms_menus_items_table where parent=$id");
    while($r=mysql_fetch_assoc($res))
	{
		array_push($list, $r['id']);
		$list=array_merge($list, shop_get_child_nodes_list($r['id']));
	}
	return $list;
}
//------------------------------------------------------------------------------
function shop_get_child_nodes($parent, $self=true)
{
	global $_cms_menus_items_table;
	$res=query("select id from $_cms_menus_items_table where parent=$parent");
	if ($self) $list="$parent, ";
	else $list='';
    while($r=mysql_fetch_assoc($res))
	{
		$list.="{$r['id']}, ";
		$list.=shop_get_child_nodes($r['id'], false);
	}
	return $list;
}
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
?>