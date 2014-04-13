<div class="left_column_container">
	<div class="content_info_container">
		<?php _renderText(); ?>
	</div>
	<br>
	<?php
	global $_cms_objects_table, $pagePath;
	$objectType = $pagePath[1];
	$menuId = get_menu_item_id();
	$objects = get_data_array_rs('*', $_cms_objects_table, "menu_item=$menuId AND type=$objectType");
	while ($objectInfo = $objects->next())
	{
		_renderSolution($objectInfo);
	}
	?>
</div>

<?php
function _renderSolution($objectInfo)
{
	global $_base_site_objects_images_url;
	$items = _getSolutionItems($objectInfo, $price);
	echo <<<SOL
<div class="content_info_solution">
<h2>{$objectInfo['name']}</h2>
<div>
<img alt="" src="$_base_site_objects_images_url/{$objectInfo['image']}">
<div class="content_info_solution_price">
$items
<div class="content_info_solution_button">Цена: {$price}* р.</div>
</div>
<br>
</div>
<span class="content_info_solution_star">* Цена с учётом скидки 10%</span>
<a class="content_info_solution_payment" href="#">Расчёт потолка</a>
<span class="content_info_solution_coner"></span>
</div>
SOL;

}

function _getSolutionItems($objectInfo, &$totalPrice)
{
//	global $_cms_objects_details;
	$items = cms_get_objects_details($objectInfo['type'], $objectInfo['id'], 'rows', 100);
	$itemsLayout = '';
	$totalPrice = 0;
//	array ( 0 => array ( 0 => 'c-Light лаковая 5м2', 1 => '1280', ), 1 => array ( 0 => 'Углы 4шт.', 1 => '0', ), 2 => array ( 0 => 'Окантовка трубы 1шт.', 1 => '299', ), )
	foreach ($items as $item)
	{
		$totalPrice += $item[1];
		$itemsLayout .= <<<IL
	<li><span class="left">{$item[0]}</span><span class="right">{$item[1]} р.</span><br></li>
IL;

	}
	return <<<ITEMS
	<ul>
$itemsLayout
</ul>
ITEMS;
}

function _renderText()
{
	global $_cms_texts_table;
	$menuId = get_menu_item_id();
	echo get_data('content', $_cms_texts_table, "menu_item=$menuId");
}