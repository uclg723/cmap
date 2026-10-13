<div class='div-detail'>
	<h3>Page Detail</h3>
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
	</style>
	
	<div class='page_detail'>
		<div style='margin:10px'>
			<input  class='btn btn-default' type='button' value='Back' onclick="window.location.href='index.php?/page/view/list';" />
		</div>

		<div class='form-group'>
			<label for='page-name'>Page Name</label>
			<span id='page-name' class='form-control'>
				<?php echo $page['page_name'] ?>
			</span>
		</div>
		<div class='form-group'>
			<label for='page-description'>Page Description</label>
			<span id='page-description' class='form-control'>
				<?php echo $page['page_description'] ?>
			</span>
		</div>
		<div class='form-group'>
			<label>Menu Order</label>
			<span class='form-control'>
				<?php echo $page['menu_order'] ?>
			</span>
		</div>
		<div class='form-group'>
			<label>Menu Visible</label>	
			<span class='form-control'>
				<?php if ($page['menu_visible']==true) echo "Visible"; else echo "Invisible"; ?>
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
					<label for="menu_en">Menu Title</label>
					<span id='menu_en' class='form-control'>
						<?php echo $page['menu_en'];?>
					</span>
					<label for='content_en'>Page Content</label>
					<div id='content_en' class='page-content'>
					<?php echo $page['content_en']; ?>
					</div>
				</div>
				<div id='tabs-hk' class='form-group'>
					<label for="menu_hk">菜單名稱</label>
					<span id='menu_en' class='form-control'>
						<?php echo $page['menu_hk'];?>
					</span>
					<label for='content_hk'>網頁內容</label>
					<div id='content_hk' class='page-content'>
					<?php echo $page['content_hk']; ?>
					</div>
				</div>
				<div id='tabs-cn' class='form-group'>
					<label for="menu_cn">菜单名称</label>
					<span id='menu_cn' class='form-control'>
						<?php echo $page['menu_cn'];?>
					</span>
					<label for='content_cn'>网页内容</label>
					<div id='content_cn' class='page-content'>
					<?php echo $page['content_cn']; ?>
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
			<input class='btn btn-default' type="button" name="edit" value="Edit" onclick="window.location.href='index.php?/page/edit/<?php echo $page['page_id']; ?>';" />
			<input class='btn btn-default' type="button" name="cancel" value="Back" onclick="window.location.href='index.php?/page/index';" />
		</div>

	</div> <!-- .page_detail -->
</div>