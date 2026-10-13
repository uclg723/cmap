<div class='main-menu'><?php
	foreach ($menu as $menu_item){		$menu_url = (array_key_exists('menu_url', $menu_item))? 			$menu_item['menu_url']:			'index.php?/frontpage/view_by_id/' . $menu_item['page_id'];?>
	<span class='nav menu_item'>
		<a href='<?php echo $menu_url;?>' class='nav'>
		<?php echo $menu_item['menu_name'];?>
		</a>
	</span><?php
	} // foreach?>

</div>