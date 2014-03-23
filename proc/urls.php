<?php
//------------------------------------------------------------------------------
// ������� ���������� ��� ���������������� ��������� ����������� � �������
function get_text_url($id, $title, $menu_item, $menu_url = '')
{
	global $_cms_texts_table;

//	echo "[$id][$title][$menu_item][$menu_url]";
	$menu = get_data('menu_item', $_cms_texts_table, "id='$id'");
	return get_menu_url($menu);
}

/**
 * ���������� ������������� url (���� � url) ��� ������ ����.
 * �������� �������� ��� ������� get_menu_id
 * ���������� ��� ����������� ��������� ����������� �����, ������������, ��������, � ��������� ����������� � �������.
 *
 * ������ ����:
 * @pre {/<menu_id>/<parent_0>/.../<parent_n>/<menu_item_id>.html)}
 *
 * @param int $id ������������� ������ ����
 *
 * @return string ������������� url
 */
function get_menu_url($id)
{
	global $_cms_menus_items_table;

	$menu = get_data_array('*', $_cms_menus_items_table, "id='$id'");
	if ($menu === false) return '/'; // �� ����� ����

	$path = get_menu_item_path($id);
	if ($menu['url'] != '') return "$path{$menu['url']}";

	return "$path/$id.html";
}

/**
 * ���������� url-���� � ���� �� ����� ������� �� ��������� ������ ����.
 * � ������, ���� ���� �������� � ������ � ����������� ����, ������ ��������� ���� ����� �� ����.
 * ���� �� ������ ���� �� (�������) ��������� ���������� $parent ��� $menuId, ����� �������� �������������� ������
 * � ��.
 *
 * @param int $id �� ������ ����
 * @param int $parent �� ��������
 * @param int $menuId �� ������ ����
 *
 * @return string ���� � ������� url
 */
function get_menu_item_path($id, $parent = null, $menuId = null)
{
	global $_cms_menus_items_table, $_cms_simple, $_main_menu_id;

	// ������ �� ��� ����������� ����������� ���������?
	if (is_null($parent) or (is_null($menuId) and !$_cms_simple))
		$menuItem = get_data_array('id, parent, menu as menu_id', $_cms_menus_items_table, "id = $id");
	else
	{
		// ����� ��� �������� �� ����������� ����������
		$menuItem = array('id' => $id, 'parent' => $parent, 'menu_id' => $_cms_simple ? $_main_menu_id : $menuId);
	}

	$menuId = $menuItem['menu_id'];
	$path = '';
	while ($menuItem['parent'] != 0)
	{
		$path = "/{$menuItem['parent']}" . $path;
		// ��������
		$menuItem = get_data_array('id, parent', $_cms_menus_items_table, "id = {$menuItem['parent']}");
	}

	return $_cms_simple ? $path : "/$menuId{$path}";
}


/**
 * TODO ��������
 * ���������� �� ������ ���� �� �������������� url.
 * �������� �������� ��� ������� get_menu_url.
 *
 * @param string $url ������������� url
 *
 * @return int �� ������ ����
 */
function get_menu_id($url)
{
	global $pagePath, $_cms_menus_table, $_cms_menus_items_table, $_cms_simple, $_main_menu_id;

	if (is_numeric($p = array_slice($pagePath, -1, 1)))
		return $p;

	$menu_id = $_cms_simple ? $_main_menu_id : $pagePath[0];

//	$menu = false;
//	$resM = new DbResultSet(data("select * from $_cms_menus_table"));
}

//------------------------------------------------------------------------------
function get_sections_url($section, $page)
{
	global $pagePath;
	if ($section < 10000)
		return "/recieps/$section/$page.html";
	else
	{
		$type_id = (int)$pagePath[2];
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