<h3>Page Block List</h3>
<div class='page_list'>
	<table class='table-page-block table table-hover'>
	  <thead>
	  <tr>
		<th>Page Block ID</th>
		<th>Parent Page ID</th>
		<th>Display Order</th>
		<th>Is Visible</th>
		<th>Control</th>
	  </tr>
	  </thead>
	<tbody>
	<?php if (!isset($page_blocks)) : ?>
		<tr><td>No Record</td></tr>
	<?php else: ?>
		<?php foreach ($page_blocks as $block_item): ?>
		  <tr class='div_page'>
			<td>
				<?php echo $block_item['page_block_id'] ?>
			</td>
			<td>
				<a href='index.php?/page/detail/<?php echo $block_item['parent_page_id']?>'>
					<?php if (isset($page_list)): ?>						
						<?php foreach ($page_list as $cur_page) : ?>
							<?php if ($cur_page['page_id'] == $block_item['parent_page_id']){
									echo $cur_page['page_name'];
								  }
							?>							
						<?php endforeach; ?>
					<?php endif; ?>				
				</a>
			</td>
			<td>
				<?php echo $block_item['display_order'] ?>
			</td>
			<td>
				<?php if ($block_item['is_visible']==true) { ?>
					<span style='color:green;'>Visible</span>
				<?php }else{ ?>
					<span style='color:red;'>Invisible</span>
				<?php } ?>
			</td>
			<td>
				<?php		
				 echo anchor(
						'/page_block/edit/' . $block_item['page_block_id'], 
						'Edit', 
						array('title' => 'Edit')
				 ); // echo			 
				?>			
					<span class="glyphicon glyphicon-pencil"></span>

				<a href='index.php?/page_block/delete/<?php echo $block_item['page_block_id']; ?>' id='page_remove' onclick="return confirm('Really Remove?');" >
				<span class="glyphicon glyphicon-remove"></span>
				Remove
				</a>
				
			</td>
		  </tr>
		<?php endforeach ?>	
	<?php endif; ?>
	<tr>
	  <td>
		<?php		
		 echo anchor(
				'/page_block/create', 
				'Create Page Block', 
				array('title' => 'Create Page Block')
		 ); // echo
		?>

	  </td>
	</tr>
	</tbody>
	</table>
</div> <!-- .page_list -->