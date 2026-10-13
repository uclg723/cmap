<div class='page_create'>
<h3>Create a New Page</h3>

	<script src='vendors/ckeditor/ckeditor.js'></script>
	<link rel="stylesheet" type="text/css" href="vendors/ckeditor/contents.css">
	<script src='vendors/jquery-ui-1.11.2/jquery-ui.min.js'></script>
	<link rel="stylesheet" type="text/css" href="vendors/jquery-ui-1.11.2/jquery-ui.css">

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

<div class='page-form '>
	<?php echo form_open('/page/create') ?>
		<div class='form-group'>
			<label for="page_name">Page Name</label>
			<input class='form-control' type="input" name="page_name" value="page"/>
		</div>
		<div class='form-group'>
			<label for="page_description">Page Description</label>
			<textarea class='form-control' name="page_description">Description</textarea>
		</div>
		<div class='form-group'>
			<label for="menu_order">Menu Order</label>
			<input class='form-control' type="input" name="menu_order" value="0"/>
		</div>
		<div class='form-group'>
			<label for="menu_visible">Visible</label>
			<select class='form-control' name="menu_visible" />
				<option value='0' >Invisible</option>
				<option value='1' selected='selected'>Visible</option>
			</select>
		</div>
		<div class='form-group'>
			<div id='tabcontrol'>
				<ul>
					<li><a href='#tabs-en'>English</a></li>
					<li><a href='#tabs-hk'>繁體</a></li>
					<li><a href='#tabs-cn'>簡體</a></li>
				</ul>
				<div id='tabs-en' class='form-group'>
					<label for="menu_en">Menu Title</label>
					<input  class='form-control' type='text' name='menu_en' id='menu_en' value='Menu' />
					<label for='content_en'>Page Content</label>
					<textarea name='content_en' id='content_en'></textarea>
				</div>
				<div id='tabs-hk' class='form-group'>
					<label for="menu_hk">菜單名稱</label>
					<input  class='form-control' type='text' name='menu_hk' id='menu_hk' value='菜單' />
					<label for='content_hk'>網頁內容</label>
					<textarea name='content_hk' id='content_hk'></textarea>
				</div>
				<div id='tabs-cn' class='form-group'>
					<label for="menu_cn">菜单名称</label>
					<input class='form-control' type='text' name='menu_cn' id='menu_cn' value='菜单' />
					<label for='content_cn'>网页内容</label>
					<textarea name='content_cn' id='content_cn'></textarea>
				</div>				
			</div> <!-- #tabs -->
			<script language='javascript'>
				$(function (){
					CKEDITOR.replace( 'content_en' );
					CKEDITOR.replace( 'content_hk' );
					CKEDITOR.replace( 'content_cn' );
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