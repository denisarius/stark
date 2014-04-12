<div class="left_column_container">
	<div class="content_info_container">
		<h1>Готовые решения для Вашего интерьера</h1>
		<p>Воспользуйтесь готовыми решениями и сориентируйтесь в ценах на установку натяжных потолков.</p>
		<p>Мы предлагаем оптимальный набор материалов, комплектующих и работы по установке натяжного потолка.</p>
		<p>Цены на готовые решения действуют при общей площади заказа от 30 м2.</p>
	</div>
	<br>
	<?php
	global $_cms_objects_table, $pagePath;
	$objectType = $pagePath[1];
	$objects = get_data_array_rs('*', $_cms_objects_table, "type=$objectType");
	while ($objectInfo = $objects->next())
	{
		_renderSolution($objectInfo);
	}
	?>
	<!--<div class="content_info_solution">-->
	<!--<div class="content_info_solution">-->
</div>
<div class="info_right_column_container">
	<div class="managers_container">
		<span></span>
		<h1>Менеджеры</h1>
		<div class="managers_node">
			<img src="@!template@/images/pics/maneger.png" alt="">
			<div class="managers_node_contacts">
				<span class="managers_node_name">Юлия Певгонен</span>
				<span class="managers_node_post">Старший менеджер</span>
				<span class="managers_node_phone">8(8142) 67-67-67</span>
				<span class="managers_node_post">e-mail: starkpot@mail.ru</span>
			</div>
			<br>
		</div>
		<div class="managers_node">
			<img src="@!template@/images/pics/maneger.png" alt="">
			<div class="managers_node_contacts">
				<span class="managers_node_name">Юлия Певгонен</span>
				<span class="managers_node_post">Старший менеджер</span>
				<span class="managers_node_phone">8(8142) 67-67-67</span>
				<span class="managers_node_post">e-mail: starkpot@mail.ru</span>
			</div>
			<br>
		</div>
	</div>
</div>
<br/>

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
//	<li><span class="left">c-Light лаковая 5 м2</span>
//<span class="right">1280 q</span>
//<br>
//</li>
}