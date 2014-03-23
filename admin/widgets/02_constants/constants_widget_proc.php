<?php
//------------------------------------------------------------------------------
function constants_get_list_html()
{
	global $_cms_constants_table;

	$html='';
	$res=query("select * from $_cms_constants_table");
	while($r=mysql_fetch_assoc($res))
		$html.=<<<stop
<div id="constants_constants_list_row_{$r['id']}">
<span onClick="constants_constant_edit({$r['id']})">{$r['name']}</span>
<span onClick="constants_constant_edit({$r['id']})">{$r['value']}</span>
</div>
stop;
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
function constants_get_list_item_html($id)
{
	global $_cms_constants_table;

	$const=get_data_array('*', $_cms_constants_table, "id='$id'");
	if ($const!==false)
		return <<<stop
<span onClick="constants_constant_edit($id)">{$const['name']}</span>
<span onClick="constants_constant_edit($id)">{$const['value']}</span>
stop;
	else
		return '';
}
//------------------------------------------------------------------------------
function constants_get_constant_edit_block_html($id)
{
	global $_cms_constants_table;

	$const=get_data_array('*', $_cms_constants_table, "id='$id'");
	if ($const===false) return '';
	$html=<<<stop
<input type="hidden" id="constants_edit_constant_name" value="{$const['name']}">
<table>
<tr><td style='width:130px;'><b>Название константы:</b></td>
<td>{$const['name']}</td></tr>
<tr><td valign='top'><b>Значение константы:</b></td>
<td><textarea id='constants_edit_constant_value' style='width:515px;' row='2'>{$const['value']}</textarea></td></tr>
</table>
<input type='button' value='Сохранить изменения' style='margin-right: 10px;' onClick='constants_constant_edit_save()'>
<input type='button' value='Отмена' onClick='constants_constant_edit_cancel()'>
<input type='button' value='Удалить' style='float:right;' onClick='constants_constant_edit_delete()'>
stop;
	return $html;
}
//------------------------------------------------------------------------------
?>
