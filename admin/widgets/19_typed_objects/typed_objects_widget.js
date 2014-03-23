// -----------------------------------------------------------------------------
var text_editor=0, fragment_text_editor=0;
var typed_objects_widget_ajax_manager_url='{@widgets_url@}/{@widget_path@}/{@widget_id@}_widget_dm.php';
var common_widget_ajax_manager_url='{@widgets_url@}/common_widget_dm.php';
// -----------------------------------------------------------------------------
function typed_objects_menu_item_select_change(menu_id, menu_item_id, menu_name, menu_item_name)
{
	$("#typed_objects_menu_id").val(menu_id);
//	$("#typed_objects_menu_selector").html(menu_name);
	$("#typed_objects_menu_item_id").val(menu_item_id);
	$("#widget_menu_item_selector").html(menu_name+' :: '+menu_item_name);
	if (menu_item_id!=0 && menu_item_id!='') typed_objects_show_objects_list_page(0);
	admin_info_close();
	$("html,body").animate({scrollTop: 0}, 500);
}
//------------------------------------------------------------------------------
function typed_objects_show_objects_list(redraw)
{
	if (redraw==undefined) redraw=true;
	page=$("#typed_objects_list_page").val();
	if (page=='') page=0;
	menu_item=$("#typed_objects_menu_item_id").val();
	if (menu_item.indexOf(',')!=-1) return;
	obj_type=$("#typed_objects_init_object_type").val();
	if (menu_item!='' && menu_item!=0)
	{
		if (redraw) $("#typed_objects_objects_list").html('<img src="images/'+loading_icon+'">');
		res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsGetObjectsList", menu_item: menu_item, obj_type: obj_type, page: page});
   		res=eval('(' + res + ')');
	    $("#typed_objects_objects_list").html(res.html);
		$("#typed_objects_list_page").val(res.page);
	}
	else
	    $("#typed_objects_objects_list").html('');
	$('[id^=typed_object_visible_]').imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
}
//------------------------------------------------------------------------------
function typed_objects_show_objects_list_page(page)
{
	$("#typed_objects_list_page").val(page);
    typed_objects_show_objects_list();
}
//------------------------------------------------------------------------------
function typed_objects_object_add()
{
	menu_item=$("#typed_objects_menu_item_id").val();
	if (menu_item=='-1')
	{
		show_message_warning('Добавление объекта', 'Необходимо выбрать раздел.');
		return;
	}
	obj_type=$("#typed_objects_object_type").val();
	if (obj_type==-1)
	{
		show_message_warning('Добавление объекта', 'Необходимо выбрать тип добавляемого объекта.');
		return;
	}
	$("#typed_objects_object_edit_id").val(-1);
	$("#typed_objects_object_edit_old_html").val('');
	admin_info_show('Добавление нового объекта', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsGetObjectEditHtml", id: -1, type: obj_type});
	admin_info_change('', res, 1000);
//	admin_info_center();
	$("#typed_object_image_img").load(function(){ admin_info_center(); });
	$("input[type=checkbox][id ^= 'prop_']").imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent: false});
}
// -----------------------------------------------------------------------------
function typed_objects_object_edit_load_image()
{
	admin_load_image(typed_objects_object_edit_image_uploaded, "*.jpg;*.png");
}
//------------------------------------------------------------------------------
function typed_objects_object_edit_image_uploaded(file)
{
	sx=$("#typed_objects_edit_object_sx").val();
	sy=$("#typed_objects_edit_object_sy").val();
	res=execQuery(common_widget_ajax_manager_url, {section : "commonTempImageProcess", file : file, sx : sx, sy : sy});
	$("#typed_object_image_img").attr("src", res);
	i=res.lastIndexOf('\\');
	if (i==-1) i=res.lastIndexOf('/');
	if (i!=-1) res=res.substr(i+1);
	$("#typed_objects_object_image").val(res);
}
//------------------------------------------------------------------------------
function typed_objects_object_edit_delete_image()
{
	$("#typed_object_image_img").attr("src", 'images/no_image_256.gif');
	$("#typed_objects_object_image").val('');
}
//------------------------------------------------------------------------------
function typed_objects_object_edit_cancel()
{
	admin_info_close();
	id=$("#typed_objects_object_edit_id").val();
	if (id=='' || id==0 || id==-1) return;
	old_html=$("#typed_objects_object_edit_old_html").val();
	$("#typed_objects_list_node_"+id).html(old_html);
	$("#typed_objects_object_edit_old_html").val('');
	$("#typed_objects_object_edit_id").val('');
}
//------------------------------------------------------------------------------
function typed_objects_object_edit_save()
{
	obj_id=$("#typed_objects_edit_object_id").val();
	obj_type=$("#typed_objects_object_type").val();
	obj_name=$("#typed_object_name").val();
	if (obj_name=='')
	{
		show_message_warning("Редактирование объекта", "Необходимо указать название объекта");
		return;
	}
	obj_note='';
	if (text_editor) obj_note=text_editor.getData();

	obj_img=$("#typed_objects_object_image").val();
	menu_item=$("#typed_objects_menu_item_id").val();

	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsEditCanBeSave", id: obj_id, name: obj_name, menu_item: menu_item});
	if (res!='')
	{
		show_message_warning("Редактирование объекта", res);
		return;
	}

	_err="";
	_need="";
	props=$("[id ^= 'prop_']").each(function(){
		try { $(this).val($(this).val().trim()); } catch(e) {}
		$(this).css('border-color', '');
		if ($(this).attr('data-type')=='d')
		{
			v=parseFloat($(this).val().replace(/,/,'.'));
			if ($(this).val()!="" &&  v!=v)
			{
				$(this).css('border-color', '#f55');
				_err="* Вы указали не правильные значения для выделенных полей.\n";
			}
		}
		if ($(this).attr('data-need')!=undefined && $(this).val()=='')
		{
			$(this).css('border-color', '#f55');
			_need="* Не указаны значения для обязательных параметров.\n"
		}
	});
	if (_need!='') _err+=_need;
	if (_err!='')
	{
		show_message_warning("Редактирование объекта", _err+"\nПожалуйста исправьте ошибки.");
		return;
	}
	props=$("[id ^= 'prop_']").serialize();
	admin_info_close();
    mask_screen(function(){
		res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsSaveObjectData", menu_item: menu_item, id: obj_id, type: obj_type, name: obj_name, note: obj_note, img: obj_img, props: props});
		if (res!='') { alert(res); return; };

		if (id==-1)
	        typed_objects_show_objects_list_page(0);
		else
			typed_objects_show_objects_list(false);
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function typed_objects_delete_object(id, name)
{
	confirm_box('Удаление объекта', "Вы действительно хотите удалить объект '"+name+"'", function(){
		res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsDeleteObject", id: id});
		typed_objects_show_objects_list(false);
	});
}
//------------------------------------------------------------------------------
function typed_objects_object_edit(id)
{
	$("#typed_objects_object_edit_id").val(id);
	$("#typed_objects_object_edit_old_html").val($("#typed_objects_list_node_"+id).html());
	admin_info_show('Редактирование объекта', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsGetObjectEditHtml", id: id, type: -1});
	admin_info_change('', res, 1000);
	admin_info_center();
	$("#typed_object_image_img").load(function(){ admin_info_center(); });
	$("input[type=checkbox][id ^= 'prop_']").imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent: false});
}
//------------------------------------------------------------------------------
function typed_objects_toggle_visible(id)
{
    img="<img src='images/"+loading_icon_24+"' id='typed_object_visible_loader_"+id+"' style='vertical-align: text-bottom;'>";
	$("#typed_object_visible_"+id+"_img").after(img);
	$("#typed_object_visible_"+id+"_img").css("display", "none");
	if ($("#typed_object_visible_"+id).is(":checked")) ch=0;
	else ch=1;
	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectSetObjectVisible", id: id, visible: ch});
	$("#typed_object_visible_loader_"+id).remove();
	$("#typed_object_visible_"+id+"_img").css("display", "inline");
}
//------------------------------------------------------------------------------
function typed_objects_go_gallery(id)
{
	menu_item=$("#typed_objects_menu_item_id").val();
	window.location.href="gallery.php?mode=object&id="+id+"&menu_item="+menu_item;
}
//------------------------------------------------------------------------------
function typed_objects_property_dir_select(type, id)
{
	vals=$("#prop_"+id).val();
	admin_info_show('Выбор из справочника', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsGetDirValuesHtml", type: type, id: id, vals: vals});
	admin_info_change('', res, 1000);
}
//------------------------------------------------------------------------------
function typed_objects_property_dir_save(type, id)
{
	obj_id=$("#typed_objects_edit_object_id").val();
	props=$("input[type=checkbox][id ^= 'dir_m_']").serialize();
	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsDirValuesSave", type: type, obj_id: obj_id, id: id, props: props});
	if (res!='')
	{
   		res=eval('(' + res + ')');
		$("#prop_"+id).val(res.data);
		$("#propt_"+id).html(res.text);
	}
	admin_info_close();
}
//------------------------------------------------------------------------------
function typed_objects_object_sort()
{
	menu_item=$("#typed_objects_menu_item_id").val();
	admin_info_show('Сортировка объектов', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsGetSortHtml", menu_item: menu_item});
	admin_info_change('', res, 1000);
	$(".typed_object_object_sort_list").sortable({
		cursor: "move",
		distance: 5,
		opacity: 0.8,
		axis: 'y',
	});
	$(".gallery_sortable_list").disableSelection();
}
//------------------------------------------------------------------------------
function typed_objects_sort_save()
{
	menu_item=$("#typed_objects_menu_item_id").val();
	var sort_id='';
	props=$("[id ^= 'obj_sort_']").each(function(){
		sort_id+=$(this).attr('id').substr(9)+'|';
	});
	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsSortSave", menu_item: menu_item, sort: sort_id});
	admin_info_close();
	$("#typed_objects_list_page").val('0');
	res=eval('(' + res + ')');
	$("#typed_objects_objects_list").html(res.html);
	$('[id^=object_visible_]').imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
}
//------------------------------------------------------------------------------
function typed_objects_object_move_start(id)
{
	$("#typed_objects_object_id").val(id);
	admin_info_show("Выбор раздела для перемещения текста", "<img src='/admin/images/"+loading_icon+"'>");
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetMenusMap", func: 'typed_objects_object_move_mode'});
	if (res=='')
		admin_info_close();
	else
		admin_info_change('', res);
}
//------------------------------------------------------------------------------
function typed_objects_object_move_mode(menu)
{
	admin_info_close();
	html='\
<div class="typed_objects_object_move_mode">\
<input type="button" class="move" value="Переместить" onClick="typed_objects_object_move_process('+menu+', 0)" />\
<input type="button" class="copy" value="Скопировать" onClick="typed_objects_object_move_process('+menu+', 1)" />\
</div>\
';
	admin_info_show("Выберите режим", html);
}
//------------------------------------------------------------------------------
function typed_objects_object_move_process(menu, copy_mode)
{
	admin_info_close();
    mask_screen(function(){
		id=$("#typed_objects_object_id").val();
		resm=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsMoveObject", id: id, menu: menu, copy: copy_mode});
		typed_objects_show_objects_list(false);
//		if (resm!='') alert(resm);
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function typed_objects_property_structured_text(id, prop_type, obj_type)
{
	admin_info_show('Редактирование текста', "<img src='/admin/images/"+loading_icon+"'>", 200);
	prop_value=$("#prop_"+prop_type).val();
	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsStructuredTextEditGetHTML", id: id, prop_type: prop_type, prop_value: prop_value, obj_type: obj_type});
	admin_info_change('', res, 1000);
	fragment_text_editor=0;
	$("[id^=fragment_image_]").load(function(){ admin_info_center(); });
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_add_fragment()
{
	var max_id;
	html='\
<div id="typed_objects_structure_text_add_fragment_icon">\
<img src="/admin/images/'+loading_icon+'">\
</div>\
';
	max_id=0;
    $("[id^=fragment_id_]").each(function(){
    	id=parseInt($(this).attr('id').substr(12), 10);
		if (id>max_id) max_id=id;
    });
	max_id++;
	$("#typed_objects_structure_text_add_fragment").before(html).slideDown(200);
	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsStructuredTextEditFragmentGetHTML", id: max_id});
	$("#typed_objects_structure_text_add_fragment_icon").remove();
	$("#typed_object_structure_text_edit_nodes_sortable").append(res);
	$("#typed_object_structure_text_edit_nodes_sortable .typed_object_structure_text_edit_node:last").slideDown(200, function(){
		mh=parseInt($("#"+admin_info_frame_inner_id()).css('max-height'), 10);
		x=inner_full_height(admin_info_frame_inner_id())-mh+50;
		$("#"+admin_info_frame_inner_id()).animate({scrollTop: x}, 200);
		admin_info_center();
	})
//	admin_info_center();


	typed_objects_structure_text_edit_refresh_sortable();
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_edit_refresh_sortable()
{
	$("#typed_object_structure_text_edit_nodes_sortable").sortable({
		cursor: "move",
		distance: 10,
		opacity: 0.5,
		placeholder: "typed_object_structure_text_edit_node_placeholder",
		forcePlaceholderSize: true,
		tolerance: "pointer",
		revert: 300,
		helper: "clone",	// При этом не обрабатывается "клик" на обьекте !!!
		update: function(event, ui){
//			gallery_save_sort_state($(this));
			setInterval(typed_objects_structure_text_edit_refresh_sortable, 200);
		}
	});
//	$("#typed_object_structure_text_edit_nodes_sortable").disableSelection();
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_edit_image_load(id)
{
	if (fragment_text_editor)
	{
		eid=$("#typed_objects_structured_text_edit_current_fragment_id").val();
		typed_objects_structure_text_edit_fragment_edit_text_save(eid);
	}
	$('#typed_objects_structured_text_edit_current_fragment_id').val(id);
	admin_load_image(typed_objects_structure_text_edit_image_uploaded, "*.jpg;*.png");
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_edit_image_uploaded(file)
{
	fragment_id=$('#typed_objects_structured_text_edit_current_fragment_id').val();
	sx=$("#typed_objects_edit_object_text_sx").val();
	sy=$("#typed_objects_edit_object_text_sy").val();
	res=execQuery(common_widget_ajax_manager_url, {section : "commonTempImageProcess", file:file, sx:sx, sy:sy});
	$("#fragment_image_"+fragment_id).attr("src", res);
	i=res.lastIndexOf('\\');
	if (i==-1) i=res.lastIndexOf('/');
	if (i!=-1) res=res.substr(i+1);
	$("#fragment_image_name_"+fragment_id).val(res);
	$('#typed_objects_structured_text_edit_current_fragment_id').val('');
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_edit_image_clear(id)
{
	confirm_box("Удаление фрагмента", '<p>Вы действительно хотите удалить изображение?</p>', function() {
		$("#fragment_image_"+id).attr("src", '');
//		file=$("#fragment_image_name_"+id).val();
		$("#fragment_image_name_"+id).val('');
//		res=execQuery(common_widget_ajax_manager_url, {section : "commonTempFileDelete", file:file});
	});
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_edit_fragment_edit_text(id)
{
	if (fragment_text_editor)
	{
		eid=$("#typed_objects_structured_text_edit_current_fragment_id").val();
		typed_objects_structure_text_edit_fragment_edit_text_save(eid);
	}
    CKEDITOR.config.height= '100px';
	CKEDITOR.config.format_tags = 'p';
	CKEDITOR.config.baseFloatZIndex=100100;
	fragment_text_editor=CKEDITOR.replace('fragment_content_'+id,
		{
		    on : { instanceReady : function( ev ) { this.focus(); } }
		});
	fragment_text_editor.focus();
	html='\
<input type="button" value="Сохранить" class="admin_tool_button" onClick="typed_objects_structure_text_edit_fragment_edit_text_save('+id+')" />\
<input type="button" value="Отмена" class="admin_tool_button _right" onClick="typed_objects_structure_text_edit_fragment_edit_text_cancel('+id+')" />\
<br/>\
';
	$("#typed_objects_structured_text_edit_current_fragment_id").val(id);
	$("#fragment_text_controls_"+id).html(html);

/*
	alert($('#fragment_text_controls_'+id+' ~ .typed_object_structure_text_edit_node_info_text').length);
	if (!$('#fragment_text_controls_'+id+' ~ .typed_object_structure_text_edit_node_info_text').length)
	{
		mh=parseInt($("#"+admin_info_frame_inner_id()).css('max-height'), 10);
		x=inner_full_height(admin_info_frame_inner_id())-mh+50;
		$("#"+admin_info_frame_inner_id()).animate({scrollTop: x}, 200);
	}
*/
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_edit_fragment_edit_text_cancel(id)
{
	if (!fragment_text_editor) return;
	CKEDITOR.remove(fragment_text_editor);
	fragment_text_editor.destroy(true);
	fragment_text_editor=0;
	$("#fragment_text_controls_"+id).html('');
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_edit_fragment_edit_text_save(id)
{
	if (!fragment_text_editor) return;
	text=fragment_text_editor.getData();
	$("#fragment_content_"+id).html(text);
    typed_objects_structure_text_edit_fragment_edit_text_cancel(id);
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_edit_cancel()
{
	admin_info_close();
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_edit_save()
{
	if (fragment_text_editor)
	{
		f_id=$("#typed_objects_structured_text_edit_current_fragment_id").val();
		typed_objects_structure_text_edit_fragment_edit_text_save(f_id);
	}
	var prop_str;
	obj_id=$("#typed_objects_structured_text_edit_object_id").val();
	prop_type=$("#typed_objects_structured_text_edit_object_prop_type").val();
	obj_type=$("#typed_objects_structured_text_edit_object_obj_type").val();
	prop_str='';
	cnt=1;
    $("[id^=fragment_id_]").each(function(){
    	id=$(this).attr('id').substr(12);
		image=$("#fragment_image_name_"+id).val();
		title=$("#fragment_title_"+id).val().trim();
		text=$("#fragment_content_"+id).html().trim();
		text=text.replace('[', '\\[');
		text=text.replace(']', '\\]');
		prop_str+='['+cnt+']['+title+']['+image+']['+text+']';
		cnt++;
    });
	cnt--;
	prop_type=$("#typed_objects_structured_text_edit_object_prop_type").val();
	$("#prop_"+prop_type).val(prop_str);
	prop_text='количество фрагментов: '+cnt+' ';
	if (cnt) prop_text+='(нажмите для редактирования)';
    $("#propt_"+prop_type).html(prop_text);
	admin_info_close();
}
//------------------------------------------------------------------------------
function typed_objects_structure_text_edit_fragment_delete(id)
{
	confirm_box("Удаление фрагмента", '<p>Вы действительно хотите удалить этот фрагмент?</p>', function() {
		$('#fragment_id_'+id).slideUp(500, function() { $('#fragment_id_'+id).remove()} );
	});
}
//------------------------------------------------------------------------------
function typed_objects_property_table(id, prop_type, obj_type)
{
	admin_info_show('Редактирование таблицы', "<img src='/admin/images/"+loading_icon+"'>", 200);
	prop_value=$("#prop_"+prop_type).val();
	res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsTableEditGetHTML", id: id, prop_type: prop_type, prop_value: prop_value, obj_type: obj_type});
	w=$("#prop_editor_width_"+prop_type).val();
	admin_info_change('', res, w);
}
//------------------------------------------------------------------------------
// Убираем все пустые строки в таблице с конца оставляя только одну пустую
//------------------------------------------------------------------------------
function typed_object_table_edit_process_empty_string()
{
	var str, max_filled_row=-1;
	$("[id^=typed_object_table_edit_row_]").each(function(){
		str='';
		id=$(this).attr('id').substr(28);
		$('input[type="text"]', $(this)).each(function(){
	    	str+=$(this).val().trim();
		});
		if (str!='') max_filled_row=id;
	});
	max_filled_row=parseInt(max_filled_row, 10)+1;
    $("#typed_object_table_edit_row_"+max_filled_row+" img").css('display', 'none');
	$("[id^=typed_object_table_edit_row_]").each(function(){
		id=parseInt($(this).attr('id').substr(28), 10);
		if (id>max_filled_row) $(this).remove();
	});
	$("#typed_objects_table_edit_max_row").val(max_filled_row);
	admin_info_center();
}
//------------------------------------------------------------------------------
// Добавляем и убираем строки в зависимости от количества пустых строк в конце таблицы
// В конце всегда должна быть одна и только одна пустая строка
//------------------------------------------------------------------------------
function typed_object_table_edit_row_key_control(row_id)
{
	var str='', max_str='', max1_str='';
	max_row=parseInt($("#typed_objects_table_edit_max_row").val(), 10);
	$('input[type="text"]', $("#typed_object_table_edit_row_"+max_row)).each(function(){
    	max_str+=$(this).val().trim();
	});
	if (max_str)
	{
        $("#typed_object_table_edit_row_"+max_row+" img").css('display', 'inline-block');
		obj_type=$("#typed_objects_table_edit_object_obj_type").val();
		prop_type=$("#typed_objects_table_edit_object_prop_type").val();
		res=execQuery(typed_objects_widget_ajax_manager_url, {section : "typed_objectsTableEditGetEmptyRowHTML", row_id: max_row+1, obj_type: obj_type, prop_type: prop_type});
        $("#typed_object_table_edit_row_"+max_row).after(res);
		$("#typed_objects_table_edit_max_row").val(max_row+1);
		admin_info_center();
		return;
	}
	max_row--;
	if (max_row<0) return;
	$('input[type="text"]', $("#typed_object_table_edit_row_"+max_row)).each(function(){
    	max1_str+=$(this).val().trim();
	});
	if (max_str=='' && max1_str=='')
	{
		typed_object_table_edit_process_empty_string();
		return;
	}
}
//------------------------------------------------------------------------------
// Удаляем строку в таблице
//------------------------------------------------------------------------------
function typed_object_table_edit_row_delete(row_id)
{
	$("#typed_object_table_edit_row_"+row_id).remove();
	typed_object_table_edit_recalc_rows();
	typed_object_table_edit_process_empty_string();
}
//------------------------------------------------------------------------------
function typed_object_table_edit_recalc_rows()
{
	var idx=0;
	$("[id^=typed_object_table_edit_row_]").each(function(){
		$(this).attr('id', 'typed_object_table_edit_row_'+idx);
		$('img', this).attr('onclick', 'typed_object_table_edit_row_delete('+idx+');');
		$('input[type=text]', this).attr('onkeyup', 'typed_object_table_edit_row_key_control('+idx+');');
		$(".typed_object_table_edit_nod_number", this).html((idx+1)+'.');
		idx++;
	});
	$("#typed_objects_table_edit_max_row").val(idx-1);
}
//------------------------------------------------------------------------------
function typed_objects_table_edit_save()
{
	var str='', row_str, fm_str, cnt=0;
	$('[id^=typed_object_table_edit_row_]').each(function(){
        row_str=''; fm_str='';
		$('input[type=text]', this).each(function(){
	        val=$(this).val().trim();
            row_str+=val;
			val=val.replace('[', '\\[');
			val=val.replace(']', '\\]');
	    	fm_str+='['+val+']';
		});
		if (row_str!='') { str+=fm_str; cnt++; }
	});
	prop_type=$("#typed_objects_table_edit_object_prop_type").val();
	$("#prop_"+prop_type).val(str);
	prop_text='количество строк: '+cnt+' ';
	if (cnt) prop_text+='(нажмите для редактирования)';
    $("#propt_"+prop_type).html(prop_text);
	admin_info_close();
}
//------------------------------------------------------------------------------
function typed_objects_table_edit_cancel()
{
	admin_info_close();
}
//------------------------------------------------------------------------------
function typed_objects_table_edit_refresh_sortable()
{
	max_row=$("#typed_objects_table_edit_max_row").val();

	$("#typed_object_table_edit_nodes_sortable").sortable({
		cursor: "move",
		distance: 10,
		opacity: 0.5,
		placeholder: "typed_object_table_edit_node_placeholder",
		tolerance: "pointer",
		revert: 100,
		helper: "clone",	// При этом не обрабатывается "клик" на обьекте !!!
		items: "> div:not(([id^=typed_object_table_edit_row_]):last)", // очень сильное колдунство. Выбирает все дивы кроме последнего
		update: function(event, ui){
			typed_object_table_edit_recalc_rows();
			setInterval(typed_objects_table_edit_refresh_sortable, 200);
		}
	});
}
//------------------------------------------------------------------------------
