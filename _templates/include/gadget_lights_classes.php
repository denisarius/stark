<?php


/**
 * Отвечает за базовые функции отображения каталога: вывод меню, доступ к информации из урла
 */
class GadgetLightsBase
{
	public function __construct()
	{
		$this->_urlData = $this->parseUrl();
		$this->_typeId = (int)$this->_urlData['type'];
	}

	public function getCurrentTypeId()
	{
		return $this->_typeId;
	}

	public function getCurrentTypeName()
	{
		global $_cms_menus_items_table;


		$res = get_data('name', $_cms_menus_items_table, "id={$this->getCurrentTypeId()}");

		if ($res === false)
		{
			show_404_text();
			return;
		}
		else
			return $res;
	}

	public function getTypesMenuLayout()
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

	protected function _getDetails($itemId, $paramNames, $paramLabels, $template = '@label@: @value@')
	{
		$details = shop_get_goods_details($itemId, $paramNames);

		// превращаем измерения размера в один параметр size
		if (@$details['height'] && @$details['length'] && @$details['width'])
		{
			$details['size'] = implode(
				'x',
				array_intersect_key($details, array('height' => 1, 'length' => 1, 'width' => 1))
			);
		}
		unset($details['height'], $details['length'], $details['width']);

		// формируем строки '<параметр> <значение>' по переданному шаблону
		$ret = array();
		foreach ($paramLabels as $name => $label)
		{
			if (isset($details[$name]))
			{
				$ret[] = str_replace(array('@label@', '@value@'), array($label, $details[$name]), $template);
			}
		}

		return $ret;
	}

	protected function renderTemplate($params, $template)
	{
		$paramNames = array();
		$paramValues = array();
		foreach ($params as $name => $value)
		{
			$paramNames[] = "::$name::";
			$paramValues[] = $value;
		}

		return str_replace($paramNames, $paramValues, $template);
	}

	private function getSectionUrl($lightType, $lightCategory = 0, $page = 0)
	{
		$menuId = get_menu_item_id();
		return "/lights/$lightType/$lightCategory/$menuId/$page.html";
	}

	public static function getDetailsUrl($lightType, $itemId)
	{
		$menuId = get_menu_item_id();
		return "/light-details/$lightType/$menuId/$itemId.html";
	}

	public static function parseUrl()
	{
		global $pagePath;
		if ($pagePath[0] === 'lights')
			return array('type' => $pagePath[1], 'category' => $pagePath[2], 'page' => $pagePath[4]);
		elseif ($pagePath[0] === 'light-details')
			return array('type' => $pagePath[1], 'id' => $pagePath[3]);
	}

	protected $_urlData;
	// текущий (активный в меню) тип
	private $_typeId;
}

class GadgetLightDetails extends GadgetLightsBase
{

	public function __construct()
	{
		parent::__construct();
		$this->_id = (int)$this->_urlData['id'];
	}

	public function getItemLayout()
	{
		global $_cms_goods_images_url, $_cms_tree_node_table;
		$item = get_data_array('*', $_cms_tree_node_table, "id={$this->_id}");
		$layoutParams = shop_get_goods_details($this->_id, array('image', 'maker', 'price'));
		$layoutParams['image'] = "$_cms_goods_images_url/{$layoutParams['image']}";

		$detailNames = array('country', 'length', 'width', 'height', 'diametr', 'lamp_type');
		$layoutParams['details'] = implode('',$this->_getDetails(
			$this->_id,
			$detailNames,
			array(
				'maker' => 'Производитель',
				'size' => 'Размеры ВхДхШ',
				'diametr' => 'Диаметр',
				'lamp_type' => 'Тип лампочки (основной)',
				'country' => 'Страна'
			),
			'<li><span class="left">@label@</span><span class="right">@value@</span></li>'
		));

		$layoutParams['name'] = $item['name'];

		return $this->renderTemplate($layoutParams, $this->_views['item']);
	}

	private $_views = array(
		'item' => <<<ITEM
	<div class="product_info_container">
		<img src="::image::" alt="">
		<div class="product_info_about">
			<h2>::name::</h2>
			<span>Производитель: ::maker::</span>
			<ul>
				::details::
			</ul>
			<br>
			<div class="product_info_order">
								<span>Под заказ<br>
								Цена:<span> ::price:: р.</span></span>
			</div>
			<div clsas=""></div>
			<div class="product_info_about_tosearch">К результатам поиска</div>
		</div>
	</div>
ITEM

	);

//<li><span class="left">Артикул</span><span class="right">::code::</span></li>
//<li><span class="left">Производитель</span><span class="right">::maker::</span></li>
//<li><span class="left">Страна</span><span class="right">::country::</span></li>
//<li><span class="left">Размеры ВхДхШ </span><span class="right">::size::</span></li>
//<li><span class="left">Тип лампочки (основной)</span><span class="right">::lamp_type::</span></li>
//<li><span class="left">Мощность лампы</span><span class="right">::power::</span></li>
//<li><span class="left">Количество ламп</span><span class="right">::lamp_count::</span></li>
//<li><span class="left">Площадь освещения </span><span class="right">::area:: м2</span></li>
//<li><span class="left">Коллекция</span><span class="right">::collection::</span></li>
//<li><span class="left">Тип светильника</span><span class="right"> Люстры</span></li>
//<li><span class="left">Стиль светильника</span><span class="right">::style::</span></li>
//<li><span class="left">Способ размещения </span><span class="right">::placing::</span></li>
//<li><span class="left">Материал</span><span class="right">::material::</span></li>
//<li><span class="left">Цвет</span><span class="right">::color::</span></li>
//<li><span class="left">Лампочки в комплекте</span><span class="right">::lamp_exists::</span></li>

	// id товара
	private $_id;
}

class GadgetLightsList extends GadgetLightsBase
{
//	public function __construct()
//	{
//		parent::__construct();
//	}

	public function getItemsLayout()
	{
		global $_cms_goods_images_url, $_cms_tree_node_table;

		$items = get_data_array_rs('*', $_cms_tree_node_table, "parent={$this->getCurrentTypeId()}");
		$itemsLayout = '';
		while ($item = $items->next())
		{
			$layoutParams = shop_get_goods_details($item['id'], array('image', 'maker', 'price'));
			$layoutParams['image'] = "$_cms_goods_images_url/thumbs/{$layoutParams['image']}";

			$detailNames = array('country', 'length', 'width', 'height', 'diametr', 'lamp_type');
			$layoutParams['details'] = implode(
				'<br/>',
				$this->_getDetails(
					$item['id'],
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
			$layoutParams['link_details'] = $this->getDetailsUrl($this->getCurrentTypeId(), $item['id']);

			$itemsLayout .= $this->renderTemplate($layoutParams, $this->_views['item']);
		}
		return $itemsLayout;
	}


	private $_views = array(
		'item' => <<<ITEM
<div class="right_column_node">
	<a href="::link_details::">
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


}