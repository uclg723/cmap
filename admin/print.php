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
$menu="calendar";
connect();

function createDateRangeArray($strDateFrom,$strDateTo)
{
    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('d-m-Y',$iDateFrom));
        }
    }
    return $aryRange;
}
 if(isset($_GET['m']) and isset($_GET['y'])){
	  $arrange_date = "01-".$_GET['m']."-".$_GET['y'];	 
	  $arrange_date = strtotime($arrange_date);
	  $currfirst=date('Y-m-01',$arrange_date);
	  $currlast= date('Y-m-t',$arrange_date);
 }else{
	 $currfirst=date('Y-m-01');
	 $currlast= date('Y-m-t');
 }
 
 $nextmonth = strtotime(date("Y-m-d", strtotime($currfirst)) . " +1 month");
 
 $nextfirst=date("Y-m-d", $nextmonth);
 $nextlast=date("Y-m-t", $nextmonth);
 
 $secondmonth = strtotime(date("Y-m-d", strtotime($currfirst)) . " +2 month");
 $secondfirst=date("Y-m-d", $secondmonth);
 $secondlast=date("Y-m-t", $secondmonth);
 
 
$dates = createDateRangeArray( $currfirst,  $currlast );
$dates2 = createDateRangeArray( $nextfirst, $nextlast );
$dates3 = createDateRangeArray( $secondfirst, $secondlast );

$btype[1]="staff";
$btype[2]="online";
$btype[3]="vip";

$bstatus['1']="inactive";
$bstatus['2']="active";
?>
<html>
<head>
<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
@media screen,print{
body{
	font-size:8px;
}
table.gridtable {
	font-family: verdana, arial, sans-serif;
	color: #333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
	font-size:8px;
	margin-bottom:15px;
}
table.gridtable th {
	border-width: 1px;
	border-style: solid;
	border-color: #666666;
	font-weight:normal;
	width:36px;
	height:24px;
}
table.gridtable td {
	border-width: 1px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
	width:36px;
	height:24px;
}
table.gridtable th.first{
	width: 90px;
	height: 24px;
}
table.gridtable td.available{
	background:#fff;
}
table.gridtable td.booked{
	background:#930;
}
table.gridtable td.rinactive{
	background:#F00;
}
/*.rinactive .booked{
	background:#F00;
}*/
table.gridtable td.rinactive .available{
	background:#FFF;
}
table.gridtable td.vip{
	background:#FFD800;
}
table.gridtable td.staff{
	background:#81C4EB;
}
table.gridtable td.online{
	background:#6F0;
}
table.gridtable td.inactive{
	background:#F00;
}
table.gridtable td.vip.inactive{
	background:#FFD800;
}
table.gridtable td.available.online{
	background:#fff;
}
#right{
	float:right;
	padding:23px;
}
.ui-datepicker-calendar {
    display: none;
}
#ui-datepicker-div{
	font-size:12px;
}
#go{
	cursor:pointer;
}
}
</style>
</head>
<body>
<!-- Table goes in the document BODY -->
<table class="gridtable">
  <tr>
    <th class="first">&nbsp;</th>
    <?php
			$max = count($dates);
			$slno=0;
			foreach($dates as $date){
				$slno++;
				$ts = strtotime($date);
				$timestamp = $ts;
				$ts = date("D",$ts);
				if(!isset($month)){
					$month=date("Y M",$timestamp);
				}				
				echo '<th>'.$ts.'</th>';
			}
		?>
  </tr>
  <tr>
    <th><?php echo $month; ?></th>
    	<?php
			$max = count($dates);
			$slno=0;
			foreach($dates as $date){
				$slno++;
				$ts = strtotime($date);
				$ts = date("d",$ts);								
				echo '<th>'.$ts.'</th>';
			}
		?>
  </tr>
  <?php
		//room list
		$rSQL = "SELECT * FROM rooms";
		$rquery = mysql_query($rSQL);
		$rnor=mysql_num_rows($rquery);
		$rlist="";

        if($rnor>0){			
			while($room = mysql_fetch_array($rquery)){  
				
				if($room['status']==2){
					echo '<tr>';
					echo '<td>'.$room['room_name'].'</td>';
				}else{
					echo '<tr class="rinactive">';
					echo '<td class="rinactive">'.$room['room_name'].'</td>';
				}
				$max = count($dates);
				$slno=0;
				foreach($dates as $date){
					$slno++;
					$ts = strtotime($date);
					$check_from = date("Y-m-d",$ts);
					$hdate = date("m-d-Y",$ts);
					$ts = date("d",$ts);
					
					$book_data = "";
					$check_out = "";
					$check_in = "";
					$book_data_all = "";
					$book_data = "";
												
					$dsql = 'SELECT * FROM booking WHERE rid='.$room['id'].' and "'.$check_from.'" between date_in and date_out';
					$dquery = mysql_query($dsql);					
					$nor = mysql_num_rows($dquery);
					if($nor>0){
						if($nor==1){
							$class = "booked";
							$book_data = "";
							$book_data = mysql_fetch_assoc($dquery);
							if($book_data['date_out']==$check_from){
								$class = "available";								
							}else{								
								//$class = "booked ".$btype[$book_data['btype']]." ".$bstatus[$book_data['status']];
								$class = "booked ".$btype[$book_data['btype']]." ".$bstatus[$book_data['status']];
							}
						}else{
							$book_data = "";
							$check_out = "";
							$check_in = "";
							$book_data_all = "";
							$book_data = "";
							while($row = mysql_fetch_assoc($dquery)){
									$check_out[] = $row['date_out'];
									$check_in[] = $row['date_in'];
									$book_data[] = $row['btype'];
									$book_data_all[] = $row;
							}
							if (in_array($check_from, $check_out)) {
								$class = "available";
							}
							if (in_array($check_from, $check_in)) {
								
								$key = array_search($check_from,$check_in);								
								$class = "booked ".$btype[$book_data[$key]]." ";
								$book_data = $book_data_all[$key];
								if(empty($book_data)){
									die("dfsd");
								}
								
							}
						}												
					}
					else{
						$class = "available";
						$book_data = "";
					}
					echo '<td class="col '.$class.'"></td>';
					
					$book_data= "";
				}				
			}
		}
	   ?>     
</table>
<table class="gridtable">
  <tr>
    <th class="first">&nbsp;</th>
    <?php
			unset($month);
			$max = count($dates2);
			$slno=0;
			foreach($dates2 as $date){
				$slno++;
				$ts = strtotime($date);
				$timestamp = $ts;
				$ts = date("D",$ts);
				if(!isset($month)){
					$month=date("Y M",$timestamp);
				}				
				echo '<th>'.$ts.'</th>';
			}
		?>
  </tr>
  <tr>
    <th><?php echo $month; ?></th>
    	<?php
			$max = count($dates2);
			$slno=0;
			foreach($dates2 as $date){
				$slno++;
				$ts = strtotime($date);
				$ts = date("d",$ts);								
				echo '<th>'.$ts.'</th>';
			}
		?>
  </tr>
  <?php
		//room list
		$rSQL = "SELECT * FROM rooms";
		$rquery = mysql_query($rSQL);
		$rnor=mysql_num_rows($rquery);
		$rlist="";

        if($rnor>0){			
			while($room = mysql_fetch_array($rquery)){  
				
				if($room['status']==2){
					echo '<tr>';
					echo '<td>'.$room['room_name'].'</td>';
				}else{
					echo '<tr class="rinactive">';
					echo '<td class="rinactive">'.$room['room_name'].'</td>';
				}
				$max = count($dates2);
				$slno=0;
				foreach($dates2 as $date){
					$slno++;
					$ts = strtotime($date);
					$check_from = date("Y-m-d",$ts);
					$hdate = date("m-d-Y",$ts);
					$ts = date("d",$ts);
					
					$book_data = "";
					$check_out = "";
					$check_in = "";
					$book_data_all = "";
					$book_data = "";
												
					$dsql = 'SELECT * FROM booking WHERE rid='.$room['id'].' and "'.$check_from.'" between date_in and date_out';
					$dquery = mysql_query($dsql);					
					$nor = mysql_num_rows($dquery);
					if($nor>0){
						if($nor==1){
							$class = "booked";
							$book_data = "";
							$book_data = mysql_fetch_assoc($dquery);
							if($book_data['date_out']==$check_from){
								$class = "available";								
							}else{								
								//$class = "booked ".$btype[$book_data['btype']]." ".$bstatus[$book_data['status']];
								$class = "booked ".$btype[$book_data['btype']]." ".$bstatus[$book_data['status']];
							}
						}else{
							$book_data = "";
							$check_out = "";
							$check_in = "";
							$book_data_all = "";
							$book_data = "";
							while($row = mysql_fetch_assoc($dquery)){
									$check_out[] = $row['date_out'];
									$check_in[] = $row['date_in'];
									$book_data[] = $row['btype'];
									$book_data_all[] = $row;
							}
							if (in_array($check_from, $check_out)) {
								$class = "available";
							}
							if (in_array($check_from, $check_in)) {
								
								$key = array_search($check_from,$check_in);								
								$class = "booked ".$btype[$book_data[$key]]." ";
								$book_data = $book_data_all[$key];
								if(empty($book_data)){
									die("dfsd");
								}
								
							}
						}												
					}
					else{
						$class = "available";
						$book_data = "";
					}
					echo '<td class="col '.$class.'"></td>';
					
					$book_data= "";
				}				
			}
		}
	   ?>     
</table>
<table class="gridtable">
  <tr>
    <th class="first">&nbsp;</th>
    <?php
			unset($month);
			$max = count($dates3);
			$slno=0;
			foreach($dates3 as $date){
				$slno++;
				$ts = strtotime($date);
				$timestamp = $ts;
				$ts = date("D",$ts);
				if(!isset($month)){
					$month=date("Y M",$timestamp);
				}				
				echo '<th>'.$ts.'</th>';
			}
		?>
  </tr>
  <tr>
    <th><?php echo $month; ?></th>
    	<?php
			$max = count($dates3);
			$slno=0;
			foreach($dates3 as $date){
				$slno++;
				$ts = strtotime($date);
				$ts = date("d",$ts);								
				echo '<th>'.$ts.'</th>';
			}
		?>
  </tr>
  <?php
		//room list
		$rSQL = "SELECT * FROM rooms";
		$rquery = mysql_query($rSQL);
		$rnor=mysql_num_rows($rquery);
		$rlist="";

        if($rnor>0){			
			while($room = mysql_fetch_array($rquery)){  
				
				if($room['status']==2){
					echo '<tr>';
					echo '<td>'.$room['room_name'].'</td>';
				}else{
					echo '<tr class="rinactive">';
					echo '<td class="rinactive">'.$room['room_name'].'</td>';
				}
				$max = count($dates3);
				$slno=0;
				foreach($dates3 as $date){
					$slno++;
					$ts = strtotime($date);
					$check_from = date("Y-m-d",$ts);
					$hdate = date("m-d-Y",$ts);
					$ts = date("d",$ts);
					
					$book_data = "";
					$check_out = "";
					$check_in = "";
					$book_data_all = "";
					$book_data = "";
												
					$dsql = 'SELECT * FROM booking WHERE rid='.$room['id'].' and "'.$check_from.'" between date_in and date_out';
					$dquery = mysql_query($dsql);					
					$nor = mysql_num_rows($dquery);
					if($nor>0){
						if($nor==1){
							$class = "booked";
							$book_data = "";
							$book_data = mysql_fetch_assoc($dquery);
							if($book_data['date_out']==$check_from){
								$class = "available";								
							}else{								
								//$class = "booked ".$btype[$book_data['btype']]." ".$bstatus[$book_data['status']];
								$class = "booked ".$btype[$book_data['btype']]." ".$bstatus[$book_data['status']];
							}
						}else{
							$book_data = "";
							$check_out = "";
							$check_in = "";
							$book_data_all = "";
							$book_data = "";
							while($row = mysql_fetch_assoc($dquery)){
									$check_out[] = $row['date_out'];
									$check_in[] = $row['date_in'];
									$book_data[] = $row['btype'];
									$book_data_all[] = $row;
							}
							if (in_array($check_from, $check_out)) {
								$class = "available";
							}
							if (in_array($check_from, $check_in)) {
								
								$key = array_search($check_from,$check_in);								
								$class = "booked ".$btype[$book_data[$key]]." ";
								$book_data = $book_data_all[$key];
								if(empty($book_data)){
									die("dfsd");
								}
								
							}
						}												
					}
					else{
						$class = "available";
						$book_data = "";
					}
					echo '<td class="col '.$class.'"></td>';
					
					$book_data= "";
				}
			}
		}
	   ?>     
</table>
</body>
</html>
<!-- /.container -->
</body>
</html>