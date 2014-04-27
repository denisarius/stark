<?php

class View
{
	protected function renderTemplate($params, $template)
	{
		$paramNames = array();
		$paramValues = array();
		foreach ($params as $name => $value)
		{
			$paramNames[] = "@$name@";
			$paramValues[] = $value;
		}

		return str_replace($paramNames, $paramValues, $template);
	}
}

/**
 * Отвечает за базовые функции отображения каталога: вывод меню, доступ к информации из урла
 */
class GadgetLightsBase extends View
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
			$link = self::getSectionUrl($type['id']);
			$class = ($type['id'] == $this->_typeId) ? 'class="current"' : '';
			$typesLayout .= "<li $class ><a href=\"$link\">{$type['name']}</a></li>";
		}
		return "<ul>$typesLayout</ul>";
	}



	/**
	 * Формирует сверстанный блок характерирсик товара.
	 *
	 * @param int $itemId ид товара
	 * @param array $params описание характеристик,
	 *   формат: array(
	 *                '<ид (тип) хар-ки в БД>' => array(
	 *                    <название>, // отображаемое название характеристики
	 *                    <порядк. номер>, // позиция при выводе
	 *                    ['get_opts' => array()], // параметры запроса характеристики, см. shop_get_goods_details когда type - массив
	 *                    ['units' => { string | array() } // единицы измерения (string) или преобразование значения характеристики (array).
	 *                        в случае единиц измерения к значению будет добавлена указанная строка (напр., 20 -> 20 Вт).
	 *                        в случае с массивом значение будет заменено на элемент переданного массива с ключом, равным значению (0|1 -> 'да'|'нет')
	 *                ),
	 *           )
	 * @param array $readyParams формат: array('size' => <info_1>, <info_2>, ... ,<info_n>)
	 *   <info> ::=   array(<название>, <порядк. номер>, <значение>, )
	 * @param string $template шаблон вывода характеристики, содержащий теги @label@ и @value@
	 * @param string $splitter разделитель при выводе характеристик (в шаблоне)
	 *
	 * @return string
	 */
	protected function _getDetails($itemId, $params, $readyParams, $template, $splitter = '')
	{
		$detailParams = array();
		foreach (array_keys($params) as $paramName)
		{
			if (isset($params[$paramName]['get_opt']))
			{
				$detailParams[$paramName] = $params[$paramName]['get_opt'];
				unset($params[$paramName]['get_opt']);
			}
			else
				$detailParams[] = $paramName;
		}
		$details = shop_get_goods_details($itemId, $detailParams);

		// превращаем измерения размера в один параметр size
		if (@$details['height'] && @$details['length'] && @$details['width'])
		{
			$readyParams['size'][2] = implode(
				'x',
				array_intersect_key($details, array('height' => 1, 'length' => 1, 'width' => 1))
			);
		}
		unset($params['height'], $params['length'], $params['width']);


		foreach ($params as $name => $info)
		{
			$info[] = $details[$name];
			$readyParams[] = $info;
		}

		// тут readyParams имеет формат
		usort(
			$readyParams,
			function ($a, $b)
			{
				return $a[1] - $b[1];
			}
		);
		$ret = array();
		foreach ($readyParams as $info)
		{
			unset($info[1]);
			if (isset($info[2]))
			{
				if (isset($info['units']))
				{
					$units = $info['units'];
					unset($info['units']);

					if (is_array($units))
						$info[2] = $units[$info[2]];
					else
						$info[2] .= " $units";


				}
				if (is_array($info[2]))
					$info[2] = implode(', ', $info[2]);
				$ret[] = str_replace(array('@label@', '@value@'), $info, $template);
			}
		}

		return implode($splitter, $ret);
	}


	public static function getSectionUrl($lightType, $lightCategory = 0, $page = 0)
	{
		$menuId = get_menu_item_id();
		return "/lights/$lightType/$lightCategory/$menuId/$page.html";
	}

	public static function getDetailsUrl($linkedMenuId, $itemId)
	{
		$menuId = get_menu_item_id();
		return "/light-details/$linkedMenuId/$menuId/$itemId.html";
	}

	public static function getSearchUrl($page = 0)
	{
		$menuId = get_menu_item_id();
		return "/search/$menuId/$page.html";
	}

	public function parseUrl()
	{
		global $pagePath;
		if ($pagePath[0] === 'lights')
			return array('type' => $pagePath[1], 'category' => $pagePath[2], 'page' => $pagePath[4]);
		elseif ($pagePath[0] === 'light-details')
			return array('type' => $pagePath[1], 'id' => $pagePath[3]);
		elseif ($pagePath[0] === 'search')
			return array('type' => 0, 'page' => $pagePath[2]);
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

		global $_cms_tree_node_table;
		$this->_itemProps = get_data_array('*', $_cms_tree_node_table, "id={$this->_id}");
	}

	public function getItemLayout()
	{
		global $_cms_goods_images_url;
		$layoutParams = shop_get_goods_details($this->_id, array('image', 'maker', 'price'));
		$layoutParams['image'] = "$_cms_goods_images_url/{$layoutParams['image']}";

//		$detailNames = array('country', 'diametr', 'lamp_type');
		$layoutParams['details'] = $this->_getDetails(
			$this->_id,
			array(
				'country' => array('Страна', 10),
				'diametr' => array('Диаметр', 20),
				'lamp_type' => array('Тип лампочки (основной)', 25),
				'power' => array('Мощность лампы', 30, 'units' => 'Вт'),
				'lamp_count' => array('Количество ламп', 35),
				'area' => array('Площадь освещения', 40, 'units' => 'м2'),
				'collection' => array('Коллекция', 45),
				'type' => array('Категория', 50),
				'style' => array('Стиль светильника', 55),
				'placing' => array('Способ размещения', 60),
				'material' => array('Материал', 65),
				'color' => array('Цвет', 70, 'get_opt' => array('count' => 100)),
				'color_glass' => array('Цвет стекла', 75),
				'lamps_exists' => array('Лампочки в комплекте', 80, 'units' => array(0 => 'нет', 1 => 'есть')),
				'height' => '',
				'length' => '',
				'width' => ''
			),
			array(
				array('Артикул', 0, $this->_itemProps['code']),
				array('Производитель', 5, $layoutParams['maker']),
				'size' => array('Размеры ВхДхШ', 15, 'units' => 'мм'),
			),
			'<li><span class="left">@label@</span><span class="right">@value@</span></li>'
		);

		$layoutParams['name'] = $this->_itemProps['name'];
		$layoutParams['search_results_url'] = self::getSearchUrl($_SESSION[GadgetSearchResults::SEARCH_PAGE_PARAM]);

		return $this->renderTemplate($layoutParams, $this->_views['item']);
	}

	public function getLayoutSimilar()
	{
		global $_cms_goods_images_url, $_cms_tree_node_table, $_cms_tree_node_details;

		$itemDetails = shop_get_goods_details($this->_id, array('collection', 'maker' => array('indexes' => true)));
		$similarItems = get_data_array_rs(
			'id, name, parent',
			"$_cms_tree_node_table n",
			"EXISTS(SELECT 1 FROM $_cms_tree_node_details nd WHERE nd.node=n.id AND typeId='collection' AND value='{$itemDetails['collection']}')".
			" AND EXISTS(SELECT 1 FROM $_cms_tree_node_details nd WHERE nd.node=n.id AND typeId='maker' AND value='{$itemDetails['maker']}')".
			" AND n.type='{$this->_itemProps['type']}'"
		);

		$layout = '';
		while ($item = $similarItems->next())
		{
			$layoutParams = shop_get_goods_details($item['id'], array('image', 'maker', 'price'));
			$layoutParams['image'] = "$_cms_goods_images_url/thumbs/{$layoutParams['image']}";
			$layoutParams['name'] = $item['name'];
			$layoutParams['link'] = $this->getDetailsUrl($item['parent'], $item['id']);
			$layout .= $this->renderTemplate($layoutParams, $this->_views['similar']);
		}

		return $layout;
	}

	private $_views = array(
		'item' => <<<ITEM
	<div class="product_info_container">
		<img src="@image@" alt="">
		<div class="product_info_about">
			<h2>@name@</h2>
			<span>Производитель: @maker@</span>
			<ul>
				@details@
			</ul>
			<br>
			<div class="product_info_order">
								<span>Под заказ<br>
								Цена:<span> @price@ руб.</span></span>
			</div>
			<div clsas=""></div>
			<div class="product_info_about_tosearch"><a href="@search_results_url@" >К результатам поиска</a></div>
		</div>
	</div>
ITEM
		,
		'similar' => <<<ITEM
			<a href="@link@" class="product_same_container_item">
				<h2>@name@</h2>
				<span class="product_same_container_dev">@maker@</span>
				<img src="@image@" alt="">
				<span class="product_same_container_ptice">@price@ руб.</span>
			</a>
ITEM

	);

	/** id товара */
	private $_id;
	/** все свойства товара из записи БД */
	private $_itemProps;
}

class GadgetLightsList extends GadgetLightsBase
{
	const ITEMS_PER_PAGE = 2;

	public function __construct()
	{
		parent::__construct();
		$this->_page = (int)$this->_urlData['page'];
	}

	public function getHtml()
	{
		global $_cms_tree_node_table;

		$from = $this->_page * self::ITEMS_PER_PAGE;
		$items = get_data_array_rs(
			'SQL_CALC_FOUND_ROWS *',
			$_cms_tree_node_table,
			"parent={$this->getCurrentTypeId()}",
			"limit $from, ".self::ITEMS_PER_PAGE
		);
		$this->_totalItems = get_data('FOUND_ROWS()');

		return $this->_getHtml($items);
	}

	protected function _getHtml($items)
	{
		global $_cms_goods_images_url;

		$itemsLayout = '';
		while ($item = $items->next())
		{
			$layoutParams = shop_get_goods_details($item['id'], array('image', 'maker', 'price'));
			$layoutParams['image'] = "$_cms_goods_images_url/thumbs/{$layoutParams['image']}";

			$layoutParams['details'] =
				$this->_getDetails(
					$item['id'],
					array(
						'diametr' => array('Диаметр', 1),
						'lamp_type' => array('Тип ламп', 2),
						'country' => array('Производство', 3),
						'length' => '',
						'width' => '',
						'height' => ''
					),
					array('size' => array('Размеры', 0)),
					'@label@: @value@',
					'<br/>'
				);

			$layoutParams['name'] = $item['name'];
			$layoutParams['link_details'] = $this->getDetailsUrl($item['parent'], $item['id']);

			$itemsLayout .= $this->renderTemplate($layoutParams, $this->_views['item']);
		}
		return $itemsLayout;
	}

	public function currentPage()
	{
		return $this->_page;
	}

	public function getPagerHtml()
	{
		return get_pager_html(
			$this->_totalItems,
			$this->_page,
			self::ITEMS_PER_PAGE,
			self::getSectionUrl($this->getCurrentTypeId(), 0, '@_page_@')
		);
	}

	protected $_totalItems;
	protected $_page;

	private $_views = array(
		'item' => <<<ITEM
<div class="right_column_node">
	<a href="@link_details@">
	<img src="@image@">
	<h2>@name@</h2>
	</a>
	<p>Производитель: @maker@</p>
	<span>@details@</span>
	<div class="right_column_node_price">Цена: <span>@price@ руб.</span></div>
	<a href="@link_details@" class="right_column_node_button">Подробнее</a>
</div>
ITEM
	);

}

class GadgetSearchResults extends GadgetLightsList
{
	const SEARCH_PAGE_PARAM = 'search_page';

	public function __construct(SearchFilter $filter)
	{
		parent::__construct();
		$this->_filter =  $filter;
		$filter->setOwner($this);
		$this->_page = (int)$this->_urlData['page'];
	}

	public function getHtml()
	{
		global $_cms_tree_node_table, $_cms_tree_node_details;

		$filterClause = '';

		/** @var $option SearchFilterOption */
		foreach ($this->_filter->options() as $option)
		{
			if ($option->isEmpty())
				continue;
			switch ($option->fieldName())
			{
				case SearchFilter::FILTER_FIELD_TYPE:
					$filterClause .= " AND n.type='" . pmMysqlSafe($option->value()) ."'";
					break;
				case SearchFilter::FILTER_FIELD_MAKER:
					$filterClause .= " AND EXISTS(SELECT 1 FROM $_cms_tree_node_details nd WHERE nd.node=n.id AND typeId='maker' AND value='".pmMysqlSafe($option->value()) ."')";
					break;
				case SearchFilter::FILTER_FIELD_LAMP:
					$filterClause .= " AND EXISTS(SELECT 1 FROM $_cms_tree_node_details nd WHERE nd.node=n.id AND typeId='lamp_type' AND value='".pmMysqlSafe($option->value()) ."')";
					break;
				case SearchFilter::FILTER_FIELD_PRICE_FROM:
					$filterClause .= " AND EXISTS(SELECT 1 FROM $_cms_tree_node_details nd WHERE nd.node=n.id AND typeId='price' AND value >= ".pmMysqlSafe($option->value()) .")";
					break;
				case SearchFilter::FILTER_FIELD_PRICE_TO:
					$filterClause .= " AND EXISTS(SELECT 1 FROM $_cms_tree_node_details nd WHERE nd.node=n.id AND typeId='price' AND value <= ".pmMysqlSafe($option->value()) .")";
					break;
			}
		}

		$from = $this->_page * self::ITEMS_PER_PAGE;
		$items = get_data_array_rs(
			'SQL_CALC_FOUND_ROWS id, name, parent, type',
			"$_cms_tree_node_table n",
			" TRUE $filterClause",
			"limit $from, ".self::ITEMS_PER_PAGE
		);

		$this->_totalItems = get_data('FOUND_ROWS()');

		return $this->_getHtml($items);
	}

	public function setCurrentPage($page)
	{
		$this->_page = $page;
	}

	public function getPagerHtml()
	{
		return get_pager_html(
			$this->_totalItems,
			$this->_page,
			self::ITEMS_PER_PAGE,
			self::getSearchUrl('@_page_@')
		);
	}

	private $_views = array(
		'item' => <<<ITEM
<div class="right_column_node">
	<a href="@link_details@">
	<img src="@image@">
	<h2>@name@</h2>
	</a>
	<p>Производитель: @maker@</p>
	<span>@details@</span>
	<div class="right_column_node_price">Цена: <span>@price@ руб.</span></div>
	<a href="@link_details@" class="right_column_node_button">Подробнее</a>
</div>
ITEM
	);

}

/**
 * Управляет формой поиска: отображение, запоминание установленных параметров.
 * Логика установки значений параметров:
 * 1. В начале шаблона создать объект, при этом содержимое полей устанавливается
 *    в пустое значение (см. SearchFilterOption::emptyValue)
 * 2. В гаджетах, наследующих параметры поиска из сессии, вызвать restoreContext - поля получат значения из сессии.
 * 3. В гаджете, запускающем поиск, вызывать (дополнительно) setContext - если переход был из формы, то значения
 *    из формы буду присвоены полям и записаны в сессию, иначе вызов не будет иметь эффекта.
 * 4. По окончании работы скрипта деструтор, если не вызывался restoreContext или setContext, уничтожит сессионные
 *    значения полей.
 */
class SearchFilter extends View
{
	const FILTER_FIELD_TYPE = 'filter_type';
	const FILTER_FIELD_LAMP = 'filter_lamp';
	const FILTER_FIELD_MAKER = 'filter_maker';
	const FILTER_FIELD_PRICE_FROM = 'price_from';
	const FILTER_FIELD_PRICE_TO = 'price_to';
//	const FILTER_FIELD_HAS_DISCOUNT = 'has_discount';

	public function __construct()
	{
		// конструируем объекты параметров формы по конфигу
		foreach ($this->_formFields as $name => &$fieldConfig)
		{
			$fieldConfig['name'] = $name;
			$fieldConfig = SearchFilterOptionFactory::create(
				$fieldConfig
			);
		}
	}

	public function setOwner($owner)
	{
		$this->_owner = $owner;
	}

	public function getFilterLayout()
	{
		$templateParams = array();
		/** @var $field SearchFilterOption */
		foreach ($this->_formFields as $name => $field)
		{
			$templateParams[$name] = $field->getHtml();
		}

		$templateParams['results_url'] = GadgetLightsBase::getSearchUrl();
		return $this->renderTemplate(
			$templateParams,
			$this->_views['filter']
		);
	}

	public function isEmpty()
	{
		foreach ($this->_formFields as $field)
		{
			if (!$field->isEmpty())
				return false;
		}
		return true;
	}

	public function setContext()
	{
		$this->_keepContext = true;

		if ($this->_owner instanceof GadgetSearchResults)
		{
			$_SESSION[GadgetSearchResults::SEARCH_PAGE_PARAM] = $this->_owner->currentPage();
		}

		$search_start = pmImportVarsList('search_start');

		if (!$search_start)
			return;

		$opts = pmImportVarsList(
			implode(
				'|',
				array_keys($this->_formFields)
			),
			true
		);

		/** @var $field SearchFilterOption */
		foreach ($this->_formFields as $name => $field)
		{
			$_SESSION[$name] = $opts[$name];
			$field->setValue($opts[$name]);
		}
	}

	public function restoreContext()
	{
		$this->_keepContext = true;

		/** @var $field SearchFilterOption */
		foreach ($this->_formFields as $name => $field)
		{
			$field->setValue(isset($_SESSION[$name]) ? $_SESSION[$name] : null);
		}
	}

	public function _destroyContext()
	{
		/** @var $field SearchFilterOption */
		foreach ($this->_formFields as $name => $field)
		{
			$field->reset();
			unset($_SESSION[$name]);
		}
	}

	/**
	 * @param $name
	 *
	 * @return array(SearchFilterOption) | SearchFilterOption
	 */
	public function options($name = null)
	{
		if ($name)
			return $this->_formFields[$name];
		else
			return $this->_formFields;
	}

	public function __destruct()
	{
		if (!$this->_keepContext)
			$this->_destroyContext();
	}

	private $_views = array(
		'filter' => <<<FILTER
<div class="left_menu_filter">
		<span>Фильтр</span>
		<br>
		<div class="left_menu_filter_form">
		<form id="search_form" action="@results_url@" method="POST" target="_self">
			<label>Тип товара</label>
			@filter_type@
			<label>Производитель</label>
			@filter_maker@
			<label>Тип ламп</label>
			@filter_lamp@
			<label>Цена</label>
			@price_from@
			@price_to@
			<br>
			<!--<div class="left_menu_checkbox_wrap">@has_discount@Товар по скидке</div>-->
			<div class="left_menu_filter_form_button" onclick="$('#search_form')[0].submit()">Подобрать</div>
			<input type="hidden" name="search_start" value="1"/>
		</form>
		</div>
	</div>
FILTER

	);

	private $_keepContext = false;

	private $_formFields = array(
		self::FILTER_FIELD_PRICE_FROM => array(
			'type' => SearchFilterOption::OPTION_TYPE_TEXT,
			'options' => array(
				'attributes' => array('class' => 'left_menu_input', 'placeholder' => 'От')
			)
		),
		self::FILTER_FIELD_PRICE_TO => array(
			'type' => SearchFilterOption::OPTION_TYPE_TEXT,
			'options' => array(
				'attributes' => array('class' => 'left_menu_input last', 'placeholder' => 'До')
			)
		),
//		self::FILTER_FIELD_HAS_DISCOUNT => array(
//			'type' => SearchFilterOption::OPTION_TYPE_CHECK,
//			'options' => array()
//		),
		self::FILTER_FIELD_TYPE => array(
			'type' => SearchFilterOption::OPTION_TYPE_COMBO,
			'options' => array(
				'source' => SearchFilterOptionCombo::OPTION_DATA_SOURCE_OBJECT_TYPE,
				// cms_get_object_type_select($id, $class, $first_empty, $init = '', $object_types = null)
				'helper_params' => array(
					'id' => self::FILTER_FIELD_TYPE,
					'class' => 'left_menu_input',
					'first_empty' => true,
					'objects_var_name' => '_cms_good_types'
				)
			)
		),
		self::FILTER_FIELD_LAMP => array(
			'type' => SearchFilterOption::OPTION_TYPE_COMBO,
			'options' => array(
				'source' => SearchFilterOptionCombo::OPTION_DATA_SOURCE_OBJECT_ENUM,
				// $obj_type, $obj_prop, $id, $class, $first_empty, $init = ''
				'helper_params' => array(
					// справочники ламп для всех одинаковые, берем для первого типа светильника
					'obj_type' => 1,
					'obj_prop' => 'lamp_type',
					'id' => self::FILTER_FIELD_LAMP,
					'class' => 'left_menu_input',
					'first_empty' => true,
					'objects_var_name' => '_cms_good_types'
				)
			)
		),
		self::FILTER_FIELD_MAKER => array(
			'type' => SearchFilterOption::OPTION_TYPE_COMBO,
			'options' => array(
				'source' => SearchFilterOptionCombo::OPTION_DATA_SOURCE_DIRECTORY,
				// $id, $class, $first_empty, $init = ''
				'helper_params' => array(
					'dir_id' => 1,
					'id' => self::FILTER_FIELD_MAKER,
					'class' => 'left_menu_input',
					'first_empty' => true,
				)
			)
		),
	);

	private $_owner;
}

abstract class SearchFilterOption
{
	const OPTION_TYPE_CHECK = 0;
	const OPTION_TYPE_COMBO = 1;
	const OPTION_TYPE_TEXT = 2;

	/**
	 * @param $name имя элемента в форме
	 * @param array $options дополнительные параметры, зависящие от конкретного класса
	 * @param null $defaultValue значение по умолчанию при выводе формы
	 */
	public function __construct($name, Array $options, $defaultValue = null)
	{
		$this->_fieldName = $name;
		$this->_defaultValue = is_null($defaultValue) ? $this->emptyValue() : $defaultValue;
		$this->reset();
		$this->_options = $options;
	}

	final public function isEmpty()
	{
		return $this->_value === $this->emptyValue();
	}

	final public function setValue($value)
	{
		if (!is_null($value))
			$this->_value = $value;
		else
			$this->_value = $this->emptyValue();
	}

	final public function value()
	{
		return $this->_value;
	}

	abstract public function getHtml();

	/**
	 * Возвращает неустановленное (пустое) значение для данного типа поля ввода того же типа, который приходит из формы.
	 * @return string
	 */
	abstract public function emptyValue();

	final public function reset()
	{
		$this->_value = $this->_defaultValue;
	}

	final public function fieldName()
	{
		return $this->_fieldName;
	}

	protected $_fieldName;
	protected $_value;

	/** @var $_defaultValue дефолтное значение для данного экземпляра поля. может отличаться
	 * от системного (см. метод emptyValue
	 */
	protected $_defaultValue;
	protected $_options;
}

class SearchFilterOptionText extends SearchFilterOption
{
	public function getHtml()
	{
		$attr = "type=\"text\" name=\"{$this->_fieldName}\" value=\"{$this->_value}\" ";
		if (isset($this->_options['attributes']))
		{
			foreach ($this->_options['attributes'] as $name => $val)
				$attr .= " $name = \"$val\"";
		}

		return "<input $attr />";
	}

	public function emptyValue()
	{
		return '';
	}
}

/**
 * Class SearchFilterOptionCombo
 * Формат параметра конструтора $options:
 * array(
 *   'source' => <источник данных: self::OPTION_DATA_SOURCE_*>,
 *   'helper_params' => array(имя => значение) параметры для вызова конкретных методов построения html,
 *     должны соответствовать сигнатуре метода.
 * )
 */
class SearchFilterOptionCombo extends SearchFilterOption
{
	const OPTION_DATA_SOURCE_OBJECT_TYPE = 1;
	const OPTION_DATA_SOURCE_OBJECT_ENUM = 2;
	const OPTION_DATA_SOURCE_DIRECTORY = 3;

	public function getHtml()
	{
		global $_cms_good_types;
		$helperParams = $this->_options['helper_params'];
		switch ($this->_options['source'])
		{
			case self::OPTION_DATA_SOURCE_OBJECT_TYPE:
				return cms_get_object_type_select(
					$this->_fieldName, //$helperParams['id'],
					$helperParams['class'],
					$helperParams['first_empty'],
					$this->value(),
					$helperParams['objects_var_name']
				);
				break;
			case self::OPTION_DATA_SOURCE_OBJECT_ENUM:
//				($obj_type, $obj_prop, $id, $class, $first_empty, $init = '')
				return cms_get_object_enum_select(
					$helperParams['obj_type'],
					$helperParams['obj_prop'],
					$this->_fieldName, //$helperParams['id'],
					$helperParams['class'],
					$helperParams['first_empty'],
					$this->value(),
					$helperParams['objects_var_name']
				);
				break;
			case self::OPTION_DATA_SOURCE_DIRECTORY:
//				($dir_id, $id, $class, $first_empty, $init = -1)
				return cms_get_dir_select(
					$helperParams['dir_id'],
					$this->_fieldName, //$helperParams['id'],
					$helperParams['class'],
					$helperParams['first_empty'],
					$this->value()
				);
				break;
			default:
				return '';

		}

	}

	public function emptyValue()
	{
		return '-1';
	}
}

class SearchFilterOptionCheck extends SearchFilterOption
{
	public function getHtml()
	{
		$checked = $this->_value === false ? '' :  'checked';
		$attr = "type=\"checkbox\" $checked name=\"{$this->_fieldName}\"";

		if (isset($this->_options['attributes']))
		{
			foreach ($this->_options['attributes'] as $name => $val)
				$attr .= " $name = \"$val\"";
		}

		return "<input $attr />";

	}

	public function emptyValue()
	{
		return false;
	}
}

class SearchFilterOptionFactory
{
	public static function create($config /*$type, $name, $options = null, $defaultValue = null*/)
	{
		$defaultValue = isset($config['default']) ? $config['default'] : null;
		switch ($config['type'])
		{
			case SearchFilterOption::OPTION_TYPE_TEXT:
				return new SearchFilterOptionText($config['name'], $config['options'], $defaultValue);
				break;
			case SearchFilterOption::OPTION_TYPE_COMBO:
				return new SearchFilterOptionCombo($config['name'], $config['options'], $defaultValue);
				break;
			case SearchFilterOption::OPTION_TYPE_CHECK:
				return new SearchFilterOptionCheck($config['name'], $config['options'], $defaultValue);
				break;
			default:
				throw new Exception('invalid filter option field type: '.$config['type']);
		}
	}
}
