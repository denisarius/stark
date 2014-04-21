<?php
$gadget = new GadgetLightsList();
?>
<div class="left_menu_container">
	<?php echo $gadget->getTypesMenuLayout(); ?>
	<?php echo $gadget->getFilterLayout(); ?>
</div>
<div class="right_column_container">
	<h1><?php echo $gadget->getCurrentTypeName(); ?></h1>
	<?php echo $gadget->getItemsLayout(); ?>
</div>
<br>