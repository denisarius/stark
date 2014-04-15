<?php

class GadgetLights
{
	public function __construct()
	{
		$this->_urlData = UrlLights::parseUrl();
		$this->_typeId = $this->_urlData['type'];
	}

	function getCurrentType()
	{
		global $_cms_menus_items_table;


		$res = get_data('name', $_cms_menus_items_table, "id={$this->_typeId}");

		if ($res === false)
		{
			show_404_text();
			return;
		}
		else
			return $res;
	}

	function getItemsLayout()
	{
		global $_cms_goods_images_url, $_cms_tree_node_table;

		$items = get_data_array_rs('*', $_cms_tree_node_table, "parent={$this->_typeId}");
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

	public function _getItemLayout($params)
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

	function _getTypesMenuLayout()
	{
		global $_cms_menus_items_table, $_shop_menu_id;
		$types = get_data_array_rs('name, id', $_cms_menus_items_table, "menu=$_shop_menu_id AND parent=0");
		$typesLayout = '';
		while ($type = $types->next())
		{
			$link = UrlLights::getSectionUrl($type['id']);
			$typesLayout .= "<li><a href=\"$link\">{$type['name']}</a></li>";
		}
		return "<ul>$typesLayout</ul>";
	}


	private $_urlData;
	private $_typeId;

	private $_views = array(
		'item' => <<<ITEM
<div class="right_column_node">
	<img src="::image::">

	<h2>::name::</h2>
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
	public static function getSectionUrl($lightType, $lightCategory = 0, $page = 0)
	{
		$menuId = get_menu_item_id();
		return "/lights/$lightType/$lightCategory/$menuId/$page.html";
	}

	public static function parseUrl()
	{
		global $pagePath;
		return array('type' => $pagePath[1], 'category' => $pagePath[2], 'page' => $pagePath[4]);
	}
}