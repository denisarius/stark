<?php
$gadget = new GadgetLights();
?>
	<div class="left_menu_container">
		<?php echo $gadget->_getTypesMenuLayout(); ?>
		<div class="left_menu_filter">
			<span>������</span>
			<br>
			<div class="left_menu_filter_form">
				<label>��� ������</label>
				<input type="text">
				<label>�������������</label>
				<input type="text">
				<label>��� ����</label>
				<input type="text">
				<label>����</label>
				<input type="text" class="left_menu_input" placeholder="��"><input type="text"
																				   class="left_menu_input last"
																				   placeholder="��">
				<br>
				<div class="left_menu_checkbox_wrap"><input type="checkbox">����� �� ������</div>
				<div class="left_menu_checkbox_wrap"><input type="checkbox">����� � �������</div>
				<div class="left_menu_filter_form_button">���������</div>
			</div>
		</div>
	</div>
	<div class="right_column_container">
		<h1><?php echo $gadget->getCurrentType(); ?></h1>
		<?php echo $gadget->getItemsLayout(); ?>
	</div>
	<br>