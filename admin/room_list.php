<?php 
include('../include/functions.php');
session_start();
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
if(isset($_SESSION['room_insert'])){
	$room_insert=true;
	unset($_SESSION['room_insert']);
}
if(isset($_SESSION['room_delete'])){
	$room_delete=true;
	unset($_SESSION['room_delete']);
}

$menu="rooms";
?>
<?php include('include/head.php'); ?>
  <body>
    <!-- Fixed navbar -->
    <?php include('include/nav.php'); ?>
  <div class="container">

      <div class="page-header">
        <h1>Rooms</h1>
      </div>
   <div class="bs-example">
 <?php
	if(isset($room_insert)){
		?>
      <div class="big">
        <p class="bg-success">New room has successfully created.</p>
      </div>
      <?php } ?>
      <?php
	if(isset($room_delete)){
		?>
      <div class="big">
        <p class="bg-success">Room has successfully deleted.</p>
      </div>
      <?php } ?>
<?php 
 connect();
		$SQL = "SELECT * FROM rooms";
		$query = mysql_query($SQL);
		$nor=mysql_num_rows($query);
		if($query){
			?>
			 <table class="table table-hover">
             	<thead>
                    <th>Sl.no</th>
                	<th>Room Name</th>
                    <th class="span2">Description</th>
                    <th>Additional inforamtion</th>
                    <th>Square Feet </th>
                    <th>Monthly Amount</th>
                    <th>Weekly Amount</th>
                    <th>Daily Amount</th>
                    <th>Status </th>
                    <th>&nbsp;</th>
                </thead>
                <tbody>
                 <?php 
			if($nor>0){
				$slno=0;
				while($row = mysql_fetch_array($query))
				  { ?>
                   	<?php
					  	if($row['status']==2){?>
							 <tr>
                      <?php } else{ ?>
	                      <tr class="danger">
                      <?php
					  }
					  ?>
                       <?php $snlo=$slno++; ?>
                      <td><?php echo $slno; ?> </td>                     
					 <td><?php echo $row['room_name']; ?></td>
    				 <td><?php echo substr($row['description'],0,50); ?></td>
					 <td><?php echo substr($row['add_info'],0,50);?></td>
                     <td><?php echo $row['sq_feet'];?></td>   
                     <td><?php echo $row['mamount'];?></td> 
                     <td><?php echo $row['wamount'];?></td> 
                     <td><?php echo $row['damount'];?></td> 
                     <?php if($row['status']==1)
					           $status='Inactive';
							elseif($row['status']==2)
					           $status='Active'; 
							else
							  $status='Disabled';   ?>

                      <td><?php echo $status; ?></td>
                                       
                     <td><a href="room_edit.php?id=<?php echo $row['id'];?>"> <span class="glyphicon glyphicon-pencil"></span></a>
                     <a href="#<?php echo $row['id'];?>" id="<?php echo $row['id'];?>" class="delete"> <span class="glyphicon glyphicon-remove"></span></a></td>
                     </tr>
		   <?php  }
				    
			} 
			else
			 	echo "NO Record Found";
				?>
                </tbody>
                </table>
                  <?php
		}

else
echo "Login";
	
?>


</div>
 </div><!-- /.container -->

<form action="room_delete.php" name="delete_form" id="delete_form" method="post">
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
  </body>
</html>