<?php
	require_once 'typed_objects_widget_proc.php';

class TTyped_objects extends TWidget
{
	public $version=array(1, 0, 6);
	protected $menu_section='Объекты';

// -----------------------------------------------------------------------------
function __construct()
{
	global $_cms_objects_types;

    $this->widget_title=array();
    $this->menu_name=array();
    $this->url=array();
	foreach($_cms_objects_types as $obj)
	{
		if(isset($obj['menu_item_id']) && $obj['menu_item_id']!='')
		{
        	$this->widget_title[$obj['id']]=$obj['name'];
        	$this->menu_name[$obj['id']]=$obj['name'];
        	$this->url[$obj['id']]="typed_objects.php?objtype={$obj['id']}";
		}
	}
}
// -----------------------------------------------------------------------------
public function init($parent)
{
    global $_cms_objects_table, $_cms_objects_details, $_cms_text_parts, $_base_site_objects_images_path, $_base_site_structured_text_images_path;
	$this->widget_tables=array($_cms_objects_table, $_cms_objects_details, $_cms_text_parts);
	$this->widget_folders=array($_base_site_objects_images_path, $_base_site_structured_text_images_path);
	return parent::init($parent);
}
// -----------------------------------------------------------------------------
public function get_addon_js()
{
	global $_admin_js_url, $_admin_root_url, $_scripts_libs_url;

	return array(
		"$_admin_root_url/uploader/swfupload.js",
		"$_scripts_libs_url/rangy/1.3/rangy-core.js",
		"$_scripts_libs_url/rangy/1.3/rangy-textrange.js",
		"$_scripts_libs_url/ckeditor/3.6.2/ckeditor.js",
	);
}
// -----------------------------------------------------------------------------
public function get_title()
{
    global $_cms_objects_types, $objtype;

	queryImportVars('objtype', false);
	$obj=typed_objects_get_object_description($objtype);
	if ($obj!==false) return $obj['name'];
	return '';
}
// -----------------------------------------------------------------------------
public function show_start_screen()
{
	global $_cms_menus_table, $_cms_menus_items_table, $menu_id, $objtype;

	queryImportVars('menu_id|objtype', true);
	if (!isset($menu_id) || $menu_id=='') { $menu_id_init=-1; $menu_id=-1; }
	if (strpos($menu_id, ',')!==false) $menu_id_init=-1;
	if (isset($objtype) && $objtype!='')
    {
		$obj=typed_objects_get_object_description($objtype);
		if (isset($obj['menu_item_id']) && $obj['menu_item_id']!='') $menu_id=$obj['menu_item_id'];
	}
	$menu_id_init=$menu_id;
	$selector=common_get_menu_item_selector($menu_id, 'Выберите раздел', 'typed_objects_menu_item_select_change');

	echo <<<stop
<script type="text/javascript">
$(document).ready(function() {
    CKEDITOR.config.toolbar = [
		['Source', '-', 'Maximize'],
		['Undo','Redo','-','Cut','Copy','Paste','PasteText', '-', 'Find','Replace'],
		['RemoveFormat','Bold','Italic','Underline','Strike', 'Subscript','Superscript'],
        ['TextColor','BGColor'],
        ['NumberedList','BulletedList','-', 'Outdent', 'Indent'],
        ['Link','Unlink','Anchor'],
        ['Table','HorizontalRule','SpecialChar']
    ];
	CKEDITOR.on( 'instanceReady', function( e ){ admin_info_center(); } );
});
</script>

$selector
<div class="typed_objects_container">
<input type="hidden" value="$_cms_simple" id="simple_mode">
<input type="hidden" value="$menu_id_init" id="typed_objects_menu_item_id">
<input type="hidden" value="$menu_item_fixed" id="typed_objects_menu_item_fixed">
<input type="hidden" value="$objtype" id="typed_objects_init_object_type">
<input type="hidden" value="0" id="typed_objects_list_page">
<input type="hidden" value="0" id="typed_objects_object_id">
<input type="hidden" value="" id="typed_objects_object_edit_id">
<input type="hidden" value="" id="typed_objects_object_edit_old_html">

<div id="typed_objects_objects_list"></div>
</div>
stop;
	if ($menu_id) echo <<<stop
<script type="text/javascript">typed_objects_show_objects_list_page(0);</script>
stop;
}
// -----------------------------------------------------------------------------
}
?>
