<div class='div-detail'>
	<h3>Company Detail</h3>
	<script src='vendors/ckeditor/ckeditor.js'></script>
	<link rel="stylesheet" type="text/css" href="vendors/ckeditor/contents.css">
	<script src='vendors/jquery-ui-1.11.2/jquery-ui.min.js'></script>
	<link rel="stylesheet" type="text/css" href="vendors/jquery-ui-1.11.2/jquery-ui.css">

	<style>
		 .page-content{
			margin: 10px;
			border: 1px solid blue;
			width: 100%;
			min-height: 500px;
		 }
		 .company-address-span{
			min-height: 70px;
		 }
	</style>
	
	<div class='page_detail'>
		<div style='margin:10px'>
			<input  class='btn btn-default' type='button' value='Back' onclick="window.location.href='index.php?/company/view/list';" />
		</div>

		<div class='form-group'>
			<label for='company-name'>Company Name</label>
			<span id='company-name' class='form-control'>
				<?php echo $company['company_name_en'] ?>
			</span>
		</div>
		<div class='form-group'>
			<label for='company-tel'>Company Telephone</label>
			<span id='company-tel' class='form-control'>
				<?php echo $company['company_tel'] ?>
			</span>
		</div>
				<div class='form-group'>
			<label for='company-fax'>Company Fax</label>
			<span id='company-fax' class='form-control'>
				<?php echo $company['company_fax'] ?>
			</span>
		</div>
				<div class='form-group'>
			<label for='company-email'>Company Email</label>
			<span id='company-email' class='form-control'>
				<?php echo $company['company_email'] ?>
			</span>
		</div>
		<div class='form-group'>
			<div id='tabcontrol'>
				<ul>
					<li><a href='#tabs-en'>English</a></li>
					<li><a href='#tabs-hk'>繁體</a></li>
					<li><a href='#tabs-cn'>簡體</a></li>
				</ul>
				<div id='tabs-en' class='form-group'>
					<div class='form-group'>
						<label for='company-name-en'>Company Name</label>
						<span id='company-name-en' class='form-control'>
							<?php echo $company['company_name_en']; ?>
						</span>
					</div>
					<div class='form-group'>
						<label for='company-address-en'>Company Name</label>
						<span id='company-address-en' class='form-control company-address-span'>
							<?php echo $company['company_address_en']; ?>
						</span>
					</div>
				</div>
				<div id='tabs-hk' class='form-group'>
					<div class='form-group'>
						<label for='company-name-hk'>公司名稱</label>
						<span id='company-name-hk' class='form-control '>
							<?php echo $company['company_name_hk']; ?>
						</span>
					</div>
					<div class='form-group'>
						<label for='company-address-hk'>公司地址</label>
						<span id='company-address-hk' class='form-control company-address-span'>
							<?php echo $company['company_address_hk']; ?>
						</span>
					</div>
				</div>
				<div id='tabs-cn' class='form-group'>
					<div class='form-group'>
						<label for='company-name-cn'>公司名称</label>
						<span id='company-name-cn' class='form-control'>
							<?php echo $company['company_name_cn']; ?>
						</span>
					</div>
					<div class='form-group'>
						<label for='company-address-cn'>公司地址</label>
						<span id='company-address-cn' class='form-control company-address-span'>
							<?php echo $company['company_address_cn']; ?>
						</span>
					</div>
				</div>				
			</div> <!-- #tabs -->
			<script language='javascript'>
				$(function (){
					$('#tabcontrol').tabs();
				});				
			</script>

		</div>
		<div class='form-group'>
			<input class='btn btn-default' type="button" name="edit" value="Edit" onclick="window.location.href='index.php?/company/edit/<?php echo $company['company_id']; ?>';" />
			<input class='btn btn-default' type="button" name="cancel" value="Back" onclick="window.location.href='index.php?/company/index';" />
		</div>

	</div> <!-- .page_detail -->
</div>