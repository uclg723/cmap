<?php 
include('../include/functions.php');
session_start();
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
if(isset($_SESSION['room_update'])){
	$room_update=true;
	unset($_SESSION['room_update']);
}
$menu="rooms";
if(isset($_GET['id'])){
		connect();
		$id = sanitize($_GET['id']);
		$SQL = "SELECT * FROM rooms WHERE id = $id";
		$query = mysql_query($SQL);
		if($query){
			$nor = mysql_num_rows($query);
			if($nor>0){
					$row = mysql_fetch_assoc($query);
					 $room_name=$row['room_name']; 
					 $description= $row['description']; 
					 $descriptioncn= $row['descriptioncn']; 
					 $add_info=$row['add_info'];
					 $add_infocn=$row['add_infocn'];
                     $sq_feet=$row['sq_feet'];  
                     $mamount=$row['mamount'];
					 $wamount=$row['wamount'];
					 $damount=$row['damount'];
                     $status=$row['status']; 
					 $daily_booking = $row['daily_booking'];
					 $weekly_booking = $row['weekly_booking'];
					 $monthly_booking = $row['monthly_booking'];
		   
			}else{
				header("Location: room_list.php");
			}
			
		}else{
			die("Error: ".mysql_error());
		}
		close();
	}

if(isset($_POST['delimg'])){
	$file = $_POST['delimg'];
	if(file_exists($file)){
		unlink($file);
		$file = str_replace("/thumb","",$file);
		unlink($file);
		header("Location: room_edit.php?id=".$id);
	}
	else{
		header("Location: room_edit.php?id=".$id);
	}
}
if(isset($_POST['submit']))
{
		$error = 0;
		$room_name = trim($_POST['room_name']);
		if(empty($room_name)){
			$room_name_error = "Enter your Room name";
			$error++;			
		}else if(strlen($room_name)>15){
			$room_name_error = "Max len should be 15";
			$error++;
		}
		  
		$description = trim($_POST['description']);
		$descriptioncn = trim($_POST['descriptioncn']);
	    $add_info = trim($_POST['add_info']);
		$add_infocn = trim($_POST['add_infocn']);
        $sq_feet = trim($_POST['sq_feet']);
		if(empty($sq_feet)){
			$sq_feet_error = "Enter Square Feet of Room";
			$error++;			
		}else if(preg_match("/^[0-9]+$/", $sq_feet) === 0){
			$sq_feet_error = "Invalid Room Square Feet";
			$error++;
		}
		
				
		$mamount = trim($_POST['mamount']);		
		if(empty($mamount)){
			$mamount_error = "Enter Monthly HKD Amount";
			$error++;			
		}else if(preg_match("/^[+-]?(([0-9]+)|([0-9]*\.[0-9]+|[0-9]+\.[0-9]*)|
(([0-9]+|([0-9]*\.[0-9]+|[0-9]+\.[0-9]*))[eE][+-]?[0-9]+))$/", $mamount) === 0){
			$mamount_error = "Invalid HKD Amount";
			$error++;
		}

		$wamount = trim($_POST['wamount']);
		if(empty($wamount)){
			$wamount_error = "Enter Weekly HKD Amount";
			$error++;			
		}else if(preg_match("/^[+-]?(([0-9]+)|([0-9]*\.[0-9]+|[0-9]+\.[0-9]*)|
(([0-9]+|([0-9]*\.[0-9]+|[0-9]+\.[0-9]*))[eE][+-]?[0-9]+))$/", $wamount) === 0){
			$wamount_error = "Invalid HKD Amount";
			$error++;
		}

		$damount = trim($_POST['damount']);
		if(empty($damount)){
			$damount_error = "Enter Daily HKD Amount";
			$error++;			
		}else if(preg_match("/^[+-]?(([0-9]+)|([0-9]*\.[0-9]+|[0-9]+\.[0-9]*)|
(([0-9]+|([0-9]*\.[0-9]+|[0-9]+\.[0-9]*))[eE][+-]?[0-9]+))$/", $damount) === 0){
			$damount_error = "Invalid HKD Amount";
			$error++;
		}
				
		if(!isset($_POST['status'])){
			$status_error = "Select status";
			$error++;			
		}else{			
			$status = $_POST['status'];
		}
		
		if(isset($_POST['daily_booking'])){
			$daily_booking = $_POST['daily_booking'];
		}else{
			$daily_booking = 2;
		}
		
		if(isset($_POST['weekly_booking'])){
			$weekly_booking = $_POST['weekly_booking'];
		}else{
			$weekly_booking = 2;
		}
		
		if(isset($_POST['monthly_booking'])){
			$monthly_booking = $_POST['monthly_booking'];
		}else{
			$monthly_booking = 2;
		}
		
		if($error==0){
			connect();
			$room_name = sanitize($room_name);
			$description = sanitize($description);
			$add_info = sanitize($add_info);
			$sq_feet = sanitize($sq_feet);
			$mamount= sanitize($mamount);
			$wamount= sanitize($wamount);
			$damount= sanitize($damount);
		    $status = sanitize($status);
			$daily_booking = sanitize($daily_booking);
			$weekly_booking = sanitize($weekly_booking);
			$monthly_booking = sanitize($monthly_booking);
		
			$id = $_POST['id'];
			$SQL = "UPDATE rooms set room_name='$room_name',description='$description',descriptioncn='$descriptioncn',add_info='$add_info',add_infocn='$add_infocn',sq_feet='$sq_feet', mamount='$mamount',wamount='$wamount',damount='$damount',status='$status',daily_booking='$daily_booking',weekly_booking='$weekly_booking',monthly_booking='$monthly_booking' WHERE id='$id'";
			
			$query = mysql_query($SQL);
			if($query){
				close();
				$_SESSION['room_update']=TRUE;
				header("Location: room_edit.php?id=$id");
				
			}else{
				echo mysql_error();
			}			
		}
		
		
}


?>

<?php include('include/head.php'); ?>
  <body>

<link rel="stylesheet" href="http://www.photoevent.com/dev/assets/css/font-awesome.min.css" type="text/css" />

   <!-- Fixed navbar -->
    <?php include('include/nav.php'); ?>



  <div class="container">
    <div class="page-header">
        <h1>Rooms</h1>
      </div>
   <div class="bs-example">
   <?php
	if(isset($room_update)){
		?>
      <div class="big">
        <p class="bg-success">Room details has successfully updated.</p>
      </div>
      <?php } ?>
       <div class="col-lg-6">
      
      <form method="post" role="form">
  <div class="form-group">
    <label for="exampleInputroom_name">Rooom Name</label>
    <input type="text" name="room_name" class="form-control" id="exampleInputroom_name" placeholder="Enter room name" value="<?php echo $room_name; ?>"><?php echo isset($room_name_error)?'<span class="error">'.$room_name_error.'</span>':''; ?>
  </div>
  
 
  
  <div class="form-group">
    <label for="exampleInputdescription">Description</label>
    <textarea name="description" class="form-control" id="exampleInputdescription" rows="3" placeholder="Enter description" value=""><?php echo $description; ?></textarea><?php echo isset($description_error)?'<span class="error">'.$description_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputdescription">Description 中文</label>
    <textarea name="descriptioncn" class="form-control" id="exampleInputdescriptioncn" rows="3" placeholder="Enter description" value=""><?php echo $descriptioncn; ?></textarea><?php echo isset($description_error)?'<span class="error">'.$description_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputadd_info">Additional Information</label>
    <textarea name="add_info" class="form-control" id="exampleInputadd_info" rows="3" placeholder="Enter additional information" value=""><?php echo $add_info; ?></textarea><?php echo isset($add_info_error)?'<span class="error">'.$add_info_error.'</span>':''; ?>
  </div>

  <div class="form-group">
    <label for="exampleInputadd_info">Additional Information中文</label>
    <textarea name="add_infocn" class="form-control" id="exampleInputadd_infocn" rows="3" placeholder="Enter additional information" value=""><?php echo $add_infocn; ?></textarea><?php echo isset($add_info_error)?'<span class="error">'.$add_info_error.'</span>':''; ?>
  </div>

  <div class="form-group">
    <label for="exampleInputsq_feet">Square Feet</label>
    <input type="text" name="sq_feet" class="form-control" id="exampleInputsq_feet" placeholder="Enter square feet" value="<?php echo $sq_feet; ?>"><?php echo isset($sq_feet_error)?'<span class="error">'.$sq_feet_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="">Monthly Amount</label>
    <input type="text" name="mamount" class="form-control" id="" placeholder="Enter Monthly amount" value="<?php echo $mamount; ?>"><?php echo isset($mamount_error)?'<span class="error">'.$mamount_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="">Weekly Amount</label>
    <input type="text" name="wamount" class="form-control" id="" placeholder="Enter Weekly amount" value="<?php echo $wamount; ?>"><?php echo isset($wamount_error)?'<span class="error">'.$wamount_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="">Daily Amount</label>
    <input type="text" name="damount" class="form-control" id="" placeholder="Enter Daily amount" value="<?php echo $damount; ?>"><?php echo isset($damount_error)?'<span class="error">'.$damount_error.'</span>':''; ?>
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
  
  <div class="form-group">
    <label for="exampleInputstatus">Booking Allowed</label>
         
    <div class="checkbox">
      <label>
        <input type="checkbox" value="1" name="daily_booking" <?php echo (isset($daily_booking) && ($daily_booking==1))?'checked':"";?>>
        Daily Booking
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" value="1" name="weekly_booking" <?php echo (isset($weekly_booking) && ($weekly_booking==1))?'checked':"";?>>
        Weekly Booking
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" value="1" name="monthly_booking" <?php echo (isset($monthly_booking) && ($monthly_booking==1))?'checked':"";?>>
        Monthly Booking
      </label>
    </div>
  </div>

  <button type="submit" name="submit" class="btn btn-default">Submit</button>
  <input type="hidden" value="<?php echo $id; ?>" name="id" />
</form>
</div>

<div class="col-lg-6">

<?php
$a = array();
$dir = 'uploads/'.$id;
$hide="";
if(is_dir($dir)){	
	if ($handle = opendir($dir)) {
	  while (false !== ($file = readdir($handle))) {
		if (preg_match("/\.png$/", $file)) $a[] = $file;
		elseif (preg_match("/\.jpg$/", $file)) $a[] = $file;
		elseif (preg_match("/\.jpeg$/", $file)) $a[] = $file;
	  }
	  closedir($handle);
	}
	if(count($a)>0){
		?>
        <h4>Room Images</h4>
        <div id="imglist">

		<style type="text/css">
		#imglist{
			margin-top:25px;
		}
        .imglist img{
            width:100px;
            margin-right:5px;
            margin-bottom:5px;
            max-height:150px;            
        }
		.imgholder {
		  margin-bottom: 5px;
		  margin-right: 5px;
		  display: inline-block;
		}
		.imglist {
			max-height: 160px;
			vertical-align: bottom;
			display: table-cell;
		}
		.delete-img {
text-align: right;
position: absolute;
}
.delete_image {
cursor: pointer;
margin-top: -10px;
color: #F00;
margin-right: -5px;
}
.fa-stack{
	font-size:14px;
	}
        </style>
<?php
		$hide="hide";	
		$slno=0;
		foreach ($a as $i) {
			$slno++;
		echo '<div class="imgholder"><div class="imglist"><div class="delete-img" style="width:100px"><div title="Delete Image" class="delete_image" id="'.$slno.'"><span class="fa-stack fa-lg">[X]<i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-times fa-stack-1x fa-inverse"></i></span></div></div>';
		  echo "<a href='" . $dir . '/' . $i . "' target=\"new\"><img src='" . $dir . '/thumb/' . $i . "' id='".$slno."img' /></a>";
		  echo'</div></div>';
		}
		echo '<div class="clear"></div></div>';
	}
}
?>
<hr>
<h4 class="">Upload Images</h4>
<form action="upload.php?id=<?php echo $id; ?>" class="dropzone"></form>
</div>


</div>
 </div><!-- /.container -->
 <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

<link href="dropzone/css/dropzone.css" type="text/css" rel="stylesheet" /> 
<script src="dropzone/dropzone.js"></script>
<script type="text/javascript">
function redirect(){
	window.location.href="room_edit.php?id=<?php echo $id; ?>";
}
$(document).ready(function() {
//$(".delete_image").show(); // show them first.
$(".delete_image").click(function() {
	  if(confirm("Are you sure? Do you want to delete?")){
		  var img = $(this).attr('id');
		  var img = $("#"+img+"img").attr('src');
		  $('input[name="delimg"]').val(img);
		  $("#del_img_form").submit();
	  }else{
		  $('input[name="delete_image"]').val(0);
	  }
	});
	
});	

</script>
<form action="" id="del_img_form" method="post">
<input type="hidden" id="delimg" name="delimg" value="0">
</form>

  </body>
</html>
        