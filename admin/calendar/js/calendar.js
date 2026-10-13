//alert('loaded js');

$(function() {
 
	//alert('jquery ready');
	$('.datepicker').datepicker( {
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

	$('.fancybox').fancybox();
	$('body').tooltip();
	
	// PDF open new window	
	$('#output_format').click(function(){
		$('#fm_postback').attr('target','_blank');
		return true; // let it fire.
	});
	
	// HTML use same windows
	$('#output_html').click(function(){
		$('#fm_postback').attr('target','_self');
		return true; // let it fire.
	});


	
	$("body").on("click", ".available", function() {
		//alert('available clicked');
		var inputs = $(this).find("input");
		var d = $(this).attr('cur_date'); // $(this).children('input:eq(0)').val();				
		var r = $(this).attr('room_id'); // children('input:eq(1)').val();
		var b = $(this).attr('booking_id'); // children('input:eq(2)').val();
		//alert('d:'+d+'r:'+r);
		/*if(b){
			alert(b);
		}*/
		$("#fburl").attr("href","../cbn.php?d="+d+"&r="+r);
		$("#fburl").trigger('click');
	});
	$("body").on("click", ".booked", function() {
		//alert('book clicked');
//		var inputs = $(this).find("input");				
		//var b = $(this).children('input:eq(2)').val();
		var b = $(this).attr('booking_id'); // children('input:eq(2)').val();
		var r = $(this).attr('room_id'); 
		//alert('b:'+b);
		
		if(b){					
			$("#fburl").attr("href","../cbne.php?id="+b+"&r="+r);
			$("#fburl").trigger('click');
		}
	});
	//alert('still alive');
});
