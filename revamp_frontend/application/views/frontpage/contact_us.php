<div class='contact-container'>
	<style>
	.left-contact-us-image{
		margin: 5px;
		display: inline-block;
	}
	.right-company-info{
		display: inline-block;
		vertical-align: top;
	}
	.contact-container{
		display: block;
	}
	.company_label{
		width: 85px;
		display: inline-block;
		margin-bottom: 20px;
	}
	</style>
	<div class='left-contact-us-image'>
		<img src="images/contact_us.jpg">
	</div>
	<div class='right-company-info'>
		<h2><?php echo $company_name; ?></h2>

		<div class=''>
			<span class='company_label'><?php echo $this->lang->line("page_contact_address"); ?>:</span>
			<span style='display:inline-block;vertical-align:middle;'><?php echo $company_address; ?></span>	
		</div>
		<div class=''>
			<span class='company_label'>
				<?php echo $this->lang->line("page_contact_tel"); ?>:
			</span>
			<span class='company_value'>
			<?php echo $company_tel; ?>
			</span>
		</div>
		<div class=''>
			<span class='company_label'>
				<?php echo $this->lang->line("page_contact_fax"); ?>:
			</span>
			<span class='company_value'>
			<?php echo $company_fax; ?>
			</span>
		</div>
		<div class=''>
			<span class='company_label'>
				<?php echo $this->lang->line("page_contact_email"); ?>:
			</span>
			<span class='company_value'>
			<a href='mailto:<?php echo $company_email; ?>'>
				<?php echo $company_email; ?>
				<img src="images/mail.png" style="vertical-align:text-top">
			</a>
			</span>
	</div>
</div>