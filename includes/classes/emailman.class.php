<?php 
	require_once 'sendgrid/sendgrid.class.php';
	class Emailman extends sendgrid{
		var $subject = '';
		var $template = 'pages/templates/email.tpl.php';
		var $recipients = array();
		var $sendgrid = false;
		var $smtp_from = 'Find A Pet Sitter <noreply@findapetsitter.ca>';
		var $smtp_host = 'mail.findapetsitter.ca';
		var $smtp_username = 'noreply@findapetsitter.ca';
		var $smtp_password = 'wbbmeYoYhPJK_3wAgXku';
		var $cssfiles = '';

		var $sendgrid_user = 'findapetsitter';
		var $sendgrid_pass = 'w46pebruwazA';
		
		function __construct($sendgrid = false,$user='',$key=''){
			$this->template = $_SERVER['DOCUMENT_ROOT'].'/'.$this->template;
			if ($sendgrid){
				$this->sendgrid = $sendgrid;
				sendgrid::__construct($this->sendgrid_user,$this->sendgrid_pass);	
			}
			
			require_once('includes/classes/emogrifier.class.php');
			//$this->emogrifier = new Emogrifier();		
		}
		
		function subject($subject){
			$this->subject = $subject;
			return true;
		}

		function setTemplate($file){
			if (is_file($file)){
				$this->template = $file;
			}
		}

		function css($file){
			if (is_file($file)){
				$this->cssfiles .= file_get_contents($file);
			}
		}


		function body($body){
			$this->body = $body;
			return true;
		}

		function to($email, $name=''){
			if (is_array($email)){
				foreach($email AS $key=>$data){
					array_push($this->recipients,array('name'=>$name[$key],'email'=>$email[$key]));
					return true;
				}
			}else{
				array_push($this->recipients,array('name'=>$name,'email'=>$email));
				return true;
			}

		}

		function send($emogrify = false, $category='',$cat_id=''){
			$message = '';
			ini_set("include_path","/var/www/vhosts/findapetsitter.ca/httpdocs/");
			ob_start();
				$template_body = $this->body;
			 	$template_date = date('Y');
			 	require($this->template);

			$message = ob_get_clean();
			if ($emogrify==true){
				if ($this->cssfiles==''){
					$this->css($_SERVER['DOCUMENT_ROOT'].'/css/global_stylesheet.css');
					$this->css($_SERVER['DOCUMENT_ROOT'].'/css/stylesheet.css');

				}
				$this->emogrifier = new Emogrifier($message,$this->cssfiles);
				$message = $this->emogrifier->emogrify();	
			}

			if (!$this->sendgrid){
				foreach($this->recipients AS $key=>$data){
					$this->smtpEmail($data['email'],$this->subject,$message);
				}
				
			}else{
				//use sendgrid
				$this->sendit($this->recipients,$this->subject,$message,strip_tags($message),$category);
			}
			unset($this);
			return true;
		}

		function smtpEmail ($to, $subject, $message) {
			ini_set("include_path", "/var/www/vhosts/findapetsitter.ca/httpdocs:/usr/share/psa-pear:.");

			require_once "Mail.php";
			$headers = array ('From' => $this->smtp_from,
			  'To' => $to,
			  'Subject' => $subject,
			  'MIME-Version' => '1.0', 'Content-Type' => 'text/html;charset=iso-8859-1', 'Content-Transfer-Encoding' => '8bit', 'X-Priority' => '3', 'Importance' => 'Normal');
			$smtp = Mail::factory('smtp',
			  array ('host' => $this->smtp_host,
				'auth' => true,
				'username' => $this->smtp_username,
				'password' => $this->smtp_password));			
			
			$mail = $smtp->send($to, $headers, $message);
			
			if (PEAR::isError($mail)) {
				return array('error'=>true,'message'=>$mail->getMessage());
			} else {
				return true;
			}

		}
	}
?>