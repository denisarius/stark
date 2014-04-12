<div class="left_column_container">
	<div class="content_info_container">
		<h1>������� ������� ��� ������ ���������</h1>
		<p>�������������� �������� ��������� � ��������������� � ����� �� ��������� �������� ��������.</p>
		<p>�� ���������� ����������� ����� ����������, ������������� � ������ �� ��������� ��������� �������.</p>
		<p>���� �� ������� ������� ��������� ��� ����� ������� ������ �� 30 �2.</p>
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
		<h1>���������</h1>
		<div class="managers_node">
			<img src="@!template@/images/pics/maneger.png" alt="">
			<div class="managers_node_contacts">
				<span class="managers_node_name">���� ��������</span>
				<span class="managers_node_post">������� ��������</span>
				<span class="managers_node_phone">8(8142) 67-67-67</span>
				<span class="managers_node_post">e-mail: starkpot@mail.ru</span>
			</div>
			<br>
		</div>
		<div class="managers_node">
			<img src="@!template@/images/pics/maneger.png" alt="">
			<div class="managers_node_contacts">
				<span class="managers_node_name">���� ��������</span>
				<span class="managers_node_post">������� ��������</span>
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
<div class="content_info_solution_button">����: {$price}* �.</div>
</div>
<br>
</div>
<span class="content_info_solution_star">* ���� � ������ ������ 10%</span>
<a class="content_info_solution_payment" href="#">������ �������</a>
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
//	array ( 0 => array ( 0 => 'c-Light ������� 5�2', 1 => '1280', ), 1 => array ( 0 => '���� 4��.', 1 => '0', ), 2 => array ( 0 => '��������� ����� 1��.', 1 => '299', ), )
	foreach ($items as $item)
	{
		$totalPrice += $item[1];
		$itemsLayout .= <<<IL
	<li><span class="left">{$item[0]}</span><span class="right">{$item[1]} �.</span><br></li>
IL;

	}
	return <<<ITEMS
	<ul>
$itemsLayout
</ul>
ITEMS;
//	<li><span class="left">c-Light ������� 5 �2</span>
//<span class="right">1280 q</span>
//<br>
//</li>
}