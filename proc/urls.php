<?php
//------------------------------------------------------------------------------
// Функция необходима для функционирования обработки псевдотэгов в текстах
function get_text_url($id, $title, $menu_item, $menu_url = '')
{
	global $_cms_texts_table;

//	echo "[$id][$title][$menu_item][$menu_url]";
	$menu = get_data('menu_item', $_cms_texts_table, "id='$id'");
	return get_menu_url($menu);
}

/**
 * Возвращает относительный url (путь в url) для пункта меню.
 * Является обратной для функции get_menu_id
 * Необходимо для поддержания ссылочной целостности сайта, используется, например, в обработке псевдотэгов в текстах.
 *
 * Формат пути:
 * @pre {/<menu_id>/<parent_0>/.../<parent_n>/<menu_item_id>.html)}
 *
 * @param int $id идентификатор пункта меню
 *
 * @return string относительный url
 */
function get_menu_url($id)
{
	global $_cms_menus_items_table;

	$menu = get_data_array('*', $_cms_menus_items_table, "id='$id'");
	if ($menu === false) return '/'; // не нашли меню

	$path = get_menu_item_path($id);
	$menuUrl = ltrim($menu['url'], '/');
	if ($menu['url'] != '') return "$path/$id/$menuUrl";

	return "$path/$id.html";
}

/**
 * Возвращает url-путь в меню от корня раздела до заданного пункта меню.
 * В случае, если сайт работает в режиме с несколькими меню, первым элементом пути будет ид меню.
 * Если не указан один из (реально) требуемых параметров $parent или $menuId, будет выполнен дополнительный запрос
 * к БД.
 *
 * @param int $id ид пункта меню
 * @param int $parent ид родителя
 * @param int $menuId ид дерева меню
 *
 * @return string путь в формате url
 */
function get_menu_item_path($id, $parent = null, $menuId = null)
{
	global $_cms_menus_items_table, $_cms_simple, $_main_menu_id;

	// заданы не все необходимые фактические параметры?
	if (is_null($parent) or (is_null($menuId) and !$_cms_simple))
		$menuItem = get_data_array('id, parent, menu as menu_id', $_cms_menus_items_table, "id = $id");
	else
	{
		// можем все получить из фактических параметров
		$menuItem = array('id' => $id, 'parent' => $parent, 'menu_id' => $_cms_simple ? $_main_menu_id : $menuId);
	}

	$menuId = $menuItem['menu_id'];
	$path = '';
	while ($menuItem['parent'] != 0)
	{
		$path = "/{$menuItem['parent']}".$path;
		// родитель
		$menuItem = get_data_array('id, parent', $_cms_menus_items_table, "id = {$menuItem['parent']}");
	}

	return $_cms_simple ? $path : "/$menuId{$path}";
}


// ПРОЧИЕ ХЕЛПЕРЫ

function get_content()
{
	global $pagePath, $_cms_texts_table;
	$text_id = (int)array_slice($pagePath, -1, 1);
	return get_data_array('*', $_cms_texts_table, "menu_item=$text_id");
}

/**
 * Возвращает ид пункта меню, находящегося на активном пути иерархии меню на заданной глубине.
 *
 * @param int $level - глубина искомого пункта. если не задана, то ищем для текущего пункта.
 * уровень 1 соответствут ид корня дерева текущего меню (учитывает режим $_cms_simple)
 *
 * @return int ид пункта меню, если найден, иначе -1
 */
function get_menu_item_id($level = 0)
{
	global $pagePath, $_cms_simple;

	// если последний элемент пути - число, то это ид меню
	$p = array_slice($pagePath, -1, 1);
	if (is_numeric($p[0]))
		$menuId = (int)$p[0];
	else
	{
		// иначе ид меню должен быть в предпоследнем элементе
		$p = array_slice($pagePath, -2, 1);
		if (is_numeric($p[0]))
			$menuId = (int)$p[0];
		else
			return -1;
	}

	if (!$level)
		return $menuId;

	// path - путь от корня до текущего пункта меню включительно
	$path = explode('/', get_menu_item_path($menuId));
	array_shift($path);
	$path[] =  $menuId;

	if (!$_cms_simple)
		// убираем ид дерева меню
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