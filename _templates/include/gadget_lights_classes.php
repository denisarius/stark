<?php


/**
 * Отвечает за базовые функции отображения каталога: вывод меню, доступ к информации из урла
 */
class GadgetLightsBase
{
	public function __construct()
	{
		$this->_urlData = UrlLights::parseUrl();
		$this->_typeId = $this->_urlData['type'];
	}

	public function getTypeId()
	{
		return $this->_typeId;
	}

	public  function getTypesMenuLayout()
	{
		global $_cms_menus_items_table, $_shop_menu_id;
		$types = get_data_array_rs('name, id', $_cms_menus_items_table, "menu=$_shop_menu_id AND parent=0");
		$typesLayout = '';
		while ($type = $types->next())
		{
			$link = $this->getSectionUrl($type['id']);
			$class = ($type['id'] == $this->_typeId) ? 'class="current"' : '';
			$typesLayout .= "<li $class ><a href=\"$link\">{$type['name']}</a></li>";
		}
		return "<ul>$typesLayout</ul>";
	}

	private  function getSectionUrl($lightType, $lightCategory = 0, $page = 0)
	{
		$menuId = get_menu_item_id();
		return "/lights/$lightType/$lightCategory/$menuId/$page.html";
	}

	protected $_urlData;
	// текущий (активный в меню) тип
	private $_typeId;
}


class GadgetLightsList extends GadgetLightsBase
{
//	public function __construct()
//	{
//		parent::__construct();
//	}

	public function getCurrentTypeName()
	{
		global $_cms_menus_items_table;


		$res = get_data('name', $_cms_menus_items_table, "id={$this->getTypeId()}");

		if ($res === false)
		{
			show_404_text();
			return;
		}
		else
			return $res;
	}

	public function getItemsLayout()
	{
		global $_cms_goods_images_url, $_cms_tree_node_table;

		$items = get_data_array_rs('*', $_cms_tree_node_table, "parent={$this->getTypeId()}");
		$itemsLayout = '';
		while ($item = $items->next())
		{
			$layoutParams = shop_get_goods_details($item['id'], array('image', 'maker', 'price'));
			$layoutParams['image'] = "$_cms_goods_images_url/thumbs/{$layoutParams['image']}";

			$detailNames = array('country', 'length', 'width', 'height', 'diametr', 'lamp_type');
			$layoutParams['details'] = implode(
				'<br/>',
				$this->_getDetails(
					$item,
					$detailNames,
					array(
						'size' => 'Размеры',
						'diametr' => 'Диаметр',
						'lamp_type' => 'Тип ламп',
						'country' => 'Производство'
					)
				)
			);

			$layoutParams['name'] = $item['name'];
			$layoutParams['link_descr'] = UrlLights::getDetailsUrl($this->getTypeId(), $item['id']);

			$itemsLayout .= $this->_getItemLayout($layoutParams);
		}
		return $itemsLayout;
	}

	private function _getDetails($item, $paramNames, $paramLabels, $template = '')
	{
		$details = shop_get_goods_details($item['id'], $paramNames);

		// превращаем измерения размера в один параметр size
		if (@$details['height'] && @$details['length'] && @$details['width'])
		{
			$details['size'] = implode(
				'x',
				array_intersect_key($details, array('height' => 1, 'length' => 1, 'width' => 1))
			);
		}
		unset($details['height'], $details['length'], $details['width']);

		// формируем строки <параметр>: <значение>
		$ret = array();
		foreach ($paramLabels as $name => $label)
		{
			if (isset($details[$name]))
			{
				$ret[] = "{$label}: {$details[$name]}";
			}
		}

		return $ret;
	}

	private function _getItemLayout($params)
	{
		$paramNames = array();
		$paramValues = array();
		foreach ($params as $name => $value)
		{
			$paramNames[] = "::$name::";
			$paramValues[] = $value;
		}

		return str_replace($paramNames, $paramValues, $this->_views['item']);
	}

	private $_views = array(
		'item' => <<<ITEM
<div class="right_column_node">
	<a href="::link_descr::">
	<img src="::image::">
	<h2>::name::</h2>
	</a>
	<p>Производитель: ::maker::</p>
	<span>::details::</span>
	<div class="right_column_node_price">Цена: <span>::price::</span></div>
	<div class="right_column_node_button">Подобрать</div>
</div>
ITEM
	);

}

class UrlLights
{

	public static function getDetailsUrl($lightType, $itemId)
	{
		$menuId = get_menu_item_id();
		return "/light-descr/$lightType/$menuId/$itemId.html";
	}

	public static function parseUrl()
	{
		global $pagePath;
		if ($pagePath[0] === 'lights')
			return array('type' => $pagePath[1], 'category' => $pagePath[2], 'page' => $pagePath[4]);
		elseif ($pagePath[0] === 'light-descr')
			return $pagePath[2];
	}
}