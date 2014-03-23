<?php
//------------------------------------------------------------------------------
// Функция необходима для функционирования обработки псевдотэгов в текстах
function get_text_url($id, $title, $menu_item, $menu_url='')
{
	global $_cms_texts_table;

//	echo "[$id][$title][$menu_item][$menu_url]";
	$menu=get_data('menu_item', $_cms_texts_table, "id='$id'");
	return get_menu_url($menu);
}
//------------------------------------------------------------------------------
// Функция необходима для функционирования обработки псевдотэгов в текстах
function get_menu_url($id)
{
	global $_cms_menus_items_table, $language;

	$menu=get_data_array('*', $_cms_menus_items_table, "id='$id'");
	if ($menu===false) return '/';		// не нашли меню

	if ($menu['url']!='') return $menu['url'];

	return "/$id.html";
	// <menu_id>/<parent_0>/.../<parent_n>/<menu_item_id>.html
}
//------------------------------------------------------------------------------
function get_reciep_url($id)
{
	return "/reciep/$id.html";
}
//------------------------------------------------------------------------------
function get_sections_url($section, $page)
{
	global $pagePath;
	if ($section<10000)
		return "/recieps/$section/$page.html";
	else
	{
		$type_id=(int)$pagePath[2];
		return "/recieps/10000/$type_id/$page.html";
	}
}
//------------------------------------------------------------------------------
function get_contents_url($section, $page)
{
	return "/contents/$section/$page.html";
}
//------------------------------------------------------------------------------
function get_info_url($id)
{
	return "/info/$id.html";
}
//------------------------------------------------------------------------------
?>