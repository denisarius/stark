<?php
$gadget = new GadgetLights();
?>
	<div class="left_menu_container">
		<?php echo $gadget->_getTypesMenuLayout(); ?>
		<div class="left_menu_filter">
			<span>Фильтр</span>
			<br>
			<div class="left_menu_filter_form">
				<label>Тип товара</label>
				<input type="text">
				<label>Производитель</label>
				<input type="text">
				<label>Тип ламп</label>
				<input type="text">
				<label>Цена</label>
				<input type="text" class="left_menu_input" placeholder="От"><input type="text"
																				   class="left_menu_input last"
																				   placeholder="До">
				<br>
				<div class="left_menu_checkbox_wrap"><input type="checkbox">Товар по скидке</div>
				<div class="left_menu_checkbox_wrap"><input type="checkbox">Товар в наличии</div>
				<div class="left_menu_filter_form_button">Подобрать</div>
			</div>
		</div>
	</div>
	<div class="right_column_container">
		<h1><?php echo $gadget->getCurrentType(); ?></h1>
		<?php echo $gadget->getItemsLayout(); ?>
	</div>
	<br>