<?php
	require_once 'constants_widget_proc.php';

class TConstants extends TWidget
{
	public $version=array(1, 0, 2);
	protected $widget_title='Константы', $menu_section='Сайт', $menu_name='Константы';

// -----------------------------------------------------------------------------
function __construct()
{
}
// -----------------------------------------------------------------------------
public function init($parent)
{
	global $_cms_constants_table;

    $this->widget_tables=array($_cms_constants_table);
	return parent::init($parent);
}
// -----------------------------------------------------------------------------
public function get_addon_js()
{
	global $_admin_js_url, $_scripts_libs_url;

	return array(
		"$_admin_js_url/autoresize.jquery.min.js",
	);
}
// -----------------------------------------------------------------------------
public function show_start_screen()
{
	$list=constants_get_list_html();
	echo <<<stop
<div class="constants_container">
<br><input type="button" value="Добавить константу" onClick="constants_add_constant()">
<input type="hidden" value="" id="constants_constant_edit_id">
<input type="hidden" value="" id="constants_constant_edit_old_item_html">
<div id="constants_constant_add_container"></div>
<div id="constants_constants_list">$list</div>

</div>
stop;
}
// -----------------------------------------------------------------------------
}

?>
