<div>
	<h3>Page Block Edit</h3>
	<div>
			<style>
		.validation_errors
		{
		  margin:10px;
		  color: red;
		}
			
		</style>
		<div class='validation_errors' >
		<?php echo validation_errors(); ?>
		</div>
	</div>
	<div class='page_detail col-lg-6'>
		<?php echo form_open('/page_block/edit/' . $page_block['page_block_id']) ?>		
			<div class='div-page-id form-group'>
				<label for='page_block_id'>Block ID</label>
				<input class='form-control'
					type='text' disabled='disabled' name='page_block_id' id='page_block_id'
					value='<?php echo $page_block['page_block_id'] ?>'
				>			
				</input>
				</label>
			</div>
			<div class='form-group'>
				<label for='parent_page_id'>Parent Page</label>
				<input class='form-control'
					type='text' name='parent_page_id' id='parent_page_id'
					value='<?php echo $page_block['parent_page_id'] ?>'
				>			
				</input>

			</div>
			<div class='form-group'>
				<label for='display_order'>Display Order</label>
				<input class='form-control'
					type='text' name='display_order' id='display_order'
					value='<?php echo $page_block['display_order'] ?>'
				>			
				</input>
			</div>

			<div class='form-group'>
				<label for='is_visible'>Visible</label>	
				<select name='is_visible' id='is_visible' class='form-control'>
					<option value='0' <?php if($page_block['is_visible']==false) echo " selected='selected' "; ?> >Invisible</option>
					<option value='1' <?php if($page_block['is_visible']==true) echo " selected='selected' "; ?> >Visible</option>
				</select>
				</input>
			</div>
			
			<div class='div-file-upload form-group'>
			<img src='../uploads/page_block/<?php echo $page_block['block_image']; ?>' />
			<label for='block_image'>Image File</label>
			<input type="file" name="block_image" size="20" />
			<?php 
				if (isset($upload_error)){
					foreach ($upload_error as $cur_up_error){
						echo "Upload Error:" . $cur_up_error . "<br/>";
					} // foreach
				} // if
		
			?>
		</div>					
			<div class='form-group'>
				<label for='content_en'>Content English</label>
				<textarea class='form-control'
					name='content_en' id='content_en'
				><?php echo $page_block['content_en'] ?></textarea>
				<script language='javascript'>
				$(function (){					
					$('#content_en').jqte();
				});				
			</script>

			</div>

			<div class='form-group'>
				<input class='btn btn-default' type='submit' value='Submit' />
			</div>
		</form>
	</div> <!-- .page_detail -->
	<div class='col-lg-6'>
				<?php		
			 echo anchor(
					'/page_block/view/list', 
					'Block List', 
					array('title' => 'Block List')
			 ); // echo
			?>
	</div>
</div>