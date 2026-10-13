<?php
	// reference to $success_view==1 to show correct menu

	$success_view = isset($_SESSION['id']);

	$block['page-menu'] = array(
		'home'=>array(
			'name'=>'Home',
			'url' =>'home.php',
			'success_view_filter'=>2, //0: false, 1: true, 2: always
		),
		'about_us'=>array(
				'name'=>'About Us',
				'url' =>'about_us.php',
			'success_view_filter'=>2, //0: false, 1: true, 2: always

		),			
		'price'=>array(
				'name'=>'Online Reservation',
				'url' =>'price.php',
			'success_view_filter'=>2, //0: false, 1: true, 2: always
		),
		'terms'=>array(
				'name'=>'Terms & Conditions',
				'url' =>'terms.php',
			'success_view_filter'=>2, //0: false, 1: true, 2: always
		),
		'contact_us'=>array(
				'name'=>'Contact Us',
				'url' =>'contact_us.php',
				'success_view_filter'=>2, //0: false, 1: true, 2: always
		),	
		'myaccount'=>array(
				'name'=>'My Account',
				'url' =>'myaccount.php',
				'success_view_filter'=>1, //0: false, 1: true, 2: always
		),
		'logout'=>array(
				'name'=>'Logout',
				'url' =>'logout.php',
				'success_view_filter'=>1, //0: false, 1: true, 2: always
		),
		'signup'=>array(
				'name'=>'Sign Up',
				'url' =>'signup.php',
				'success_view_filter'=>0, //0: false, 1: true, 2: always
		),
		'login'=>array(
				'name'=>'Login',
				'url' =>'login.php',
				'success_view_filter'=>0, //0: false, 1: true, 2: always
		),			
	); // block page-menu
?>
<div class='page-content'>
	<ul class='page-menu'>
		<?php
			foreach ($block['page-menu'] as $key=>$menu_content){
				if (
					($menu_content['success_view_filter']==2) ||
					($menu_content['success_view_filter']==0 && $success_view==0) ||
					($menu_content['success_view_filter']==1 && $success_view==1)
				){
		?>
		<li>
			<a href='<?php echo $menu_content['url'];?>' class='nav'><?php echo $menu_content['name'];?></a>
		</li>
		<?php 
				} // if display
			} // foreach
		?>
	</ul>
</div>
