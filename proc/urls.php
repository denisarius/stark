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
 * Возвращает относительный url (путь в url) для подраздела меню.
 * Является обратной для функции get_menu_item_id
 * Необходимо для поддержания ссылочной целостности сайта, используется, например, в обработке псевдотэгов в текстах.
 *
 * Формат пути:
 * 1. Для текстовых подразделов (не иерархический, "плоский" урл):  @pre{ /<menu_item_id>.html }.
 * 2. Для заданных статических урлов: @pre{ /<menu_item_id>/menu_item_url },
 *   где menu_item_url не должен быть числом и иерархическим путем.
 * 3. Для заданных динамических урлов: @pre{ /<menu_item_url>/<menu_item_id>/<число>.html },
 *   где <menu_item_url> может быть произвольным и содержать специфическую информацию для данного раздела
 *
 * Таким образом, имеется соглашение, что ид подраздела меню располагается либо в конце "плоского" урла, (<id>.html),
 * либо на предпоследнем сегменте пути иерархического урла.
 *
 * @param int $id идентификатор подраздела меню
 *
 * @return string относительный url
 */
// TODO верифицировать на соответствие соглашению
function get_menu_url($id)
{
	global $_cms_menus_items_table;

	$menu = get_data_array('*', $_cms_menus_items_table, "id='$id'");
	if ($menu === false) return '/'; // не нашли меню

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
 * Возвращает путь в меню от корня подраздела до заданного подраздела меню.
 * Если не указан требуемый  (реально) параметр $parent, будет выполнен дополнительный запрос к БД.
 *
 * @param int $id ид подраздела меню
 * @param int $parent ид родителя
 *
 * @return array путь
 */
function get_menu_item_path($id, $parent = null)
{
	global $_cms_menus_items_table, $_cms_simple, $_main_menu_id;

	// кэшируем активный путь, т.к. он не изменяется
	static $path;

	if (isset($path[$id]))
		return $path[$id];

	// Получим данные текущего подраздела для запуска цикла обхода дерева

	// заданы не все необходимые фактические параметры?
	if (is_null($parent))
		$menuItem = get_data_array('id, parent', $_cms_menus_items_table, "id = $id");
	else
	{
		// можем все получить из фактических параметров
		$menuItem = array('id' => $id, 'parent' => $parent);
	}

	// пройдем по дереву вверх
	$path = array();
	while ($menuItem['parent'] != 0)
	{
		array_unshift($path, $menuItem['parent']);
		// родитель
		$menuItem = get_data_array('id, parent', $_cms_menus_items_table, "id = {$menuItem['parent']}");
	}

	return $path;
}


// ПРОЧИЕ ХЕЛПЕРЫ

function get_content()
{
	global $pagePath, $_cms_texts_table;
	$text_id = (int)end($pagePath);
	return get_data_array('*', $_cms_texts_table, "menu_item=$text_id");
}

/**
 * TODO верифицировать на соответствие соглашению
 * Возвращает ид подраздела меню, находящегося на активном пути на заданной глубине.
 * Активный путь - это путь от корня до активного подраздела.
 *
 * @param int $level - глубина искомого подраздела. если не задана, то ищем для активного подраздела.
 * уровень 1 соответствут ид корня дерева текущего меню (учитывает режим $_cms_simple)
 *
 * @return int ид подраздела меню (>0), если найден, иначе 0
 */
function get_menu_item_id($level = 0)
{
	global $pagePath;

	// если последний элемент пути - число >0, то это ид меню
	$p = end($pagePath);
	if (is_numeric($p) and $p)
		$menuId = (int)$p;
	else
	{
		// иначе ид меню должен быть в предпоследнем элементе
		$p = array_slice($pagePath, -2, 1);
		if (is_numeric($p[0]))
			$menuId = (int)$p[0];
		else
			return 0;
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
 * Проверяет, существет ли на активной ветке меню контента (меню 2-го уровеня)
 */
function content_menu_exists()
{
	// активный путь проходит через второй уровень?
	if (get_menu_item_id(2))
		return true;

	// мы на первом уровне, значит остается проверить, есть ли у активного подраздела потомки
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