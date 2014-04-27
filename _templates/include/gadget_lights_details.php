<?php
$gadget = new GadgetLightDetails();
global $searchFilter;
$searchFilter->restoreContext();
?>
<div class="left_menu_container">
	<?php echo $gadget->getTypesMenuLayout(); ?>
	<?php echo $searchFilter->getFilterLayout(); ?>
</div>
<div class="right_column_container">
	<h1><?php echo $gadget->getCurrentTypeName(); ?></h1>
	<?php echo $gadget->getItemLayout(); ?>
	<br>
	<div class="product_same_container">
		<span>Похожие товары</span>
		<div class="product_same_container_wrap">
				<?php echo $gadget->getLayoutSimilar(); ?>
			<br>
		</div>
	</div>
</div>
<br>