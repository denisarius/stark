var text_editor=0;
//------------------------------------------------------------------------------
var goods_widget_ajax_manager_url='{@widgets_url@}/{@widget_path@}/{@widget_id@}_widget_dm.php';
var common_widget_ajax_manager_url='{@widgets_url@}/common_widget_dm.php';
//------------------------------------------------------------------------------
function goods_menu_select()
{
	menu_id=$("#goods_menu_id").val();
	if (menu_id==0 || menu_id=='') menu_id=-1;
	common_menu_item_select('Выберите раздел с товарами', 'goods_menu_item_select_change', menu_id);
}
//------------------------------------------------------------------------------
function goods_menu_item_select_change(menu_id, menu_item_id, menu_name, menu_item_name)
{
	admin_info_close();
	$("#goods_page").val('0');
	$("#goods_menu_id").val(menu_id);
	$("#goods_menu_selector").html(menu_name+' :: '+menu_item_name);
	$("#goods_menu_item_id").val(menu_item_id);
	goods_create_list(-1, true);
	$("#goods_filter_container").css('display', 'block');
}
//------------------------------------------------------------------------------
function goods_get_goods_list(obj_type)
{
	menu=$("#goods_menu_id").val();
	category=$("#goods_menu_item_id").val();
	page=$('#goods_page').val();
	filter=$("#goods_filter_sql").val();
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGetGoodsList", menu: menu, category: category, page: page, filter: filter, obj_type: obj_type});
	if (res!='') res=eval('('+res+')');
	else res={type:'', filter:'', list:''};
//	alert(res.sql);
	return res;
}
//------------------------------------------------------------------------------
function goods_get_selected_good_type()
{
	if ($("#goods_good_type").length>0)
		obj_type=$("#goods_good_type").val();
	else
		obj_type=-1;
	return obj_type;
}
//------------------------------------------------------------------------------
function goods_create_list(obj_type, repaint)
{
	if (repaint) show_wait_image("#cms_goods_list");
	res=goods_get_goods_list(obj_type);
	$("#cms_goods_list").html(res.list);
	$("#goods_type_selector_container").html(res.type);
	$("#goods_filter_container").html(res.filter);
	$('.img_checkbox').imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
}
//------------------------------------------------------------------------------
function goods_show_list(page)
{
	$('#goods_page').val(page);
	obj_type=goods_get_selected_good_type();
	show_wait_image("#cms_goods_list");
	res=goods_get_goods_list(obj_type);
	$("#cms_goods_list").html(res.list);
	$('.img_checkbox').imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
}
//------------------------------------------------------------------------------
function goods_add_good()
{
	obj_type=goods_get_selected_good_type();
	if (obj_type==-1)
	{
		show_message_warning('Добавление товара', 'Необходимо выбрать тип товара');
		return;
	}
	admin_info_show('Добавление товара', '<img src="images/'+loading_icon+'">', 600);
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGetGoodAddHTML", obj_type: obj_type});
	admin_info_change('', res, null)
}
//------------------------------------------------------------------------------
function goods_save_new_good()
{
	name=$("#good_name").val().trim();
	code=$("#good_code").val().trim();
	menu=$("#goods_menu_id").val();
	category=$("#goods_menu_item_id").val();
	obj_type=$("#goods_good_type").val();
	if (name=='' || code=='')
	{
		show_message_warning('Добавление товара', 'Необходимо ввести название товара и его артикул');
		return;
	}
    mask_screen(function(){
		res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGoodCanBeAdd", name: name, code: code});
		if (res=="yes")
		{
			res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGoodAdd", name: name, menu: menu, category: category, code: code, type: obj_type});
			if(res!='') alert(res);
            $('#goods_page').val('0');
			obj_type=goods_get_selected_good_type();
			show_wait_image("#cms_goods_list");
			res=goods_get_goods_list(obj_type);
			$("#cms_goods_list").html(res.list);
			$('.img_checkbox').imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
			admin_info_close();
		}
		else
			show_message_warning('Добавление товара', 'Товар с таким артикулом уже есть');
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function goods_delete_good(id)
{
    mask_screen(function(){
		name=execQuery(goods_widget_ajax_manager_url, {section : "goodsGetGoodName", id: id});
		html="<p>Вы действительно хотите удалить товар:</p><p><b>"+name+"</b></p>";
		confirm_box("Удаление товара", html, function()
		{
			res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGoodDelete", id: id});
			obj_type=goods_get_selected_good_type();
			show_wait_image("#goods_list_item_"+id);
			res=goods_get_goods_list(obj_type);
			$("#cms_goods_list").html(res.list);
			$('.img_checkbox').imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
		});
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function goods_good_visible_toggle(id)
{
    img="<img src='images/"+loading_icon_24+"' id='goods_visible_loader_"+id+"' style='vertical-align: text-bottom;'>";
	$("#goods_visible_"+id+"_img").after(img);
	$("#goods_visible_"+id+"_img").css("display", "none");
	if ($("#goods_visible_"+id).is(":checked")) ch=0;
	else ch=1;
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsSetGoodVisible", id: id, visible: ch});
	$("#goods_visible_loader_"+id).remove();
	$("#goods_visible_"+id+"_img").css("display", "inline");
}
//------------------------------------------------------------------------------
function goods_create_good_code()
{
	name=$("#good_name").val().trim();
	if (name=='')
	{
		show_message_warning('Генерация артикула', 'Для генерации артикула необходимо ввести название товара.');
		return;
	}
    mask_screen(function(){
		res=execQuery(goods_widget_ajax_manager_url, {section : "goodsCreateGoodCode", name: name});
        $("#good_code").val(res);
	});
	clear_mask();
}
//------------------------------------------------------------------------------
/*
function goods_good_edit(id)
{
    eid=$("#goods_good_edit_id").val();
	if (eid!='') goods_cansel_good_edit(eid)
	show_wait_image("#goods_item_block_"+id);
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGetGoodData", id: id});
	if (res=='')
	{
		$("#goods_item_block_"+id).html("<h3 class='admin_error_note'>Не удается связаться с базой данных.</h3>");
	}
	else
	{
		res=eval('(' + res + ')');
		res.note=res.note.replace(/{@n@}/g, "\n");
		$("#goods_list_item_"+id).addClass('cms_goods_good_edit_box');
		html="\
<table width='100%'>\
<tr><td style='width: 120px;'>Артикул</td>\
<td><input type='text' id='good_code' style='width:130px;' value='"+res.code+"'></td></tr>\
<tr><td valign='top'>Название товара</td>\
<td><input type='text' id='good_name' style='width:100%;' value='"+res.name+"'></td></tr>\
<tr><td valign='top'>Описание товара</td>\
<td><textarea id='good_description' style='width:100%;' row='5'>"+res.note+"</textarea></td></tr>\
";
		html+=res.detailsBlock;
		html+="\
</table><br>\
<input type='button' value='Сохранить изменения' style='margin-right:10px;' onClick='goods_save_good_edit("+id+")'>\
<input type='button' value='Отменить' onClick='goods_cansel_good_edit("+id+")'>\
<script type='text/javascript'>\
    CKEDITOR.config.height= '300px';\
	CKEDITOR.config.format_tags = 'p';\
	if (text_editor) CKEDITOR.remove(text_editor);\
	text_editor=CKEDITOR.replace('good_description');\
</script>\
";
		$("#goods_item_block_"+id).html(html);
		$("#goods_good_edit_id").val(id);
		$("#good_description").change();
		$(".prop_img_checkbox").imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
	}
}
*/
//------------------------------------------------------------------------------
function goods_good_edit(id)
{
	admin_info_show('Редактирование товара', '<img src="images/'+loading_icon+'">', 800);
	h=$(window).height()*4/5-60;
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGetGoodEditHtml", id: id, mh: h});
	if (res=='')
		admin_info_change('', '<h2>Ошибка выбора товара</h2>');
	else
	{
		admin_info_change('', res);
		$("#goods_good_edit_id").val(id);
		$(".prop_img_checkbox").imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
	}
}
//------------------------------------------------------------------------------
function goods_save_good_edit(id)
{
    name=$("#good_name").val();
    code=$("#good_code").val();
	if (name=='' || code=='')
	{
		show_message_warning('Редактирование товара', 'Необходимо указать название и артикул товара.');
		return;
	}
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGoodEditCanBeSave", id: id, name: name, code: code});
	if (res!='')
	{
		show_message_warning('Редактирование товара', 'Товар с таким артикулом уже есть.');
		return;
	}
	note=text_editor.getData();
	_err="";
	_need="";
	var c_props='';
	props=$("[id ^= 'prop_']").each(function(){
		if ($(this).is(":checkbox") && !$(this).is(":checked")) c_props=c_props+'&'+$(this).attr('name')+'=';
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
		show_message_warning('Редактирование товара', _err+"\nПожалуйста исправьте ошибки.");
		return;
	}
	props=$("[id ^= 'prop_']").serialize()+c_props;
	$("#goods_list_item_"+id).removeClass('cms_goods_good_edit_box');
	show_wait_image("#goods_item_block_"+id);
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsSaveGoodData", id: id, name: name, note: note, code: code, props: props});
//	if (res!='') alert(res);
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGetGoodDataBlock", id: id});
	$("#goods_list_item_"+id).html(res);
    $("#goods_good_edit_id").val('');
	admin_info_close();
	$('#goods_visible_'+id).imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
}
//------------------------------------------------------------------------------
function goods_good_delete_image(id, imageId, typeId)
{
//	$('#good_image_cell_'+imageId).attr('valign', 'center');
//	show_wait_image('#good_image_cell_'+imageId);
	el="good_images_box_"+$("#goods_good_edit_id").val();
	mask_element(el, function(){
		res=execQuery(goods_widget_ajax_manager_url, {section : "goodsDeleteImage", id: id, imageId: imageId, typeId: typeId});
		$('#good_images_box_'+id).html(res);
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function goods_edit_load_image()
{
	admin_load_image(goods_edit_load_image_uploaded, "*.jpg;*.png");
}
//------------------------------------------------------------------------------
function goods_edit_load_image_uploaded(file)
{
    id=$("#goods_good_edit_id").val();
	typeId=$('#typeId_'+$('#goods_good_edit_id').val()).val()
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsUploadedImageProcess", id: id, typeId: typeId, file : file});
	$('#good_images_box_'+id).html(res);
}
//------------------------------------------------------------------------------
function goods_property_dir_select(type, id)
{
	vals=$("#prop_"+id).val();
	admin_info_show('Выбор из справочника', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGetDirValuesHtml", type: type, id: id, func: 'goods_property_dir_save', vals: vals});
	admin_info_change('', res, 1000);
}
//------------------------------------------------------------------------------
function goods_property_dir_save(id)
{
	good_id=$("#goods_edit_object_id").val();
	props=$("input[type=checkbox][id ^= 'dir_m_']").serialize();
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsDirValuesSave", obj_id: good_id, id: id, props: props});
	if (res!='')
	{
   		res=eval('(' + res + ')');
		$("#prop_"+id).val(res.data);
		$("#propt_"+id).html(res.text);
	}
	admin_info_close();
}
//------------------------------------------------------------------------------
function goods_property_features_select(type, id)
{
	vals=$("#prop_"+id).val();
	admin_info_show('Выбор характеристик', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGetFeaturesHtml", type: type, id: id, func: 'goods_property_features_save', vals: vals});
	admin_info_change('', res, 1000);
}
//------------------------------------------------------------------------------
function goods_property_features_save(id)
{
	good_id=$("#goods_edit_object_id").val();
	props_c=$("input[type=checkbox][id ^= 'dir_fc_']").serialize();
	props_v=$("input[type=text][id ^= 'dir_fv_']").serialize();
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsFeaturesValuesSave", obj_id: good_id, id: id, props_c: props_c, props_v: props_v});
	if (res!='')
	{
   		res=eval('(' + res + ')');
		$("#prop_"+id).val(res.data);
		$("#propt_"+id).html(res.text);
	}
	admin_info_close();
}
//------------------------------------------------------------------------------
function goods_filter_toggle()
{
	$("#goods_filter_data").slideToggle(300, function(){
		if ($("#goods_filter_data").css('display')=='none')
			$('#goods_filter_container').css('border-color', '#ddd');
		else
			$('#goods_filter_container').css('border-color', '#555');
	});
	return;
}
//------------------------------------------------------------------------------
function goods_filter_property_dir_select(id)
{
	vals=$("#goods_filter_prop_"+id).val();
	admin_info_show('Выбор из справочника', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGetDirValuesHtml", id: id, func: 'goods_filter_property_dir_save', vals: vals});
	admin_info_change('', res, 1000);
}
//------------------------------------------------------------------------------
function goods_filter_property_dir_save(id)
{
	props=$("input[type=checkbox][id ^= 'dir_m_']").serialize();
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsDirValuesSave", id: id, props: props});
	if (res!='')
	{
   		res=eval('(' + res + ')');
		$("#goods_filter_prop_"+id).val(res.data);
		$("#goods_filter_propt_"+id).html(res.text);
	}
	admin_info_close();
}
//------------------------------------------------------------------------------
function goods_filter_set()
{
	signature=$('#goods_filter_signature').val().trim();
	name=$('#goods_filter_name').val().trim();
	visible=$('#goods_filter_visible').val();
	$("[id ^= 'goods_filter_prop_']").each(function(){
		try { $(this).val($(this).val().trim()); } catch(e) {}
		if ($(this).attr('data-type')=='d')
		{
			v=parseFloat($(this).val().replace(/,/,'.'));
			if ($(this).val()!="" &&  v!=v)
			{
				$(this).css('border-color', '#f55');
				_err="* Вы указали не правильные значения для выделенных полей.\n";
			}
		}
	});
	pr=$("[id ^= 'goods_filter_prop_']").serialize();
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGetFilterSql", signature: signature, name: name, visible: visible, props: pr});
	if (res!='')
		$("#goods_filter_sql").val(res);
	else
		$("#goods_filter_sql").val('');
	goods_show_list(0);
}
//------------------------------------------------------------------------------
function goods_filter_reset()
{
	sql=$("#goods_filter_sql").val();
	$("[id ^= 'goods_filter_']").each(function(){
		$(this).val('');
	});
	$("[id ^= 'goods_filter_propt_']").each(function(){
		$(this).html('выберите значения');
	});
	if (sql!='')
	{
		$("#goods_filter_sql").val('');
		goods_show_list(0);
	}
}
//------------------------------------------------------------------------------
function goods_move_good(good)
{
	$("#goods_good_move_id").val(good);
	admin_info_show('Выберите подраздел', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkMenuHTML", func: "goods_good_move_selected"});
	admin_info_change('', res, null);
}
//------------------------------------------------------------------------------
function goods_good_move_selected(menu_item, menu, item_name)
{
	admin_info_close();
	good=$("#goods_good_move_id").val();
	res=execQuery(goods_widget_ajax_manager_url, {section : "goodsGoodMove", id: good, menu_item: menu_item});
	goods_show_list(0);
}
//------------------------------------------------------------------------------
function goods_good_type_change()
{
	$("#goods_page").val('0');
	obj_type=goods_get_selected_good_type();
	show_wait_image("#cms_goods_list");
	res=goods_get_goods_list(obj_type);
	$("#cms_goods_list").html(res.list);
	$("#goods_filter_container").html(res.filter);
	$('.img_checkbox').imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
}
//------------------------------------------------------------------------------
