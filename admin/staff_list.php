<?php include('../include/functions.php'); 
session_start();
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
if(isset($_SESSION['user_update'])){
	$profile_update=true;
	unset($_SESSION['user_update']);
}
if(isset($_SESSION['staff_delete'])){
	$staff_delete=true;
	unset($_SESSION['staff_delete']);
}

$menu="staff";
?>
  <?php include('include/head.php'); ?>
  <body>
    <!-- Fixed navbar -->
    <?php include('include/nav.php'); ?>
  <div class="container">

      <div class="page-header">
        <h1>Staff</h1>
      </div>
   <div class="bs-example">
   <?php
	if(isset($profile_update)){
		?>
      <div class="big">
        <p class="bg-success">Staff details successfully updated.</p>
      </div>
      <?php } ?>
      <?php
	if(isset($staff_delete)){
		?>
      <div class="big">
        <p class="bg-success">Staff details successfully deleted.</p>
      </div>
      <?php } ?>
<?php 
 connect();
		$SQL = "SELECT * FROM users WHERE utype>2 ORDER BY firstname ASC";
		$query = mysql_query($SQL);
		$nor=mysql_num_rows($query);
		if($query){
			?>
	 <table class="table table-hover">
     	<thead>
        <tr>
          <th>S.No</th>
          <th>User ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
       <!--   <th>phone</th>
          <th>Country Code</th>-->
          <th>User Type</th>
          <th>Status</th>
          <th>Created</th>
          <th>&nbsp;</th>
          </tr>
         </thead>
         <tbody>
                 <?php 
			if($nor>0){
				$slno=0;
				while($row = mysql_fetch_array($query))
				  {   $slno++;  				  	
				  ?>
                      <tr>
                     <td><?php echo $slno; ?></td>
                     <td><?php echo $row['id']; ?></td>
					 <td><?php echo $row['firstname']; ?></td>
					 <td><?php echo $row['lastname']; ?></td>
					 <td><?php echo $row['email']; ?></td>
					<!-- <td><?php // echo $row['phone'];?></td>
                     <td><?php // echo $row['country']?></td> -->
                     <?php
					         if($row['utype']==3)
					           $utype='Managers';
							else
							  $utype='Staff'; ?>
                     <td><?php echo $utype; ?></td>
                      <?php if($row['status']==1)
					           $status='Inactive';
							elseif($row['status']==2)
					           $status='Active'; 
							else
							  $status='Disabled';   ?>
                               
                               
                      <td><?php echo $status; ?></td>
                       <td><?php echo $row['created']?></td>
                     <td><a href="staff_edit.php?id=<?php echo $row['id'];?>"><span class="glyphicon glyphicon-pencil"></span></a>
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

<form action="staff_delete.php" name="delete_form" id="delete_form" method="post">
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