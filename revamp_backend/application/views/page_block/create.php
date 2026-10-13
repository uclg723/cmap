<div class='page_block_create'>
	<script src='vendors/ckeditor/ckeditor.js'></script>
	<link rel="stylesheet" type="text/css" href="vendors/ckeditor/contents.css">
	<script src='vendors/jquery-ui-1.11.2/jquery-ui.min.js'></script>
	<link rel="stylesheet" type="text/css" href="vendors/jquery-ui-1.11.2/jquery-ui.css">
<h3>Create a New Page Block</h3>
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

<div class='page-block-form '>
	<?php echo form_open_multipart('/page_block/create') ?>
		<div class='form-group'>
			<label for="parent_page_id">Parent Page</label>
			<select name='parent_page_id'>
				<option value=""></option>
				<?php if (isset($page_list)): ?>						
					<?php foreach ($page_list as $cur_page) : ?>
						<option value="<?php echo $cur_page['page_id']; ?>">
							<?php echo $cur_page['page_name']; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>

			</select>
		</div>
		<div class='form-group'>
			<label for="display_order">Display Order</label>
			<input class='form-control' type="input" name="display_order" value="0"/>
		</div>
		<div class='form-group'>
			<label for="menu_visible">Visible</label>
			<select class='form-control' name="is_visible" />
				<option value='0' >Invisible</option>
				<option value='1' selected='selected'>Visible</option>
			</select>
		</div>
		<div class='div-file-upload form-group'>
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
		<div id='tabcontrol' class='div-block-content form-group'>
			<ul>
				<li><a href='#tabs-en'>English</a></li>
				<li><a href='#tabs-tc'>Traditional Chinese</a></li>
				<li><a href='#tabs-sc'>Simplified Chinese</a></li>
			</ul>
			<div id='tabs-en'>
				<label for="content_en">English</label>
				<textarea name='content_en' class='ckeditor' id='content_en'></textarea>
			</div>
			<div id='tabs-tc'>
				<label for="content_tc">Traditional Chinese</label>			
				<textarea name='content_tc' class='ckeditor' id='content_tc'></textarea>
			</div>
			<div id='tabs-sc'>
				<label for="content_sc">Simplified</label>			
				<textarea name='content_sc' class='ckeditor' id='content_sc'></textarea>
			</div>
			
			<script language='javascript'>
				$(function (){
					CKEDITOR.replace( 'content_en' );
					CKEDITOR.replace( 'content_tc' );
					CKEDITOR.replace( 'content_sc' );
					$('#tabcontrol').tabs();
				});				
			</script>
		</div>
		<div class='form-group'>
			<input class='btn btn-default' type="submit" name="submit" value="Create Page" />
			<input class='btn btn-default' type="button" name="cancel" value="Cancel" onclick="if (confirm('Cancel will loss the current data. Confirm?')) window.location.href='index.php?/page/index';" />
		</div>
		
	</form>
</div> <!-- .page-form -->
</div> <!-- .page_create -->