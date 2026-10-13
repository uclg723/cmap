<?php 

require('admin_config.php');

include('../module/Email_Template/Email_Template.php');
include('../module/EditController/EditController.php');
include_once('../module/EditView/EditView.php');

include('../include/functions.php');
 
session_start();
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
if(isset($_SESSION['email_edit'])){
	$email_edit=true;
	unset($_SESSION['email_edit']);
}
$menu="system";




if(isset($_REQUEST['template_id'])){
	$template_id = 	$_REQUEST['template_id'];
}else{
	// default edit 1 : paypal_success_email
	$template_id = 1;
}
	
	$email = new Email_Template();
	if (!empty($template_id)){		
		$email->retrieve($template_id);
	}
		$view = new EditView();
		$view->setOption(array(
				'header'=>false,
				'menu'  => false,
				'footer'=>false,
				'content'=>true,
		));
		$view->setAction('email_edit.php');
		
		$ctlr = new EditController();
		$ctlr->setModel($email);
		$ctlr->setView($view);
		
		// process is moved to later for correct display
		$ctlr->process(); // this will process

		if (isset($_REQUEST['template_name'])){
			$email_edit = true;
		}
		// OLD CODE
?>
<?php include('./include/head.php'); ?>  
<body>
    <!-- Fixed navbar -->
    <?php include('./include/nav.php'); ?>
  <div class="container" style='display:block; background-color:#lightBlue;'>
    <div class="page-header">
        <h1>Email</h1>
      </div>
      <div class="bs-example">
    <?php
	if(isset($email_edit)){
		?>
      <div class="big">
        <p class="bg-success">Email Template has successfully updated.</p>
      </div>
    <?php }  ?>
    </div>
    <div class='div-ctlr-render'>
    	<?php 
    		$ctlr->render();    	
    	?>    
    </div>    
    
 </div>

 	<style>
 		.div_email_body textarea{
 			height: 300px;
 		}
 	</style>


    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="bootstrap-datepicker-master/css/datepicker.css" rel="stylesheet">
     <link href="bootstrap-datepicker-master/css/bootstrap.css" rel="stylesheet">

    <script src="bootstrap-datepicker-master/js/prettify.js"></script>
    <script src="bootstrap-datepicker-master/js/bootstrap-datepicker.js"></script>    
    
    <script>
		$(function(){
			window.prettyPrint && prettyPrint();
            			           
		});
	</script>
     <link href="css/bootstrap.min.css" rel="stylesheet">
    <br>
<br>
<br>


  </body>
</html>
        