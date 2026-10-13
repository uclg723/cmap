<?php
function get_template($color='#FFFFFF')
{
	global $SETTINGS;
	global $LANG;
	global $TIME_START;

	$file=fopen("$SETTINGS[system_root]$SETTINGS[file_template]",'r');

	if($file)
		while(!feof($file))
			$template.=fgets($file,4096);

	$TIME_STOP=explode(" ",microtime());
	$TIME_STOP=$TIME_STOP[1].substr($TIME_STOP[0],1);
	$sTime=number_format($TIME_STOP-$TIME_START,3);

	$template=str_replace("<!--BACKGROUND_COLOR-->",$color,$template);
	$template=str_replace("<!--TITLE-->",$LANG[title],$template);
	$template=str_replace("<!--COMPANY_NAME-->",$LANG[company_name],$template);
	$template=str_replace("<!--HEADER-->",output_banner(),$template);
	$template=str_replace("<!--MENU-->",output_menu(),$template);
	$template=str_replace("<!--FOOTER-->",output_footer(),$template);
	$template=str_replace("<!--TEL-->",$SETTINGS[tel],$template);
	$template=str_replace("<!--EMAIL-->",$SETTINGS[email],$template);

	return $template;
}

function output_header()
{
	header("Content-Type: text/html; charset=utf-8");
	header("Expires:".gmdate("D, d M Y H:i:s",time())." GMT");
	header("Last-Modified:".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");
}

function output_banner()
{
	global $LANG;

	$output=<<< HTML
$LANG[banner_caption]<br><br>
<a href="?lang=en" class="nav">Eng</a>
|
<a href="?lang=big5" class="nav">$LANG[chi]</a>
HTML;
	return $output;
}

function output_menu()
{
	global $LANG;

	$output=<<< HTML
<table cellspacing="0" cellpadding="0">
<tr>
	<td><a href="home.php" class="nav">$LANG[menu_home]</a></td>
	<td width="50px"></td>
	<td><a href="about_us.php" class="nav">$LANG[menu_about_us]</a></td>
	<td width="50px"></td>
	<td><a href="price.php" class="nav">$LANG[menu_price]</a></td>
	<td width="50px"></td>
	<td><a href="terms.php" class="nav">$LANG[menu_terms]</a></td>
	<td width="50px"></td>
	<td><a href="contact_us.php" class="nav">$LANG[menu_contact_us]</a></td>
</tr>
</table>
HTML;
	return $output;
}

function output_footer()
{
	global $LANG;

	$output=<<< HTML
&copy; $LANG[company_name]
HTML;
	return $output;
}
?>
