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
if(isset($_SESSION['user_delete'])){
	$user_delete=true;
	unset($_SESSION['user_delete']);
}

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

$menu="users";
?>
  <?php include('include/head.php'); ?>
  <body>
    <!-- Fixed navbar -->
    <?php include('include/nav.php'); ?>
  <div class="container">

      <div class="page-header">
        <h1>Users</h1>
      </div>
   <div class="bs-example">
   <?php
	if(isset($profile_update)){
		?>
      <div class="big">
        <p class="bg-success">User details successfully updated.</p>
      </div>
      <?php } ?>
      <?php
	if(isset($user_delete)){
		?>
      <div class="big">
        <p class="bg-success">User details successfully deleted.</p>
      </div>
      <?php } ?>
<?php 
		$start=0;
		if(isset($_GET['page'])){
			$start = $_GET['page']-1;
			$start = $start*10;
			$current_page = $_GET['page'];
		}
		
		connect();
		// OLD SQL
		// $SQL = "SELECT * FROM users WHERE utype=1";
		$SQL = "SELECT * FROM users";
		$query = mysql_query($SQL);
		$total=mysql_num_rows($query);
		
		$SQL = "SELECT * FROM users ORDER BY firstname ASC LIMIT $start,10";
		// OLD SQL
		//$SQL = "SELECT * FROM users WHERE utype=1 ORDER BY firstname ASC LIMIT $start,10";
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
          <th>phone</th>
          <th>Country Code</th>
          <th>Status</th>
          <th>Created</th>
          <th>&nbsp;</th>
          </tr>
         </thead>
         <tbody>
                 <?php 
			if($nor>0){
				$slno=$start;
				while($row = mysql_fetch_array($query))
				  {   $slno++;  				  	
				  ?>
                      <tr>
                     <td><?php echo $slno; ?></td>
                     <td><?php echo $row['id']; ?></td>
					 <td><?php echo $row['firstname']; ?></td>
					 <td><?php echo $row['lastname']; ?></td>
					 <td><?php echo $row['email']; ?></td>
					 <td><?php echo $row['phone'];?></td>
                     <td><?php echo $row['country']?></td>
                      <?php if($row['status']==1)
					           $status='Inactive';
							elseif($row['status']==2)
					           $status='Active'; 
							else
							  $status='Disabled';   ?>
                               
                               
                      <td><?php echo $status; ?></td>
                       <td><?php echo $row['created']?></td>
                     <td><a href="edit.php?id=<?php echo $row['id'];?>"><span class="glyphicon glyphicon-pencil"></span></a><a href="#<?php echo $row['id'];?>" id="<?php echo $row['id'];?>" class="delete"> <span class="glyphicon glyphicon-remove"></span></a></td>
                     
                     </tr>
		   <?php  }
				    
			} // if $nor>0
			else{
			 	echo "<tr><td>NO Record Found</td></tr>";
			}
				?>
                </tbody>
                </table>
                <?php
					$per_page = 10;					
					$adjacent_links = 10;
					$pages = Pagination($total, $per_page, $current_page, $adjacent_links);
					if(count($pages)>0){
						echo ' <ul class="pagination">';
						foreach($pages as $page){
							if($page==$current_page){
								echo '<li class="active"><a href="users_list.php?page='.$page.'">'.$page.'</a></li>';
							}else{
								echo '<li><a href="users_list.php?page='.$page.'">'.$page.'</a></li>';
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
 </div><!-- /.container -->

<form action="user_delete.php" name="delete_form" id="delete_form" method="post">
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