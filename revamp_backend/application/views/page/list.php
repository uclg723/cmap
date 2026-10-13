<h3>Page List</h3>
<div class='page_list'>
	<table class='table-page table table-hover'>
	  <thead>
	  <tr>
		<th>Page ID</th>
		<th>Page Name</th>
		<th>Description</th>
		<th>Menu Order</th>
		<th>Menu Visible</th>
		<th>Control</th>
	  </tr>
	  </thead>
	<tbody>
	<?php foreach ($page as $page_item): ?>
	  <tr class='div_page'>
		<td>
			<?php echo $page_item['page_id'] ?>
		</td>
		<td>
			<a href='index.php?/page/detail/<?php echo $page_item['page_id']?>'>
			<?php echo $page_item['page_name'] ?>
			</a>
		</td>
		<td>
			<?php echo $page_item['page_description'] ?>
		</td>
		<td>
			<?php echo $page_item['menu_order'] ?>
		</td>
		<td>
			<?php if ($page_item['menu_visible']==true) { ?>
				<span style='color:green;'>Visible</span>
			<?php }else{ ?>
				<span style='color:red;'>Invisible</span>
			<?php } ?>
		</td>
		<td>
			<?php		
			 echo anchor(
					'/page/edit/' . $page_item['page_id'], 
					'Edit', 
					array('title' => 'Edit')
			 ); // echo			 
			?>			
				<span class="glyphicon glyphicon-pencil"></span>

			<a href='index.php?/page/delete/<?php echo $page_item['page_id']; ?>' id='page_remove' onclick="return confirm('Really Remove?');" >
			<span class="glyphicon glyphicon-remove"></span>
			Remove
			</a>
			
		</td>
	  </tr>
	<?php endforeach ?>	
	<tr>
	  <td>
		<?php		
		 echo anchor(
				'/page/create', 
				'Create Page', 
				array('title' => 'Create Page')
		 ); // echo
		?>

	  </td>
	</tr>
	</tbody>
	</table>
</div> <!-- .page_list -->