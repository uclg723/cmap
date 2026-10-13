<?php 
include('../include/functions.php');
session_start();
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
if(isset($_SESSION['booking_new'])){
	$booking_new=true;
	unset($_SESSION['booking_new']);
}
if(isset($_SESSION['booking_delete'])){
	$booking_delete=true;
	unset($_SESSION['booking_delete']);
}
$menu="email";

function Pagination($data, $limit = null, $current = null, $adjacents = null)
{
    $result = array();

    if (isset($data, $limit) === true)
    {
        $result = range(1, ceil($data / $limit));

        if (isset($current, $adjacents) === true)
        {
            if (($adjacents = floor($adjacents / 2) * 2 + 1) >= 1)
            {
                $result = array_slice($result, max(0, min(count($result) - $adjacents, intval($current) - ceil($adjacents / 2))), $adjacents);
            }
        }
    }

    return $result;
}

//room list
connect();
$rSQL = "SELECT * FROM rooms";
$rquery = mysql_query($rSQL);
$rnor=mysql_num_rows($rquery);
$rlist="";
if($rquery){
	if($rnor>0){
		while($row = mysql_fetch_array($rquery)){
			if(isset($_GET['rid']) and $_GET['rid']==$row['id']){
				$rlist.='<option value="'.$row['id'].'" selected>'.$row['room_name'].'</option>';
			}else{
				$rlist.='<option value="'.$row['id'].'">'.$row['room_name'].'</option>';
			}
		}
	}
}


if(isset($_GET['rid'])){
	$rid = $_GET['rid']-1;
	$start = $start*10;
	$current_page = $_GET['page'];
}


?>
<?php include('include/head.php'); ?>
<body>
<!-- Fixed navbar -->
<?php include('include/nav.php'); ?>
<div class="container">
  <div class="page-header">
    <h1>Email</h1>
  </div>
  <div class="bs-example">
    <form class="form-inline" role="form">
      <div class="form-group">
        <label for="exampleInputrid">Room</label>
        <select name="rid" id="rid" class="form-control">
          <option value="0" selected>Select Room</option>
          <?php echo $rlist; ?>
        </select>
        <?php echo isset($rid_error)?'<span class="error">'.$rid_error.'</span>':''; ?> </div>
      <div class="form-group">
        <label for="exampleInputfdate">Day In</label>
        <input type="text" name="fdate" class="form-control" id="dp1" placeholder="Select date" value="<?php echo $_GET['fdate']; ?>">
      </div>
      <button type="submit" name="submit" class="btn btn-default" value="search">Search</button>
      <a href="booking_list.php" class="btn btn-default" role="button">Clear Search</a>
    </form>
    <br>
    <?php
	if(isset($booking_new)){
		?>
    <div class="big">
      <p class="bg-success">Booking has successfully created.</p>
    </div>
    <?php }  ?>
    <?php
	if(isset($booking_delete)){
		?>
    <div class="big">
      <p class="bg-success">Booking has successfully deleted.</p>
    </div>
    <?php }  ?>
    <?php 
      connect();
	  					
		
		$start=0;
		if(isset($_GET['page'])){
			$start = $_GET['page']-1;
			$start = $start*50;
			$current_page = $_GET['page'];
		}

		$fdate = "";
		$where = "WHERE 1=1";
		if(isset($_GET['fdate']) and !empty($_GET['fdate'])){
			$fdate = str_replace("-","/",$_GET['fdate']);
			$fdate = date("Y-m-d",strtotime($fdate));
			$where.=" and date_in='".$fdate."'";			
		}
		
		if(isset($_GET['rid']) and $_GET['rid']!=0){
			$rid = $_GET['rid'];			
			$where.=" and rid='".$rid."'";			
		}
		
		$SQL = "SELECT * FROM booking ".$where." ORDER BY id desc LIMIT $start,50";
		//echo $SQL;
		
		//$SQL = "SELECT * FROM booking ORDER BY id desc";
		$tSQL = "SELECT * FROM booking ".$where."";
		$query = mysql_query($tSQL);
		$total=mysql_num_rows($query);				
		
		$query = mysql_query($SQL);
		$nor=mysql_num_rows($query);
		if($query){
			?>
    <?php 
			if($nor>0){
				?>
    <table class="table table-hover">
      <thead>
      <th>Sl.no</th>
        <th>User ID</th>
        <th>Date In</th>
        <th>Date Out</th>
        <th>Additional inforamtion</th>
        <th>No. of days </th>
        <th>room ID </th>
        <th>Booking Type </th>
        <th>Status </th>
        <th>Created</th>
        <th>&nbsp;</th>
          </thead>
      <tbody>
        <?php
				$slno=$start;
				while($row = mysql_fetch_array($query))
				  { 
				  	$usql = "SELECT * FROM users WHERE id=".$row['uid'];
					$uquery = mysql_query($usql);
					if($uquery){
						$u = mysql_num_rows($uquery);
					}

				  	$rsql = "SELECT * FROM rooms WHERE id=".$row['rid'];
					$rquery = mysql_query($rsql);
					if($rquery){
						$r = mysql_num_rows($rquery);
					}
					//if($u==1 and $r==1){
					  if($r==1){
						if($u==1){  
							$user = mysql_fetch_assoc($uquery);
							$user_txt = '<a href="edit.php?id='.$user['id'].'">'.$user['firstname'].'</a>';
						}else{
							$user_txt = 'User Deleted';
						}
						$room = mysql_fetch_assoc($rquery);
						$date_in = strtotime($row['date_in']);
						$date_in = date("d-m-Y",$date_in);

						$date_out = strtotime($row['date_out']);
						$date_out = date("d-m-Y",$date_out);
				  ?>
        <tr>
          <?php $snlo=$slno++; ?>
          <td><?php echo $slno; ?></td>
          <td><?php echo $user_txt; ?></td>
          <td><?php echo $date_in; ?></td>
          <td><?php echo $date_out; ?></td>
          <td><?php echo $row['add_info'];?></td>
          <td><?php echo $row['days'];?></td>
          <td><a href="room_edit.php?id=<?php echo $room['id'];?>"><?php echo $room['room_name'];?></a></td>
          <?php if($row['btype']==1)
					           $btype='Phone';
							elseif($row['btype']==2)
					           $btype='Online'; 
							else
							  $btype='VIP';   ?>
          <td><?php echo $btype; ?></td>
          <?php if($row['status']==1)
					           $status='Inactive';
							elseif($row['status']==2)
					           $status='Active'; 
							else
							  $status='Disabled';   ?>
          <td><?php echo $status; ?></td>
          <td><?php echo $row['created'];?></td>
          <td><a href="booking_edit.php?id=<?php echo $row['id'];?>"> <span class="glyphicon glyphicon-pencil"></span></a> <a href="#<?php echo $row['id'];?>" id="<?php echo $row['id'];?>" class="delete"> <span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
        <?php  }
				  }
				    
			} 
			else
			 	echo "NO Record Found";
				?>
      </tbody>
    </table>
    <?php
					$per_page = 50;					
					$adjacent_links = 10;
					$pages = Pagination($total, $per_page, $current_page, $adjacent_links);
					if(count($pages)>0){
						echo ' <ul class="pagination">';
						foreach($pages as $page){
							if($page==$current_page){
								echo '<li class="active"><a href="booking_list.php?page='.$page.'">'.$page.'</a></li>';
							}else{
								echo '<li><a href="booking_list.php?page='.$page.'">'.$page.'</a></li>';
							}
						}
						echo '</ul>';
					}

				?>
    <?php
		}

else
echo "Login";
	
?>
  </div>
</div>
<!-- /.container -->

<form action="booking_delete.php" name="delete_form" id="delete_form" method="post">
  <input type="hidden" id="delete" name="delete" value="">
</form>

<!--<div id="footer">
      <div class="container">
        &#169; Copyrights 2014
      </div>
    </div>--> 
<br />
<br />
<br />
<br />
<br />

<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script>
$(document).ready(function($) {
	$( ".delete" ).click(function() {		
		if(confirm("Are You sure? Do you want to delete?")){
			$("#delete").val($(this).attr("id"));			
			$("#delete_form").submit();			
		}else{
			$("#delete").val("");			
		}
	});
});
</script>
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
</body>
</html>