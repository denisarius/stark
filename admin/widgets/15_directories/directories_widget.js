//------------------------------------------------------------------------------
var directories_widget_ajax_manager_url='{@widgets_url@}/{@widget_path@}/{@widget_id@}_widget_dm.php';
var common_widget_ajax_manager_url='{@widgets_url@}/common_widget_dm.php';
//------------------------------------------------------------------------------
function dirs_dir_add()
{
	admin_info_show('Добавление справочника', '<img src="images/'+loading_icon+'">', 400);
	res=execQuery(directories_widget_ajax_manager_url, {section : "dirsGetEditDirHtml", id: -1});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function dirs_dir_data_save(dir_id)
{
	name=$("#dirs_dir_name").val().trim();
	if (name=='')
	{
		show_message_warning('Сохранение данных', 'Необходимо ввести имя справочника');
		return;
	}
    mask_screen(function(){
		res=execQuery(directories_widget_ajax_manager_url, {section : "dirsDirDataSave", id: dir_id, name: name});
		if (res!='')
		{
	   		res=eval('(' + res + ')');
			if (res.error!='')
				show_message_warning('Сохранение данных', res.error);
			else
			{
				$("#dirs_current_dir").html(res.dirs);
				$("#dirs_dir_content").html(res.dir_content);
                admin_info_close();
			}
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function dirs_dir_changed()
{
	dir_id=$("#dirs_current_dir").val();
    mask_screen(function(){
		res=execQuery(directories_widget_ajax_manager_url, {section : "dirsGetDirContent", id: dir_id});
		$("#dirs_dir_content").html(res);
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function dirs_dir_edit()
{
	dir_id=$("#dirs_current_dir").val();
	if (dir_id==-1) return;
	admin_info_show('Редактирование справочника', '<img src="images/'+loading_icon+'">', 600);
	res=execQuery(directories_widget_ajax_manager_url, {section : "dirsGetEditDirHtml", id: dir_id});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function dirs_edit_value(val_id)
{
	dir_id=$("#dirs_current_dir").val();
	if (dir_id==-1) return;
	admin_info_show('Редактирование значения', '<img src="images/'+loading_icon+'">', 600);
	res=execQuery(directories_widget_ajax_manager_url, {section : "dirsGetEditValueHtml", dir_id: dir_id, val_id: val_id});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function dirs_dir_value_save(val_id)
{
	dir_id=$("#dirs_current_dir").val();
	if (dir_id==-1) return;
	val=$("#dirs_value_content").val().trim();
	if (val=='')
	{
		show_message_warning('Сохранение данных', 'Необходимо ввести значение');
		return;
	}
	menu_id=$("#dirs_link_to_menu_id").val().trim();
    mask_screen(function(){
		res=execQuery(directories_widget_ajax_manager_url, {section : "dirsDirValueSave", dir_id: dir_id, val_id: val_id, val: val, menu_id: menu_id});
		if (res!='')
		{
	   		res=eval('(' + res + ')');
			if (res.error!='')
				show_message_warning('Сохранение данных', res.error);
			else
			{
				if (val_id!=-1)
					$("#dirs_value_node_"+val_id).replaceWith(res.node);
				else
					$("#dirs_dir_content").append(res.node);
				admin_info_close();
			}
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function dirs_delete_value(val_id)
{
    mask_screen(function(){
		res=execQuery(directories_widget_ajax_manager_url, {section : "dirsDirValueDelete", val_id: val_id});
		if (res!='')
			show_message_warning('Удаление данных', res);
		else
			$("#dirs_value_node_"+val_id).remove();
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function dirs_link_to_menu()
{
	admin_info_show('Выберите подраздел', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkMenuHTML", func: 'dirs_link_to_menu_store'});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function dirs_link_to_menu_store(id, menu, menu_item)
{
	admin_info_close();
	$("#dirs_link_to_menu_id").val(id);
	$("#dirs_link_menu_name").html(menu_item);
}
//------------------------------------------------------------------------------
function dirs_add_list_values()
{
	html='\
<textarea id="dirs_add_items_list" style="width:98%; height: 400px;"></textarea>\
<hr>\
<input type="button" value="Добавить" onClick="dirs_add_list_values_save()"/>\
';
	admin_info_show('Список элементов справочника', html, 500);
}
//------------------------------------------------------------------------------
function dirs_add_list_values_save()
{
	dir_id=$("#dirs_current_dir").val();
	if (dir_id==-1) return;
	items=$("#dirs_add_items_list").val().trim();
	if (items=='')
	{
		show_message_warning('Добавление элементов', 'Необходимо ввести хотябы один элемент для добавления');
		return;
	}
    mask_screen(function(){
		res=execQuery(directories_widget_ajax_manager_url, {section : "dirsDirValueAddList", dir_id: dir_id, vals: items});
		$("#dirs_dir_content").html(res);
		admin_info_close();
	});
	clear_mask();
}
//------------------------------------------------------------------------------
