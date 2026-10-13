<?php 
include('../include/functions.php');
session_start();
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
if(isset($_SESSION['block_insert'])){
	$block_insert=true;
	unset($_SESSION['block_insert']);
}
$menu="block";
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
 <?php
	if(isset($block_insert)){
		?>
      <div class="big">
        <p class="bg-success">New block has successfully created.</p>
      </div>
      <?php } ?>
<?php 
 connect();
		$SQL = "SELECT * FROM block";
		$query = mysql_query($SQL);
		$nor=mysql_num_rows($query);
		if($query){
			?>
			 <table class="table table-hover">
             	<thead>
                    <th>Sl.no</th>
                	<th>Block Date</th>
                    <th>Reason</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody>
                 <?php 
			if($nor>0){
				$slno=0;
				while($row = mysql_fetch_array($query))
				  { 
				  	$ts = strtotime($row['block_date']);
					$ts = date("d-m-Y",$ts);
				  ?>
                      <tr>
                       <?php $snlo=$slno++; ?>
                      <td><?php echo $slno; ?> </td>
					 <td><?php echo $ts; ?></td>
					 <td><?php echo $row['reason']; ?></td>
					
                                       
                     <td><a href="block_edit.php?id=<?php echo $row['id'];?>"> <span class="glyphicon glyphicon-pencil"></span></a></td>
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


<?php include('include/footer.php'); ?>