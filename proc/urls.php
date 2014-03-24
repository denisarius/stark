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
	$menuUrl = ltrim($menu['url'], '/');
	if ($menu['url'] != '') return "$path/$id/$menuUrl";

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
		$path = "/{$menuItem['parent']}".$path;
		// ��������
		$menuItem = get_data_array('id, parent', $_cms_menus_items_table, "id = {$menuItem['parent']}");
	}

	return $_cms_simple ? $path : "/$menuId{$path}";
}


// ������ �������

function get_content()
{
	global $pagePath, $_cms_texts_table;
	$text_id = (int)array_slice($pagePath, -1, 1);
	return get_data_array('*', $_cms_texts_table, "menu_item=$text_id");
}

/**
 * ���������� �� ������ ����, ������������ �� �������� ���� �������� ���� �� �������� �������.
 *
 * @param int $level - ������� �������� ������. ���� �� ������, �� ���� ��� �������� ������.
 * ������� 1 ������������ �� ����� ������ �������� ���� (��������� ����� $_cms_simple)
 *
 * @return int �� ������ ����, ���� ������, ����� -1
 */
function get_menu_item_id($level = 0)
{
	global $pagePath, $_cms_simple;

	// ���� ��������� ������� ���� - �����, �� ��� �� ����
	$p = array_slice($pagePath, -1, 1);
	if (is_numeric($p[0]))
		$menuId = (int)$p[0];
	else
	{
		// ����� �� ���� ������ ���� � ������������� ��������
		$p = array_slice($pagePath, -2, 1);
		if (is_numeric($p[0]))
			$menuId = (int)$p[0];
		else
			return -1;
	}

	if (!$level)
		return $menuId;

	// path - ���� �� ����� �� �������� ������ ���� ������������
	$path = explode('/', get_menu_item_path($menuId));
	array_shift($path);
	$path[] =  $menuId;

	if (!$_cms_simple)
		// ������� �� ������ ����
		array_shift($path);

	$maxLevel = count($path);
	if ($level <= $maxLevel)
		return (int)$path[$level-1];
	else
		return -1;
}

function get_menu_root_id()
{
	global $pagePath;

	return (int)$pagePath[0];
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