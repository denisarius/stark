//------------------------------------------------------------------------------
//-- Генерация текстов псевдотэгов
//------------------------------------------------------------------------------
function texts_edit_tag_generate_link_text(signature, text, title)
{
	if (title!='') title=', "'+title+'"';
	return '{@@link(text, '+signature+', "'+text+'"'+title+')}';
}
//------------------------------------------------------------------------------
function texts_edit_tag_generate_link_menu(id, text, title)
{
	if (title!='') title=', "'+title+'"';
	return '{@@link(menu, '+id+', "'+text+'"'+title+')}';
}
//------------------------------------------------------------------------------
function texts_edit_tag_generate_link_document(id, text, title)
{
	if (title!='') title=', "'+title+'"';
	return '{@@link(document, '+id+', "'+text+'"'+title+')}';
}
//------------------------------------------------------------------------------
function texts_edit_tag_generate_constant(name)
{
	return '{@@const("'+name+'")}';
}
//------------------------------------------------------------------------------
//-- Вставка тэга ссылки на текст
//------------------------------------------------------------------------------
function texts_edit_insert_text_link(func)
{
	if (!text_editor) return;
	var selection = text_editor.getSelection();
	if (CKEDITOR.env.ie) {
	    selection.unlock(true);
	    selectedText = selection.getNative().createRange().text;
	} else
	    selectedText = selection.getNative();
	if (selectedText=='')
	{
		show_message_warning("Вставка тэга", "Для вставки этого тэга необходимо выделить фрагмент текста который будет являться текстом ссылки.");
		return;
	}
	admin_info_show('Выберите текст', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkTextsList", func: func});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function texts_link_text_text_select(signature, name, id)
{
	html="\
<input type='hidden' value='"+signature+"' id='texts_link_text_signature'>\
<b>Текст: </b> "+name+"<br><br>\
<b>Альтернативный текст ссылки:</b><br>\
<input type='text' id='texts_link_text_alt' style='width:90%'>\
<br><br><br>\
<input type='button' value='Создать' onClick='texts_link_text_create()' style='margin-right: 20px;'>\
<input type='button' value='Отмена' onClick='admin_info_close()'>\
";
	admin_info_change('Создание ссылки на текст', html, null);
}
//------------------------------------------------------------------------------
function texts_link_text_create()
{
	if (text_editor)
	{
		var selection = text_editor.getSelection();
		if (CKEDITOR.env.ie) {
		    selection.unlock(true);
		    selectedText = selection.getNative().createRange().text;
		} else
		    selectedText = selection.getNative();
		signature=$("#texts_link_text_signature").val();
		if (signature!='')
		{
			alt=$("#texts_link_text_alt").val().trim();
			link=texts_edit_tag_generate_link_text(signature, selectedText, alt);
			text_editor.insertText(link);
		}
	}
	admin_info_close();
}
//------------------------------------------------------------------------------
//-- Вставка тэга ссылки на подраздел
//------------------------------------------------------------------------------
function texts_edit_insert_menu_link(func)
{
	if (!text_editor) return;
	var selection = text_editor.getSelection();
	if (CKEDITOR.env.ie) {
	    selection.unlock(true);
	    selectedText = selection.getNative().createRange().text;
	} else
	    selectedText = selection.getNative();
	if (selectedText=='')
	{
		show_message_warning("Вставка тэга", "Для вставки этого тэга необходимо выделить фрагмент текста который будет являться текстом ссылки.");
		return;
	}
	admin_info_show('Выберите подраздел', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkMenuHTML", func: func});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function texts_link_menu_menu_selected(func)
{
	menu_id=$("#texts_link_menu_menu_list").val();
	menu_name=$("#texts_link_menu_menu_list option:selected").text();
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkMenuItemsHTML", menu: menu_id, menu_name: menu_name, func: func});
	$("#common_link_menu_menu_item_block").html(res);
	fr_id=_admin_info_frames[_admin_info_frames.length-1];                
	$('#'+fr_id).screenCenter();
}
//------------------------------------------------------------------------------
function texts_link_text_menu_select(id, menu, menu_item)
{
	html="\
<input type='hidden' value='"+id+"' id='texts_link_menu_id'>\
<b>Раздел: </b> "+menu+"<br>\
<b>Подраздел: </b> "+menu_item+"<br><br>\
<b>Альтернативный текст ссылки:</b><br>\
<input type='text' id='texts_link_text_alt' style='width:90%'>\
<br><br><br>\
<input type='button' value='Создать' onClick='texts_link_menu_create()' style='margin-right: 20px;'>\
<input type='button' value='Отмена' onClick='admin_info_close()'>\
";
	admin_info_change('Создание ссылки на подраздел', html, null);
}
//------------------------------------------------------------------------------
function texts_link_menu_create()
{
	if (text_editor)
	{
		var selection = text_editor.getSelection();
		if (CKEDITOR.env.ie) {
		    selection.unlock(true);
		    selectedText = selection.getNative().createRange().text;
		} else
		    selectedText = selection.getNative();
		id=$("#texts_link_menu_id").val();
		if (id!='')
		{
			alt=$("#texts_link_text_alt").val().trim();
			link=texts_edit_tag_generate_link_menu(id, selectedText, alt)
			text_editor.insertText(link);
		}
	}
	admin_info_close();
}
//------------------------------------------------------------------------------
//-- Вставка тэга ссылки на документ
//------------------------------------------------------------------------------
function texts_edit_insert_document_link(func)
{
	if (!text_editor) return;
	var selection = text_editor.getSelection();
	if (CKEDITOR.env.ie) {
	    selection.unlock(true);
	    selectedText = selection.getNative().createRange().text;
	} else
	    selectedText = selection.getNative();
	if (selectedText=='')
	{
		show_message_warning("Вставка тэга", "Для вставки этого тэга необходимо выделить фрагмент текста который будет являться текстом ссылки.");
		return;
	}
	admin_info_show('Выберите документ', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkDocumentListHTML", func: func});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function texts_link_text_document_select(id, doc)
{
	html="\
<input type='hidden' value='"+id+"' id='texts_link_document_id'>\
<b>Документ: </b> "+doc+"<br>\
<b>Альтернативный текст ссылки:</b><br>\
<input type='text' id='texts_link_document_alt' style='width:90%'>\
<br><br><br>\
<input type='button' value='Создать' onClick='texts_link_document_create()' style='margin-right: 20px;'>\
<input type='button' value='Отмена' onClick='admin_info_close()'>\
";
	admin_info_change('Создание ссылки на документ', html, null);
}
//------------------------------------------------------------------------------
function texts_link_document_create()
{
	if (text_editor)
	{
		var selection = text_editor.getSelection();
		if (CKEDITOR.env.ie) {
		    selection.unlock(true);
		    selectedText = selection.getNative().createRange().text;
		} else
		    selectedText = selection.getNative();
		id=$("#texts_link_document_id").val();
		if (id!='')
		{
			alt=$("#texts_link_document_alt").val().trim();
			link=texts_edit_tag_generate_link_document(id, selectedText, alt)
			text_editor.insertText(link);
		}
	}
	admin_info_close();
}
//------------------------------------------------------------------------------
//-- Вставка константы
//------------------------------------------------------------------------------
function texts_edit_insert_constant(func)
{
	if (!text_editor) return;
	admin_info_show('Выберите константу', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetConstantsList", func: func});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function texts_constant_select(name)
{
	if (text_editor)
	{
		if (name!='')
		{
			link=texts_edit_tag_generate_constant(name);
			text_editor.insertText(link);
		}
	}
	admin_info_close();
}
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
function texts_edit_tag_text_select(start, end)
{
	if (text_editor)
	{
		doc=text_editor.document.$;
		win=doc.defaultView? doc.defaultView : doc.parentWindow;
		sel=rangy.getIframeSelection(win.frameElement);
		sel.collapseToStart();
		range=sel.getRangeAt(0);
		pos=range.startOffset;
		range.setStart(range.startContainer, start);
		range.setEnd(range.startContainer, end);
		sel.setSingleRange(range);
	}
}
//------------------------------------------------------------------------------
function texts_edit_edit_pseudotag()
{
	if (text_editor)
	{
		doc=text_editor.document.$;
		win=doc.defaultView? doc.defaultView : doc.parentWindow;
		sel=rangy.getIframeSelection(win.frameElement);
		sel.collapseToStart();
		range=sel.getRangeAt(0);
		pos=range.startOffset;
		range.setStartBefore(range.startContainer);
		range.setEndAfter(range.startContainer);
		selectedText=range.text();
		if (selectedText=='') return;
		admin_info_show('Изменение параметров тэга', '<center><img src="images/'+loading_icon+'"></center>');
		res=execQuery(common_widget_ajax_manager_url, {section : "commonEditTagEdit", text_block: selectedText, pos: pos});
		if (res!='') admin_info_change('', res, null);
	}
}
//------------------------------------------------------------------------------
//-- Редактирование тэга ссылки на текст
//------------------------------------------------------------------------------
function texts_tag_edit_link_text_select_signature()
{
	texts_edit_insert_text_link("texts_tag_edit_link_text_signature_selected");
}
//------------------------------------------------------------------------------
function texts_tag_edit_link_text_signature_selected(signature, name)
{
	$("#texts_tag_edit_text_signature").val(signature);
	$("#texts_tag_edit_text_name").html(name);
	admin_info_close();
}
//------------------------------------------------------------------------------
function texts_tag_edit_link_text_save()
{
	if (text_editor)
	{
		sign=$("#texts_tag_edit_text_signature").val();
		linkText=$("#texts_tag_edit_link_text").val().trim();
		title=$("#texts_tag_edit_link_title").val().trim();
		if (linkText=='')
			show_message('Ошибка', 'Необходимо указать текст ссылки');
		else
		{
			link=texts_edit_tag_generate_link_text(sign, linkText, title);
			text_editor.insertText(link);
			admin_info_close();
		}
	}
}
//------------------------------------------------------------------------------
//-- Редактрирование тэга ссылки на подраздел
//------------------------------------------------------------------------------
function texts_tag_edit_link_menu_item_select()
{
	admin_info_show('Выберите подраздел', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkMenuHTML", func: "texts_tag_edit_link_menu_item_changed"});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function texts_tag_edit_link_menu_item_changed(id, menu, menu_item)
{
	$("#texts_tag_edit_menu_item_id").val(id);
	$("#texts_tag_edit_menu_name").html(menu);
	$("#texts_tag_edit_menu_item_name").html(menu_item);
	admin_info_close();
}
//------------------------------------------------------------------------------
function texts_tag_edit_link_menu_save()
{
	if (text_editor)
	{
		id=$("#texts_tag_edit_menu_item_id").val();
		linkText=$("#texts_tag_edit_link_text").val().trim();
		title=$("#texts_tag_edit_link_title").val().trim();
		if (linkText=='')
			show_message('Ошибка', 'Необходимо указать текст ссылки');
		else
		{
			link=texts_edit_tag_generate_link_menu(id, linkText, title);
			text_editor.insertText(link);
			admin_info_close();
		}
	}
}
//------------------------------------------------------------------------------
//-- Редактирование тэга ссылки на документ
//------------------------------------------------------------------------------
function texts_tag_edit_link_document_select()
{
	admin_info_show('Выберите документ', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkDocumentListHTML", func: "texts_tag_edit_link_document_changed"});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function texts_tag_edit_link_document_changed(id, doc)
{
	$("#texts_tag_edit_document_id").val(id);
	$("#texts_tag_edit_document_name").html(doc);
	admin_info_close();
}
//------------------------------------------------------------------------------
function texts_tag_edit_link_document_save()
{
	if (text_editor)
	{
		id=$("#texts_tag_edit_document_id").val();
		linkText=$("#texts_tag_edit_link_text").val().trim();
		title=$("#texts_tag_edit_link_title").val().trim();
		if (linkText=='')
			show_message('Ошибка', 'Необходимо указать текст ссылки');
		else
		{
			link=texts_edit_tag_generate_link_document(id, linkText, title);
			text_editor.insertText(link);
			admin_info_close();
		}
	}
}
//------------------------------------------------------------------------------
//-- Редактрирование тэга константы
//------------------------------------------------------------------------------
function texts_tag_edit_const_select_const()
{
	admin_info_show('Выберите константу', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetConstantsList", func: "texts_tag_edit_const_select_const_selected"});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function texts_tag_edit_const_select_const_selected(name)
{
	$("#texts_tag_edit_text_name").html(name);
	admin_info_close();
}
//------------------------------------------------------------------------------
function texts_tag_edit_const_save()
{
	if (text_editor)
	{
		name=$("#texts_tag_edit_text_name").html().trim();
		if (name=='')
			show_message('Ошибка', 'Необходимо указать имя константы');
		else
		{
			link=texts_edit_tag_generate_constant(name);
			text_editor.insertText(link);
			admin_info_close();
		}
	}
}
//------------------------------------------------------------------------------
// -- Выбор пункта меню
//------------------------------------------------------------------------------
function common_menu_item_select(title, func, menu_id, menu_items_list)
{
	h=$(window).height()*2/3;
	if (typeof(menu_items_list)=='undefined') menu_items_list='';
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetMenuItemSelectorHtml", menu_id: menu_id, menu_items:menu_items_list, func: func, height: h});
	if (res!="")
	{
		if (res!='n/a')
			admin_info_show(title, res, 600);
		else
			show_message_warning(title, "К сожалению, отсутствуют разделы сайта.<br><br>Для редактирования разделов выберите пункт меню 'Разделы'.");
	}
}
//------------------------------------------------------------------------------
function common_menu_item_select_menu_changed(func)
{
	menu_id=$("#common_menu_item_selector_menu").val();
	$("#menu_item_selector_items_container").html('<img src="images/'+loading_icon+'">');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetMenuItemSelectorItemsHtml", id: menu_id, func: func});
	$("#menu_item_selector_items_container").html(res);
	admin_info_center();
}
// ---------------------------------------------------------------------------------------
// -- Выбор объекта
// ---------------------------------------------------------------------------------------
function common_object_select_menu_item_changed(menu_id, menu_item_id, menu_name, menu_item_name)
{
	$("#common_link_object_objects_list").html('<img src="images/'+loading_icon+'">');
	func=$("#common_link_object_function_name").val();
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkObjectObjectsListHtml", menu_item_id: menu_item_id, func: func});
	$("#common_link_object_objects_list").html(res);
	admin_info_close();
	info_id=admin_info_frame_id();
	$('#'+info_id+' #widget_menu_item_selector').html(menu_name+' :: '+menu_item_name);;
	admin_info_center();
}
// ---------------------------------------------------------------------------------------
// -- Работа с прицепленными документами
// ---------------------------------------------------------------------------------------
var common_attachment_swfu, common_attachment_uploaded_files=new Array();
//------------------------------------------------------------------------------
function common_attachement_edit(attachmet_id, func)
{
	delete common_attachment_swfu;

	admin_info_show('Прикрепленный документ', '<center><img src="images/'+loading_icon+'"></center>');
	html=execQuery(common_widget_ajax_manager_url, {section : "commonGetAttachmentEditHtml", attachmet_id: attachmet_id, func: func});
	admin_info_change('', html, 440);
	file_mask='{@attachments_file_types@}';
	common_attachment_swfu = new SWFUpload(
		{	upload_url : "{@admin_url@}/uploader/upload.php",
			flash_url : "{@admin_url@}/uploader/swfupload.swf",
			file_size_limit : "10 MB",

			file_types : file_mask,
			file_types_description: "Files",
			file_upload_limit : 1,

			button_placeholder_id : "common_attachment_button_holder",
			button_image_url : "{@admin_url@}/images/upload_button.png",
			button_width : 148,
			button_height : 27,
			button_text : "<span class='uploadButtonText'>Выберите файл</span>",
			button_text_style : ".uploadButtonText { color: #666666; font-size: 11px; font-weight: bold; font-family: Sans-Serif;}",
			button_text_left_padding : 30,
			button_text_top_padding : 4,
			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
			button_disabled : false,
			button_cursor : SWFUpload.CURSOR.HAND,
			button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,

//			swfupload_loaded_handler : swfupload_loaded_function,
//			file_dialog_start_handler : file_dialog_start_function,
			file_queued_handler : common_attachment_fileQueued,
			file_queue_error_handler : common_attachment_fileQueueError,
//			file_dialog_complete_handler : fileDialogComplete,
			upload_start_handler : common_attachment_uploadStart,
			upload_progress_handler : common_attachment_uploadProgress,
			upload_error_handler : common_attachment_uploadError,
//			upload_success_handler : upload_success_function,
			upload_complete_handler : common_attachment_uploadComplete,
//			debug_handler : debug_function,
//			debug: true,

		}
	);

}
//------------------------------------------------------------------------------
function common_attachment_fileQueued(file)
{
   	$("#common_attachment_progress_block").html("\
<div class='common_attachment_progres_bar' id='common_attachment_progress_bar'></div>\
<span id='common_attachment_progress_text'></span>\
");
	common_attachment_swfu.addFileParam(file.id, "upload_file_real_name", file.name)
	common_attachment_swfu.startUpload()
}
// -----------------------------------------------------------------------------
function common_attachment_fileQueueError()
{}
function common_attachment_uploadStart()
{}
// -----------------------------------------------------------------------------
function common_attachment_uploadProgress(file, bytes, total)
{
	w100=300;
	w=parseInt(w100*bytes/total);
	if (bytes==total) w=w100;
    $("#common_attachment_progress_bar").css({"width": w+"px"});
    $("#common_attachment_progress_text").html(admin_num_to_text(bytes)+" из "+admin_num_to_text(total));
}
// -----------------------------------------------------------------------------
function common_attachment_uploadError(file, error, message)
{
	alert(message);
}
// -----------------------------------------------------------------------------
function common_attachment_uploadComplete(file)
{
	$("#common_attachment_progress_block").html('');
	$("#common_attachment_edit_document").html(file.name);
	if ($("#common_attachment_edit_name").val().trim()=='') $("#common_attachment_edit_name").val(file.name);
}
// -----------------------------------------------------------------------------
function common_attachment_save(func)
{
	id=$("#common_attachment_edit_id").val();
	name=$("#common_attachment_edit_name").val().trim();
	if (name=='')
	{
		show_message_warning("Сохронение документа", "Необходимо ввести название документа");
		return;
	}
	file=$("#common_attachment_edit_document").html();
	common_attachment_swfu.destroy();
	admin_info_close();
	func(id, name, file);
}
// -----------------------------------------------------------------------------
function common_attachement_delete(attachmet_id)
{
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetAttachmentDelete", attachmet_id: attachmet_id});
//	alert(res);
}
// -----------------------------------------------------------------------------
