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
 * ���������� ������������� url (���� � url) ��� ���������� ����.
 * ���������� ��� ����������� ��������� ����������� �����, ������������, ��������, � ��������� ����������� � �������.
 *
 * ������ ����:
 * 1. ��� ��������� ����������� (�� �������������, "�������" ���):  @pre{ /<menu_item_id>.html }.
 * 2. ��� �������� ����������� �����: @pre{ /<menu_item_id>/menu_item_url },
 *   ��� menu_item_url �� ������ ���� ������ � ������������� �����.
 * 3. ��� �������� ������������ �����: @pre{ /<menu_item_url>/<menu_item_id>/<�����>.html },
 *   ��� <menu_item_url> ����� ���� ������������ � ��������� ������������� ���������� ��� ������� �������,
 *   ��� ���� <�����> ����� ����� ������������ ����������� ������� (����� ��������, �� ������ � �.�.)
 *
 * ����� �������, ������� ����������, ��� �� ���������� ���� ������������� ���� � ����� "��������" ����, (<id>.html),
 * ���� �� ������������� �������� ���� �������������� ����.
 *
 * @param int $id ������������� ���������� ����
 *
 * @return string ������������� url
 */
function get_menu_url($id)
{
	global $_cms_menus_items_table;

	$menu = get_data_array('*', $_cms_menus_items_table, "id='$id'");
	if ($menu === false) return '/'; // �� ����� ����

	$menuUrl = ltrim($menu['url'], '/');
	if ($menu['url'] != '')
	{
		if (strpos($menu['url'], 'html') === false)
			return "/$menuUrl/$id/0.html";
		else
			return "/$id/$menuUrl";
	}

	return "/$id.html";
}

/**
 * ���������� ���� � ���� �� ����� ���������� �� ��������� ���������� ����.
 * ���� �� ������ ���������  (�������) �������� $parent, ����� �������� �������������� ������ � ��.
 *
 * @param int $id �� ���������� ����
 * @param int $parent �� ��������
 *
 * @return array ����
 */
function get_menu_item_path($id, $parent = null)
{
	global $_cms_menus_items_table, $_cms_simple, $_main_menu_id;

	// �������� �������� ����, �.�. �� �� ����������
	static $path;

	if (isset($path[$id]))
		return $path[$id];

	// ������� ������ �������� ���������� ��� ������� ����� ������ ������

	// ������ �� ��� ����������� ����������� ���������?
	if (is_null($parent))
		$menuItem = get_data_array('id, parent', $_cms_menus_items_table, "id = $id");
	else
	{
		// ����� ��� �������� �� ����������� ����������
		$menuItem = array('id' => $id, 'parent' => $parent);
	}

	// ������� �� ������ �����
	$path = array();
	while ($menuItem['parent'] != 0)
	{
		array_unshift($path, $menuItem['parent']);
		// ��������
		$menuItem = get_data_array('id, parent', $_cms_menus_items_table, "id = {$menuItem['parent']}");
	}

	return $path;
}


// ������ �������

function get_content()
{
	global $pagePath, $_cms_texts_table;
	$text_id = (int)end($pagePath);
	return get_data_array('*', $_cms_texts_table, "menu_item=$text_id");
}

/**
 * ���������� �� ���������� ����, ������������ �� �������� ���� �� �������� �������.
 * �������� ���� - ��� ���� �� ����� �� ��������� ����������.
 *
 * @param int $level - ������� �������� ����������. ���� �� ������, �� ���� ��� ��������� ����������.
 * ������� 1 ������������ �� ����� ������ �������� ���� (��������� ����� $_cms_simple)
 *
 * @return int �� ���������� ���� (>0), ���� ������, ����� 0 (��������� ��������)
 */
function get_menu_item_id($level = 0)
{
	global $pagePath;

	// ���� ��������� ������� ���� - ����� >0, �� ��� �� ����
	$p = end($pagePath);
	if (is_numeric($p) and count($pagePath) == 1)
		$menuId = (int)$p;
	else
	{
		// ����� �� ���� ������ ���� � ������������� ��������
		$p = array_slice($pagePath, -2, 1);
		if (is_numeric($p[0]))
			$menuId = (int)$p[0];
		else
		{
			error_log('failed to parse menu id for url: '.$_SERVER['REQUEST_URI']);
			return 0;
		}
	}

	if (!$level)
		return $menuId;

	$path = get_menu_item_path($menuId);
	$path[] = $menuId;

	$maxLevel = count($path);
	if ($level <= $maxLevel)
		return (int)$path[$level - 1];
	else
		return 0;
}


/**
 * ���������, ��������� �� �� �������� ����� ���� �������� (���� 2-�� �������)
 */
function content_menu_exists()
{
	// �������� ���� �������� ����� ������ �������?
	if (get_menu_item_id(2))
		return true;

	// �� �� ������ ������, ������ �������� ���������, ���� �� � ��������� ���������� �������
	global $_cms_menus_items_table;
	return false !== get_data('id', $_cms_menus_items_table, 'parent = '.get_menu_item_id());
}

//------------------------------------------------------------------------------
function get_sections_url($section, $page)
{
	global $pagePath;


//	if ($section < 10000)
//		return "/recieps/$section/$page.html";
//	else
//	{
//		$type_id = (int)$pagePath[2];
//		return "/recieps/10000/$type_id/$page.html";
//	}
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