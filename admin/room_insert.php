<?php 
include('../include/functions.php');
session_start();
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
$menu="rooms";
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
		
		$status = isset($_POST['status'])?trim($_POST['status']):'';
		if(empty($status)){
			$status_error = "Enter Status";
			$error++;			
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
			$descriptioncn = sanitize($descriptioncn);
			$add_infocn = sanitize($add_infocn);
			$sq_feet = sanitize($sq_feet);
			$mamount= sanitize($mamount);
			$wamount= sanitize($wamount);
			$damount= sanitize($damount);
		    $status = sanitize($status);
			$daily_booking = sanitize($daily_booking);
			$weekly_booking = sanitize($weekly_booking);
			$monthly_booking = sanitize($monthly_booking);
			
			
			$SQL = "INSERT INTO rooms (room_name,description,descriptioncn,add_info,add_infocn,sq_feet,mamount,wamount,damount,status,daily_booking,weekly_booking,monthly_booking) VALUES ('$room_name','$description','$descriptioncn','$add_info','$add_infocn','$sq_feet','$mamount','$wamount','$damount','$status','$daily_booking','$weekly_booking','$monthly_booking')";
					
			$query = mysql_query($SQL);
			if($query){
			
				close();
				$_SESSION['room_insert']=TRUE;
				header("Location: room_list.php");
				
			}else{
				echo mysql_error();
			}			
		}
		
		
}
else
{
	$room_name="";
	$add_info="";
	$description="";
	$add_infocn="";
	$descriptioncn="";
	$sq_feet="";
	$mamount="";
	$damount="";
	$wamount="";
	$status="";
}


?>
 <?php include('include/head.php'); ?>
  <body>
        <!-- Fixed navbar -->
     <?php include('include/nav.php'); ?>

<div class="container">
    <div class="page-header">
        <h1>Rooms - Add New</h1>
      </div>
   <div class="bs-example">
      
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
    <textarea name="descriptioncn" class="form-control" id="exampleInputdescription" rows="3" placeholder="输入简介" value=""><?php echo $descriptioncn; ?></textarea><?php echo isset($description_error)?'<span class="error">'.$description_error.'</span>':''; ?>
  </div>

  <div class="form-group">
    <label for="exampleInputadd_info">Additional Information</label>
    <textarea name="add_info" class="form-control" id="exampleInputadd_info" rows="3" placeholder="Enter additional information" value=""><?php echo $add_info; ?></textarea><?php echo isset($add_info_error)?'<span class="error">'.$add_info_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputadd_info">Additional Information 中文</label>
    <textarea name="add_infocn" class="form-control" id="exampleInputadd_info" rows="3" placeholder="输入附加数据" value=""><?php echo $add_infocn; ?></textarea><?php echo isset($add_info_error)?'<span class="error">'.$add_info_error.'</span>':''; ?>
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
</form>
</div>

</div>
 </div>
    
    
<?php include('include/footer.php'); ?>