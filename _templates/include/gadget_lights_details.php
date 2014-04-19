<?php
$gadget = new GadgetLightDetails();
?>
<div class="left_menu_container">
	<?php echo $gadget->getTypesMenuLayout(); ?>
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
			<input type="text" class="left_menu_input" placeholder="От">
			<input type="text" class="left_menu_input last" placeholder="До">
			<br>
			<div class="left_menu_checkbox_wrap"><input type="checkbox">Товар по скидке</div>
			<div class="left_menu_checkbox_wrap"><input type="checkbox">Товар в наличии</div>
			<div class="left_menu_filter_form_button">Подобрать</div>
		</div>
	</div>
</div>
<div class="right_column_container">
	<h1><?php echo $gadget->getCurrentTypeName(); ?></h1>
	<?php echo $gadget->getItemLayout(); ?>
<!--	<div class="product_info_container">-->
<!--		<img src="img/pics/img_product_1.jpg" alt="">-->
<!--		<div class="product_info_about">-->
<!--			<h2>Люстра потолочная </h2>-->
<!--			<span>Производитель: ODEON LIGHT</span>-->
<!--			<ul>-->
<!--				<li><span class="left">Артикул</span><span class="right">2006/4C</span></li>-->
<!--				<li><span class="left">Производитель</span><span class="right"> ODEON LIGHT</span></li>-->
<!--				<li><span class="left">Страна</span><span class="right"> Италия</span></li>-->
<!--				<li><span class="left">Размеры ВхДхШ </span><span class="right">380х580х580 мм</span></li>-->
<!--				<li><span class="left">Тип лампочки (основной)</span><span class="right"> G9</span></li>-->
<!--				<li><span class="left">Мощность лампы</span><span class="right">  W 40</span></li>-->
<!--				<li><span class="left">Количество ламп</span><span class="right"> 4</span></li>-->
<!--				<li><span class="left">Площадь освещения </span><span class="right">11 м2</span></li>-->
<!--				<li><span class="left">Коллекция</span><span class="right"> FORTA</span></li>-->
<!--				<li><span class="left">Тип светильника</span><span class="right"> Люстры</span></li>-->
<!--				<li><span class="left">Стиль светильника</span><span class="right"> Модерн</span></li>-->
<!--				<li><span class="left">Способ размещения </span><span class="right">Потолочный</span></li>-->
<!--				<li><span class="left">Материал</span><span class="right"> Метал/Стекло</span></li>-->
<!--				<li><span class="left">Цвет</span><span class="right"> Хром</span></li>-->
<!--				<li><span class="left">Лампочки в комплекте</span><span class="right"> есть</span></li>-->
<!--			</ul>-->
<!--			<br>-->
<!--			<div class="product_info_order">-->
<!--								<span>Под заказ<br>-->
<!--								Цена:<span>125.666 q</span></span>-->
<!--			</div>-->
<!--			<div clsas=""></div>-->
<!--			<div class="product_info_about_tosearch">К результатам поиска</div>-->
<!--		</div>-->
<!--	</div>-->
	<br>
	<div class="product_same_container">
		<span>Похожие товары</span>
		<div class="product_same_container_wrap">
			<a href="" class="product_same_container_item">
				<h2>Люстра потолочная </h2>
				<span class="product_same_container_dev">ODEON LIGHT</span>
				<img src="img/pics/img_product_1.jpg" alt="">
				<span class="product_same_container_ptice">3001 q</span>
			</a>
			<a href="" class="product_same_container_item">
				<h2>Люстра потолочная </h2>
				<span class="product_same_container_dev">ODEON LIGHT</span>
				<img src="img/pics/img_product_1.jpg" alt="">
				<span class="product_same_container_ptice">3001 q</span>
			</a>
			<a href="" class="product_same_container_item">
				<h2>Люстра потолочная </h2>
				<span class="product_same_container_dev">ODEON LIGHT</span>
				<img src="img/pics/img_product_1.jpg" alt="">
				<span class="product_same_container_ptice">3001 q</span>
			</a>
			<a href="" class="product_same_container_item">
				<h2>Люстра потолочная </h2>
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