<?php
if (!class_exists('CalendarView')){
	//include('config.php');
	//include('module/View/View.php');

	include(__DIR__ .'/../../config.php');
	include(__DIR__ .'/../../module/View/View.php');

class CalendarView extends View{
	
	var $calendar_setting = array();
	//			$prev_book_type = 'none';
	//		$prev_color_type = 'light';
	//	$prev_book_id = null;

	function __construct(){
		
	}
	
		
	function organizeData($data){
		$date_list = & $data['date_list'];
		$room_list = & $data['room_list'];
		$booking_map = & $data['booking_map'];
		$date_start = &$data['date_start'];
		$date_end   = &$data['date_end'];
		
		$ds = DateTime::createFromFormat('Y-m-d', $date_start);
		$ds->setTime(0,0,1); // first minute of the date.
		$de = DateTime::createFromFormat('Y-m-d', $date_end);
		$de->setTime(23,59,59); // the last minue of the date
		$de_ts = $de->getTimestamp();
		
		$cur_d = DateTime::createFromFormat('Y-m-d', $date_start);
		
		$table_element = array(); // [row][column] = array('textNode'=>'', 'class'=>'', 'img_url')
		// Row 1
		$row1 = array();
		$row1_child_node = array();
		$row1_child_node[] = array(
				'elementType'=>'td',
				'class'=>'room-info',
				'textNode'=>'',
		);
		// Row 2
		$row2 = array();
		$row2_child_node = array();
		// First Cell for Month and Year
		$row2_child_node[] = array(
				'elementType'=>'td',
				'class'=>'room-info',
				'textNode'=> '', //$cur_d->format('M Y'),
		);
		
		while ($cur_d->getTimestamp()<=$de_ts){
			$day_of_week = $cur_d->format('D');
			$day_only = $cur_d->format('d');
			$date_key = $cur_d->format('Y-m-d');
			$date_class = $cur_d->format('m-d-Y');
			$row1_child_node[$date_key] = array(
				'elementType' => 'td',
				'textNode'=>$day_of_week,
				'class'   => 'calendar-table-head ' . strtolower($day_of_week) . ' ' . $date_class,
			);
			$row2_child_node[$date_key] = array(
				'elementType' => 'td',
				'textNode' => $day_only,
				'class' => 'calendar-table-head ' . 'day-' . $day_only  . ' ' . $date_class,

			);
			
			$cur_d->add(new DateInterval('P1D'));
		} // while
		$row1['childNode'] = $row1_child_node;
		$row2['childNode'] = $row2_child_node;
		$table_element [] = $row1;
		$table_element [] = $row2;
		
		
		$book_type_map = array(
				1=>'phone',
				2=>'online',
				3=>'vip',
				4=>'repair',
		);
		$booking_status=array(
				0=>'Disabled',
				1=>'Inactive',
				2=>'Active',
		);
		
		$class_to_file = array(
				'none-light-none-light'=>'../../module/CalendarView/images/jpg/none-none.jpg',
				'none-light-phone-light'=>'../../module/CalendarView/images/jpg/none-phone-light.jpg',
				'none-light-online-light'=>'../../module/CalendarView/images/jpg/none-online-light.jpg',
				'none-light-vip-light'=>'../../module/CalendarView/images/jpg/none-vip-light.jpg',
				'none-light-phone-dark'=>'../../module/CalendarView/images/jpg/none-phone-dark.jpg',
				'none-light-online-dark'=>'../../module/CalendarView/images/jpg/none-online-dark.jpg',
				'none-light-vip-dark'=>'../../module/CalendarView/images/jpg/none-vip-dark.jpg',
				'none-light-repair-light'=>'../../module/CalendarView/images/jpg/none-repair-light.jpg',
				'none-light-repair-dark'=>'../../module/CalendarView/images/jpg/none-repair-dark.jpg',

		
				'phone-light-none-light'=>'../../module/CalendarView/images/jpg/phone-light-none.jpg',
				'phone-light-phone-light'=>'../../module/CalendarView/images/jpg/phone-light-phone-light.jpg',
				'phone-light-online-light'=>'../../module/CalendarView/images/jpg/phone-light-online-light.jpg',
				'phone-light-vip-light'=>'../../module/CalendarView/images/jpg/phone-light-vip-light.jpg',
				'phone-light-phone-dark'=>'../../module/CalendarView/images/jpg/phone-light-phone-dark.jpg',
				'phone-light-online-dark'=>'../../module/CalendarView/images/jpg/phone-light-online-dark.jpg',
				'phone-light-vip-dark'=>'../../module/CalendarView/images/jpg/phone-light-vip-dark.jpg',
				'phone-light-repair-light'=>'../../module/CalendarView/images/jpg/phone-light-repair-light.jpg',
				'phone-light-repair-dark'=>'../../module/CalendarView/images/jpg/phone-light-repair-dark.jpg',
		
				'phone-dark-none-light'=>'../../module/CalendarView/images/jpg/phone-dark-none.jpg',
				'phone-dark-phone-light'=>'../../module/CalendarView/images/jpg/phone-dark-phone-light.jpg',
				'phone-dark-online-light'=>'../../module/CalendarView/images/jpg/phone-dark-online-light.jpg',
				'phone-dark-vip-light'=>'../../module/CalendarView/images/jpg/phone-dark-vip-light.jpg',
				'phone-dark-phone-dark'=>'../../module/CalendarView/images/jpg/phone-dark-phone-dark.jpg',
				'phone-dark-online-dark'=>'../../module/CalendarView/images/jpg/phone-dark-online-dark.jpg',
				'phone-dark-vip-dark'=>'../../module/CalendarView/images/jpg/phone-dark-vip-dark.jpg',
				'phone-dark-repair-light'=>'../../module/CalendarView/images/jpg/phone-dark-repair-light.jpg',
				'phone-dark-repair-dark'=>'../../module/CalendarView/images/jpg/phone-dark-repair-dark.jpg',

					
				'online-light-none-light'=>'../../module/CalendarView/images/jpg/online-light-none.jpg',
				'online-light-phone-light'=>'../../module/CalendarView/images/jpg/online-light-phone-light.jpg',
				'online-light-online-light'=>'../../module/CalendarView/images/jpg/online-light-online-light.jpg',
				'online-light-vip-light'=>'../../module/CalendarView/images/jpg/online-light-vip-light.jpg',
				'online-light-phone-dark'=>'../../module/CalendarView/images/jpg/online-light-phone-dark.jpg',
				'online-light-online-dark'=>'../../module/CalendarView/images/jpg/online-light-online-dark.jpg',
				'online-light-vip-dark'=>'../../module/CalendarView/images/jpg/online-light-vip-dark.jpg',
				'online-light-repair-light'=>'../../module/CalendarView/images/jpg/online-light-repair-light.jpg',
				'online-light-repair-dark'=>'../../module/CalendarView/images/jpg/online-light-repair-dark.jpg',
		
				'online-dark-none-light'=>'../../module/CalendarView/images/jpg/online-dark-none.jpg',
				'online-dark-phone-light'=>'../../module/CalendarView/images/jpg/online-dark-phone-light.jpg',
				'online-dark-online-light'=>'../../module/CalendarView/images/jpg/online-dark-online-light.jpg',
				'online-dark-vip-light'=>'../../module/CalendarView/images/jpg/online-dark-vip-light.jpg',
				'online-dark-phone-dark'=>'../../module/CalendarView/images/jpg/online-dark-phone-dark.jpg',
				'online-dark-online-dark'=>'../../module/CalendarView/images/jpg/online-dark-online-dark.jpg',
				'online-dark-vip-dark'=>'../../module/CalendarView/images/jpg/online-dark-vip-dark.jpg',
				'online-dark-repair-light'=>'../../module/CalendarView/images/jpg/online-dark-repair-light.jpg',
				'online-dark-repair-dark'=>'../../module/CalendarView/images/jpg/online-dark-repair-dark.jpg',
		
					
				'vip-light-none-light'=>'../../module/CalendarView/images/jpg/vip-light-none.jpg',
				'vip-light-phone-light'=>'../../module/CalendarView/images/jpg/vip-light-phone-light.jpg',
				'vip-light-online-light'=>'../../module/CalendarView/images/jpg/vip-light-online-light.jpg',
				'vip-light-vip-light'=>'../../module/CalendarView/images/jpg/vip-light-vip-light.jpg',
				'vip-light-phone-dark'=>'../../module/CalendarView/images/jpg/vip-light-phone-dark.jpg',
				'vip-light-online-dark'=>'../../module/CalendarView/images/jpg/vip-light-online-dark.jpg',
				'vip-light-vip-dark'=>'../../module/CalendarView/images/jpg/vip-light-vip-dark.jpg',
				'vip-light-repair-light'=>'../../module/CalendarView/images/jpg/vip-light-repair-light.jpg',
				'vip-light-repair-dark'=>'../../module/CalendarView/images/jpg/vip-light-repair-dark.jpg',
		
		
				'vip-dark-none-light'=>'../../module/CalendarView/images/jpg/vip-dark-none.jpg',
				'vip-dark-phone-light'=>'../../module/CalendarView/images/jpg/vip-dark-phone-light.jpg',
				'vip-dark-online-light'=>'../../module/CalendarView/images/jpg/vip-dark-online-light.jpg',
				'vip-dark-vip-light'=>'../../module/CalendarView/images/jpg/vip-dark-vip-light.jpg',
				'vip-dark-phone-dark'=>'../../module/CalendarView/images/jpg/vip-dark-phone-dark.jpg',
				'vip-dark-online-dark'=>'../../module/CalendarView/images/jpg/vip-dark-online-dark.jpg',
				'vip-dark-vip-dark'=>'../../module/CalendarView/images/jpg/vip-dark-vip-dark.jpg',
				'vip-dark-repair-light'=>'../../module/CalendarView/images/jpg/vip-light-repair-light.jpg',
				'vip-dark-repair-dark'=>'../../module/CalendarView/images/jpg/vip-light-repair-dark.jpg',
		
				
				'repair-light-none-light'=>'../../module/CalendarView/images/jpg/repair-light-none.jpg',
				'repair-light-phone-light'=>'../../module/CalendarView/images/jpg/repair-light-phone-light.jpg',
				'repair-light-online-light'=>'../../module/CalendarView/images/jpg/repair-light-online-light.jpg',
				'repair-light-vip-light'=>'../../module/CalendarView/images/jpg/repair-light-vip-light.jpg',
				'repair-light-phone-dark'=>'../../module/CalendarView/images/jpg/repair-light-phone-dark.jpg',
				'repair-light-online-dark'=>'../../module/CalendarView/images/jpg/repair-light-online-dark.jpg',
				'repair-light-vip-dark'=>'../../module/CalendarView/images/jpg/repair-light-vip-dark.jpg',
				'repair-light-repair-light'=>'../../module/CalendarView/images/jpg/repair-light-repair-light.jpg',
				'repair-light-repair-dark'=>'../../module/CalendarView/images/jpg/repair-light-repair-dark.jpg',
		
				'repair-dark-none-light'=>'../../module/CalendarView/images/jpg/repair-dark-none.jpg',
				'repair-dark-phone-light'=>'../../module/CalendarView/images/jpg/repair-dark-phone-light.jpg',
				'repair-dark-online-light'=>'../../module/CalendarView/images/jpg/repair-dark-online-light.jpg',
				'repair-dark-vip-light'=>'../../module/CalendarView/images/jpg/repair-dark-vip-light.jpg',
				'repair-dark-phone-dark'=>'../../module/CalendarView/images/jpg/repair-dark-phone-dark.jpg',
				'repair-dark-online-dark'=>'../../module/CalendarView/images/jpg/repair-dark-online-dark.jpg',
				'repair-dark-vip-dark'=>'../../module/CalendarView/images/jpg/repair-dark-vip-dark.jpg',
				'repair-dark-repair-light'=>'../../module/CalendarView/images/jpg/repair-dark-repair-light.jpg',
				'repair-dark-repair-dark'=>'../../module/CalendarView/images/jpg/repair-dark-repair-dark.jpg',

				);
		
		$room_color_setting = array(); // maintain the room color setting.
		
		foreach ($room_list as $room_id=>$cur_room) :
			$cur_row = array(); // a new row
			$row_child_node = array();
		
			$room_status_str = ($cur_room->status==1)?'inactive':'active';
			$row_child_node[] = array(
					'elementType'=>'td',
					'textNode'=>$cur_room->room_name,
					'class'  => "room-info room-status-" . $room_status_str ." room-{$room_id} ",
					'room_id'=> $room_id,
			);
			
			
			if (empty($room_color_setting[$room_id])){
				//initialized
				$room_color_setting[$room_id]['book_type'] = 'none';
				$room_color_setting[$room_id]['color_type'] = 'light';
				$room_color_setting[$room_id]['book_id'] = null;
			}
			$prev_book_type = & $room_color_setting[$room_id]['book_type'];
			$prev_color_type =  & $room_color_setting[$room_id]['color_type'];
			$prev_book_id =  & $room_color_setting[$room_id]['book_id'];
			
			$cur_d = DateTime::createFromFormat('Y-m-d', $date_start);
			$cur_d->sub(new DateInterval('P1D')); // go back for 1 more day (for color init)
			if (empty($booking_map[$room_id][$cur_d->format('Y-m-d')])){
				// no such date's data
				$cur_d->add(new DateInterval('P1D')); // go forward 1 day (original date).
			}
			
			while ($cur_d->getTimestamp()<=$de_ts){
				 
				$cur_date_idx = $cur_d->format('Y-m-d');
				$cur_book = &$booking_map[$room_id][$cur_date_idx];
			
				if (!($prev_book_type == 'none' &&
						is_null($cur_book)
				)){
						
					$cur_book_id = $cur_book->id;
					$cur_book_type = is_null($cur_book)?'none':$book_type_map[$cur_book->btype];
			
					if ($cur_book_id != $prev_book_id){
			
						$next_book_type = is_null($cur_book_type)?'none':$cur_book_type;
						$next_color_type = 'light';
						if ($prev_book_type == $next_book_type){
							// alternating the color
							$next_color_type = ($prev_color_type=='light')?'dark':'light';
						}
			
						$class_string = $prev_book_type .'-'. $prev_color_type . '-' .
								$next_book_type .'-'. $next_color_type;
			
						$prev_book_id = $cur_book_id;
						$prev_book_type = $cur_book_type;
						$prev_color_type = $next_color_type;
					}else{
						// same booking id
						$class_string = $prev_book_type . '-' . $prev_color_type. '-' .
								$prev_book_type . '-' . $prev_color_type ;
					}
				}else{
					$class_string ='';
				}
				$img_file = $class_to_file[$class_string];
				
				$date_book_status = (is_null($cur_book))? 'available':'booked';
				$cur_booking_status = strtolower($booking_status[$cur_book->status]);
				$date_class = $cur_d->format('m-d-Y');
				$row_child_node[$cur_date_idx] = array(
					'elementType'=>'td',
					'class'=>	"calendar-cell {$class_string} {$date_book_status} room-{$room_id} {$date_class} " ,
					'textNode' => (!is_null($cur_book))? "{$cur_book->id}":"",
					'img_src'  => $img_file,
					'room_id'  => $room_id,
					'booking_id' => $cur_book->id,
					'cur_date' => $cur_d->format('m-d-Y'),
					'title' => $cur_book->add_info,
					'booking_status'=> $cur_book->status,
				);
				$cur_d->add(new DateInterval('P1D'));
				
			} // while		
			$cur_row['childNode'] = $row_child_node;
			$table_element [] = $cur_row;
				
		endforeach;
		
		
		return $table_element;
	}
	
	function displayHtmlCalendarTable($data){
		$calendar_date_start = $data['date_start'];
		$calendar_date_end   = $data['date_end'];
		
		
		$table_element = $this->organizeData($data);
		
		$cur_month = DateTime::createFromFormat("Y-m-d", $calendar_date_start);
		while ($cur_month->format("Y-m-d")<$calendar_date_end){
			$data['date_start'] = $cur_month->format("Y-m") . "-01";
			$cur_month_end = DateTime::createFromFormat("Y-m-d", $data['date_start']);
			$cur_month_end->add(new DateInterval('P1M'));// 1 month
			$cur_month_end->sub(new DateInterval("P1D")); // - 1 day
		
			$data['date_end']   = $cur_month_end->format("Y-m-d") ;
			
			
			$ret_str = $this->renderTableCalendar($data, $table_element);
		
			echo "<div><h3>Calendar of " . $cur_month->format("M Y") ."</h3><br></div>";
			echo $ret_str;
			$cur_month->add(new DateInterval("P1M"));
		} // while
		
	}
	
	function renderTableCalendar($data, $table_element){
		
		// use the date from data and rendering settings from table_element
		$date_start = $data['date_start'];
		$date_end   = $data['date_end'];
		
		$ret_str = '';
		$ret_str .= "<table class='calendar-table'>";
			
		foreach ($table_element as $cur_row){
			$ret_str .= "<tr class='calendar-row'>";
			foreach ($cur_row['childNode'] as $date_key=>$cur_col){
				$textNode = $cur_col['textNode'];
				$img_src  = $cur_col['img_src'];
				$class    = $cur_col['class'];
				$room_id  = $cur_col['room_id'];
				$booking_id = $cur_col['booking_id'];
				$cur_date = $cur_col['cur_date'];
				$title    = $cur_col['title'];
				
				if (empty($date_key) || (($date_key>=$date_start && $date_key<=$date_end))){
					//$today=array();
				//	$today=explode(" ",$class);
					//if($today[0]=="calendar-table-head"&&$today[2]==date('m-d-Y')){
					//	$ret_str .= "<td style='background-color:#FFB3FF;' class='calendar-col {$class}' title='{$title}' cur_date='{$cur_date}' room_id='{$room_id}' booking_id='{$booking_id}'>" .					
					//	"{$textNode}" . 
					//	"</td>";
					//}else{
						$ret_str .= "<td class='calendar-col {$class}' title='{$title}' cur_date='{$cur_date}' room_id='{$room_id}' booking_id='{$booking_id}'>" .					
						"{$textNode}" . 
						"</td>";
					//}	
				}elseif ($date_key>$date_end){
					// break the loop, save some time
					break; //foreach $cur_col
				}
			}
			$ret_str .= "</tr>";
		}
				
		$ret_str .="</table>";
		
		return $ret_str;
	}// function renderTableCalender
	
	
	function outputPDF($data){
		/**
		 * Creates an example PDF TEST document using TCPDF
		* @package com.tecnick.tcpdf
		* @abstract TCPDF - Example: XHTML + CSS
		* @author Nicola Asuni
		* @since 2010-05-25
		*/
		
		// Include the main TCPDF library (search for installation path).
		require_once('../../vendor/tcpdf/tcpdf.php');
		
		// create new PDF document
		// TCPDF::__construct 	( 	  	
		// $orientation = 'P', or L
		// 	$unit = 'mm', 'pt': px.
		//  	$format = 'A4',
		//  	$unicode = true,
		//  	$encoding = 'UTF-8',
		//  	$diskcache = false,
		//  	$pdfa = false 
		//) 		
		

		
		$pdf = new TCPDF(
					'P', //PDF_PAGE_ORIENTATION, 
					'pt', // PDF_UNIT: mm 
					'A3', //PDF_PAGE_FORMAT, 
					true, 
					'UTF-8', 
					false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Nicola Asuni');
		$pdf->SetTitle('Monthly Calendar');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		
		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set some language-dependent strings (optional)
		//if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		//	require_once(dirname(__FILE__).'/lang/eng.php');
		//	$pdf->setLanguageArray($l);
		//}
		
		// ---------------------------------------------------------
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		$pdf->setFontSubsetting(false); // performance faster?
		
		// add a page
		$pdf->AddPage();
		
		/* NOTE:
		 * *********************************************************
		* You can load external XHTML using :
		*
		* $html = file_get_contents('/path/to/your/file.html');
		*
		* External CSS files will be automatically loaded.
		* Sometimes you need to fix the path of the external CSS.
		* *********************************************************
		*/
		
		// define some HTML content with style
		//$css_content = file_get_contents(__DIR__ . '/CalendarView.css');
		//$html = '<style>' . $css_content . '</style>';
		// $ret_str = $this->renderTableCalendar($data);
		
		//$table_element = $this->organizeData($data);
		
		$year_month = DateTime::createFromFormat("Y-m-d",$data['date_start'])->format("Y-M");
		//$html .= '<h3> Booking Calendar Of ' . $year_month . '</h3>';
		
		$calendar_date_start = $data['date_start'];
		$calendar_date_end   = $data['date_end'];
		
		
		$table_element = $this->organizeData($data);
		
		$cur_month = DateTime::createFromFormat("Y-m-d", $calendar_date_start);
		while ($cur_month->format("Y-m-d")<$calendar_date_end){
			$data['date_start'] = $cur_month->format("Y-m") . "-01";
			$cur_month_end = DateTime::createFromFormat("Y-m-d", $data['date_start']);
			$cur_month_end->add(new DateInterval('P1M'));// 1 month
			$cur_month_end->sub(new DateInterval("P1D")); // - 1 day
		
			$data['date_end']   = $cur_month_end->format("Y-m-d") ;
				
				
			$ret_str = $this->renderPDFTable($data, $table_element, $pdf);
		
			// $html= "<div><h3>Calendar of " . $cur_month->format("M Y") ."</h3><br></div>";			
			// $html.= $ret_str;
			
			//$pdf->writeHTML($html, true, false, true, false, 'C'); // less complicated, faster?
			
			$cur_month->add(new DateInterval("P1M"));
		} // while

		// output the HTML content

		
		// room code table
		$count = 0;
		$room_list = $data['room_list'];
		$pdf->Ln();
		foreach ($room_list as $room_id=>$room_obj){
			if ($room_obj->status==1){
				// inactive?
				$pdf->setFillColor(255,0,0);
			}else{
				$pdf->setFillColor(255,255,255);
			}
			$room_celltext = 'Room ' . $room_id . ': ' . $room_obj->room_name . '';
			//$pdf->Write(24,$room_celltext);
			$pdf->Cell(160,24, $room_celltext, 1, 0, '', true);
			$count++;
			if ($count % 5 ==0){
				$pdf->Ln();
			}
		}
		
		

		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				
		// reset pointer to the last page
		$pdf->lastPage();
		
		// ---------------------------------------------------------
		
		//Close and output PDF document
		$file_name = 'calendar.pdf';
		$pdf->Output($file_name, 'D'); // I: will send the file to the browser
		// D: force download
		
		//============================================================+
		// END OF FILE
		//============================================================+
	} //outputPdf
	
	function renderPDFTable($data, $table_element, &$pdf){
		// use the date from data and rendering settings from table_element

		// $pdf : the tcpdf object
		
		$date_start = $data['date_start'];
		$date_end   = $data['date_end'];
		
		// $ret_str = '';
		
		$ds = DateTime::createFromFormat('Y-m-d', $date_start); 
		
		$pdf->Ln();
		$pdf->WriteHTML('<h3>Calendar of ' . $ds->format('M Y') . '</h3>');
		$pdf->Ln();
		// $ret_str .= "<table class='calendar-table'>";

//		$room_code_table = array();
		
		$pdf->setFontSize(9); 
		
		foreach ($table_element as $cur_row){
			$ret_str .= "<tr class='calendar-row'>";
			foreach ($cur_row['childNode'] as $date_key=>$cur_col){
				$textNode = $cur_col['textNode'];
				$img_src  = $cur_col['img_src'];
				$class    = $cur_col['class'];
				$room_id  = $cur_col['room_id'];
				$booking_id = $cur_col['booking_id'];
				$cur_date = $cur_col['cur_date'];
				$title    = $cur_col['title'];
		
				if (empty($date_key) || (($date_key>=$date_start && $date_key<=$date_end))){

					$w = 0;
					if (strpos($class, 'room-info')!==false){
						// room-info
						$w = 50;
						if ($room_id){
							//$room_code_table[$room_id] = $textNode;
							//$textNode = 'Room ' . $room_id;
						}
					}else{
						$w = 24;
					}
					
					if (strpos($class, 'calendar-cell')!==false){
						$h = 24;
					}else{
						$h = 24;
					}
					$is_fill = false;
					if (strpos($class, 'room-status-inactive')!==false){
						$pdf->setFillColor(255,0,0);				
						$is_fill = true;		
					}else{
						//$pdf->setFillColor(255,255,255);
						$is_fill = false;
					}
					
					if (!empty($img_src)){
						// has image
						
						$file = file_get_contents($img_src);
						//$pdf->image('@' . $file); //, 'JPEG',null,'T',false,72,'C',false,false, 1);
						$pdf->image('@' . $file, '','', 23, 23); //, 'JPEG',null,'T',false,72,'C',false,false, 1);
						$pdf->Cell($w,$h,$textNode, 1, 0, 'L', $is_fill);
						//$pdf->MultiCell($w,$h,$textNode, 1,'L', 0,  $is_fill);
												
					}else{
						$pdf->MultiCell($w,$h,$textNode, 1, 'L', 0, $is_fill);
					}
					
					/*
					if (strpos($class, 'room-status-inactive')!==false){
						// inactive class
						$ret_str .= '<td bgcolor="red" border="1" class="' . $class . '">' . $textNode . "</td>";
						// w, h, txt, border,
						$pdf->Cell(50,0,$textNode, 1);
					}elseif (is_null($img_src)){
						$ret_str .= "<td border=\"1\"  class=\"{$class}\">{$textNode}</td>";
						$pdf->Cell(50,0,$textNode, 1);
					}else{
						$ret_str .= "<td border=\"1\"  class=\"{$class}\"><img border=\"1\" src=\"{$img_src}\"></td>";
						$pdf->Cell(32,32,'C', 1);
						// not to display the textNode {$textNode}

					}											
					*/
					
					// room-info: width: 50px
					// calendar-table-head: 32px
					// calendar-cell: 32px
					
				}elseif ($date_key>$date_end){
				// break the loop, save some time
						break; //foreach $cur_col
				}
			} // foreach $cur_col
			$pdf->Ln();
			//$ret_str .= "</tr>";
		} // foreach table_element
		//$pdf->Ln();
		//$ret_str .="</table>";
		
		
		//return $ret_str;
	}

} // class CalendarView

} // if not defined class