<?php
	require_once 'banners_widget_proc.php';

class TBanners extends TWidget
{
	public $version=array(1, 0, 1);
	protected $widget_title='Баннеры', $menu_section='Сайт', $menu_name='Баннеры';

// -----------------------------------------------------------------------------
function __construct()
{
}
// -----------------------------------------------------------------------------
public function init($parent)
{
    global $_cms_banners_table, $_base_site_banners_images_path;
	$this->widget_tables=array($_cms_banners_table);
	$this->widget_folders=array($_base_site_banners_images_path);
	return parent::init($parent);
}
// -----------------------------------------------------------------------------
public function get_addon_js()
{
	global $_admin_js_url, $_admin_root_url, $_scripts_libs_url;

	return array(
		"$_admin_root_url/uploader/swfupload.js",
//		"$_scripts_libs_url/libs/js/jcrop/0.9.12/jquery.Jcrop.min.js",
		"$_admin_js_url/jcrop/jquery.Jcrop.min.js",
	);
}
// -----------------------------------------------------------------------------
public function show_start_screen()
{
    global $_languages, $_cms_banners_description;

	$language_id=$_languages[0]['id'];
	if (count($_languages)<2) $language_selector_style='display:none;';
	else $language_selector_style='';

    $banner_type_selector=banners_get_banner_type_selector_html();
	echo <<<stop
<div class="cms_banners_container">
<div style="$language_selector_style border-bottom:solid 1px #777; padding-bottom: 15px;"><b>Язык:</b> <select id="banners_language" style="margin-left: 20px; width: 300px;" onChange="banners_language_changed()">
stop;
	$sl='selected';
	foreach($_languages as $l)
	{
		echo "<option value='{$l['id']}' $sl>{$l['title']}</option>";
		$sl='';
	}
	echo <<<stop
</select><br>
</div>
$banner_type_selector

<input type="button" value="Добавить баннер" id="banners_banner_add" onClick="banners_banner_add()">

<div id="banners_banners_list" class="banners_banners_list">
stop;
	$keys=array_keys($_cms_banners_description);
	echo banners_get_banners_list_html($language_id, $keys[0]);
	echo <<<stop
</div>
</div>
stop;
}
// -----------------------------------------------------------------------------
}
?>