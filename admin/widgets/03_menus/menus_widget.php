<?php
	require_once 'menus_widget_proc.php';

class TMenus extends TWidget
{
	public $version=array(1, 0, 4);
	protected $widget_title='Разделы', $menu_section='Сайт', $menu_name='Разделы';

// -----------------------------------------------------------------------------
function __construct()
{
}
// -----------------------------------------------------------------------------
public function init($parent)
{
	global $_cms_menus_table, $_cms_menus_items_table;
	global $_cms_simple, $_main_menu_id;

    $this->widget_tables=array($_cms_menus_table, $_cms_menus_items_table);
	if ($_cms_simple)
	{
		if (!isset($_main_menu_id))
		{
			echo <<<stop
Необходимо задать значение переменной \$_main_menu_id в файле конфигурации.
stop;
			exit;
		}
		$id=get_data('id', $_cms_menus_table, "id='$_main_menu_id'");
		if ($id===false) query("insert into $_cms_menus_table (id, name) values ('$_main_menu_id', 'Основной раздел')");
	}
	return parent::init($parent);
}
// -----------------------------------------------------------------------------
public function show_start_screen()
{
	global $_cms_simple, $_main_menu_id;

	echo <<<stop
<script type="text/javascript">
$(document).ready(function(){
	$("input[type=checkbox]").imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:true});
	menus_set_cms_simple_mode($_cms_simple, $_main_menu_id);
});
</script>

<input type="hidden" id="menu_id" value="">
<input type="hidden" id="menu_name" value="">
<input type="hidden" id="menu_item_id" value="">
<input type="hidden" id="menu_item_name" value="">
<input type="hidden" id="menu_item_parent" value="">
<input type="hidden" id="menu_item_subtree" value="">
<div id="menu_selector" onClick="menus_menu_select()" class="cms_menu_selector">Выберите раздел</div>
<div id="cms_menu_selector_buttons" class="cms_menu_selector_buttons_container">
<input type="button" class="admin_tool_button" value="Добавить раздел" onClick="menus_add_menu();">
<input type="button" class="admin_tool_button" value="Изменить название раздела" onClick="menus_edit_menu();">
<input type="button" class="admin_tool_button" style="float:right;" value="Удалить раздел" onClick="menus_delete_menu();">
</div>
<div id="cms_menu_top_spacer"></div>
stop;
	echo $this->menus_get_menus_frame(-1, -1);
	echo $this->menus_get_detail_frame(-1);
	if ($_cms_simple)
		echo <<<stop
<br><div class="admin_dev_note">
CMS сконфигурирована в режиме "simple mode".<br>
Основной раздел: ID=$_main_menu_id
</div>
stop;
}
// -----------------------------------------------------------------------------
private function menus_get_menus_frame($menu, $current)
{
	$menu=mysql_safe($menu);
	$html=<<<stop
<div class="cms_menu_menus_container">
<h1>Подразделы</h1><br>
<input type="button" id="menu_add_btn" value="Добавить" onClick="menus_new_menu_item_type_select();">
<input type="button" id="menu_del_btn" value="Удалить" onClick="menus_menu_item_delete();">
<div id="menu_items_list">
stop;
	if ($menu!=-1) $html.=menus_get_menus_items_group($menu, 0, $current, '');
	$html.='</div></div>';
	return $html;
}
// -----------------------------------------------------------------------------
private function menus_get_menus_items_group($menu, $parent, $current, $prefix)
{
	global $_cms_menus_items_table;

	$initial_current='';
	$html_header='<ul class="sortable_menu">';
	$res=query("select * from $_cms_menus_items_table where menu='$menu' and parent=$parent order by sort, id");
	$i=1;
	$html_inner='';
	while($r=mysql_fetch_assoc($res))
	{
        if ($current==-1)
		{
			$current=$r['id'];
			$initial_current="<input type='hidden' id='menu_items_initial_current' value='$current'>";
		}
    		$html_inner.= <<<stop
<li id="li-{$r['id']}"><span class="cms_menu_items_list_prefix">$prefix$i.</span> <a onMouseDown="menus_menu_item_list_select({$r['id']});"><span id="name-{$r['id']}">{$r['name']}</span></a></li>
stop;
    	$html_inner.=menus_get_menus_items_group($menu, $r['id'], $current, "$prefix$i.");
		$i++;
	}
	mysql_free_result($res);
	$html_footer='</ul>';
	if ($html_inner=='') $html='';
	else $html=$html_header.$html_inner.$html_footer;
	if ($initial_current!='') $html.=$initial_current;
    return $html;
}
//------------------------------------------------------------------------------
private function menus_get_detail_frame($menu_item_id)
{
	global $_cms_menus_items_table;

	$menu_item=get_data_array('*', $_cms_menus_items_table, "id='$menu_item_id'");
	$menu_item['name']=htmlspecialchars($menu_item['name'], ENT_QUOTES);
	$subtree=htmlspecialchars(menus_get_menu_subtree($menu_item_id), ENT_QUOTES);
	if (strlen($menu_item['name'])<50)
		$ed=<<<stop
<input type="text" id="menus_menu_item_name" onkeypress="return menu_item_data_edit_keypress(event);" value="{$menu_item['name']}" style="width:322px;">
stop;
	else
		$ed=<<<stop
<textarea id="menus_menu_item_name" style="width:322px;" onkeypress="return menu_item_data_edit_keypress(event);">{$menu_item['name']}</textarea>
stop;
	$vis_ch='';
	if ($menu_item_id!=-1 && $menu_item['visible']==0) $vis_ch='checked="checked"';

	echo <<<stop
<div id="menus_menu_item_detail" class="cms_menus_menu_item_detail">
<h1>Редактирование подраздела</h1><br>

<b>ID:</b>
<span id="menu_item_id_label">{$menu_item['id']}</span><br><br>

<h3>Название:</h3>
<div id="menu_item_name_edit_control">$ed</div>

<h3>URL:</h3>
<div id="menu_item_url_edit_control"><input type="text" id="menus_menu_item_url" onkeypress="return menu_item_data_edit_keypress(event);" value="{$menu_item['url']}" style="width:322px;"></div>

<h3>Tag:</h3>
<div><i>(не меняйте если не знаете что это такое)</i></div>
<div id="menu_item_tag_edit_control"><input type="text" id="menus_menu_item_tag" onkeypress="return menu_item_data_edit_keypress(event);" value="{$menu_item['tag']}" style="width:322px;"></div>

<br><h3>Скрыть: <input type="checkbox" id="menus_menu_item_visible" $vis_ch></h3>

<br><input type="button" value="Сохранить" id="menu_item_data_edit_save" onClick="menu_item_data_edit_save();">
<br><hr>
<h3>Тэг для ссылки на подраздел:</h3>
<div id="menu_item_link_tag"></div>
<br><hr>
<h3>Быстрые ссылки</h3>
<div id="menu_item_quick_links" class="menu_item_quick_links"></div>
</div>

<script type="text/javascript">
$(window).scroll(function() {
	if ($("#menus_menu_item_detail").css("margin-top")!=0)
    	$("#menus_menu_item_detail").css("margin-top", $(window).scrollTop());
});
</script>
stop;
}
// -----------------------------------------------------------------------------
}

?>
