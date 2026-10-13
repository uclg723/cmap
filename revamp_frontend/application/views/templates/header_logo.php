<div class='header_logo'>
	<div class='header_logo_icon'>
	<a title="Title" href="index.php?/">
		<img width="250px" src="images/logo.png"></img>
	</a>
	</div>
	<div style="" class="header_logo_phone align-right vertical-align-middle">
		<img src="images/phone_icon.jpg">
		<span style="font-size:22px;color:#53181a"><?php echo $company_tel; ?></span><br>
		<?php echo $this->lang->line("page_enquiry"); ?> <br><br>
		<a href="javascript:document.cookie='lang=english'; window.location.assign('index.php?/<?php echo $this->uri->uri_string()?>');" class="nav">Eng</a>
		|
		<a href="javascript:document.cookie='lang=cn'; window.location.assign('index.php?/<?php echo $this->uri->uri_string()?>');" class="nav">中文 </a>
	</div>
	
</div>