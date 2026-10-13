<div>
<h3>Page Detail</h3>
<div class='page_detail'>

	<div class='div-page-id form-group'>
		<label for='page-id'>Page ID</label>
		<span id='page-id' class='form-control'>
			<?php echo $page['page_id'] ?>
		</span>
		</label>
	</div>
	<div class='form-group'>
		<label for='page-name'>Page Name</label>
		<span id='page-name' class='form-control'>
			<?php echo $page['page_name'] ?>
		</span>
	</div>
	<div class='form-group'>
		<label for='page-description'>Page Description</label>
		<span id='page-description' class='form-control'>
			<?php echo $page['page_description'] ?>
		</span>
	</div>
	<div class='form-group'>
		<label>Menu Order</label>
		<span class='form-control'>
			<?php echo $page['menu_order'] ?>
		</span>
	</div>
	<div class='form-group'>
		<label>Menu Visible</label>	
		<span clas='form-control'>
			<?php if ($page['menu_visible']==true) echo "Visible"; else echo "Invisible"; ?>
		</span>
	</div>
</div> <!-- .page_detail -->
<div style='margin:10px'>
			<?php		
		 echo anchor(
				'/page/view/list', 
				'Pages List', 
				array('title' => 'Page List')
		 ); // echo
		?>
</div>
</div>