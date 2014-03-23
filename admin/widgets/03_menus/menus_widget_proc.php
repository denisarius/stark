<?php
//------------------------------------------------------------------------------
function menus_get_menu_subtree($id)
{
	global $_cms_menus_items_table;
	$cat=get_data_array('name, parent', $_cms_menus_items_table, "id='$id'");
	$tree="<u>{$cat['name']}</u>";
	while($cat['parent'])
	{
		$cat=get_data_array('*', $_cms_menus_items_table, "id='{$cat['parent']}'");
		$tree="{$cat['name']}<blockquote>$tree</blockquote>";
	}
	$tree="<blockquote>$tree</blockquote>";
	return $tree;
}
//------------------------------------------------------------------------------
function menus_get_menu_item_buttons($id)
{
	if (widget_exists('texts')) $html.=<<<stop
<a href="javascript:window.location.href='texts.php?menu_id=$id'">Связанные тексты</a>
stop;
	if (widget_exists('gallery')) $html.=<<<stop
<a href="javascript:window.location.href='gallery.php?menu_item=$id'">Связанные галереи</a>
stop;
	if (widget_exists('objects')) $html.=<<<stop
<a href="javascript:window.location.href='objects.php?menu_id=$id'">Связанные объекты</a>
stop;
	return $html;
}
//------------------------------------------------------------------------------
function menus_change_menu_items_sort($sort, $menu)
{
	global $_cms_menus_items_table;

	$items=explode(',', $sort);
	for($i=0; $i<count($items); $i++)
		$items[$i]=preg_replace('/^\D+(\d+)\D*/', '$1', $items[$i]);
	for($i=0; $i<count($items); $i++)
		query("update $_cms_menus_items_table set sort='$i' where id='{$items[$i]}' and menu='$menu'");
}
//------------------------------------------------------------------------------
function menus_get_menus_items_group($menu, $parent, $current, $prefix)
{
	global $_cms_menus_items_table;

	$initial_current='';
	$html_header='<ul class="sortable_menu">';
	$res=query("select * from $_cms_menus_items_table where menu='$menu' and parent=$parent order by sort, id");
	$i=1;
	$html_inner='';
	while($r=mysql_fetch_assoc($res))
	{
        if ($current==-1)
		{
			$current=$r['id'];
			$initial_current="<input type='hidden' id='menu_items_initial_current' value='$current'>";
		}
    		$html_inner.= <<<stop
<li id="li-{$r['id']}"><span class="cms_menu_items_list_prefix">$prefix$i.</span> <a onMouseDown="menus_menu_item_list_select({$r['id']});"><span id="name-{$r['id']}">{$r['name']}</span></a></li>
stop;
    	$html_inner.=menus_get_menus_items_group($menu, $r['id'], $current, "$prefix$i.");
		$i++;
	}
	mysql_free_result($res);
	$html_footer='</ul>';
	if ($html_inner=='') $html='';
	else $html=$html_header.$html_inner.$html_footer;
	if ($initial_current!='') $html.=$initial_current;
    return $html;
}
//------------------------------------------------------------------------------
function menus_get_menu_edit_name_html($id)
{
	global $_cms_menus_table;
	$name=get_data('name', $_cms_menus_table, "id='$id'");
	if ($name===false) return '';
	echo <<<stop
<b>Название раздела:</b><br>
<input type="text" value="$name" id="menus_menu_edit_name" style="width: 90%">
<br><br>
<input type='button' class='admin_tool_button admin_tool_ok_button' value='Записать' onClick="menus_edit_menu_save('$id')">
<input type='button' class='admin_tool_button admin_tool_cancel_button' value='Отменить' onClick="admin_info_close()">
stop;
}
//------------------------------------------------------------------------------
/*
function menus_get_detail_frame($menu_item_id)
{
	global $_cms_menus_items_table;

	$menu_item=get_data_array('*', $_cms_menus_items_table, "id='$menu_item_id'");
	$menu_item['name']=htmlspecialchars($menu_item['name'], ENT_QUOTES);
	$subtree=htmlspecialchars(menus_get_menu_subtree($menu_item_id), ENT_QUOTES);
	if (strlen($menu_item['name'])<50)
		$ed=<<<stop
<input type="text" id="menus_menu_item_name" onkeypress="return menus_item_data_edit_keypress(event);" value="{$menu_item['name']}" style="width:322px;">
stop;
	else
		$ed=<<<stop
<textarea id="menus_menu_item_name" style="width:322px;" onkeypress="return menus_item_data_edit_keypress(event);">{$menu_item['name']}</textarea>
stop;
	$vis_ch='';
	if ($menu_item_id!=-1 && $menu_item['visible']==0) $vis_ch='checked="checked"';

	echo <<<stop
<div id="menus_menu_item_detail" class="cms_menus_menu_item_detail">
<h1>Редактирование подраздела</h1><br>

<h3>ID:</h3>
<div id="menu_item_id_label">{$menu_item['id']}</div>

<h3>Название:</h3>
<div id="menu_item_name_edit_control">$ed</div>

<h3>URL:</h3>
<div id="menu_item_url_edit_control"><input type="text" id="menus_menu_item_url" onkeypress="return menus_item_data_edit_keypress(event);" value="{$menu_item['url']}" style="width:322px;"></div>

<br><h3>Скрыть: <input type="checkbox" id="menus_menu_item_visible" $vis_ch></h3>

<br><input type="button" value="Сохранить" id="menu_item_data_edit_save" onClick="menus_item_data_edit_save();">
</div>

<script type="text/javascript">
$(window).scroll(function() {
	if ($("#menus_menu_item_detail").css("margin-top")!=0)
    	$("#menus_menu_item_detail").css("margin-top", $(window).scrollTop());
});
</script>
stop;
}
*/
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
?>