<?php
//------------------------------------------------------------------------------
function texts_get_texts_list($menu, $page, $strip_slashes=true)
{
	global $_cms_texts_table, $_cms_texts_admin_list_page_length;

	$start=$page*$_cms_texts_admin_list_page_length;
	$res=query("select SQL_CALC_FOUND_ROWS id, date, title, signature, substring(content, 1, 300) as content from $_cms_texts_table where menu_item='$menu' order by id desc limit $start, $_cms_texts_admin_list_page_length");
	$start=$page*$_cms_texts_admin_list_page_length;
	$total=get_data('FOUND_ROWS()');
	$totalPages=ceil($total/$_cms_texts_admin_list_page_length);
	if ($totalPages>0 and $page>$totalPages-1)
	{
		$page=$totalPages-1;
		$start=$page*$_cms_texts_admin_list_page_length;
		mysql_free_result($res);
		$res=query("select id, date, title, signature, substring(content, 1, 300) as content from $_cms_texts_table where menu_item='$menu' order by id desc limit $start, $_cms_texts_admin_list_page_length");
	}
	$html=<<<stop
<input type="button" onClick="texts_text_edit(-1)" value="Добавить новый текст" style="margin: 10px 0px 10px 0px;">
stop;
	if ($total)
	{
		$html.=get_admin_pager($total, $page, $_cms_texts_admin_list_page_length, 'texts_show_texts_list_page');
		while ($r=mysql_fetch_assoc($res))
			$html.=texts_get_texts_data_block($r, $strip_slashes);
		$html.=get_admin_pager($total, $page, $_cms_texts_admin_list_page_length, 'texts_show_texts_list_page');
	}
	mysql_free_result($res);
	return array('html'=>$html, 'page'=>$page);
}
//------------------------------------------------------------------------------
function texts_get_texts_data_block($r, $strip_slashes)
{
	$anons=str2html(strip_tags(substr($r['content'], 0, 256).' ...'));
	$r['title']=str2html(strip_tags($r['title']));
	if ($strip_slashes)
	{
		$anons=stripslashes($anons);
		$r['title']=stripslashes($r['title']);
	}
	$html=<<<stop
<div class="cms_texts_list_item">
<div class="cms_texts_list_item_title">{$r['date']} [{$r['signature']}] {$r['title']}</div>
$anons
<br><input type="button" class="admin_tool_button" value="Изменить текст" style="float:left; margin-top: 10px;" onClick="texts_text_edit({$r['id']})">
<input type="button" class="admin_tool_button" value="Переместить в другой раздел" style="float:left; margin-top: 10px; margin-left: 10px;" onClick="texts_text_move({$r['id']})">
<input type="button" class="admin_tool_button" value="Удалить текст" style="float:right; margin-top: 10px;" onClick="texts_text_delete({$r['id']})">
<br></div>
stop;
	return $html;
}
//------------------------------------------------------------------------------
function texts_get_text_edit_block_html($id, $menu_item)
{
	global $_admin_js_url, $_cms_texts_table;
	$post=$title=$keywords=$description=$content=$signature=$en='';
    if ($id==-999)
	{
    	$id=get_data('id', $_cms_texts_table, "menu_item='$menu_item'");
		if ($id===false) $id=-1;
		else $post=<<<stop
<input type="button" value="Удалить текст" onClick="texts_text_delete($id)" style="float: right; margin: 10px; 0px 0px 15px;">
<input type="button" value="Переместить в другой раздел" onClick="texts_text_move($id)" style="float: right; margin-top: 10px;">
stop;
	}
	if ($id>=0)
	{
		$text=get_data_array('*', $_cms_texts_table, "id='$id'");
		if ($text!==false)
		{
			$title=str2html(stripslashes($text['title']));
			$keywords=str2html(stripslashes($text['keywords']));
			$description=str2html(stripslashes($text['descr']));
			$content=str2html(stripslashes($text['content']));
			$signature=str2html(stripslashes($text['signature']));
			$en='disabled="disabled"';
		}
	}
	$styleUrl=pmTemplateURL().'/css/texts.css';
	echo <<<stop
<input type="hidden" id="texts_edit_text_id" value="$id">
<iframe id="texts_html_load_iframe" name="texts_html_load_iframe" width="100%" style="display:none" onLoad="texts_load_html_complete()"></iframe>
<div class="cms_texts_edit_block" id="texts_edit_block">
<b>Загрузить HTML файл:</b>
<form action="admin_data_manager.php" target="texts_html_load_iframe" method="POST" enctype="multipart/form-data" id="texts_html_load_form">
<div class="cms_texts_html_file_load_frame">
	<input type="hidden" name="section" value="textsUploadHTML">
	<input type="file" name="html_file" size="29" onChange='texts_load_html_file_change(this.value)'>
	<input type="text" id="text_html_load_file_name">
    <input type="button" class="admin_tool_button cms_texts_html_load_select_button" value="Выбрать">
    <input type="button" class="admin_tool_button cms_texts_html_load_load_button" value="Загрузить" onClick="texts_load_html_submit();" id="texts_load_html_load_button">
	<img src="" id="texts_load_html_wait_icon">
</div></form>
<hr size="1">
<table>
<tr>
<td style="width:120px;"><b>Сигнатура</b></td>
<td><input type="text" id="texts_edit_text_signature" style="width: 100px; text-align: center;" value="$signature" onExit="texts_edit_check_signature();" $en><input type="button" onClick="texts_edit_generate_signature()" class="admin_tool_button" style="margin: -3px 0px 0px 10px;" value="Создать" $en></td>
</tr>
<tr>
<td><b>Заголовок</b></td>
<td><input type="text" id="texts_edit_text_title" style="width: 580px;" value="$title"></td>
</tr>
<tr>
<td valign="top"><b>Ключевые слова<br>(через запятую)</b></td>
<td><textarea id="texts_edit_text_keywords" style="width: 580px; height:40px;">$keywords</textarea></td>
</tr>
<tr>
<td valign="top"><b>Описание текста<br>(description)</b></td>
<td><textarea id="texts_edit_text_description" style="width: 580px; height:60px;">$description</textarea></td>
</tr>
</table>
<textarea id="texts_edit_text_content">$content</textarea>
<input type="button" value="Сохранить изменения" onClick="texts_edit_save_changes()" style="margin-top: 10px;">
$post
</div>
<script type="text/javascript">
	h=$(window).height()-230;
    CKEDITOR.config.height= h+'px';
	CKEDITOR.config.format_tags = 'p;h1;h2;h3';
    CKEDITOR.config.contentsCss = ['$styleUrl'];
	if (text_editor) CKEDITOR.remove(text_editor);
	text_editor=CKEDITOR.replace('texts_edit_text_content');
	text_editor.on( 'instanceReady', texts_scroll_objects_check);
</script>
stop;
}
//------------------------------------------------------------------------------
function texts_parse_loaded_html_file($file)
{
	$list=array('title'=>'', 'html'=>'');

	preg_match('/<title>(.*)<\/title>/i', $file, $t);
	$list['title']=$t[1];
	preg_match_all('/<script(.*)>(.+)<\/script>/isU', $file, $t);
	foreach($t[0] as $chunk)
		$list['html'].="$chunk\r\n";
	preg_match('/<body(.*)>(.+)<\/body>/isU', $file, $t);
	$list['html'].=$t[2];
	$list['html']=str_replace('</script>', '<==script>', $list['html']);
	return $list;
}
//------------------------------------------------------------------------------
?>
