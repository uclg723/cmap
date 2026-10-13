function convert_hkd(){	
	var v1 = document.getElementById('check-in').value;
	var v2 = document.getElementById('check-out').value;
	$("#bf_from").val(v1);
	$("#bf_to").val(v2);
	
	if(v1!="" && v2!=""){
		var from_date = $("#check-in").datepicker('getDate'),
		 to_date   = $("#check-out").datepicker('getDate');
		if(to_date.getTime()<=from_date.getTime()){
			alert("Booking dates not valid");
		}else{
			var parts1 = v1.split('/'); 
			var parts2 = v2.split('/');
			var date1 = new Date(parts1[2],(parts1[0]-1),parts1[1]);
			var date2 = new Date(parts2[2],(parts2[0]-1),parts2[1]);
			var diffDays = Math.round(Math.abs((date1.getTime() - date2.getTime())/(24*3600*1000)));
			$("#non").html(diffDays);				
			cal_amt(diffDays);
		}
	}else{	
		 var cc = $("#change_currency").val();
		 if(cc==1){
		 	$("#sub-amt").html("$0 HKD");
		 }else{
			 $("#sub-amt").html("$0 USD");
		 }
	}
} // function 

function cal_amt(totalDays){
	$("#bfnon").val(totalDays);
	totalDays = parseInt(totalDays);
	months = 0;
	weeks = 0;
	days = 0;
	if(totalDays>=30){
		months = parseInt(totalDays/30);
		modulo = parseInt(totalDays%30);
		if(modulo>0){
			if(modulo>=7){
				weeks =  parseInt(modulo/7);
				days =  parseInt(modulo%7);
			}else{
				days =  modulo;
			}
		}
	}else if(totalDays>=7){
		  weeks =  parseInt(totalDays/7);
		  days =  parseInt(totalDays%7);
	}else{
		days =  totalDays;
	}	
		
	mamt = parseInt($("#per_month").val());
	wamt = parseInt($("#per_week").val());
	damt = parseInt($("#per_day").val());
	
	months = months*mamt;
	weeks = weeks*wamt;
	days = days*damt;
	total = months+weeks+days;
	
	var cc = $("#change_currency").val();
	//var hkd = $("#bfhkd").val();
	var hkd = "0.129";
	
	if(cc==2){
//		total = total*hkd;
//		total = Math.round(total);
//	 	$("#sub-amt").html("$"+total+"  USD");
	}else{	 	
//		$("#sub-amt").html("$"+total+"  HKD");
	}
	update_sub_amt(total);	
} // function 
$(document).ready(function() {
	
	$("#mdropdown").val($("#active_m").val());			
	
	$('.fancybox-thumbs').fancybox({
		prevEffect : 'none',
		nextEffect : 'none',

		closeBtn  : false,
		arrows    : false,
		nextClick : true,

		helpers : {
			thumbs : {
				width  : 50,
				height : 50
			}
		}
	});
	$( "#check-in" ).datepicker();
	$( "#check-out" ).datepicker();
		
	$( "#booking_type" ).change(update_type_amt);
	$( "#check-in" ).change(function() {
		var v1 = document.getElementById('check-in').value;
		var v2 = document.getElementById('check-out').value;
		$("#bf_from").val(v1);
		$("#bf_to").val(v2);
		if(v1!="" && v2!=""){
			var from_date = $("#check-in").datepicker('getDate'),
			to_date   = $("#check-out").datepicker('getDate');
			if(to_date.getTime()<=from_date.getTime()){
				alert("Booking dates not valid");
			}else{
				var parts1 = v1.split('/'); 
				var parts2 = v2.split('/');
				var date1 = new Date(parts1[2],(parts1[0]-1),parts1[1]);
				var date2 = new Date(parts2[2],(parts2[0]-1),parts2[1]);
				var diffDays = Math.round(Math.abs((date1.getTime() - date2.getTime())/(24*3600*1000)));
				$("#non").html(diffDays);						
				cal_amt(diffDays);
			}
		}
	});
	$( "#check-out" ).change(function() {
		var from_date = $("#check-in").datepicker('getDate'),
		 to_date   = $("#check-out").datepicker('getDate');

		var v1 = document.getElementById('check-in').value;
		var v2 = document.getElementById('check-out').value;
		$("#bf_from").val(v1);
		$("#bf_to").val(v2);
		
		if(v1!="" && v2!=""){
			var from_date = $("#check-in").datepicker('getDate'),
			 to_date   = $("#check-out").datepicker('getDate');
			if(to_date.getTime()<=from_date.getTime()){
				alert("Booking dates not valid");
			}else{
				var parts1 = v1.split('/'); 
				var parts2 = v2.split('/');
				var date1 = new Date(parts1[2],(parts1[0]-1),parts1[1]);
				var date2 = new Date(parts2[2],(parts2[0]-1),parts2[1]);
				var diffDays = Math.round(Math.abs((date1.getTime() - date2.getTime())/(24*3600*1000)));
				$("#non").html(diffDays);				
				cal_amt(diffDays);
			}
		}
	});
	
	$( "#mdropdown" ).change(function() {
		 var month = $(this).val();
		 var current_m = $("#current_m").val();
		 if(month!=current_m){
			 window.location.href='index.php?/Frontpage/booking/' + room_id + '&m='+month;
		 }
	});

	$( "#change_currency" ).change(function (data){
		var cc = $('#change_currency').val();
		$('#bfcc').val(cc); 
		update_type_amt(); 
		convert_hkd(); 
	});
	
		 
	
	$('body').on('click','#book-btn-unipay', function (data){
		//alert('unipay click');
		dialog = $( "#dialog-form" );
		state = dialog.dialog( "open" );
		//alert('state:'+state);
		//book_btn_click();
		
	});
	$('body').on('click','#book-btn-paypal',  book_btn_click);
	//$(".book-btn").click(alert('dummy 2')); // function of onclick
	//book_btn_click();
	
	//alert('setting dialog');
	 dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 400,
      width: 400,
      modal: true,
      buttons: {
        "Go": update_prc_bank,
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
       // form[ 0 ].reset();
       // allFields.removeClass( "ui-state-error" );
      }
    });
	//alert('finish dialog setup');
	
}); // ready function 



// helper function

// update the input for submission
function update_prc_bank(){
 // alert('update_prc_bank');
	pay_id = $('#pay_id').val();
	$('#bf_prcbank').val(pay_id);
	$( "#dialog-form" ).dialog("close");
	//alert('submit form');
	
	$('#bf_payment').val($('#book-btn-unipay').val()); // payment gateway updated
	$('#bfnog').val($('#number_of_guests').val()); // no. of guest, updated

	$("#booking_form").submit();

}

function update_type_amt(){		 
// alert('update_type_amt');

 // convert currency.
 cc = $('#change_currency').val();
 var type = $("#booking_type").val(); 	
 if(type==1){
	 amt = $("#per_day").val();		 
 }
 if(type==2){
	 amt = $("#per_week").val();		 
 }
 if(type==3){
	 amt = $("#per_month").val();
 }
from_fx='HKD';
if (cc==3){
	// HKD
	to_fx = 'CNY';
}else if (cc==2){
	// USD
	to_fx = 'USD';
}else{
	to_fx = 'HKD'; 
} // if cc
	 
	//alert('start ajax, amt:'+amt + ', from_fx:'+from_fx + ' to_fx:'+ to_fx);
	url = './index.php?c=Frontpage&m=convert_fx&amt=' + amt + '&from=' + from_fx + '&to=' + to_fx;
	//alert('url:'+url);
	$('body').css('cursor', 'progress');
	 result = $.get(
		url,
		function (data) {
		  //alert('back. with data' + data);
		  amt = data;
		  $("#type-amt").html("<h2>Price: $"+amt+" " + to_fx + "</h2>");
		  $('body').css('cursor', 'default');
		}
	 );
} // function update_type_amt

function update_sub_amt(total){
	//alert('update_sub_amt');
	// total is 
	cc = $('#change_currency').val();
	from_fx='HKD';
	if (cc==3){
		// HKD
		to_fx = 'CNY';
	}else if (cc==2){
		// USD
		to_fx = 'USD';
	}else{
		to_fx = 'HKD'; 
	} // if cc
	
	//alert('get input');
	from_date = $( "#bf_from" ).val();
	to_date = $( "#bf_to" ).val();
	room_id = $("#id").val();
	
		url = './index.php?c=Frontpage&m=calculate_amount&room_id=' + room_id + '&from_date=' + from_date + '&to_date=' + to_date + '&fx=' + to_fx;
	//alert('url:'+url);
	$('body').css('cursor', 'progress');
	 result = $.get(
		url,
		function (data) {
		 //alert('back. with data' + data);
		  amt = data;
		  $("#sub-amt").html("Price: $"+amt+" " + to_fx + "");
		  $('body').css('cursor', 'default');
		}
	 );
//	alert ('finish update_sub_amt');
}


function book_btn_click() {
//alert('calling click.');
//alert('this.val:'+$(this).val());
$('#bf_payment').val($(this).val()); // payment gateway updated
$('#bfnog').val($('#number_of_guests').val()); // no. of guest, updated

		$("#booking_form").submit();
/*		var from = $.trim($("#bf_from").val());
		var to = $.trim($("#bf_to").val());
		if(from==""){
			$("#errors").html("Select From and To Date");
			$("#errors").show(1000);
		}	
		else if(to==""){
			$("#errors").html("Select From and To Date");
			$("#errors").show(1000);
		}
		else {
			non = $("#bfnon").val();
			if(non<7){
				ba_daily = $("#ba_daily").val();
				if(ba_daily==2){
					$("#errors").html("Daily Booking is not allowed");
					$("#errors").show(1000);
					return false;
				}
			}else{
				$("#errors").hide();
				$("#errors").html("");
			}
			if(non>=7 && non<30){
				ba_weekly = $("#ba_weekly").val();
				if(ba_weekly==2){
					$("#errors").html("Weekly Booking is not allowed");
					$("#errors").show(1000);
					return false;
				}
			}else{
				$("#errors").hide();
				$("#errors").html("");
			}
			if(non>=30){
				ba_monthly = $("#ba_monthly").val();
				if(ba_monthly==2){
					$("#errors").html("Sorry monthly booking is not allowed");
					$("#errors").show(1000);
					return false;
				}
			}else{
				$("#errors").hide();
				$("#errors").html("");
			}
					
			var from_date = $("#check-in").datepicker('getDate'),
				to_date   = $("#check-out").datepicker('getDate');
				if(to_date.getTime()<=from_date.getTime()){
					$("#errors").html("Booking dates not valid");
					$("#errors").show(1000);
				}else{
					$("#booking_form").submit();
				}
		}
*/		
} // function 
	
// dummy