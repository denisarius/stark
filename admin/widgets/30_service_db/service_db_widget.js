//------------------------------------------------------------------------------
var service_db_widget_ajax_manager_url='{@widgets_url@}/{@widget_path@}/{@widget_id@}_widget_dm.php';
var common_widget_ajax_manager_url='{@widgets_url@}/common_widget_dm.php';
//------------------------------------------------------------------------------
function service_db_buckup_create()
{
	$('#service_backup_submit_block').html('');
	return true;
}
//------------------------------------------------------------------------------
function service_db_select_all()
{
	$('#service_backup_form #tbls').attr('checked', true);
}
//------------------------------------------------------------------------------
function service_db_select_none()
{
	$('#service_backup_form #tbls').attr('checked', false);
}
//------------------------------------------------------------------------------
function service_restore_db(file)
{
	$('#service_db_restore_table').remove();
	$('#restore_db_debug_img').html("\
<div id='restore_progress_bar_block' class='admin_progress_bar_block'>\
<div id='restore_progress_bar_image' class='admin_progress_bar_image'></div></div>\
");
	$('#restore_progress_bar_image').width(0);
	$('#service_restore_frame').attr('src', 'service.php?action=restore_backup&file='+file);
}
//------------------------------------------------------------------------------
function service_restore_db_iteration(cmd)
{
	$('#service_restore_frame').attr('src', 'service.php?'+cmd);
}
//------------------------------------------------------------------------------
function service_restore_db_show_state(state, progress)
{
	$('#restore_progress_bar_image').width($('#restore_progress_bar_block').innerWidth()*progress);
	$('#restore_db_debug').html(state);
}
//------------------------------------------------------------------------------
function service_restore_db_show_end_state()
{
	$('#restore_db_debug').html('');
	$('#restore_db_debug_img').html('');
}
//------------------------------------------------------------------------------
