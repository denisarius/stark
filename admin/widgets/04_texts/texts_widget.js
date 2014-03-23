//------------------------------------------------------------------------------
var text_editor=0;
var texts_widget_ajax_manager_url='{@widgets_url@}/{@widget_path@}/{@widget_id@}_widget_dm.php';
var common_widget_ajax_manager_url='{@widgets_url@}/common_widget_dm.php';
//------------------------------------------------------------------------------
function texts_set_cms_simple_mode(simple_mode, main_menu_id, menu_id, menu_item_name)
{
	if (simple_mode==1)
	{
		$("#texts_menu_id").val(main_menu_id);
		$("#texts_menu_selector").css('display', 'none');
		$("#cms_texts_top_spacer").css('height', '10px');
		if (!menu_id)
			texts_menu_select_change(main_menu_id, '');
		else
			texts_menu_item_select_change(menu_id, menu_item_name)
	}
}
//------------------------------------------------------------------------------
function texts_menu_select()
{
    mask_screen(function(){
		res=execQuery(common_widget_ajax_manager_url, {section : "commonGetMenusList", func: 'texts_menu_select_change'});
		if (res!="")
		{
			if (res!='n/a')
			{
				$("#_show_dialog:ui-dialog").dialog("close");
				$("#_show_dialog:ui-dialog").dialog("destroy");
				$("#_show_dialog").attr("title", "Выбор раздела");
				h=$(window).height()*2/3;
				$("#_show_dialog").html("<div style='max-height:"+h+"px; overflow-y:auto;'>"+res+"</div>");
				$("#_show_dialog").dialog({
					width: 600,
					modal: true,
					resizable: false,
				});
			}
			else
				show_message("Выбор раздела", "В данный момент не созданно ни одного раздела.<br><br>Для создания раздела выберите пункт меню 'Разделы'.");
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function texts_menu_select_change(id, name)
{
	$("#texts_menu_id").val(id);
	$("#texts_menu_selector").html(name);
	$("#texts_texts_list").html('');
	$("#texts_menu_item_selector_container").html('<br><div id="texts_menu_item_selector" onClick="texts_menu_item_select('+id+')" class="cms_menu_item_selector">Выберите подраздел</div><div id="texts_go_to_content_block"></div>');
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog("destroy");
	texts_hide_text_tool_panel();
}
//------------------------------------------------------------------------------
function texts_menu_item_select(id)
{
    mask_screen(function(){
		res=execQuery(common_widget_ajax_manager_url, {section : "commonGetMenuItemsList", id : id, func: 'texts_menu_item_select_change'});
		if (res!="")
		{
			if (res!='n/a')
			{
				$("#_show_dialog:ui-dialog").dialog("close");
				$("#_show_dialog:ui-dialog").dialog("destroy");
				$("#_show_dialog").attr("title", "Выбор подраздела");
				h=$(window).height()*2/3;
				$("#_show_dialog").html("<div style='max-height:"+h+"px; overflow-y:auto;'>"+res+"</div>");
				$("#_show_dialog").dialog({
					width: 600,
					modal: true,
					resizable: false,
				});
			}
			else
				show_message("Выбор подраздела", "Этот раздел не содержит ни одного подраздела.<br><br>Для создания подразделов выберите пункт меню 'Разделы'.");
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function texts_menu_item_select_change(menu_item_id, name)
{
	$("#texts_text_page").val('0');
	$("#texts_menu_item_id").val(menu_item_id);
	$("#texts_menu_item_selector").html(name);
	if (menu_item_id!=0 && menu_item_id!='') texts_show_text_list();
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog("destroy");
}
//------------------------------------------------------------------------------
function texts_show_text_list()
{
	menu=$("#texts_menu_item_id").val();
    sm=$("#simple_mode").val();
	if (sm!=1)
	{
		page=$("#texts_text_page").val();
		if (menu!=0 && menu!='')
		{
			res=execQuery(texts_widget_ajax_manager_url, {section : "textsGetTextsList", menu: menu, page : page});
			if (res!='')
			{
		   		res=eval('(' + res + ')');
				$("#texts_texts_list").html(res.html);
				$("#texts_text_page").val(res.page);
			}
			else
				$("#texts_texts_list").html('');
		}
		else
			$("#texts_texts_list").html('');
		texts_hide_text_tool_panel();
	}
	else
	{
		show_wait_image("#texts_texts_list");
		res=execQuery(texts_widget_ajax_manager_url, {section : "textsGetEditBlock", id: -999, menu: menu});
		$("#texts_texts_list").html(res);
		texts_show_text_tool_panel();
	}
    sm=$("#simple_mode").val();
	if (sm!=1)
	{
		if ($("#texts_go_to_content_block").html()=='') $("#texts_go_to_content_block").html('<input type="button" id="texts_go_to_content" class="admin_tool_button cms_menu_selector_button" value="Вернуться к оглавлению" onClick="texts_show_text_list_page(0);">');
	}
}
//------------------------------------------------------------------------------
function texts_show_text_list_page(page)
{
    sm=$("#simple_mode").val();
	$("#texts_text_page").val(page);
    texts_show_text_list();
}
//------------------------------------------------------------------------------
function texts_scroll_objects_check()
{
	dy=$(document).height()-($("#texts_edit_block").offset().top+$("#texts_edit_block").outerHeight())+20;
	div_top=$(window).scrollTop()+$(window).height()-$("#texts_text_edit_tool_panel").outerHeight()-dy;
    $("#texts_text_edit_tool_panel").css({top: div_top});
	left=$("#admin_main_menu").offset().left;
	$("#texts_text_edit_tool_panel").css({left: left});
}
//------------------------------------------------------------------------------
function texts_show_text_tool_panel()
{
//	left=$("#admin_main_menu").offset().left;
//	$("#texts_text_edit_tool_panel").css({left: left, display: 'block'});
	texts_scroll_objects_check();
	$("#texts_text_edit_tool_panel").css({display: 'block'});
}
//------------------------------------------------------------------------------
function texts_hide_text_tool_panel()
{
	$("#texts_text_edit_tool_panel").css({display: 'none'});
}
//------------------------------------------------------------------------------
function texts_text_edit(id)
{
	show_wait_image("#texts_texts_list");
	res=execQuery(texts_widget_ajax_manager_url, {section : "textsGetEditBlock", id: id});
	$("#texts_texts_list").html(res);
	texts_show_text_tool_panel();
}
//------------------------------------------------------------------------------
function texts_edit_save_changes()
{
	signature=$("#texts_edit_text_signature").val().trim();
	title=$("#texts_edit_text_title").val().trim();
	kw=$("#texts_edit_text_keywords").val().trim();
	descr=$("#texts_edit_text_description").val().trim();
	html=text_editor.getData();
	id=$("#texts_edit_text_id").val();
	menu=$("#texts_menu_item_id").val();
	if (signature=='')
	{
		alert("Нужно указать сигнатуру для статьи")
		return;
	}
	if (title.length<1)
	{
		alert("Не введено название текста");
		return;
	}
	if (html.length<10)
	{
		alert("Текст должен содержать не менее 10 символов");
		return;
	}
	res=execQuery(texts_widget_ajax_manager_url, {section : "textCheckSignatureUnique", id: id, signature: signature});
	if (res=='no')
	{
		show_message("Запись текста", "Сигнатура должна быть уникальной")
		return;
	}
	show_wait_image("#texts_edit_block");
	res=execQuery(texts_widget_ajax_manager_url, {section : "textsEditTextSave", id: id, menu: menu, signature: signature, title: title, kw: kw, descr: descr, content: html});
    $("#texts_edit_block").html(res);
	texts_hide_text_tool_panel();
}
//------------------------------------------------------------------------------
function texts_text_delete(id)
{
    mask_screen(function(){
		title=execQuery(texts_widget_ajax_manager_url, {section : "textsGetTextTitle", id: id});
		html="\
<p>Вы действительно хотите удалить текст:</p>\
<p><b>"+title+"</b></p>\
";
		confirm_box("Удаление текста", html, function()
		{
			res=execQuery(texts_widget_ajax_manager_url, {section : "textsTextDelete", id: id});
            texts_show_text_list();
		});
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function texts_text_move(id)
{
	$("#texts_text_id").val(id);
	admin_info_show("Выбор раздела для перемещения текста", "<img src='/admin/images/"+loading_icon+"'>");
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetMenusMap", func: 'texts_text_move_process'});
	if (res=='')
		admin_info_close();
	else
		admin_info_change('', res);
}
//------------------------------------------------------------------------------
function texts_text_move_process(menu)
{
	admin_info_close();
    mask_screen(function(){
		id=$("#texts_text_id").val();
		res=execQuery(texts_widget_ajax_manager_url, {section : "textsMoveText", id: id, menu: menu});
		texts_show_text_list();
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function texts_show_list_page(page)
{
	type=$("#texts_text_type").val();
	show_wait_image("#texts_texts_list");
	res=execQuery(texts_widget_ajax_manager_url, {section : "textsGetTextListHtml", type: type, page: page});
	res=eval('(' + res + ')');
	$("#texts_text_page").val(res.page);
    $("#texts_texts_list").html(res.html);
}
//------------------------------------------------------------------------------
function texts_edit_generate_signature()
{
	name=$("#texts_edit_text_title").val().trim();
	if (name=='')
	{
		show_message_warning("Генерация сигнатуры", "Для генерации сигнатуры необходимо ввести название текста");
		return;
	}
	mask_element_noicon('texts_edit_text_signature', function() {
		name=$("#texts_edit_text_title").val().trim();
		res=execQuery(texts_widget_ajax_manager_url, {section : "textsGenerateSignature", name: name});
		if ($("#texts_edit_text_signature").val().trim()!='')
		{
			confirm_box("Генерирование сигнатуры", "Вы хотите заменить существующую сигнатуру сгенерированной?", function() { $("#texts_edit_text_signature").val(res); });
		}
		else
			$("#texts_edit_text_signature").val(res);
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function texts_load_html_file_change(file)
{
	pos=file.lastIndexOf('\\');
	if (pos!=-1) file=file.substr(pos);
	$("#text_html_load_file_name").val(file);
	if(file!='') $("#texts_load_html_load_button").css({display: 'inline'});
}
//------------------------------------------------------------------------------
function texts_load_html_complete()
{
	return;
	res=$("#texts_html_load_iframe").contents().find('body').html().trim();
	alert(res);
	if (res=='' || !text_editor) return;
	res=eval(res);
	$("#texts_edit_text_title").val(res.title);
//	alert(res.html);
	text_editor.setData(res.html);
}
//------------------------------------------------------------------------------
function texts_set_loaded_content(title, html)
{
	$("#texts_edit_text_title").val(title);
	html=html.replace(/<==script>/g, "</script>");
	if (text_editor) text_editor.setData(html);
	$("#texts_load_html_wait_icon").css({display: 'none'}).attr('src', '');
}
//------------------------------------------------------------------------------
function texts_load_html_submit()
{
	if ($("#text_html_load_file_name").val().trim()=='')
		show_message('Загрузка файла', 'Необходимо выбрать файл для загрузки.');
	else
	{
		$("#text_html_load_file_name").val('');
		$("#texts_load_html_wait_icon").attr('src', 'images/'+loading_icon_24).css({display: 'inline'});
		$("#texts_load_html_load_button").css({display: 'none'});
		$('#texts_html_load_form').submit();
	}
}
//------------------------------------------------------------------------------
