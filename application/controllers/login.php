<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	var $cTable = 'customer';
	var $cAutoId = 'customer_id';
	var $is_ajax = false;
	
	//parent constructor will load model inside it
	function Login()
	{
		parent::__construct();
		$this->load->model('mdl_login','lgn');
		$this->lgn->cTable = $this->cTable;
		$this->lgn->cAutoId = $this->cAutoId;		
		$this->is_ajax = $this->input->is_ajax_request();
		
		//not Accessiblefunction after login
		if(in_array($this->router->method,array('index','activateAccount','createNewAccount','forgotpassword')))
		{
			if($this->session->userdata('customer_id') && $this->session->userdata('customer_group_type') != "C")
			{
				redirect('account');
			}
		}
		
		//cache driver
// 		$this->load->driver( 'cache', array( 'adapter' => 'apc', 'backup' => 'file'));
	}
	
	function index()
	{
		if($this->input->post() != '')
		{
			$this->form_validation->set_rules('login_email','Email Address','trim|required|valid_email');
			$this->form_validation->set_rules('login_password','Password','trim|required');
			
			if($this->form_validation->run() == FALSE)
			{
				$returnArr['error'] = $this->form_validation->get_errors();
			}
			else
			{
				$email = trim($this->input->post('login_email'));
				$password   = md5($this->input->post('login_password').$this->config->item('encryption_key'));
				
				$response = $this->lgn->getCustomerData($email,$password);
				if($response)
				{
					//On 01-05-2015 allowed login to guest and set thier type as G
					if($response['customer_group_type'] == 'U' || $response['customer_group_type'] == 'G')
					{
						if(($response['customer_emailid'] == $email) && ($response['customer_password'] == $password))
						{
							if($response['customer_status'] == '0')
							{
								//update customer group if G then to U
								checkAndUpdateGuestCustomerGroup( $response['customer_id'], $response['customer_group_type'] );
								
								//set all login sessions and upd cart/wish database
								setLoginSessions($response['customer_id'], 'U', $response['customer_emailid']);
								
								$returnArr['success'] = 'true';
							}
							else
							{
								$this->lgn->isCustomerDisabled($response);
								$returnArr['warning'] = getErrorMessageFromCode('01002');
							}
						}
						else
						{
							$returnArr['error'] = array('login_not_match'=>getErrorMessageFromCode('01013'));
						}
					}
					elseif($response['customer_group_type'] == 'G')
					{
						$returnArr['warning'] = getErrorMessageFromCode('01020');
					}
					else
					{
						$returnArr['warning'] = getErrorMessageFromCode('01015');
					}
				}
				else
				{
					$returnArr['error'] = array('login_not_match'=>getErrorMessageFromCode('01013'));
				}
			}
			
			$msg = getFlashMessage('customer_referrer');

			if( $this->session->userdata('transaction_id') !== FALSE )	//complete pending order left due to session time out
			{
				$email = $this->session->userdata('email');
				if($email == $this->session->userdata('customer_emailid'))
				{
					$msg ='checkout/completeOrdOnTimeOut';	
				}
				else
				{
					setFlashMessage('error', 'One order pending with this session. Login with same account to complete the pending order.');
					$msg='account';
				}
			}
			else if($msg=='')
			{
				$msg='account';
			}
			else
			{
				$msg = customizeRedUrl($msg);
			}
			
			$returnArr['ref_url'] = site_url($msg);
			echo json_encode($returnArr);
		}
		else
		{
			if($this->session->userdata('customer_id') == '' || $this->session->userdata('customer_group_type') == "C")
			{
				$data['custom_page_title'] = 'Login '.baseDomain();
				$data['pageName'] = 'login';
				$this->load->view('site-layout',$data);
			}
			else
			{
				redirect('/');
			}
		}
	}
/*
+-------------------------------------------------------+
	Callback function While customer registering to the site.
	check email duplication in database.
+-------------------------------------------------------+
*/	
	function checkMailDuplication($str)
	{
// 		$d = $this->lgn->getCustomerData($str);
		
// 		if(@$d['customer_group_type'] == 'U' || @$d['customer_group_type'] == 'G' || @$d['customer_group_type'] == 'C' && count($d) > 0)
// 		{

		//Guest User
		
		if( checkIfRowExist( "SELECT 1 FROM customer WHERE customer_emailid='".$str."' AND customer_group_id
							  NOT IN ( SELECT customer_group_id FROM customer_group WHERE customer_group_type='G' ) " ) )
		{
			//change : 01-05-2015 Check mail for password remove message for guest user.
			$this->form_validation->set_message('checkMailDuplication','This email address already registered with us.');
			return false;
		}		
		else
		{
			return true;
		}
	}

/**
 * @abstract mainly used form moobile site register
 */	
	function register()
	{
		$data['pageName'] = 'register';
		$this->load->view('site-layout',$data);
	}
	
/*
+-------------------------------------------------------+
	Register Account : create new account for customer
+-------------------------------------------------------+
*/	
	function createNewAccount()
	{
		cmn_vw_signup();
	}

/*
+-------------------------------------------------------+
	Function will Activate the account and id link is not
	valid then it will show the error.
+-------------------------------------------------------+
*/		
	function activateAccount()
	{
		if($this->input->get())
		{
			$c = $this->lgn->validSignature();
			if(count($c) == 0)
				setFlashMessage('error',getErrorMessageFromCode('01017'));
			
			else if($c['customer_approved'] == '1')
				setFlashMessage('error',getErrorMessageFromCode('01018'));
			
			else
			{
				$this->lgn->activateCustomer($c);
				setFlashMessage('success',getErrorMessageFromCode('01019'));
			}
			redirect('login');
		}
		else
			redirect('login'); 
	}
	
/*
+-----------------------------------------+
	Function will save data and send email
	using facebook.
+-----------------------------------------+
*/	
	function facebookSignup()
	{	
		if($_POST && $_POST['fb_user_email'] != 'undefined')
		{
			$redirectUrl = ""; 
			
			$data['customer_firstname'] = $this->input->post('fb_user_fname');
			$data['customer_lastname'] = $this->input->post('fb_user_lname');
			$data['customer_emailid'] = $this->input->post('fb_user_email');
			$data['customer_phoneno'] = $this->input->post('fb_user_phoneno');
			$data['facebook_id'] = $this->input->post('fb_facebook_id');			
			
			$res = $this->db->where('customer_emailid',$data['customer_emailid'])->get($this->cTable);
			$row = $res->row_array();
			
			$this->load->helper('string');
			$user_pass = random_string('alnum', 6); //random generate string
			$data['customer_password'] = md5($user_pass.$this->config->item('encryption_key'));
			$data['customer_ip_address'] = @$this->input->ip_address();
			$email = $data['customer_emailid'];
		
				if($this->session->userdata('customer_id') == '' && $res->num_rows() < 1)
				{
					$data['email_list_id'] = saveEmailList($data['customer_emailid'], 1, 'N', 'FB_REGISTER', 7); //save email_list table
					
					$data['customer_salt'] = md5(random_string('alnum','15'));
					$data['customer_group_id'] = getField('customer_group_id','customer_group','customer_group_type','U');
					//$this->db->insert($this->cTable, $data);
					$last_insert_id = saveCustomer($data['customer_emailid'], $data);
					
					$data['email_address'] = $email;
					$data['text_password'] = $user_pass;
					$data['activation_link'] = base_url('activateAccount?signature='.$data['customer_salt']);
			
					//we are sending activation mail
					getTemplateDetailAndSendMail('ACCOUNT_ACTIVATION',$data);
					
					//send notification to admin
					$data['email_address'] = getField('config_value','configuration','config_key','ADMIN_EMAIL');
					$data['first_name'] = $data['customer_firstname'];
					$data['last_name'] = $data['customer_lastname'];
					$data['user_email'] = $email;
					getTemplateDetailAndSendMail('ADMIN_NOTI_CUSTOMER_ACCOUNT_CREATED',$data);
			
					setFlashMessage('success',getErrorMessageFromCode('01016'));				
					$redirectUrl = site_url('login');
				}
				else //update data to database
				{
					$u_data['customer_firstname'] = $data['customer_firstname'];
					$u_data['customer_lastname'] = $data['customer_lastname'];
					$u_data['facebook_id'] = $data['facebook_id'];
					$u_data['customer_ip_address'] = @$this->input->ip_address();
					//$this->db->where('customer_emailid',$data['customer_emailid']);
					//$this->db->update($this->cTable,$u_data);
					saveCustomer($data['customer_emailid'], $u_data);
					
					$last_insert_id = $row['customer_id'];
					
					
					$redirectUrl = $this->session->userdata( "FB_HTTP_REFERER" );
					if( strpos( $redirectUrl, "checkout" ) === FALSE )
					{
						$redirectUrl = site_url("account/invite-friends");  
					}
				}
				
			//set all login sessions and upd cart/wish database
			setLoginSessions(@$last_insert_id, 'U', $data['customer_emailid']);

			//pr($redirectUrl);die;
			header('Location:'.$redirectUrl);
			//redirect('login');
		}
		else
		{
			$errMsg = "Facebook API does not seem to be working properly"; 
			if( isset($_POST['fb_user_email']) && $_POST['fb_user_email'] == 'undefined' ) 
			{
				$errMsg = "Your Facebook email address is not available"; 
			}
			
			$redirectUrl = $this->session->userdata( "FB_HTTP_REFERER" );
			if( strpos( $redirectUrl, "checkout" ) === FALSE )
			{
				$redirectUrl = "login";
				setFlashMessage("error",  $errMsg . ", please use direct login or signup.");
			}
			else 
			{
				setFlashMessage("error", $errMsg . ", please use guest signup.");
			}
				
			redirect( $redirectUrl );
		}
	}
	
/*
+-----------------------------------------+
	Function will save data and send email, 
	all parameters will be in post method.
+-----------------------------------------+
*/	
	function forgotpassword()
	{
		if($this->is_ajax)
		{
			if($this->input->post())
			{
				$this->form_validation->set_rules('forgot_email','Email Address','trim|required|valid_email');
				
				if($this->form_validation->run() == FALSE)
					$returnArr['error'] = $this->form_validation->get_errors();
				else
				{
					$email 	  = trim($this->input->post('forgot_email'));
					$response = $this->lgn->getCustomerData($email);
					pr($response);
					die;
					if($response && ($response['customer_emailid'] == $email))
					{
						if($response['customer_status'] == '1')
						{
							$this->lgn->isCustomerDisabled($response);
							$returnArr['error'] = array('forgot_not_match'=>getErrorMessageFromCode('01002'));
						}
						else
						{
							$this->load->helper('string');
							$user_pass = random_string('alnum', 6); //random generate string
							$data['customer_password'] = md5($user_pass.$this->config->item('encryption_key'));
							
							$this->db->where($this->cAutoId,$response['customer_id'])->update($this->cTable,$data);
							
							$data['first_name'] = $response['customer_firstname'];
							$data['last_name'] = $response['customer_lastname'];
							$data['email_address'] = $response['customer_emailid'];
							$data['email_list_id'] = $response['email_list_id'];
							$data['text_password'] = $user_pass;
							$data['login_link'] = base_url('login');
							//getTemplateDetailAndSendMail('RESET_PASSWORD_EMAIL',$data);	
							
							$subject = 'Reset Your Password at Stationery';
							$mail_body = $this->load->view('templates/forgot-password',$data,TRUE);
							$mail_body .= $this->load->view('templates/footer-template',array('email_list_id'=>$data['email_list_id'],'email_id'=>$data['email_address']),TRUE);
// 							echo $mail_body;
// 							die;
							sendMail($data['email_address'], $subject, $mail_body);
							
							$returnArr['success'] = getErrorMessageFromCode('01014');
						}
					}
					else
						$returnArr['error'] = array('forgot_not_match'=>getErrorMessageFromCode('01015'));
					
				}
				echo json_encode($returnArr);
				
			}
			else
				$this->load->view('elements/forgot_password');//redirect('/');
		}
		else
			redirect(site_url());
	}
	
	
	function logout()
	{
		$customer_id = (int)$this->session->userdata('customer_id');
		if($customer_id!=0)
		{
			unsetLoginSessions();
			setFlashMessage('success','You are successfully logged out.');
			redirect('login');
		}
		else
			redirect('/');
	}
/*
+--------------------------------------------------+
	footer email subscription
	function will check email id, return error
	if name already exist.
+--------------------------------------------------+
*/	
	function existsEmailId($str)
	{
		if($this->input->post('newsletter_subscriber_id'))
		$this->db->where($this->input->post('newsletter_subscriber_id')." !=",$this->input->post('newsletter_subscriber_id'));
		$c = $this->db->where('newsletter_email',$str)->get('newsletter_subscriber')->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('existsEmailId', 'This '.$str.' is already subscribed.');
			return false;
		}
		else
		return true;
	}		
/*
+-----------------------------------------+
	Function will save data for newsletter 
	subscriber.
+-----------------------------------------+
*/
	function newsletterSubscribe()
	{
		$this->form_validation->set_rules('newsletter_email','Email','trim|required|valid_email|callback_existsEmailId');
		if($this->form_validation->run() == FALSE )
		{
			$returnArr['error'] = $this->form_validation->get_errors();
		}
		else // saving data to database
		{
			$this->lgn->saveNewsletterSubscriber();
			$returnArr['success'] = getErrorMessageFromCode('01011');
		}
		echo json_encode($returnArr);
	}
	
/*
* @abstract fetch state as per country id passed
* 
*/
	function getState()
	{
		$countryid = $this->input->post('country_id');
		$name = $this->input->post('name');
		echo loadStateDropdown($name,$countryid);
	}
/*
* function will when success register that time called
*/	
	function success()
	{
		$data['custom_page_title'] = 'Success';
		$data['pageName'] = 'register_success';
		$this->load->view('site-layout',$data);
	}
}
