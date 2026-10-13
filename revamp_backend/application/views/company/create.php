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
	<?php echo form_open('/company/create') ?>
		<div class='form-group'>
			<label for="company_tel">Telephone</label>
			<input class='form-control' type="input" name="company_tel" value=""/>
		</div>
		<div class='form-group'>
			<label for="company_fax">Fax</label>
			<input class='form-control' type="input" name="company_fax" value=""/>
		</div>
		<div class='form-group'>
			<label for="company_email">Email</label>
			<input class='form-control' type="input" name="company_email" value=""/>
		</div>
		<div class='form-group'>
			<div id='tabcontrol'>
				<ul>
					<li><a href='#tabs-en'>English</a></li>
					<li><a href='#tabs-hk'>繁體</a></li>
					<li><a href='#tabs-cn'>簡體</a></li>
				</ul>
				<div id='tabs-en' class='form-group'>
					<label for="company_name_en">Company Name</label>
					<input  class='form-control' type='text' name='company_name_en' id='company_name_en' value='[Company]' />
					<label for='company_address_en'>Company Address</label>
					<textarea name='company_address_en' id='company_address_en'>[Company Address]</textarea>
				</div>
				<div id='tabs-hk' class='form-group'>
					<label for="company_name_hk">公司名稱</label>
					<input  class='form-control' type='text' name='company_name_hk' id='company_name_hk' value='' />
					<label for='company_address_hk'>公司地址</label>
					<textarea name='company_address_hk' id='company_address_hk'></textarea>
				</div>
				<div id='tabs-cn' class='form-group'>
					<label for="company_name_cn">公司名称</label>
					<input  class='form-control' type='text' name='company_name_cn' id='company_name_cn' value='' />
					<label for='company_address_cn'>公司地址</label>
					<textarea name='company_address_cn' id='company_address_cn'></textarea>
				</div>				
			</div> <!-- #tabs -->
			<script language='javascript'>
				$(function (){
					CKEDITOR.replace( 'company_address_en' );
					CKEDITOR.replace( 'company_address_hk' );
					CKEDITOR.replace( 'company_address_cn' );
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