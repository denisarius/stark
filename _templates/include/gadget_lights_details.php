<?php

global $searchFilter;
$searchFilter->restoreContext();
$gadget = new GadgetLightDetails($searchFilter);
?>
<div class="left_menu_container">
	<?php echo $gadget->getTypesMenuHtml(); ?>
	<?php echo $searchFilter->getHtml(); ?>
</div>
<div class="right_column_container">
	<h1><?php echo $gadget->getCurrentTypeName(); ?></h1>
	<?php echo $gadget->getItemHtml(); ?>
	<br>
	<div class="product_same_container">
		<span>Похожие товары</span>
		<div class="product_same_container_wrap">
			<?php echo $gadget->getSimilarHtml(); ?>
			<br>
		</div>
	</div>
</div>
<br>