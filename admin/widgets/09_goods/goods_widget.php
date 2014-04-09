<?php
	require_once 'goods_widget_proc.php';

class TGoods extends TWidget
{
	public $version=array(1, 2, 0);
	protected $widget_title='Товары', $menu_section='Магазин', $menu_name='Товары';

// -----------------------------------------------------------------------------
function __construct()
{
}
// -----------------------------------------------------------------------------
public function init($parent)
{
    global $_cms_tree_node_table, $_cms_tree_node_details, $_cms_shop_orders, $_cms_shop_orders_goods;
	global $_cms_goods_images_path;
	$this->widget_tables=array($_cms_tree_node_table, $_cms_tree_node_details, $_cms_shop_orders, $_cms_shop_orders_goods);
	$this->widget_folders=array($_cms_goods_images_path, "$_cms_goods_images_path/thumbs");
	return parent::init($parent);
}
// -----------------------------------------------------------------------------
public function get_addon_js()
{
	global $_admin_js_url, $_admin_root_url, $_scripts_libs_url;

	return array(
		"$_admin_root_url/uploader/swfupload.js",
		"$_admin_js_url/autoresize.jquery.min.js",
		"$_scripts_libs_url/colorbox/jquery.colorbox-min.js",
		"$_scripts_libs_url/rangy/1.3/rangy-core.js",
		"$_scripts_libs_url/rangy/1.3/rangy-textrange.js",
		"$_scripts_libs_url/ckeditor/3.6.2/ckeditor.js",
	);
}
// -----------------------------------------------------------------------------
public function show_start_screen()
{
	echo <<<stop
<script type="text/javascript">
$(document).ready(function() {
    CKEDITOR.config.toolbar = [
		['Source', '-', 'Maximize'],
		['Undo','Redo','-','Cut','Copy','Paste','PasteText', '-', 'Find','Replace'],
		['RemoveFormat','Bold','Italic','Underline','Strike', 'Subscript','Superscript'],
        ['TextColor','BGColor'],
        ['NumberedList','BulletedList','-', 'Outdent', 'Indent'],
        ['Table','HorizontalRule','SpecialChar']
    ];
});
</script>

<div class="cms_goods_container">
<div id="goods_menu_selector" onClick="goods_menu_select()">Выберите раздел с товарами</div>
<div id="goods_type_selector_container"></div>
<div id="goods_filter_container"></div>
<input type="hidden" value="0" id="goods_menu_id">
<input type="hidden" value="0" id="goods_menu_item_id">
<input type="hidden" value="0" id="goods_page">
<input type="hidden" value="" id="goods_good_edit_id">
<input type="hidden" value="" id="goods_good_move_id">
stop;
//	$type_selector=goods_good_type_selector_html($category, $obj_type);
//	$filter=goods_get_filter_html();
	echo <<<stop
<div id="cms_goods_list"></div>
</div>
stop;
}
// -----------------------------------------------------------------------------
}
?>