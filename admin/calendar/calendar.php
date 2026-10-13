<?php

include '../../include/functions.php';
session_start();
//ini_set('display_errors',"1");
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
}
if (isset($_SESSION['user_update'])) {
    $profile_update = true;
    unset($_SESSION['user_update']);
}
$menu = "calendar";
connect();
$target_date = false;
if (!empty($_REQUEST['target_date'])) {
    $target_date = DateTime::createFromFormat('M Y', $_REQUEST['target_date']);
}
if (!$target_date) {
    $target_date = new DateTime();
}
// $target_date: DateTime Object of the calendar date.

$calendar_head_string = $target_date->format('M Y');

// $model_date_start: the model data start date (Y-m-d)
$temp_date = DateTime::createFromFormat('Y-m-d', $target_date->format("Y-m") . '-01');
$temp_date->setTime(0, 0, 0);
$temp_date->sub(new DateInterval("P1M")); // - 1 month
$temp_date->sub(new DateInterval("P1D")); // - 1 date
$model_date_start = $temp_date->format("Y-m-d");

// $model_date_end: the model data end date (Y-m-d)
$temp_date = DateTime::createFromFormat('Y-m-d', $target_date->format("Y-m") . '-01');
$temp_date->setTime(23, 59, 59);
$temp_date->add(new DateInterval("P2M")); // + 2 month
//$temp_date->sub(new DateInterval("P1D")); // - 1 date
$model_date_end = $temp_date->format("Y-m-d");

include __DIR__ . '/../../module/CalendarModel/CalendarModel.php';

// Data grabbing
$cal_model = new CalendarModel();
$booking_map = $cal_model->get_booking_map($model_date_start, $model_date_end);
$room_list   = $cal_model->get_room_list();
$date_list   = $cal_model->get_date_list($model_date_start, $model_date_end);
/*$booking_map = $cal_model->getBookingMap($model_date_start, $model_date_end);
$room_list   = $cal_model->getRoomList();
$date_list   = $cal_model->getDateList($model_date_start, $model_date_end);*/

// break the dates into 3 different tables.

include __DIR__ . '/../../module/CalendarView/CalendarView.php';

$cal_view            = new CalendarView();
$data['booking_map'] = $booking_map;
$data['room_list']   = $room_list;
$data['date_list']   = $date_list;
// date start: starting date of the calendar display (Y-m-d)
$calendar_date_start = DateTime::createFromFormat('Y-m-d', $target_date->format("Y-m") . '-01');
$calendar_date_start->setTime(0, 0, 0);
$calendar_date_start->sub(new DateInterval('P1M')); // last month's 1st date
$calendar_date_end = DateTime::createFromFormat('Y-m-d', $target_date->format("Y-m") . '-01');
$calendar_date_end->setTime(23, 59, 59);
$calendar_date_end->add(new DateInterval('P2M')); // last month's 1st date
$calendar_date_end->sub(new DateInterval("P1D"));
$data['date_start'] = $calendar_date_start->format("Y-m-d");
$data['date_end']   = $calendar_date_end->format("Y-m-d");

if (strtolower($_REQUEST['output_format']) == 'pdf') {
	$cal_view->outputPDF($data);
} else {
    ?>
<html>
<head>

    <script src="../js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="../fbox/lib/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="../fbox/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="../fbox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

	<link rel="stylesheet" type="text/css" href="../../module/CalendarView/CalendarView.css">

	<!--  Javascript list -->
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>


	<script type="text/javascript" src='./js/calendar.js'></script>
</head>
<body style=''>
<div class='calendar-control'>
<span style='display:inline-block;'><h3>Booking Calendar</h3></span>
<span style='display:inline-block; float:right; margin-left:10px; margin-right:10px;'>
<label style='display:inline-block; float: left; margin-right:5px'>Select Month:</label>
<form method='GET' action='calendar.php' style='display:block;float:right;' target="_self" id='fm_postback'>
	<input type='text' value="<?php echo $target_date->format('M Y'); ?>" name='target_date' class='datepicker'/>
	<input type='submit' value='Update' name='output_html' id='output_html' />
	<input type='submit' value='PDF' name='output_format' id='output_format'  />
</form>
</span>
<div></div>
</div>
<a class="fancybox fancybox.iframe" href="" id="fburl" style="display:none">Iframe</a>

<div class='calendar-content'>

<?php
echo "-";
// Render 3 times for last month, this month and next month
    $cal_view->displayHtmlCalendarTable($data);
    ?>

</div>
</body>
</html>
<?php

}
; //if
?>