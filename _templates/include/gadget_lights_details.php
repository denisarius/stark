<?php
$gadget = new GadgetLightDetails();
?>
<div class="left_menu_container">
	<?php echo $gadget->getTypesMenuLayout(); ?>
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
			<input type="text" class="left_menu_input" placeholder="��">
			<input type="text" class="left_menu_input last" placeholder="��">
			<br>
			<div class="left_menu_checkbox_wrap"><input type="checkbox">����� �� ������</div>
			<div class="left_menu_checkbox_wrap"><input type="checkbox">����� � �������</div>
			<div class="left_menu_filter_form_button">���������</div>
		</div>
	</div>
</div>
<div class="right_column_container">
	<h1><?php echo $gadget->getCurrentTypeName(); ?></h1>
	<?php echo $gadget->getItemLayout(); ?>
<!--	<div class="product_info_container">-->
<!--		<img src="img/pics/img_product_1.jpg" alt="">-->
<!--		<div class="product_info_about">-->
<!--			<h2>������ ���������� </h2>-->
<!--			<span>�������������: ODEON LIGHT</span>-->
<!--			<ul>-->
<!--				<li><span class="left">�������</span><span class="right">2006/4C</span></li>-->
<!--				<li><span class="left">�������������</span><span class="right"> ODEON LIGHT</span></li>-->
<!--				<li><span class="left">������</span><span class="right"> ������</span></li>-->
<!--				<li><span class="left">������� ����� </span><span class="right">380�580�580 ��</span></li>-->
<!--				<li><span class="left">��� �������� (��������)</span><span class="right"> G9</span></li>-->
<!--				<li><span class="left">�������� �����</span><span class="right">  W 40</span></li>-->
<!--				<li><span class="left">���������� ����</span><span class="right"> 4</span></li>-->
<!--				<li><span class="left">������� ��������� </span><span class="right">11 �2</span></li>-->
<!--				<li><span class="left">���������</span><span class="right"> FORTA</span></li>-->
<!--				<li><span class="left">��� �����������</span><span class="right"> ������</span></li>-->
<!--				<li><span class="left">����� �����������</span><span class="right"> ������</span></li>-->
<!--				<li><span class="left">������ ���������� </span><span class="right">����������</span></li>-->
<!--				<li><span class="left">��������</span><span class="right"> �����/������</span></li>-->
<!--				<li><span class="left">����</span><span class="right"> ����</span></li>-->
<!--				<li><span class="left">�������� � ���������</span><span class="right"> ����</span></li>-->
<!--			</ul>-->
<!--			<br>-->
<!--			<div class="product_info_order">-->
<!--								<span>��� �����<br>-->
<!--								����:<span>125.666 q</span></span>-->
<!--			</div>-->
<!--			<div clsas=""></div>-->
<!--			<div class="product_info_about_tosearch">� ����������� ������</div>-->
<!--		</div>-->
<!--	</div>-->
	<br>
	<div class="product_same_container">
		<span>������� ������</span>
		<div class="product_same_container_wrap">
			<a href="" class="product_same_container_item">
				<h2>������ ���������� </h2>
				<span class="product_same_container_dev">ODEON LIGHT</span>
				<img src="img/pics/img_product_1.jpg" alt="">
				<span class="product_same_container_ptice">3001 q</span>
			</a>
			<a href="" class="product_same_container_item">
				<h2>������ ���������� </h2>
				<span class="product_same_container_dev">ODEON LIGHT</span>
				<img src="img/pics/img_product_1.jpg" alt="">
				<span class="product_same_container_ptice">3001 q</span>
			</a>
			<a href="" class="product_same_container_item">
				<h2>������ ���������� </h2>
				<span class="product_same_container_dev">ODEON LIGHT</span>
				<img src="img/pics/img_product_1.jpg" alt="">
				<span class="product_same_container_ptice">3001 q</span>
			</a>
			<a href="" class="product_same_container_item">
				<h2>������ ���������� </h2>
				<span class="product_same_container_dev">ODEON LIGHT</span>
				<img src="img/pics/img_product_1.jpg" alt="">
				<span class="product_same_container_ptice">3001 q</span>
			</a>
			<br>
		</div>
	</div>
<!--	<h1>--><?php //echo $gadget->getCurrentTypeName(); ?><!--</h1>-->
<!--	--><?php //echo $gadget->getItemsLayout(); ?>
</div>
<br>