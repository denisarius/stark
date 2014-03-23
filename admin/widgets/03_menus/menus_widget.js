//------------------------------------------------------------------------------
var menus_widget_ajax_manager_url='{@widgets_url@}/{@widget_path@}/{@widget_id@}_widget_dm.php';
var common_widget_ajax_manager_url='{@widgets_url@}/common_widget_dm.php';
//------------------------------------------------------------------------------
function menus_set_cms_simple_mode(simple_mode, main_menu_id)
{
	if (simple_mode==1)
	{
		$("#menu_id").val(main_menu_id);
		$("#menu_selector").css('display', 'none');
		$("#cms_menu_selector_buttons").css('display', 'none');
		$("#cms_menu_top_spacer").css('height', '10px');
		menus_menu_select_change(main_menu_id, '');
	}
}
//------------------------------------------------------------------------------
function menus_menu_select()
{
    mask_screen(function(){
		res=execQuery(common_widget_ajax_manager_url, {section : "commonGetMenusList", func: 'menus_menu_select_change'});
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
				show_message("Выбор раздела", "В данный момент не созданно ни одного раздела.<br><br>Для создания раздела нажмите кнопку <b>'Добавить раздел'</b>.");
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function menus_add_menu()
{
    mask_screen_noicon(function(){
		$("#_show_dialog:ui-dialog").dialog("close");
		$("#_show_dialog:ui-dialog").dialog("destroy");
		$("#_show_dialog").attr("title", "Добавление Раздела");
		h=$(window).height()*2/3;
		$("#_show_dialog").html("\
Введите название раздела<br>\
<input type='text' style='width: 300px' id='menus_new_menu_name'>\
");
		$("#_show_dialog").dialog({
			width: 400,
			modal: true,
			resizable: false,
			buttons: {
				"Записать": function() {
					menus_add_menu_save($("#menus_new_menu_name").val());
				},
				"Отменить": function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function menus_add_menu_save(name)
{
	name=name.trim();
	if (name=='')
	{
		alert("Необходимо ввести название раздела");
		return;
	}
	res=execQuery(menus_widget_ajax_manager_url, {section : "menusAddMenu", name: name});
	res=eval('(' + res + ')');
	if (res.status=='no')
	{
		alert("Раздел с таким названием уже существует");
		return;
	}
	menus_menu_select_change(res.menu_id, res.menu_name);
}
//------------------------------------------------------------------------------
function menus_menu_select_change(id, name)
{
	$("#menu_id").val(id);
	$("#menu_name").val(name);
	if (id!='')
	{
		$("#menu_selector").html(name+" [&nbsp;ID="+id+"&nbsp;]");
		res=execQuery(menus_widget_ajax_manager_url, {section : "menusGetMenuItemList", id: id});
		$("#menu_items_list").html(res);
		if (res!='')
		{
			current=$("#menu_items_initial_current").val();
			if (current!='') menus_menu_item_list_select(current);
		}
		else
			$("#menu_item_id, #menu_item_name, #menu_item_parent, #menu_item_subtree, #menus_menu_item_name, #menus_menu_item_url").val('');
	}
	else
	{
		$("#menu_items_list").html('');
		$("#menu_selector").html("Выберите раздел");
		$("#menu_id, #menu_name, #menu_item_id, #menu_item_name, #menu_item_parent, #menu_item_subtree, #menus_menu_item_name, #menus_menu_item_url").val('');
	}
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog("destroy");
	menus_refresh_sortable();
}
//------------------------------------------------------------------------------
function menus_delete_menu()
{
	id=$("#menu_id").val();
	name=$("#menu_name").val();
	if (id=='')
	{
		show_message('Удаление раздела', 'Сначала нужно выбрать раздел');
		return;
	}
	html="Вы уверены что хотите удалить раздел <b>'"+name+"'<b>?";
	confirm_box("Удаление меню", html, function(){
		res=execQuery(menus_widget_ajax_manager_url, {section : "menusDeleteMenu", id : id});
		if (res=='no') show_message('Удаление раздела', 'Произошла ошибка при удалении раздела');
		if (res=='texts') show_message('Удаление раздела', 'Выбранный раздел <b>не может быть удален</b> так как содержит связанные с ним тексты.<br><br>Перед удалением раздела необходимо удалить все связанные с ним тексты или переместить их в другие разделы.');
		if (res=='ok') menus_menu_select_change('', '');
		menus_refresh_sortable();
	});
}
//------------------------------------------------------------------------------
function menus_edit_menu()
{
	menu_id=$("#menu_id").val();
	if (menu_id=='' || menu_id==0)
		show_message("Изменение названия раздела", "Необходимо выбрать раздел");
	else
	{
		res=execQuery(menus_widget_ajax_manager_url, {section : "menusMenuNameCahangeGetHTML", id : menu_id});
		if (res=="") return;
		admin_info_show("Изменение названия раздела", res, null);
	}
}
//------------------------------------------------------------------------------
function menus_edit_menu_save(id)
{
	name=$("#menus_menu_edit_name").val().trim();
	if (name=='')
		show_message("Изменение названия раздела", "Необходимо ввести название раздела");
	else
	{
		admin_info_close();
	    mask_screen(function(){
			res=execQuery(menus_widget_ajax_manager_url, {section : "menusMenuNameChangeSave", id : id, name: name});
		});
		clear_mask();
		$("#menu_selector").html(name+" [ ID="+id+" ] ");
	}
}
//------------------------------------------------------------------------------
function menus_menu_item_list_select(id)
{
	if (id=='') return;
	c=$("#menu_item_id").val();
	$("[id ^= 'li-']").removeClass("cms_menu_items_current");
	$("#li-"+id).addClass("cms_menu_items_current");
    mask_element('menus_menu_item_detail', function(){
		res=execQuery(menus_widget_ajax_manager_url, {section : "menusMenuItemGetData", id : id});
		if (res!='')
		{
			res=eval('(' + res + ')');
			$("#menu_item_id").val(id);
			$("#menu_item_id_label").html(id);
			$("#menu_item_name").val(res.name);
			$("#menu_item_parent").val(res.parent);
			$("#menu_item_subtree").val(res.subtree);
			if (res.name.length<50)
				$('#menu_item_name_edit_control').html("<input type='text' id='menus_menu_item_name' onkeypress='return menu_item_data_edit_keypress(event);' value='"+res.name+"' style='width:322px;'>");
			else
			{
				l=res.name.length/40+1;
				$('#menu_item_name_edit_control').html("<textarea id='menus_menu_item_name' style='width:322px;' onkeypress='return menu_item_data_edit_keypress(event);' rows='"+l+"'>"+res.name+"</textarea>");
			}
			$("#menus_menu_item_url").val(res.url);
			$("#menus_menu_item_tag").val(res.tag);
			if (res.visible==0) res.visible=false;
			else res.visible=true;
			$("#menus_menu_item_visible").imagecbox_set(!res.visible);
			$("#menu_item_link_tag").html(texts_edit_tag_generate_link_menu(id, "Link text", "[Link title]"));
			$("#menu_item_quick_links").html(res.buttons);
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function menu_item_data_edit_save()
{
    mask_screen(function(){
    	id=$("#menu_item_id").val();
		name=$("#menus_menu_item_name").val();
		url=$("#menus_menu_item_url").val();
		tag=$("#menus_menu_item_tag").val();
		if (!$("#menus_menu_item_visible").is(":checked")) vis=1;
		else vis=0;
		res=execQuery(menus_widget_ajax_manager_url, {section : "menusMenuItemSaveData", id : id, name : name, url : url, tag : tag, visible: vis});
		$("#name-"+id).html(res);
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function menu_item_data_edit_keypress(e)
{
	if (e.which==13) menu_item_data_edit_save();
	return true;
}
//------------------------------------------------------------------------------
function menus_show_hint(prefix, current, total)
{
	for (i=1; i<=total; i++)
		$("#"+prefix+i).css("display", "none");
	$("#"+prefix+current).css("display", "");
}
//------------------------------------------------------------------------------
function menus_new_menu_item_type_select()
{
	if ($("#menu_id").val()=="")
	{
		show_message("Добавление подраздела", "Необходимо выбрать раздел в который добавляется подраздел");
		return;
	}
	$("#_show_dialog:ui-dialog").dialog( "destroy" );
	$("#_show_dialog").attr("title", "Добавление подраздела");
	current=$("#menu_item_id").val();
	parent_cat=$("#menu_item_parent").val();
	subtree=$("#menu_item_subtree").val();
	html="\
<p><b>Текущий подраздел:</b></p>\
<p>"+subtree+"</p>\
<div style='width:100px;height:120px;float:right;margin-top:50px;'>\
<img src='images/menus_new_top_level.png' id='_cat_new_1'>\
<img src='images/menus_new_in_current.png' id='_cat_new_2' style='display:none'>\
<img src='images/menus_new_to_current.png' id='_cat_new_3' style='display:none'>\
</div>\
<div style='float:left'>\
<p><b>Добавить:</b></p>\
<input type='button' value='Основной подраздел (верхнего уровня)' onClick='menus_new_menu_item_add(0)' onMouseOver='menus_show_hint(\"_cat_new_\", 1, 3)'><br><br>\
";
	if (parent_cat!='' && parent_cat!=0)
		html+="<input type='button' value='Подраздел в текущую секцию' onClick='menus_new_menu_item_add(parent_cat)' onMouseOver='menus_show_hint(\"_cat_new_\", 2, 3)'><br><br>";
    if (current!='' && current!=0)
		html+="<input type='button' value='Подраздел в выбранный раздел' onClick='menus_new_menu_item_add(current)' onMouseOver='menus_show_hint(\"_cat_new_\", 3, 3)'><br><br>";
	html+="</div><br>";
	$("#_show_dialog").html(html);
	x=$("#menu_add_btn").position().left;
	y=$("#menu_add_btn").position().top+$("#menu_add_btn").outerHeight();
	$("#_show_dialog").dialog({
		width: 400,
		modal: true,
		resizable: false,
		position: [x, y]
	});
}
//------------------------------------------------------------------------------
function menus_new_menu_item_add(parent_cat)
{
	subtree=$("#menu_item_subtree").val();
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog("destroy");
	$("#_show_dialog").attr("title", "Добавление подраздела");
	html="";
	if (subtree!='')
		html+="\
<p><b>Текущий подраздел:</b></p>\
<p>"+subtree+"</p>\
";
	html+="\
<p><b>Название подраздела:</b></p>\
<input type='hidden' id='new_menu_item_parent' value='"+parent_cat+"'>\
<input type='text' id='menus_new_menu_item_name' style='width: 100%;'><br><br>\
<input type='button' value='Записать' onClick='menus_new_menu_item_save()'><br><br>\
";
	$("#_show_dialog").html(html);
	$("#_show_dialog").dialog({
		modal: true,
		resizable: false,
	});
}
//------------------------------------------------------------------------------
function menus_new_menu_item_save()
{
	if ($("#menus_new_menu_item_name").val()=='')
	{
		alert("Необходимо указать название подраздела");
		return;
	}
	menu=$("#menu_id").val();
	parent=$("#new_menu_item_parent").val();
	name=$("#menus_new_menu_item_name").val();
	current=$("#menu_item_id").val();
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog("destroy");
    mask_screen(function(){
		res=execQuery(menus_widget_ajax_manager_url, {section : "menusAddMenuItem", menu: menu, current : current, parent: parent, name: name});
		res=eval('(' + res + ')');
		if (res.status=='ok')
		{
			$("#menu_items_list").html(res.list_html);
			menus_refresh_sortable();
            menus_menu_item_list_select(res.current_id);
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function menus_save_sort_state(obj, event, ui)
{
	res='';
    mask_element('menu_items_list', function(){
		menu=$("#menu_id").val();
		current=$("#menu_item_id").val();
    	sort=obj.sortable('toArray').toString();
		if (sort!='')
		{
			res=execQuery(menus_widget_ajax_manager_url, {section : "menusMenuItemSotrChange", menu: menu, sort : sort, current: current});
			if (res!='') $("#menu_items_list").html(res);
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function menus_refresh_sortable()
{
	$(".sortable_menu" ).sortable({
		items: ">li",
		cancel: ".sort-disabled",
		placeholder: "ui-state-highlight cms_menus_menu_sort_placeholder",
		axis: "y",
		cursor: "n-resize",
		distance: 5,
		update: function(event, ui){
			menus_save_sort_state($(this), event, ui);
			setInterval(menus_refresh_sortable, 200);
		}
	});
	$( ".sortable_menu li" ).disableSelection();
}
//------------------------------------------------------------------------------
function menus_menu_item_delete()
{
	id=$("#menu_item_id").val();
	menu=$("#menu_id").val();
	name=$("#menus_menu_item_name").val();
    mask_screen(function(){
		res=execQuery(menus_widget_ajax_manager_url, {section : "menusMenuItemCanDelete", id : id});
		if (res!='yes')
			alert("Эту подраздел нельзя удалать т.к. он содержит подразделы или связанные тексты.");
		else
		{
			html="\
<p>Вы действительно хотите удалить подраздел:</p>\
<p><b>"+name+"</b></p>\
";
			confirm_box("Удаление подраздела", html, function()
			{
				res=execQuery(menus_widget_ajax_manager_url, {section : "menusMenuItemDelete", id : id, menu : menu});
				res=eval('(' + res + ')');
				if (res.status=='ok')
				{
					$("#menu_items_list").html(res.list_html);
		            menus_menu_item_list_select(res.current_id);
				}
			});
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

