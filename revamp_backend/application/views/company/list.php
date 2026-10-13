<h3>Company List</h3>
<div class='company_list'>
	<table class='table-company table table-hover'>
	  <thead>
	  <tr>
		<th>Company ID</th>
		<th>Company Name</th>
		<th>繁體</th>
		<th>簡體</th>
		<th>Telphone</th>
		<th>Fax</th>
		<th>Control</th>
	  </tr>
	  </thead>
	<tbody>
	<?php foreach ($company as $cur_company): ?>
	  <tr class='div_page'>
		<td>
			<?php echo $cur_company['company_id'] ?>
		</td>
		<td>
			<a href='index.php?/company/detail/<?php echo $cur_company['company_id']?>'>
			<?php echo $cur_company['company_name_en'] ?>
			</a>
		</td>
		<td>
			<a href='index.php?/company/detail/<?php echo $cur_company['company_id']?>'>
			<?php echo $cur_company['company_name_hk'] ?>
			</a>
		</td>
		<td>
			<a href='index.php?/company/detail/<?php echo $cur_company['company_id']?>'>
			<?php echo $cur_company['company_name_cn'] ?>
			</a>
		</td>

		<td>
			<a href='index.php?/company/detail/<?php echo $cur_company['company_id']?>'>
			<?php echo $cur_company['company_tel'] ?>
			</a>
		</td>

		<td>
			<a href='index.php?/company/detail/<?php echo $cur_company['company_id']?>'>
			<?php echo $cur_company['company_fax'] ?>
			</a>
		</td>

		<td>
			<?php		
			 echo anchor(
					'/company/edit/' . $cur_company['company_id'], 
					'Edit', 
					array('title' => 'Edit')
			 ); // echo			 
			?>			
				<span class="glyphicon glyphicon-pencil"></span>

			<a href='index.php?/company/delete/<?php echo $cur_company['company_id']; ?>' id='company_remove' onclick="return confirm('Really Remove?');" >
			<span class="glyphicon glyphicon-remove"></span>
			Remove
			</a>
			
		</td>
	  </tr>	
	  
	<?php endforeach; ?>	
	
	<?php
		if (count($company)<=0) :
	?>
	<tr>
	  <td>
		<?php		
		 echo anchor(
				'/company/create', 
				'Create Company', 
				array('title' => 'Create Company')
		 ); // echo
		?>

	  </td>
	</tr>
	<?php
		endif;
	?>
	</tbody>
	</table>
</div> <!-- .company_list -->