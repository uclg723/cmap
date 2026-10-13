<?php include('../include/functions.php'); 
session_start();
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
if(isset($_SESSION['booking_edit'])){
	$booking_edit=true;
	unset($_SESSION['booking_edit']);
}
$menu="booking";



if(isset($_GET['id'])){
		connect();
		$id = sanitize($_GET['id']);
		$SQL = "SELECT * FROM booking WHERE id = $id";
		$query = mysql_query($SQL);
		if($query){
		
			$nor = mysql_num_rows($query);
			if($nor>0){
					$row = mysql_fetch_assoc($query);
					 $uid=$row['uid']; 
					 $fdate=strtotime($row['date_in']);
					 $fdate=date("m-d-Y",$fdate);
					 
					 $todate=strtotime($row['date_out']);
					 $todate=date("m-d-Y",$todate);
					 $add_info=$row['add_info'];
                     $days=$row['days'];
					 $rid=$row['rid'];
					 $btype=$row['btype'];
                     $status=$row['status'];    
			}else{
				header("Location: booking_list.php");
			}
			
		}else{
			die("Error: ".mysql_error());
		}
		close();
	}




if(isset($_POST['submit']))
{
		$error = 0;
	
		$uid = trim($_POST['uid']);
		if(empty($uid)){
			$uid_error = "Select User";
			$error++;			
		
		}else if(preg_match("/^[0-9]+$/", $uid) === 0){
			$uid_error = "Invalid Uid";
			$error++;
		}
		
        $fdate = trim($_POST['fdate']);
       $fdate = $_POST['fdate'];
		if(empty($fdate)){
			$fdate_error = "Enter From Date";
			$error++;			
		}
		
		$todate= trim($_POST['todate']);
		$todate = $_POST['todate'];
		if(empty($todate)){
			$todate_error = "Enter To Date";
			$error++;			
		}
		 $add_info = trim($_POST['add_info']);
			/*if(empty($add_info)){
				$add_info_error = "Invalid Additional information";
				$error++;
			}*/
		
		$days = trim($_POST['days']);
		if(empty($days)){
			$days_error = "Enter your days";
			$error++;			
		
		}else if(preg_match("/^[0-9]+$/", $days) === 0){
			$days_error = "Invalid days";
			$error++;
		}
        
		$rid = trim($_POST['rid']);
		if(empty($rid)){
			$rid_error = "Select Room";
			$error++;			
		
		}else if(preg_match("/^[0-9]+$/", $rid) === 0){
			$rid_error = "Invalid Rid";
			$error++;
		}
     
	  if(!isset($_POST['btype'])){
			$btype_error = "Select Booking type";
			$error++;			
		}else{			
			$btype = $_POST['btype'];
		}
		
		if(!isset($_POST['status'])){
			$status_error = "Select status";
			$error++;			
		}else{			
			$status = $_POST['status'];
		}
		
		
		 $farr = explode('-', $fdate);
        $fdate = $farr[2].'-'.$farr[0].'-'.$farr[1];
		$fdate=strtotime($fdate);

	    $toarr = explode('-', $todate);
        $todate = $toarr[2].'-'.$toarr[0].'-'.$toarr[1];
		$todate=strtotime($todate);

		$fdate=date("Y-m-d", $fdate);
		$todate=date("Y-m-d", $todate);
		
		if($error==0){
			connect();
			$uid = sanitize($uid);
			$add_info = sanitize($add_info);
			$days = sanitize($days);
			$rid = sanitize($rid);
		    $status = sanitize($status);
		    
			$SQL="UPDATE booking set uid='$uid',date_in='$fdate',date_out='$todate',add_info='$add_info',days='$days',rid='$rid',status='$status',btype='$btype' WHERE id='$id'";
				
			$query = mysql_query($SQL);
			if($query){
			
				close();
				$_SESSION['booking_edit']=TRUE;
				header("Location: booking_edit.php?id=$id");
				
			}else{
				echo mysql_error();
			}			
		}	
}


// users list
connect();
$uSQL = "SELECT * FROM users WHERE utype!=2 ORDER BY firstname ASC";
$uquery = mysql_query($uSQL);
$unor=mysql_num_rows($uquery);
$ulist="";
if($uquery){
	if($unor>0){		
		while($row = mysql_fetch_array($uquery)){  
			if($uid==$row['id']){
				$ulist.='<option value="'.$row['id'].'" selected>'.$row['id'].': '.$row['firstname'].'</option>';
			}else{
				$ulist.='<option value="'.$row['id'].'">'.$row['id'].': '.$row['firstname'].'</option>';
			}
		}
	}
}

//room list
$rSQL = "SELECT * FROM rooms";
$rquery = mysql_query($rSQL);
$rnor=mysql_num_rows($rquery);
$rlist="";
if($rquery){
	if($rnor>0){
		while($row = mysql_fetch_array($rquery)){  
			if($rid==$row['id']){
				$rlist.='<option value="'.$row['id'].'" selected>'.$row['room_name'].'</option>';
			}else{
				$rlist.='<option value="'.$row['id'].'">'.$row['room_name'].'</option>';
			}
		}
	}
}


?>

  <?php include('include/head.php'); ?>  

  <body>


    <!-- Fixed navbar -->
    <?php include('include/nav.php'); ?>



  <div class="container">
 
  <form>
</form>
    <div class="page-header">
        <h1>Booking</h1>
      </div>
   <div class="bs-example">
    <?php
	if(isset($booking_edit)){
		?>
      <div class="big">
        <p class="bg-success">Booking details has successfully updated.</p>
      </div>
      <?php }  ?>
       <div class="col-lg-6">
      
      <form method="post" role="form">
  <div class="form-group">

    <label for="exampleInputuid">User</label>
    <select name="uid" id="uid" class="form-control">
            <option value="0" selected>Select User</option>
            <?php echo $ulist; ?>
	</select>
   <?php echo isset($uid_error)?'<span class="error">'.$uid_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputfdate">Day In</label>
    <input type="text" name="fdate" class="form-control" id="dp1" placeholder="Select date" value="<?php echo $fdate; ?>"><?php echo isset($fdate_error)?'<span class="error">'.$fdate_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputtodate">Day Out</label>
    <input type="text" name="todate" class="form-control" id="dp2" placeholder="Select date" value="<?php echo $todate; ?>"><?php echo isset($todate_error)?'<span class="error">'.$todate_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputadd_info">Additional Information</label>
    <textarea name="add_info" class="form-control" id="exampleInputadd_info" rows="3" placeholder="Enter additional information" value=""><?php echo $add_info; ?></textarea><?php echo isset($add_info_error)?'<span class="error">'.$add_info_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputdays">Days</label>
    <input type="text" name="days" class="form-control" id="exampleInputdays" placeholder="Enter days" value="<?php echo $days; ?>"><?php echo isset($days_error)?'<span class="error">'.$days_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputrid">Room</label>
        <select name="rid" id="rid" class="form-control">
            <option value="0" selected>Select Room</option>
            <?php echo $rlist; ?>
	</select>

    <?php echo isset($rid_error)?'<span class="error">'.$rid_error.'</span>':''; ?>
  </div>
  
    <div class="form-group">
    <label for="exampleInputtype">Booking Type</label>
         
          <div class="radio">
          <label>
            <input type="radio" name="btype" id="optionsRadios1" value="1"  <?php echo (isset($btype) && ($btype==1))?'checked':"";?> >
            Phone
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="btype" id="optionsRadios2" value="2" <?php  echo (isset($btype) && ($btype==2))?'checked':"";?>>
           Online
          </label>
        </div>
         <div class="radio">
          <label>
            <input type="radio" name="btype" id="optionsRadios3" value="3" <?php  echo (isset($btype) && ($btype==3))?'checked':"";?>>
             VIP
          </label>
        </div>
         <div class="radio">
          <label>
            <input type="radio" name="btype" id="optionsRadios4" value="4" <?php  echo (isset($btype) && ($btype==4))?'checked':"";?>>
             Repair
          </label>
        </div>

	<?php echo isset($btype_error)?'<span class="error">'.$btype_error.'</span>':''; ?>
  </div>
  
   <div class="form-group">
    <label for="exampleInputstatus">Status</label>
         
          <div class="radio">
          <label>
            <input type="radio" name="status" id="optionsRadios1" value="1"  <?php echo (isset($status) && ($status==1))?'checked':"";?> >
            Inactive
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="status" id="optionsRadios2" value="2" <?php  echo (isset($status) && ($status==2))?'checked':"";?>>
            Active
          </label>
        </div>

	<?php echo isset($status_error)?'<span class="error">'.$status_error.'</span>':''; ?>
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
    <!--<link href="bootstrap-datepicker-master/css/datepicker.css" rel="stylesheet">
     <link href="bootstrap-datepicker-master/css/bootstrap.css" rel="stylesheet">

    <script src="bootstrap-datepicker-master/js/prettify.js"></script>-->
    <script src="bootstrap-datepicker-master/js/bootstrap-datepicker.js"></script>    
    
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	
	
    <script>
		$(function(){
			window.prettyPrint && prettyPrint();
			$('#dp1').datepicker({
				dateFormat: 'mm-dd-yy',
                todayBtn: 'linked'
			});
			$('#dp2').datepicker({
				dateFormat: 'mm-dd-yy',
                todayBtn: 'linked'
			});
			
			var v1 = document.getElementById('dp1').value;
			var v2 = document.getElementById('dp2').value;
			if(v1!="" && v2!=""){
			  var parts1 = v1.split('-'); 
			  var parts2 = v2.split('-');
			  var date1 = new Date(parts1[2],(parts1[0]-1),parts1[1]);
			  var date2 = new Date(parts2[2],(parts2[0]-1),parts2[1]);
			  var diffDays = Math.round(Math.abs((date1.getTime() - date2.getTime())/(24*3600*1000)));
			  $("#exampleInputdays").val(diffDays);
			}
   			           
		});
		
		
$( "#dp1" ).change(function() {
	var v1 = document.getElementById('dp1').value;
	var v2 = document.getElementById('dp2').value;
	
	check_booking_date(v1,v2);
	
	
	if(v1!="" && v2!=""){
		var from_date = $("#dp1").datepicker('getDate'),
		to_date   = $("#dp2").datepicker('getDate');
		if(to_date.getTime()<=from_date.getTime()){
			alert("Booking dates not valid");
			$("#dp1").val("");
			$("#dp2").val("");
		}else{
			var parts1 = v1.split('-'); 
			var parts2 = v2.split('-');
			var date1 = new Date(parts1[2],(parts1[0]-1),parts1[1]);
			var date2 = new Date(parts2[2],(parts2[0]-1),parts2[1]);
			var diffDays = Math.round(Math.abs((date1.getTime() - date2.getTime())/(24*3600*1000)));
			$("#exampleInputdays").val(diffDays);
		}
	}
});
$( "#dp2" ).change(function() {
	var v1 = document.getElementById('dp1').value;
	var v2 = document.getElementById('dp2').value;
	
	check_booking_date(v1,v2);
	
	
	if(v1!="" && v2!=""){
		var from_date = $("#dp1").datepicker('getDate'),
		to_date   = $("#dp2").datepicker('getDate');
		if(to_date.getTime()<=from_date.getTime()){
			alert("Booking dates not valid");
			$("#dp1").val("");
			$("#dp2").val("");
		}else{
			var parts1 = v1.split('-'); 
			var parts2 = v2.split('-');
			var date1 = new Date(parts1[2],(parts1[0]-1),parts1[1]);
			var date2 = new Date(parts2[2],(parts2[0]-1),parts2[1]);
			var diffDays = Math.round(Math.abs((date1.getTime() - date2.getTime())/(24*3600*1000)));
			$("#exampleInputdays").val(diffDays);
		}
	}
});			
		
function check_booking_date(date_from,date_to){

	console.log(location.search);
	
	var date_from_tmp = date_from.split("-");
	var date_to_tmp = date_to.split("-");
	
	var date_from2 = date_from_tmp[2]+date_from_tmp[0]+date_from_tmp[1];
	var date_to2 = date_to_tmp[2]+date_to_tmp[0]+date_to_tmp[1];
	
	var strUrl = location.search.split("=");
	var r_id = $("#rid").val();
	var b_id = strUrl[1];

	//console.log(b_id);

	$.ajax({
		url :"../booking_ajax.php",
		type : "POST",
		dataType : "text",
		data :{
				booking_id : b_id,
				check_booking_rid: r_id,
				date_from: date_from2,
				date_to: date_to2
			},	
		success : function(Message){
			console.log(Message);
			var obj = JSON.parse(Message);
			if(obj['status'] == "ERROR"){
				alert(obj['message']);
				$("#dp1").val("");
				$("#dp2").val("");
			}else{
				$('#error_message').hide();
			}
		},
		error : function(){
			alert("Error");
		}
	})
}
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
        