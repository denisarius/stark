<?php

/** @var $filter SearchFilter */
global $searchFilter;
$searchFilter->restoreContext();
$searchFilter->setContext();

$gadget = new GadgetSearchResults($searchFilter);

?>
<div class="left_menu_container">
	<?php echo $gadget->getTypesMenuHtml(); ?>
	<?php echo $searchFilter->getHtml(); ?>
</div>
<div class="right_column_container">
	<?php if ($searchFilter->isEmpty()): ?>
		<h1>Необходимо ввести данные для поиска</h1>
	<?php else: ?>
		<h1>Результаты поиска</h1>
		<?php echo $gadget->getHtml(); ?>
		<?php echo $gadget->getPagerHtml(); ?>
	<?php endif; ?>
</div>
<br>
