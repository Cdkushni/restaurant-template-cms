<?php 

	class Contact_us extends Website{

		function index(){
			//ok what? 
			$this->templateman->setTemplate('page');
			$this->templateman->write('content','<h1>'.$this->page['page_title'].'</h1>'.$this->page['content']);


			//load validator
			if (count($_POST)){
				require_once('includes/classes/validman.class.php');
				$validman = new Validman();

				$valid = false;
				
				$criteria = array(
					'name' => array('required','Full name'),
					'email' => array('reuqired|email','Email'),
					'message'=> array('minsize:10|required','Message'),
					'security'=>array('required|captcha','Security'));
			
				if ($validman->validate($criteria,$_POST)){
					//valid
					$valid = true;
					$this->templateman->write('content',"<div class='success' style='clear:both; float: left;'>Success</div><div class='alert' style='clear:both; float: left;'><p>Your message has been sent!</p></div>");
					
					if ($this->globals['contact_email']!=''){

						$subject = 'Web Contact';
						$body = '<img src="http://www.twinriverhomes.ca.php53-2.ord1-1.websitetestlink.com/images/logo.png"/><br /><hr /><b>Name: </b> '.$_POST['name'].'<br /><b>Email: </b> '.$_POST['email'].'<br /><b>Message: </b>'.$_POST['message'];
						$email = $this->globals['contact_email'];
						$email = 'info@zazzle-extensions.com';
						
						//echo $email;
						smtpEmail($email,$subject,$body);

					}

				}
			}

			if (!$valid){
				if ($validman->errors){
					$this->templateman->write('content',$validman->returnErrors());
				}
				$this->templateman->write_file('content','views/contact/contact_form.php');
			}

			$this->templateman->render();
		}
	}
	?>