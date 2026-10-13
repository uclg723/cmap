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
<style type="text/css">
.row{
	border:1px solid #000;
	border-top:none;
	width:1216px;
}
.h20{
}
.col{
	width:35px;
	height:30px;
	float:left;
	border-right:1px solid #000;
	text-align:center;
	font-size:12px;
	font-family:Tahoma, Geneva, sans-serif;
	cursor:pointer;
}
.col2{
	width:90px;
	height:20px;
	float:left;
	border-right:1px solid #000;
	font-size:11px;
	font-family:Tahoma, Geneva, sans-serif;
	padding:5px;
}
.clear{
	float:none;
	clear:both;
}
.btop{
	border-top:1px solid #000;
}
.bnone{
	border:none;
}
.mon{
	font-weight:bold;
}
.bheight
{
	margin-bottom:30px;
}
.available{
	background:#fff;
}
.booked{
	background:#930;
}
.rinactive .col2{
	background:#F00;
}
/*.rinactive .booked{
	background:#F00;
}*/
.rinactive .available{
	background:#FFF;
}
.vip{
	background:#FFD800;
}
.staff{
	background:#81C4EB;
}
.online{
	background:#6F0;
}
.inactive{
	background:#F00;
}
.vip.inactive{
	background:#FFD800;
}
.available.online{
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
</style>
</head>
  <body>
  <div class="container-fluid">

      <div class="page-header">
        <h1 style="float:left">Booking Calendar</h1>
        <div id="right">
        	Select Month: <input type="text" id="datepicker"> <input type="button" id="go" value="Go"> <input type="button" id="print" value="Print">			
        </div>
        <div class="clear"></div>
      </div>
      
      <div id="month1" class="bheight">
       <div class="row btop">
       <span class="col2"></span>
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
				if($slno==31){
					echo '<span class="col bnone">'.$ts.'</span>';
				}else{
					echo '<span class="col">'.$ts.'</span>';
				}
			}
		?>
        <div class="clear"></div>
		</div>
        <div class="row">
       <span class="col2 mon"><?php echo $month; ?></span>
       	<?php
			$max = count($dates);
			$slno=0;
			foreach($dates as $date){
				$slno++;
				$ts = strtotime($date);
				$ts = date("d",$ts);
								
				if($slno==31){
					echo '<span class="col bnone">'.$ts.'</span>';
				}else{
					echo '<span class="col">'.$ts.'</span>';
				}
			}
		?>
        <div class="clear"></div>
		</div>
        <?php
		//room list
		$rSQL = "SELECT * FROM rooms";
		$rquery = mysql_query($rSQL);
		$rnor=mysql_num_rows($rquery);
		$rlist="";

        if($rnor>0){			
			while($room = mysql_fetch_array($rquery)){  
				
				if($room['status']==2){
					echo ' <div class="row">';
					echo '<span class="col2">'.$room['room_name'].'</span>';
				}else{
					echo ' <div class="row rinactive">';
					echo '<span class="col2">'.$room['room_name'].'</span>';
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
					if($slno==31){
						echo '<span class="col bnone '.$class.'">
						<input name="date[]" type="hidden" value="'.$hdate.'">
						<input type="hidden" name="room[]" value="'.$room['id'].'">';
						if(isset($book_data['id']) and $class!="available"){
							echo '<input type="hidden" name="book[]" value="'.$book_data['id'].'">';
						}
						echo '</span>';
					}else{
						echo '<span class="col '.$class.'"><input name="date[]" type="hidden" value="'.$hdate.'">
						<input type="hidden" name="room[]" value="'.$room['id'].'">';
						if(isset($book_data['id']) and $class!="available"){
							echo '<input type="hidden" name="book[]" value="'.$book_data['id'].'">';
						}
						echo '</span>';
					}
					
					$book_data= "";
				}
				echo '<div class="clear"></div>';
				echo '</div>';
			}
		}
	   ?>
       
      </div>
      
      <div id="month2" class="bheight">
       <div class="row btop">
       <span class="col2"></span>
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
				if($slno==31){
					echo '<span class="col bnone">'.$ts.'</span>';
				}else{
					echo '<span class="col">'.$ts.'</span>';
				}
			}
		?>
        <div class="clear"></div>
		</div>
        <div class="row">
       <span class="col2 mon"><?php echo $month; ?></span>
       	<?php
			$max = count($dates2);
			$slno=0;
			foreach($dates2 as $date){
				$slno++;
				$ts = strtotime($date);
				$ts = date("d",$ts);
								
				if($slno==31){
					echo '<span class="col bnone">'.$ts.'</span>';
				}else{
					echo '<span class="col">'.$ts.'</span>';
				}
			}
		?>
        <div class="clear"></div>
		</div>
        <?php
		//room list
		$rSQL = "SELECT * FROM rooms";
		$rquery = mysql_query($rSQL);
		$rnor=mysql_num_rows($rquery);
		$rlist="";

        if($rnor>0){			
			while($room = mysql_fetch_array($rquery)){  
				
				if($room['status']==2){
					echo ' <div class="row">';
					echo '<span class="col2">'.$room['room_name'].'</span>';
				}else{
					echo ' <div class="row rinactive">';
					echo '<span class="col2">'.$room['room_name'].'</span>';
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
					if($slno==31){
						echo '<span class="col bnone '.$class.'">
						<input name="date[]" type="hidden" value="'.$hdate.'">
						<input type="hidden" name="room[]" value="'.$room['id'].'">';
						if(isset($book_data['id']) and $class!="available"){
							echo '<input type="hidden" name="book[]" value="'.$book_data['id'].'">';
						}
						echo '</span>';
					}else{
						echo '<span class="col '.$class.'"><input name="date[]" type="hidden" value="'.$hdate.'">
						<input type="hidden" name="room[]" value="'.$room['id'].'">';
						if(isset($book_data['id']) and $class!="available"){
							echo '<input type="hidden" name="book[]" value="'.$book_data['id'].'">';
						}
						echo '</span>';
					}
					
					$book_data= "";
				}
				echo '<div class="clear"></div>';
				echo '</div>';
			}
		}
	   ?>
       
      </div>

      <div id="month3" class="bheight">
       <div class="row btop">
       <span class="col2"></span>
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
				if($slno==31){
					echo '<span class="col bnone">'.$ts.'</span>';
				}else{
					echo '<span class="col">'.$ts.'</span>';
				}
			}
		?>
        <div class="clear"></div>
		</div>
        <div class="row">
       <span class="col2 mon"><?php echo $month; ?></span>
       	<?php
			$max = count($dates3);
			$slno=0;
			foreach($dates3 as $date){
				$slno++;
				$ts = strtotime($date);
				$ts = date("d",$ts);
								
				if($slno==31){
					echo '<span class="col bnone">'.$ts.'</span>';
				}else{
					echo '<span class="col">'.$ts.'</span>';
				}
			}
		?>
        <div class="clear"></div>
		</div>
        <?php
		//room list
		$rSQL = "SELECT * FROM rooms";
		$rquery = mysql_query($rSQL);
		$rnor=mysql_num_rows($rquery);
		$rlist="";

        if($rnor>0){			
			while($room = mysql_fetch_array($rquery)){  
				
				if($room['status']==2){
					echo ' <div class="row">';
					echo '<span class="col2">'.$room['room_name'].'</span>';
				}else{
					echo ' <div class="row rinactive">';
					echo '<span class="col2">'.$room['room_name'].'</span>';
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
					if($slno==31){
						echo '<span class="col bnone '.$class.'">
						<input name="date[]" type="hidden" value="'.$hdate.'">
						<input type="hidden" name="room[]" value="'.$room['id'].'">';
						if(isset($book_data['id']) and $class!="available"){
							echo '<input type="hidden" name="book[]" value="'.$book_data['id'].'">';
						}
						echo '</span>';
					}else{
						echo '<span class="col '.$class.'"><input name="date[]" type="hidden" value="'.$hdate.'">
						<input type="hidden" name="room[]" value="'.$room['id'].'">';
						if(isset($book_data['id']) and $class!="available"){
							echo '<input type="hidden" name="book[]" value="'.$book_data['id'].'">';
						}
						echo '</span>';
					}
					
					$book_data= "";
				}
				echo '<div class="clear"></div>';
				echo '</div>';
			}
		}
	   ?>
       
      </div>
      
 </div><!-- /.container -->
<a class="fancybox fancybox.iframe" href="" id="fburl" style="display:none">Iframe</a> 
    <script src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="fbox/lib/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="fbox/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="fbox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

	<script type="text/javascript">
		$(document).ready(function() {
			$('.fancybox').fancybox();
			
			
			$( ".available" ).click(function() {
				var inputs = $(this).find("input");
				var d = $(this).children('input:eq(0)').val();				
				var r = $(this).children('input:eq(1)').val();
				var b = $(this).children('input:eq(2)').val();
				/*if(b){
					alert(b);
				}*/
				$("#fburl").attr("href","cbn.php?d="+d+"&r="+r);
				$("#fburl").trigger('click');
			});
			$( ".booked" ).click(function() {
				var inputs = $(this).find("input");				
				var b = $(this).children('input:eq(2)').val();
				if(b){					
					$("#fburl").attr("href","cbne.php?id="+b);
					$("#fburl").trigger('click');
				}
			});
			$( "#go" ).click(function() {
				datemonth = $.trim($("#datepicker").val());
				if(datemonth.length!=0){					
					var datemonth = datemonth.split(" ");
					var month = datemonth[0].toLowerCase();
					var year = datemonth[1];
					location.href='calendar.php?m='+month+'&y='+year;
				}
			});
			$( "#print" ).click(function() {
				
				datemonth = $.trim($("#datepicker").val());
				if(datemonth.length!=0){					
					var datemonth = datemonth.split(" ");
					var month = datemonth[0].toLowerCase();
					var year = datemonth[1];
					location.href='print.php?m='+month+'&y='+year;
				}
			});
			
			
		});
	</script>    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  $(function() {
    /*$( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true
    });*/
	$('#datepicker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
  });
  </script>
  </body>
</html>