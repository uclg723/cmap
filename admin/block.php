<?php include('../include/functions.php'); 
session_start();
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}

$menu="block";

if(isset($_POST['submit']))
{
		$error = 0;
	
        $block = trim($_POST['block']);
	
		if(empty($block)){
			$block = "Enter blocking Date";
			$error++;			
		}
	
	
	    $reason = trim($_POST['reason']);
			if(strlen($reason)>50){
				$reason_error = "Max len should not exceed 50";
				$error++;
			}else if(preg_match("/^[A-Za-z0-9][a-zA-Z -]+$/ ", $reason) === 0){
				$reason_error = "Invalid reason";
				$error++;
			}
		
	
		
		if($error==0){
			connect();
			
			$block = sanitize($block);
			$block=str_replace("-","/",$block);
			$block=strtotime($block);
			$block=date('Y-m-d',$block);

			$reason = sanitize($reason);
		     $SQL = "INSERT INTO block (block_date,reason) VALUES('$block','$reason')";

			$query = mysql_query($SQL);
			if($query){
			
				close();
				$_SESSION['block_insert']=true;
				header("Location: block_list.php");
				
			}else{
				echo mysql_error();
			}			
		}	
}

else{
	$block="";
	$reason="";

}

?>

  <?php include('include/head.php'); ?>  

  <body>


    <!-- Fixed navbar -->
    <?php include('include/nav.php'); ?>



  <div class="container">


    <div class="page-header">
        <h1>Block</h1>
      </div>
   <div class="bs-example">
   
       <div class="col-lg-6">
      
      <form method="post" role="form">
 
  <div class="form-group">
    <label for="exampleInputbdate">Block</label>
    <input type="text" name="block" class="form-control" id="dp1" placeholder="Enter date to block" value="<?php echo $block; ?>"><?php echo isset($block_error)?'<span class="error">'.$block_error.'</span>':''; ?>
  </div>
  

  <div class="form-group">
    <label for="exampleInputadd_info">Reason</label>
    <textarea name="reason" class="form-control" id="exampleInputadd_info" rows="3" placeholder="Enter reason" value=""><?php echo $reason; ?></textarea><?php echo isset($reason_error)?'<span class="error">'.$reason_error.'</span>':''; ?>
  </div>
  <button type="submit" name="submit" class="btn btn-default">Submit</button>
</form>
</div>

</div>
 </div><!-- /.container -->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="bootstrap-datepicker-master/css/datepicker.css" rel="stylesheet">
     <link href="bootstrap-datepicker-master/css/bootstrap.css" rel="stylesheet">

    <script src="bootstrap-datepicker-master/js/prettify.js"></script>
    <script src="bootstrap-datepicker-master/js/bootstrap-datepicker.js"></script>    
    
    <script>
		$(function(){
			window.prettyPrint && prettyPrint();
			$('#dp1').datepicker({
				format: 'mm-dd-yyyy',
                todayBtn: 'linked'
			});			
            			           
		});
	</script>
     <link href="css/bootstrap.min.css" rel="stylesheet">
    <!--<div id="footer">
      <div class="container">
        &#169; Copyrights 2014
      </div>
    </div>-->
    <br>
<br>
<br>


  </body>
</html>
        