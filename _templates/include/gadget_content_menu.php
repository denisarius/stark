<!-- ���� �������� (���� 2-�� ������ ��� ����� ������� ����) -->
<div class="content_menu_container">
	<ul>
		<?php
		global $language, $_cms_menus_items_table, $page;
		$menuItems = get_data_array_rs(
			'id, name',
			$_cms_menus_items_table,
			'parent='.get_menu_item_id(1)
		);
		$levelTwoMenuId = get_menu_item_id(2);

		while ($item = $menuItems->next())
		{
			$url = get_menu_url($item['id']);

			// �������� � ���� ���������?
			if ($item['id'] == $levelTwoMenuId)
				$classSelected = 'current';
			else
				$classSelected = '';

			$subMenuItems = get_data_array_rs(
				'id, name',
				$_cms_menus_items_table,
				'parent='.$item['id']
			);

			$subMenu = '';
			$classSubMenu = '';

			while ($subItem = $subMenuItems->next())
			{
				$subUrl = get_menu_url($subItem['id']);
				$subMenu .= "<li><a href=\"$subUrl\">{$subItem['name']}</a></li>";
			}

			if ($subMenu)
			{
				$classSubMenu = 'content_menu_submenu';
				$subMenu = "<ul>$subMenu</ul>";
			}

			echo <<<ITEM
		<li class="$classSelected $classSubMenu"><a href="$url">{$item['name']}</a>
		$subMenu
		</li>
ITEM;
		}
		?>


		<!--		<li><a href="">� ������   </a></li>-->
		<!--	    <li><a href=""> ������� �������  </a></li>-->
		<!--	    <li class="content_menu_submenu"><a href="">������� ������  </a>-->
		<!--	    	<ul>-->
		<!--	    		<li><a href="">������ ��� ���� ���</a></li>-->
		<!--	    		<li><a href="">������������ ������</a></li>-->
		<!--	    		<li><a href="">�������������� ������</a></li>-->
		<!--	    		<li><a href="">�������� �����</a></li>-->
		<!--	    	</ul>-->
		<!--	    </li>-->
		<!--	    <li><a href="">������� � ������� </a></li>-->
		<!--	    <li><a href="">����ר� ��������� </a></li>-->
		<!--	    <li><a href="">�����������</a></li>-->
	</ul>
</div>