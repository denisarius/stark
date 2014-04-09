<?php
//------------------------------------------------------------------------------
function dirs_get_edit_dir_html($id)
{
	global $html_charset, $_cms_directories;

	$name='';
	if($id!=-1) $name=htmlentities(get_data('name', $_cms_directories, "id='$id'"), ENT_QUOTES, $html_charset);
	$html=<<<stop
<b>Название справочника</b><br>
<input type="text" id="dirs_dir_name" value="$name" style="width: 98%;"/>
<br><br>
<input type="button" value="Сохранить" onClick="dirs_dir_data_save($id)" />
stop;
	return $html;
}
//------------------------------------------------------------------------------
function dirs_get_dirs_html($id)
{
	global $_cms_directories;
	$html='';
	$res=query("select * from $_cms_directories order by name");
	while($r=mysql_fetch_assoc($res))
	{
		if ($r['id']==$id) $sl=' selected="selected"';
		else $sl='';
		$html.=<<<stop
<option value="{$r['id']}" $sl>{$r['name']} [ID={$r['id']}]</option>
stop;
	}
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
function dirs_get_dir_list_node($r)
{
	global $_cms_menus_items_table, $html_charset;

	if (!$r['linked']) $link='';
	else
	{
		$menu=get_data('name', $_cms_menus_items_table, "id='{$r['linked']}'");
		$link="<span>==> '$menu'</span>";
	}
	$content=htmlentities($r['content'], ENT_QUOTES, $html_charset);
	$html=<<<stop
<div class="dirs_value_node" id="dirs_value_node_{$r['id']}">
<div class="dirs_value_name" id="dirs_dir_value_{$r['id']}">{$content}$link</div>
<div class="dirs_value_buttons">
<img src="images/options_24.png" style="margin-right: 10px;" onClick="dirs_edit_value({$r['id']})" />
<img src="images/delete_24.png"  onClick="dirs_delete_value({$r['id']})" />
</div><br>
</div>
stop;
	return $html;
}
//------------------------------------------------------------------------------
function dirs_get_dir_list_html($id)
{
	global $_cms_directories_data;

	$html=<<<stop
<hr style="margin-bottom: 10px;">
<input type="button" value="Добавить значение" onClick="dirs_edit_value(-1)" style="margin: 10px 20px 0 0;"/>
<input type="button" value="Добавить значения списком" onClick="dirs_add_list_values()" style="margin: 10px 20px 0 0;"/>
stop;
	$res=query("select * from $_cms_directories_data where dir='$id' order by content");
	while($r=mysql_fetch_assoc($res))
		$html.=dirs_get_dir_list_node($r);
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
function dirs_save_dir_data($id, $name)
{
	global $_cms_directories;

	if ($id==-1)
	{
		$i=get_data('id', $_cms_directories, "name='$name'");
		if ($i===false)
		{
			query("insert into $_cms_directories (name) values ('$name')");
			$id=mysql_insert_id();
			return serialize_data('error|dirs|dir_content', '', dirs_get_dirs_html($id), dirs_get_dir_list_html($id));
		}
		else
			return serialize_data('error|dirs|dir_content', 'Справочник с таким названием уже существует', '', '');
	}
	else
	{
		$i=get_data('id', $_cms_directories, "name='$name' and id!='$id'");
		if ($i===false)
		{
			query("update $_cms_directories set name='$name' where id='$id'");
			return serialize_data('error|dirs|dir_content', '', dirs_get_dirs_html($id), dirs_get_dir_list_html($id));
		}
		else
			return serialize_data('error|dirs|dir_content', 'Существует другой справочник с таким названием', '', '');
	}
}
//------------------------------------------------------------------------------
function dirs_get_edit_value_html($dir_id, $val_id)
{
	global $_cms_directories, $_cms_directories_data, $_cms_menus_items_table, $html_charset;

	$dir=get_data('name', $_cms_directories, "id='$dir_id'");
	if ($val_id==-1) $val=array('content'=>'', 'linked'=>0);
	else $val=get_data_array('content, linked', $_cms_directories_data, "dir='$dir_id' and id='$val_id'");
	if ($val['linked']==0) $link=array('name'=>'', 'id'=>0);
	else $link=get_data_array('id, name', $_cms_menus_items_table, "id='{$val['linked']}'");
	$content=htmlentities($val['content'], ENT_QUOTES, $html_charset);
	$html=<<<stop
<b>Cправочник: $dir</b><br><br>
<b>Значение</b><br>
<input type="text" id="dirs_value_content" value="{$content}" style="width: 98%;"/>
<div class="dirs_link_menu_block">
<input type="hidden" id="dirs_link_to_menu_id" value="{$link['id']}" />
<input type="button" class="admin_tool_button" value="Связать с разделом" onClick="dirs_link_to_menu()" />
<div id="dirs_link_menu_name">{$link['name']}</div>
<br>
</div>
<input type="button" value="Сохранить" onClick="dirs_dir_value_save($val_id)" />
stop;
	return $html;
}
//------------------------------------------------------------------------------
function dirs_save_dir_value($dir_id, $val_id, $val, $menu_id)
{
	global $_cms_directories_data;

	if ($val_id==-1)
	{
		$i=get_data('id', $_cms_directories_data, "content='$val' and dir='$dir_id'");
		if ($i===false)
		{
			$note="[$val][$dir_id]";
			query("insert into $_cms_directories_data (dir, content, linked) values ('$dir_id', '$val', '$menu_id')");
			$id=mysql_insert_id();
			$r=get_data_array('*', $_cms_directories_data, "id='$id'");
			$node=dirs_get_dir_list_node($r);
			return serialize_data('error|node|note', '', $node, $note);
		}
		else
			return serialize_data('error|node|note', 'В справочнике уже есть такое значение', '', $note);
	}
	else
	{
		$i=get_data('id', $_cms_directories_data, "content='$val' and dir='$dir_id' and id!='$val_id'");
		if ($i===false)
		{
			query("update $_cms_directories_data set content='$val', linked='$menu_id' where id='$val_id'");
			$r=get_data_array('*', $_cms_directories_data, "id='$val_id'");
			return serialize_data('error|node|note', '', dirs_get_dir_list_node($r), $note);
		}
		else
			return serialize_data('error|node|note', 'В справочнике уже есть такое значение', '', $note);
	}
}
//------------------------------------------------------------------------------
function dirs_add_items_list($dir_id, $vals)
{
	global $html_charset, $_cms_directories_data;

	$vals=explode('\n', $vals);
	foreach($vals as $v)
	{
		$v=trim($v);
		if ($v=='') continue;
		$v=mysql_safe(iconv ('utf-8', $html_charset, $v));
        $id=get_data('id', $_cms_directories_data, "content='$v' and dir='$dir_id'");
		if ($id===false) query("insert into $_cms_directories_data (dir, content) values ('$dir_id', '$v')");
	}
}
//------------------------------------------------------------------------------
?>