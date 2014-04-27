<?php

/** @var $filter SearchFilter */
global $searchFilter;
$gadget = new GadgetSearchResults($searchFilter);

$searchFilter->restoreContext();
$searchFilter->setContext();

?>
<div class="left_menu_container">
	<?php echo $gadget->getTypesMenuLayout(); ?>
	<?php echo $searchFilter->getFilterLayout();?>
</div>
<div class="right_column_container">
	<h1><?php echo $searchFilter->isEmpty() ? 'Необходимо ввести данные для поиска' : 'Результаты поиска'; ?></h1>
	<?php echo $gadget->getHtml(); ?>
	<?php echo $gadget->getPagerHtml(); ?>
</div>
<br>
