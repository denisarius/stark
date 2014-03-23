//------------------------------------------------------------------------------
var constants_widget_ajax_manager_url='{@widgets_url@}/{@widget_path@}/{@widget_id@}_widget_dm.php';
//------------------------------------------------------------------------------
function constants_add_constant()
{
    eid=$("#constants_constant_edit_id").val();
	if (eid!='') constants_constant_edit_cancel(eid);
	html="\
<h2>Добавление константы</h2>\
<table>\
<tr><td style='width:130px;'><b>Название константы:</b></td>\
<td><input type='text' style='width: 70%;' id='constants_constant_name' value=''></td></tr>\
<tr><td valign='top'><b>Значение константы:</b></td>\
<td><textarea id='constants_constant_value' style='width:535px;' row='2'></textarea></td></tr>\
</table>\
<br><input type='button' value='Сохранить константу' style='margin-right: 10px;' onClick='constants_constant_add_save()'>\
<input type='button' value='Отмена' onClick='constants_constant_form_hide()'>\
";
	$("#constants_constant_add_container").addClass("constants_constant_add_block_active").html(html);
	$("#constants_constant_value").autoResize({ animateDuration : 800, animate: true, extraSpace : 0 });
}
//------------------------------------------------------------------------------
function constants_constant_form_hide()
{
	$("#constants_constant_add_container").html('').removeClass("constants_constant_add_block_active");
}
//------------------------------------------------------------------------------
function constants_constant_add_save()
{
	var res='', save;
	name=$("#constants_constant_name").val().trim();
	val=$("#constants_constant_value").val().trim();
	if (name=='')
		alert("Необходимо указать название константы.");
	else
	{
		save=false;
		if (val=='')
			confirm_box("Добавление константы", "Записать константу c <u>пустым значением</u>?" , function(){ save=true; });
		else
			save=true;
		if (save)
		{
			mask_screen(function(){
				res=execQuery(constants_widget_ajax_manager_url, {section: "constantsConstAddSave", name: name, val: val});
				if (res!='')
				{
					$("#constants_constants_list").html(res);
					constants_constant_form_hide();
				}
				else show_message("Добавление константы", "Ошибка записи");
			});
			clear_mask();
		}
	}
}
//------------------------------------------------------------------------------
function constants_constant_edit_cancel()
{
    id=$("#constants_constant_edit_id").val();
    $("#constants_constant_edit_id").val('');
	$("#constants_constants_list_row_"+id).removeClass('constants_constant_edit_block').html($("#constants_constant_edit_old_item_html").val());
}
//------------------------------------------------------------------------------
function constants_constant_edit_save()
{
    id=$("#constants_constant_edit_id").val();
    $("#constants_constant_edit_id").val('');
	name=$("#constants_edit_constant_name").val().trim();
	val=$("#constants_edit_constant_value").val().trim();
	if (name=='')
	{
		show_message("Изменение константы", "Ошибка записи");
		$("#constants_constants_list_row_"+id).html($("#constants_constant_edit_old_item_html").val());
	}
	else
	{
		save=false;
		if (val=='')
			confirm_box("Изменение константы", "Записать константу c <u>пустым значением</u>?" , function(){ save=true; });
		else
			save=true;
		if (save)
		{
			mask_screen(function(){
				res=execQuery(constants_widget_ajax_manager_url, {section: "constantsConstEditSave", name: name, val: val});
				if (res=='')
				{
					show_message("Добавление константы", "Ошибка записи");
					$("#constants_constants_list_row_"+id).html($("#constants_constant_edit_old_item_html").val());
				}
					$("#constants_constants_list_row_"+id).html(res);
			});
			clear_mask();
		}
	}
	$("#constants_constants_list_row_"+id).removeClass('constants_constant_edit_block');
}
//------------------------------------------------------------------------------
function constants_constant_edit(id)
{
	constants_constant_form_hide();
    eid=$("#constants_constant_edit_id").val();
	if (eid!='') constants_constant_edit_cancel(eid);
    $("#constants_constant_edit_old_item_html").val($("#constants_constants_list_row_"+id).html());
	$("#constants_constants_list_row_"+id).html("<img src='images/"+loading_icon+"'>");
	res=execQuery(constants_widget_ajax_manager_url, {section: "constantsConstEditHtml", id: id});
	if(res=='')
	{
		show_message("Изменение константы", "Ошибка связи с сервером");
		$("#constants_constants_list_row_"+id).html(old);
	}
    else
	{
   		res=eval('(' + res + ')');
		tag=texts_edit_tag_generate_constant(res.name);
		tag_html="<hr><b>Тэг:</b><br>"+tag;
		$("#constants_constants_list_row_"+id).addClass('constants_constant_edit_block').html(res.html+tag_html);
	    $("#constants_constant_edit_id").val(id);
	}
}
//------------------------------------------------------------------------------
function constants_constant_edit_delete()
{
    id=$("#constants_constant_edit_id").val();
	mask_screen(function(){
		res=execQuery(constants_widget_ajax_manager_url, {section: "constantsConstEditDelete", id: id});
		if (res=='ok')
		{
			$("#constants_constants_list_row_"+id).remove();
		    $("#constants_constant_edit_id").val('');
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
