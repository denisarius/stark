//------------------------------------------------------------------------------
var service_files_widget_ajax_manager_url='{@widgets_url@}/{@widget_path@}/{@widget_id@}_widget_dm.php';
var common_widget_ajax_manager_url='{@widgets_url@}/common_widget_dm.php';
//------------------------------------------------------------------------------
function service_fiels_show_unchanged()
{
	$('#service_files_non_changed_files').slideDown(1000);
}
//------------------------------------------------------------------------------
function service_files_update()
{
	window.location='service_files.php?action=update';
}
//------------------------------------------------------------------------------
function service_fiels_restore_changed(tr_id, id)
{
    mask_screen(function(){
		res=execQuery(service_files_widget_ajax_manager_url, {section : "serviceFilesRestoreFile", id: id});
		if (res=='ok')
			$('#'+tr_id).remove();
	});
	clear_mask();
}
//------------------------------------------------------------------------------
function service_fiels_delete_new(tr_id, file)
{
    mask_screen(function(){
		res=execQuery(service_files_widget_ajax_manager_url, {section : "serviceFilesDeleteFile", file: file});
		if (res=='ok')
			$('#'+tr_id).remove();
		else
			alert(res);
	});
	clear_mask();
}
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
