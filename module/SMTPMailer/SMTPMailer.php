<?php
if (!class_exists('SMTPMailer')){
	//include('email/PHPMailerAutoload.php');
	//include('module/Email_Template/Email_Template.php');
	//include('config/phpmailer_config.php');
	
	include(__DIR__ .'/../../email/PHPMailerAutoload.php');
	include(__DIR__ .'/../../module/Email_Template/Email_Template.php');
	include(__DIR__ .'/../../config/phpmailer_config.php');
	
	// class SMTPMailer: sending email, using Email_Template
	// and given context
	class SMTPMailer{
		protected $email_tpl = null;
		protected $mail = null;
		
		protected $send_back = false;
		
		// setSendBack: whether to send back to the from address.
		public function setSendBack($flag=true){
			$this->send_back = $flag;
			return $this->send_back;
		}
		
		public function __construct(){
	
	//		require 'email/PHPMailerAutoload.php';
	
			$include_path = get_include_path();
	
			global $phpmailer_config;
			//Create a new PHPMailer instance
			$this->mail = new PHPMailer();
			
			// alias
			$mail = &$this->mail;
			//Tell PHPMailer to use SMTP
			$mail->isSMTP();
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->CharSet = 'utf-8'; // change encoding
			$mail->SMTPDebug = $phpmailer_config['SMTPDebug'];
			//Ask for HTML-friendly debug output
			$mail->Debugoutput = $phpmailer_config['Debugoutput'];
			$mail->SMTPSecure = $phpmailer_config['SMTPSecure'];
			//Set the hostname of the mail server
			$mail->Host = $phpmailer_config['Host'];
			//Set the SMTP port number - likely to be 25, 465 or 587
			$mail->Port = $phpmailer_config['Port'];
			//Whether to use SMTP authentication
			$mail->SMTPAuth = $phpmailer_config['SMTPAuth'];
			//Username to use for SMTP authentication
			$mail->Username = $phpmailer_config['Username'];
			//Password to use for SMTP authentication
			$mail->Password = $phpmailer_config['Password'];
			
			// using the phpmailer_config
	//		foreach ($phpmailer_config as $key=>$content){
	//			$mail->$key = $content;
	//		}
			
			
			
		}
		public function __destruct(){
			$this->mail = null; // destruct
			$this->email_tpl = null; // destruct 
		}
		
		public function setEmailTemplateById($id){
			// give the ID, load the corresponding email template
			$this->email_tpl = new Email_Template();
			$email_tpl = &$this->email_tpl; // alias
			$email_tpl->retrieve($id);
			// Done
		}
		
		// Context = array (
		// 	'email' 		=> email to send
		//	'first_name'	=> first name of recipient
		//	'last_name'		=> last name of receipient
		//	other can be used in subject and body as well.
		public function send($context=null){
			// send email using the email template
			$mail = &$this->mail; // alias
			$email_tpl = &$this->email_tpl; // alias
			if ($email_tpl==null){
				return false; // not send.
			}
	
			//Set who the message is to be sent from
			$from_addr = $email_tpl->from_address;
			$from_name = $email_tpl->from_name;
			$mail->setFrom($from_addr, $from_name);
			//Set an alternative reply-to address
			
			$mail->addReplyTo($from_addr, $from_name);
			//Set who the message is to be sent to
			$to_addr = $context['{$email}'];
			$to_name = $context['{$first_name}'] . " " . $context['{$last_name}'];
			$mail->addAddress($to_addr, $to_name);
			if ($this->send_back){
				// send back
				$mail->addAddress($from_addr, $from_name);
			}
			
			if (!empty($email_tpl->cc_address)){
				$email_addr = explode(',', $email_tpl->cc_address);
				foreach ($email_addr as $cur_cc){
					$mail->addCC($cur_cc, $cur_cc);
				}
			}
			
			//$mail->addAddress('hkcmap@gmail.com', 'China Max');
			//Set the subject line
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$search = array_keys($context);
			$replace = array_values($context);				
	
			$sub_subject = str_replace($search,$replace, $email_tpl->email_subject);
			$substituted_body = str_replace($search,$replace, $email_tpl->email_body);
			
			$mail->Subject = $sub_subject; //'Booking Confirmation - China Max Asia Pacific Limited';		
			$mail->msgHTML($substituted_body);
					
	//'Dear '.$fname.', <br/> <br/> Your booking was made successfully, here is the details of your booking. <br/> <br/> Unit Name: '.$room['room_name'].' <br> Day in  - '.$din.' <br> Day out  - '.$dout.' <br> Total night stay: '.$nod.' <br> Amt: '.$item_amount." ".$booking['currency']."<br><br><br>謝�����使���������������,���������������次������������ <br>
	//Thank you for staying with us, we look forward to services again.<br><br><br>
	//		
	//The Metropolis Motel / China Max Asia Pacific Limited<br>
	//No.A on 2nd Floor, Dolford Mansion, Nos. 1 & 3 Chatham Court, Tsim Sha Tsui Kln<br>
	//Tel: 8170-3484 Fax: 30206793 www.cmaphk.com E-mail address: cs@cmaphk.com");
	//		//Replace the plain text body with one created manually
			$mail->AltBody = $substituted_body; 
	//'Dear '.$fname.', <br/> <br/> Your booking was made successfully, here is the details of your booking. <br/> <br/> Unit Name: '.$room['room_name'].' <br> Day in  - '.$din.' <br> Day out  - '.$dout.' <br> Total night stay: '.$nod.' <br> Amt: '.$item_amount." ".$booking['currency']."<br><br><br>謝�����使���������������,���������������次������������ <br>
	//Thank you for staying with us, we look forward to services again.<br><br><br>
	//		
	//The Metropolis Motel / China Max Asia Pacific Limited<br>
	//No.A on 2nd Floor, Dolford Mansion, Nos. 1 & 3 Chatham Court, Tsim Sha Tsui Kln<br>
	//Tel: 8170-3484 Fax: 30206793 www.cmaphk.com E-mail address: cs@cmaphk.com";
			
			//send the message, check for errors
			// avoid sending email first.
			//$this->admin_send($context);
			return $mail->send();
			
		}
		
		public function admin_send($context=NULL){
			
			$sql="SELECT `value` FROM `settings` WHERE `name`='complete_email'";
			$query=mysql_query($sql);
			$row=mysql_fetch_array($query);
			$set_mail=$row['value'];
			$sql="SELECT `value` FROM `settings` WHERE `name`='path_id'";
			$query=mysql_query($sql);
			$row=mysql_fetch_array($query);
			$path=$row['value'];
			$transaction_id=$context['{$transaction_id}'];  //unipay paypal return number
			$exchange=$context['{$exchange_rate}'];
			$mark_up=$context['{$mark_up_rate}'];
			$total=$context['{$item_amount}'];
			$currency=$context['{$currency}'];
			$txn_id=$context['{$pay_no}'];					//paypal booking number
			$pay_type=$context['{$pay_type}'];
			($mark_up=="")?$mark_up=0:$mark_up=$mark_up;
			if($exchange==""){
				$exchange=1;$mark_up=0;
			}
			$old_total=$total/($exchange+$mark_up);
			$mark_up_amt=point_number($old_total*$mark_up);
			$exchange_amt=point_number($old_total*$exchange);
			$new_total=$mark_up_amt+$exchange_amt;
			if($total<=$new_total){
				$exchange_amt=$exchange_amt-($new_total-$total);
			}else{
				$mark_up_amt=$mark_up_amt+($total-$new_total);
			}
			if($mark_up_amt==0){
				$sql2="INSERT INTO `mark_up_receivable` (transaction_id,path,total,currency,mark_up_rate,receivable,state,gateway,txn_id) VALUES ('$txn_id','$path','$total','$currency','$mark_up','$mark_up_amt','1','$pay_type','$transaction_id')";
			}else{
				$sql2="INSERT INTO `mark_up_receivable` (transaction_id,path,total,currency,mark_up_rate,receivable,gateway,txn_id) VALUES ('$txn_id','$path','$total','$currency','$mark_up','$mark_up_amt','$pay_type','$transaction_id')";
			}
			$post_data=array("sql"=>$sql2);
			$response=update_inn_db($post_data);
			if(!$response){
				die("fail to record data");
			}
			
			$mail2 = &$this->mail; // alias
			$email_tpl = &$this->email_tpl; // alias
			if ($email_tpl==null){
				return false; // not send.
			}
	
			//Set who the message is to be sent from
			$from_addr = $email_tpl->from_address;
			$from_name = $email_tpl->from_name;
			$mail2->setFrom($from_addr, $from_name);
			//Set an alternative reply-to address
			//Set who the message is to be sent to
			
			$search = array_keys($context);
			$replace = array_values($context);				
	
			$sub_subject = str_replace($search,$replace, $email_tpl->email_subject);
			$substituted_body = str_replace($search,$replace, $email_tpl->email_body);
			
			$mail2->ClearAllRecipients();
			$mail2->addAddress($set_mail);
			$mail2->Subject = $sub_subject; //'Booking Confirmation - China Max Asia Pacific Limited';		
			$mail2->msgHTML($substituted_body."
			
			<br/><br/><br/>----------------------------------------------------------------------------------------------------<br/><h2>DETAIL</h2><br/>
			<h2>Total Amount: ".$total." ".$currency."</h2><br/>
			<h2>Exchange Rate: ".$exchange."</h2><br/>
			<h2>Mark Up Rate: ".$mark_up."</h2><br/>
			<h2>Exchange AMT: ".$exchange_amt."</h2><br/>
			<h2>Mark Up AMT: ".$mark_up_amt."</h2><br/>
			<h3>Pay_Type: ".$pay_type."</h3><br/>
			<h3>Booking Number: ".$context['{$pay_no}']."</h3><br/>
			<h3>$pay_type Return Number: ".$context['{$transaction_id}']."</h3><br/>
			");
			$mail2->AltBody = $substituted_body;
			
			return $mail2->send();
		}
		
		
	} //class
} //if