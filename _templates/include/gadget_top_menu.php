<div class="top_menu_container">
	<div class="top_menu_inner">
		<ul>
			<?php
			global $language, $_cms_menus_items_table, $page;
			$menuItems = get_data_array_rs(
				'id, name',
				$_cms_menus_items_table,
				'menu = '.$language['top_menu_id'].' and parent=0'
			);

			while ($item = $menuItems->next())
			{
				$url = get_menu_url($item['id']);

				// пункт меню текущей страницы?
				if ($url === "/$page")
					$class = 'current';
				else
					$class = '';

				echo "<li><a href=\"$url\" path=\"$page\" class=\"$class\">{$item['name']}</a></li>";
			}
			?>
		</ul>
		<div class="top_menu_dillers"><a href="">¬ход дл€ дилеров</a></div>
		<br>
	</div>
</div>
