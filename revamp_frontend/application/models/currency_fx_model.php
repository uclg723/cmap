<?php

class Currency_fx_model extends CI_Model{

	function convertCurrency($amount, $from, $to){
	
		if ($from == $to){
			return round($amount, 10);
			// no need to convert and google will not convert as well.
		}
	    $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
	    $data = file_get_contents($url);
	    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
	    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
	    return round($converted,10);
	}	


}

?>