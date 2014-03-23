<?php
	require_once 'texts_widget_proc.php';

class TTexts extends TWidget
{
	public $version=array(1, 0, 3);
	protected $widget_title='Тексты', $menu_section='Сайт', $menu_name='Тексты';

// -----------------------------------------------------------------------------
function __construct()
{
}
// -----------------------------------------------------------------------------
public function init($parent)
{
	global $_cms_texts_table, $_cms_menus_table, $_cms_menus_items_table, $_cms_constants_table;

	$this->widget_tables=array($_cms_texts_table, $_cms_menus_table, $_cms_menus_items_table, $_cms_constants_table, '_vfs');
	return parent::init($parent);
}
// -----------------------------------------------------------------------------
public function get_addon_js()
{
	global $_admin_js_url, $_scripts_libs_url;

	return array(
		"$_admin_js_url/autoresize.jquery.min.js",
		"$_scripts_libs_url/rangy/1.3/rangy-core.js",
		"$_scripts_libs_url/rangy/1.3/rangy-textrange.js",
		"$_scripts_libs_url/ckeditor/3.6.2/ckeditor.js",
	);
}
// -----------------------------------------------------------------------------
public function show_start_screen()
{
	global $menu_id, $_admin_js_url, $_cms_simple, $_main_menu_id, $_cms_menus_table, $_cms_menus_items_table;

	queryImportVars('menu_id', true);
	$menu_item_name='';
	if (!isset($menu_id) || $menu_id=='') $menu_id=0;
	else $menu_item_name=get_data('name', $_cms_menus_items_table, "id='$menu_id'");
	$menu_item_name=strtr($menu_item_name, array("\""=>"&quot;", "\\"=>"\\\\", "'" => "\\'"));

	echo <<<stop
<script type="text/javascript">
$(window).scroll(function() {
	texts_scroll_objects_check();
});

$(document).ready(function() {
	CKEDITOR.config.filebrowserImageBrowseUrl = '/admin/js/pmfinder/pmfinder.php?type=images';

    CKEDITOR.config.toolbar = [
		['Source','Preview', '-', 'Maximize'],
		['Undo','Redo','-','Cut','Copy','Paste','PasteText','PasteFromWord', '-', 'Find','Replace'],
		['Styles', 'Format', 'RemoveFormat','Bold','Italic','Underline','Strike', 'Subscript','Superscript'],
        ['TextColor','BGColor'],
        ['NumberedList','BulletedList','-', 'Outdent', 'Indent'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Link','Unlink','Anchor'],
        ['Image','Table','HorizontalRule','SpecialChar']
    ];

	CKEDITOR.stylesSet.add( 'text_styles',
        [
            { name : 'Left side image', element : 'img', attributes : { 'class' : 'image_left' } },
            { name : 'Right side image', element : 'img', attributes : { 'class' : 'image_right' } },
            { name : 'InText gallery', element : 'img', attributes : { 'class' : 'in_text_gallery' } },
            { name : 'No border', element : 'table', attributes : { 'class' : 'no_border' } },
            { name : 'Table header', element : 'td', attributes : { 'class' : 'table_header' } },
        ]);
    CKEDITOR.config.stylesSet = 'text_styles';
	CKEDITOR.config.forcePasteAsPlainText = true;
	CKEDITOR.config.toolbarCanCollapse = false;

	texts_set_cms_simple_mode($_cms_simple, $_main_menu_id, $menu_id, '{$menu_item_name}');
});

</script>
stop;

	$cnt=get_data('count(*)', $_cms_menus_table);
	if (!$cnt)
	{
		echo <<<stop
<div class="cms_menu_item_selector">Не создано ни одного раздела. Сначала нужно создать хотя бы один раздел выбрав пункт меню "<a href="cms_menus.php">Разделы</a>"</div>
stop;
		return;
	}

	if ($cnt<2)
	{
		$cnt_i=get_data('count(*)', $_cms_menus_items_table);
		if (!$cnt_i)
		{
			echo <<<stop
<div class="cms_menu_item_selector">Основной раздел не содержит ни одного подраздела. Сначала нужно создать хотя бы один подраздел в основном <a href="cms_menus.php">разделе</a></div>
stop;
			return;
		}
		$menu=get_data_array('id, name', $_cms_menus_table);
		if ($menu_id)
		{
			$mi=get_data_array('*', $_cms_menus_items_table, "id='$menu_id'");
			$selector=<<<stop
<input type="hidden" value="{$menu['id']}" id="texts_menu_id">
<div id="texts_menu_selector" class="cms_menu_selector">{$menu['name']}</div>
<div id="texts_menu_item_selector_container">
<br><div id="texts_menu_item_selector" onClick="texts_menu_item_select({$menu['id']})" class="cms_menu_item_selector">{$mi['name']}</div><div id="texts_go_to_content_block"></div>
</div>
stop;
		}
		else
			$selector=<<<stop
<input type="hidden" value="{$menu['id']}" id="texts_menu_id">
<div id="texts_menu_selector" class="cms_menu_selector">{$menu['name']}</div>
<div id="texts_menu_item_selector_container">
<br><div id="texts_menu_item_selector" onClick="texts_menu_item_select({$menu['id']})" class="cms_menu_item_selector">Выберите подраздел</div><div id="texts_go_to_content_block"></div>
</div>
stop;
	}
	else
	{
		if (!$menu_id)
	 		$selector=<<<stop
<input type="hidden" value="0" id="texts_menu_id">
<div id="texts_menu_selector" onClick="texts_menu_select()" class="cms_menu_selector">Выберите раздел</div>
<div id="texts_menu_item_selector_container"></div>
stop;
		else
		{
			$mi=get_data_array('*', $_cms_menus_items_table, "id='$menu_id'");
			$mp=get_data_array('*', $_cms_menus_table, "id='{$mi['menu']}'");
	 		$selector=<<<stop
<input type="hidden" value="{$mp['id']}" id="texts_menu_id">
<div id="texts_menu_selector" onClick="texts_menu_select()" class="cms_menu_selector">{$mp['name']}</div>
<div id="texts_menu_item_selector_container">
<br><div id="texts_menu_item_selector" onClick="texts_menu_item_select({$mp['id']})" class="cms_menu_item_selector">{$mi['name']}</div><div id="texts_go_to_content_block"></div>
</div>
stop;
		}
	}
	echo <<<stop
$selector
<div class="texts_container">
<input type="hidden" value="$_cms_simple" id="simple_mode">
<input type="hidden" id="texts_menu_item_id" value="$menu_id">
<input type="hidden" value="0" id="texts_text_page">
<input type="hidden" value="0" id="texts_text_id">
<br><div id="texts_texts_list">
</div>

<div class='cms_texts_text_edit_tool_panel' id='texts_text_edit_tool_panel'>
stop;
// Кнопка "вставка ссылки на текст" не отображается в "Simple Mode"
	if (!$_cms_simple) echo <<<stop
<input type='button' class='admin_tool_button texts_edit_tool_button' value='Вставить ссылку на текст' onClick='texts_edit_insert_text_link("texts_link_text_text_select")'>
stop;
	echo <<<stop
<input type='button' class='admin_tool_button texts_edit_tool_button' value='Вставить ссылку на подраздел' onClick='texts_edit_insert_menu_link("texts_link_text_menu_select")'>
<input type='button' class='admin_tool_button texts_edit_tool_button' value='Вставить ссылку на документ' onClick='texts_edit_insert_document_link("texts_link_text_document_select")'>
<input type='button' class='admin_tool_button texts_edit_tool_button' value='Вставить константу' onClick='texts_edit_insert_constant("texts_constant_select")'>
<!-- input type='button' class='admin_tool_button texts_edit_tool_button' value='Найти текст по сигнатуре' onClick="texts_edit_get_text_name()" -->
<input type='button' class='admin_tool_button texts_edit_tool_button' value='Изменить тэг' onClick="texts_edit_edit_pseudotag()">
</div>
</div>

<div id="cms_texts_top_spacer"></div>
stop;
	if (!$_cms_simple) echo <<<stop
<script type="text/javascript">
	texts_show_text_list();
</script>
stop;
}
// -----------------------------------------------------------------------------
}

?>
