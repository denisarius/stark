// -----------------------------------------------------------------------------
function _pmf_window_resize()
{
	h=$(window).height();
	w=$(window).width();
	$('#pmf_frame_left').height(h);
	$('#pmf_left_frame_content').height(h-40);
	$('#pmf_frame_thumbnails').height(h);
	dw=$('#pmf_frame_left').width();
	$('#pmf_frame_thumbnails').width(w-dw);
	y_toolbar=$('#pmf_toolbar').outerHeight(true);
	y_status=$('#pmf_status').outerHeight(true);
	$('#pmf_thumbnails_frame_content').height(h-21-y_toolbar-y_status+10);
}
// -----------------------------------------------------------------------------
function _pmf_execQuery(params)
{
	url=window.location.href;
	url=url.substring(0, url.lastIndexOf('/'))+'/pmfinder_dm.php';
	return execQuery(url, params);
}
// -----------------------------------------------------------------------------
function pmf_set_url(funcNum, url)
{
	window.opener.CKEDITOR.tools.callFunction(funcNum, url);
	close();
}
// -----------------------------------------------------------------------------
function pmf_select_folder(id)
{
	$("[id ^= 'pmf_folder_']").removeClass('pmf_folder_node_active');
	$('#pmf_folder_'+id).addClass('pmf_folder_node_active');
	mask_element('pmf_thumbnails_frame_content', function(){
		p=$('#pmf_folder_'+id).attr('data');
		$('#cPath').val(p);
		res=_pmf_execQuery({section : "pmfGetFilesList", path: p});
		$('#pmf_thumbnails_frame_content').html(res);
		$("#pmf_toolbar_path").html($('#pmf_folder_'+id).attr('data-status'));
	});
	clear_mask();
}
// -----------------------------------------------------------------------------
function pmf_select_item(id)
{
	url=$('#pmf_image_'+id).attr('src');
	func=$('#CKEditorFuncNum').val();
	pmf_set_url(func, url);
}
// -----------------------------------------------------------------------------
function pmf_image_upload()
{
	admin_load_image(pmf_image_uploaded, "*.jpg;*.png;*.gif");
}
//------------------------------------------------------------------------------
function pmf_image_uploaded(file)
{
	p=$('#cPath').val();
	mask_element('pmf_thumbnails_frame_content', function(){
		res=_pmf_execQuery({section : "pmfImageProcess", file : file, path: p});
		$('#pmf_thumbnails_frame_content').html(res);
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function pmf_folder_add()
{
	html='\
<p>Введите имя создаваемой папки</p>\
<input type="text" id="pmf_folder_add_folder_name" style="width: 276px;"/>\
';
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog("destroy");
	$("#_show_dialog").attr("title", 'Создание папки');
	$("#_show_dialog").html(html);
	$("#_show_dialog").dialog({
		modal: true,
		draggable: true,
		resizable: false,
		dialogClass: "_admin_dialogs",
		buttons: {
				"Создать": function() {
					$( this ).dialog( "close" );
					pmf_folder_create();
				},
				"Отмена": function() {
					$( this ).dialog( "close" );
				}
			}
	});

}
//------------------------------------------------------------------------------
function pmf_folder_create()
{
	name=$("#pmf_folder_add_folder_name").val().trim();
	p=$('#cPath').val();
	mask_element('pmf_thumbnails_frame_content', function(){
		res=_pmf_execQuery({section : "pmfFolderCreate", name : name, path: p});
		res=eval('(' + res + ')');
		if (res.err!='')
			show_message_warning('Создание папки', res.err);
		else
		{
			$("#pmf_left_frame_content").html(res.dir_tree);
			$("#pmf_thumbnails_frame_content").html(res.files);
			$('#cPath').val(res.cur_path);
		}
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function pmf_folder_delete()
{
	p=$("[id ^= 'pmf_folder_'].pmf_folder_node_active").attr('data');
	ps=$("[id ^= 'pmf_folder_'].pmf_folder_node_active").attr('data-status');
	if (ps=='/' || ps=='')
	{
		show_message_warning('Удаление папки', 'Нельзя удалить корневую папку');
		return;
	}
	res=_pmf_execQuery({section : "pmfFolderDeleteIsPossible", path: p});
	if (res!='1')
	{
		show_message_warning('Удаление папки', 'Нельзя удалять непустую папку');
		return;
	}
//	mask_element('pmf_thumbnails_frame_content', function(){
		res=_pmf_execQuery({section : "pmfFolderDelete", path: p});
//		alert(res);
		res=eval('(' + res + ')');
		if (res.err!='')
			show_message_warning('Удаление папки', res.err);
		else
		{
			$("#pmf_left_frame_content").html(res.dir_tree);
			$("#pmf_thumbnails_frame_content").html(res.files);
		}
//	});
//	clear_mask();
}
//------------------------------------------------------------------------------
function pmf_image_delete(id)
{
	src=$('#pmf_image_'+id).attr('src');
	file=$('#pmf_file_name_'+id).html();
	path=$('#pmf_folder_item_'+id).attr('data');
	html='\
<img src="'+src+'" style="max-width: 200px; max-height: 200px; margin: 0 20px 15px 0; display: block; float: left;"/>\
<p><b>'+file+'</b></p>\
<p>Вы действительно хотите удалить это изображение?</p>\
';
	confirm_box('Удаление изображения', html, function(){
		_pmf_execQuery({section : "pmfImageDelete", file: path});
		$('#pmf_folder_item_'+id).remove();
	}, 400);
}
//------------------------------------------------------------------------------
