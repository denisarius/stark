<?php


/**
 * �������� �� ������� ������� ����������� ��������: ����� ����, ������ � ���������� �� ����
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

	public function getFilterLayout()
	{
		return $this->_views['filter'];
	}

	/**
	 * ��������� ����������� ���� ������������� ������.
	 *
	 * @param int $itemId �� ������
	 * @param array $params �������� �������������,
	 *   ������: array(
	 *                '<�� (���) ���-�� � ��>' => array(
	 *                    <��������>, // ������������ �������� ��������������
	 *                    <������. �����>, // ������� ��� ������
	 *                    ['get_opts' => array()], // ��������� ������� ��������������, ��. shop_get_goods_details ����� type - ������
	 *                    ['units' => { string | array() } // ������� ��������� (string) ��� �������������� �������� �������������� (array).
	 *                        � ������ ������ ��������� � �������� ����� ��������� ��������� ������ (����., 20 -> 20 ��).
	 *                        � ������ � �������� �������� ����� �������� �� ������� ����������� ������� � ������, ������ �������� (0|1 -> '��'|'���')
	 *                ),
	 *           )
	 * @param array $readyParams ������: array('size' => <info_1>, <info_2>, ... ,<info_n>)
	 *   <info> ::=   array(<��������>, <������. �����>, <��������>, )
	 * @param string $template ������ ������ ��������������, ���������� ���� @label@ � @value@
	 * @param string $splitter ����������� ��� ������ ������������� (� �������)
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

		// ���������� ��������� ������� � ���� �������� size
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

		// ��� readyParams ����� ������
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

	private function getSectionUrl($lightType, $lightCategory = 0, $page = 0)
	{
		$menuId = get_menu_item_id();
		return "/lights/$lightType/$lightCategory/$menuId/$page.html";
	}

	public static function getDetailsUrl($linkedMenuId, $itemId)
	{
		$menuId = get_menu_item_id();
		return "/light-details/$linkedMenuId/$menuId/$itemId.html";
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
	// ������� (�������� � ����) ���
	private $_typeId;

	private $_views = array(
		'filter' => <<<FILTER
<div class="left_menu_filter">
		<span>������</span>
		<br>
		<div class="left_menu_filter_form">
			<label>��� ������</label>
			<input type="text">
			<label>�������������</label>
			<input type="text">
			<label>��� ����</label>
			<input type="text">
			<label>����</label>
			<input type="text" class="left_menu_input" placeholder="��">
			<input type="text" class="left_menu_input last" placeholder="��">
			<br>
			<div class="left_menu_checkbox_wrap"><input type="checkbox">����� �� ������</div>
			<div class="left_menu_checkbox_wrap"><input type="checkbox">����� � �������</div>
			<div class="left_menu_filter_form_button">���������</div>
		</div>
	</div>
FILTER

	);
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
				'country' => array('������', 10),
				'diametr' => array('�������', 20),
				'lamp_type' => array('��� �������� (��������)', 25),
				'power' => array('�������� �����', 30, 'units' => '��'),
				'lamp_count' => array('���������� ����', 35),
				'area' => array('������� ���������', 40, 'units' => '�2'),
				'collection' => array('���������', 45),
				'type' => array('���������', 50),
				'style' => array('����� �����������', 55),
				'placing' => array('������ ����������', 60),
				'material' => array('��������', 65),
				'color' => array('����', 70, 'get_opt' => array('count' => 100)),
				'color_glass' => array('���� ������', 75),
				'lamps_exists' => array('�������� � ���������', 80, 'units' => array(0 => '���', 1 => '����')),
				'height' => '',
				'length' => '',
				'width' => ''
			),
			array(
				array('�������', 0, $this->_itemProps['code']),
				array('�������������', 5, $layoutParams['maker']),
				'size' => array('������� �����', 15, 'units' => '��'),
			),
			'<li><span class="left">@label@</span><span class="right">@value@</span></li>'
		);

		$layoutParams['name'] = $this->_itemProps['name'];

		return $this->renderTemplate($layoutParams, $this->_views['item']);
	}

	public function getLayoutSimilar()
	{
		global $_cms_goods_images_url, $_cms_tree_node_table, $_cms_tree_node_details;

		$itemDetails = shop_get_goods_details($this->_id, array('collection', 'maker' => array('indexes' => true)));
		$similarItems = get_data_array_rs('id, name, parent',  "$_cms_tree_node_table n" ,
		"EXISTS(SELECT 1 FROM $_cms_tree_node_details nd WHERE nd.node=n.id AND typeId='collection' AND value='{$itemDetails['collection']}')".
		" AND EXISTS(SELECT 1 FROM $_cms_tree_node_details nd WHERE nd.node=n.id AND typeId='maker' AND value='{$itemDetails['maker']}')".
		" AND n.type='{$this->_itemProps['type']}'");

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
			<span>�������������: @maker@</span>
			<ul>
				@details@
			</ul>
			<br>
			<div class="product_info_order">
								<span>��� �����<br>
								����:<span> @price@ &#8381;</span></span>
			</div>
			<div clsas=""></div>
			<div class="product_info_about_tosearch">� ����������� ������</div>
		</div>
	</div>
ITEM
		,
		'similar' => <<<ITEM
			<a href="@link@" class="product_same_container_item">
				<h2>@name@</h2>
				<span class="product_same_container_dev">@maker@</span>
				<img src="@image@" alt="">
				<span class="product_same_container_ptice">@price@ &#8381;</span>
			</a>
ITEM

	);

	/** id ������ */
	private $_id;
	/** ��� �������� ������ �� ������ �� */
	private $_itemProps;
}

class GadgetLightsList extends GadgetLightsBase
{
	public function getItemsLayout()
	{
		global $_cms_goods_images_url, $_cms_tree_node_table;

		$items = get_data_array_rs('*', $_cms_tree_node_table, "parent={$this->getCurrentTypeId()}");
		$itemsLayout = '';
		while ($item = $items->next())
		{
			$layoutParams = shop_get_goods_details($item['id'], array('image', 'maker', 'price'));
			$layoutParams['image'] = "$_cms_goods_images_url/thumbs/{$layoutParams['image']}";

//			$detailNames = array('country', 'length', 'width', 'height', 'diametr', 'lamp_type');
			$layoutParams['details'] =
				$this->_getDetails(
					$item['id'],
					array(
						'diametr' => array('�������', 1),
						'lamp_type' => array('��� ����', 2),
						'country' => array('������������', 3),
						'length' => '',
						'width' => '',
						'height' => ''
					),
					array('size' => array('�������', 0)),
					'@label@: @value@',
					'<br/>'
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
	<a href="@link_details@">
	<img src="@image@">
	<h2>@name@</h2>
	</a>
	<p>�������������: @maker@</p>
	<span>@details@</span>
	<div class="right_column_node_price">����: <span>@price@ &#8381;</span></div>
	<a href="@link_details@" class="right_column_node_button">���������</a>
</div>
ITEM
	);

}