<?php
	require_once 'directories_widget_proc.php';

class TDirectories extends TWidget
{
	public $version=array(1, 0, 3);
	protected $widget_title='Справочники', $menu_section='Магазин', $menu_name='Справочники';

// -----------------------------------------------------------------------------
function __construct()
{
}
// -----------------------------------------------------------------------------
public function init($parent)
{
    global $_cms_directories, $_cms_directories_data;
	$this->widget_tables=array($_cms_directories, $_cms_directories_data);
	return parent::init($parent);
}
// -----------------------------------------------------------------------------
public function get_addon_js()
{
	global $_admin_js_url;

	return array(
		"$_admin_js_url/autoresize.jquery.min.js",
	);
}
// -----------------------------------------------------------------------------
public function show_start_screen()
{
    global $_cms_shop_directories;
	echo <<<stop
<div class="dirs_container">
<b>Справочники</b>
<select id="dirs_current_dir" onChange="dirs_dir_changed()">
<option value="-1"></option>
stop;
	echo dirs_get_dirs_html(-1);
	echo <<<stop
</select>
<input type="button" value="Добавить справочник" id="dirs_dir_add" onClick="dirs_dir_add()" style="float:left; margin-right: 20px;" />
<input type="button" value="Изменить название справочника" id="dirs_dir_edit" onClick="dirs_dir_edit()" style="float:left; margin-right: 20px;" />
<br>
<div id="dirs_dir_content" class="dirs_dir_content">
</div>
</div>
stop;
}
// -----------------------------------------------------------------------------
}
?>