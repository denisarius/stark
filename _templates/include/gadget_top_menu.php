<div class="top_menu_container">
    <div class="top_menu_inner">
        <ul>
            <?php
            global $language, $_cms_menus_items_table;
            $menuItems = get_data_array_rs('id, name', $_cms_menus_items_table, 'menu = '.$language['top_menu_id']. ' and parent=0');

            while ($item = $menuItems->next())
            {
            $url=get_menu_url($item['id']);
                echo "<li><a href=\"$url\">{$item['name']}</a></li>";
//                var_export($menuItems)
            }
            ?>
        </ul>
        <div class="top_menu_dillers"><a href="">¬ход дл€ дилеров</a></div>
        <br>
    </div>
</div>
