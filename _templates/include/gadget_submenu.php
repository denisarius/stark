<div class="content_container_top"></div>
<div class="content_container_bg"></div>
<div class="index_submenu_container">
	<?php
	global $language, $_cms_menus_items_table, $page;
	$menuItems = get_data_array_rs(
		'id, name',
		$_cms_menus_items_table,
		'menu = '.$language['submenu_id'].' and parent=0'
	);

	$itemOrd = 1;
	while ($item = $menuItems->next())
	{
		$url = get_menu_url($item['id']);

		// пункт меню текущей страницы?
		if ($url === "/$page")
			$classSelected = 'current';
		else
			$classSelected = '';

		// дополнительные классы для первого и последнего
		switch ($itemOrd)
		{
			case 1: $classEnding = 'first';
				break;
			case 4: $classEnding = 'last';
				break;
			default: $classEnding = '';
		}

		echo <<<ITEM
	<a href="$url" class="index_submenu_node $classEnding">
		<div class="index_submenu_node_wrap $classSelected">
			<img src="@!template@/images/submenu-$itemOrd.png">
			<p>{$item['name']}</p>
		</div>
	</a>
ITEM;
		++$itemOrd;
	}
	?>
<!--	 TODO убрать мусор-->
<!--	<a href="#" class="index_submenu_node first">-->
<!--		<div class="index_submenu_node_wrap">-->
<!--			<img src="@!template@/images/submenu-1.png">-->
<!--			<p>Натяжные потолки</p>-->
<!--		</div>-->
<!--	</a>-->
<!--	<a href="#" class="index_submenu_node">-->
<!--		<div class="index_submenu_node_wrap">-->
<!--			<img src="@!template@/images/submenu-2.png">-->
<!--			<p>Люстры и светильники</p>-->
<!--		</div>-->
<!--	</a>-->
<!--	<a href="#" class="index_submenu_node">-->
<!--		<div class="index_submenu_node_wrap">-->
<!--			<img src="@!template@/images/submenu-3.png">-->
<!--			<p>Жалюзи</p>-->
<!--		</div>-->
<!--	</a>-->
<!--	<a href="#" class="index_submenu_node last">-->
<!--		<div class="index_submenu_node_wrap">-->
<!--			<img src="@!template@/images/submenu-4.png">-->
<!--			<p>Пластиковые окна</p>-->
<!--		</div>-->
<!--	</a>-->
	<br>
</div>
