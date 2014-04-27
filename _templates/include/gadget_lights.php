<?php
$gadget = new GadgetLightsList();
global $searchFilter;
?>
<div class="left_menu_container">
	<?php echo $gadget->getTypesMenuLayout(); ?>
	<?php echo $searchFilter->getFilterLayout(); ?>
</div>
<div class="right_column_container">
	<h1><?php echo $gadget->getCurrentTypeName(); ?></h1>
	<?php echo $gadget->getHtml(); ?>
	<?php echo $gadget->getPagerHtml(); ?>
</div>
<br>