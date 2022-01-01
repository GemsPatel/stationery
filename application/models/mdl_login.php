<?php
class mdl_login extends CI_Model
{
	var $cTable = '';
	var $cAutoId = '';
/*
+--------------------------------------------------+
	Function will use for newsletter subscriber
+--------------------------------------------------+
*/
	function saveNewsletterSubscriber()
	{
		$data['email_address'] = @$this->input->post('newsletter_email');
		$email_list_id = saveEmailList($data['email_address'], 2, 'S', 'SUBSCRIBED', 5); //save email_list table

		$customerArr = array(
								'email_list_id' => @$email_list_id
							);
		
		/**
		 * code flow revised On 02-05-2015, to only update group id if user is not exist until now 
		 */
		$grpid = getField('customer_group_id','customer_group','customer_group_type','S');
		if( !checkIfRowExist( " SELECT 1 FROM customer WHERE  customer_emailid = '".$data['email_address']."' " ) ) 
		{
			$customerArr["customer_emailid"] = $data['email_address'];
			$customerArr["customer_firstname"] = "Subscribe User";
			
			$customerArr["customer_group_id"] = $grpid;
			$customerArr["customer_ip_address"] = $this->input->ip_address();
		}
		$customer_id = saveCustomer($data['email_address'], $customerArr); //save to customer
		
		$subscribeData = array(
			/**
			 * Cloudwebs: changed on 26-03-2015 to let use customer_id when login session is not set 
			 */
			//'customer_id' => @$this->session->userdata('customer_id'),	
			'customer_id' => $customer_id,
			'customer_group_id' => @$grpid,
			'email_list_id' => @$email_list_id,
			'newsletter_email' => @$data['email_address'],
			'newsletter_status' => '0',
			'newsletter_ip_address' => @$this->input->ip_address()
		);
		$this->db->insert('newsletter_subscriber', $subscribeData);
		
		$data['customer_salt'] = md5(random_string('alnum','15'));
		$data['email_address'] = $data['email_address'];
		$data['activation_link'] = base_url('activateAccount?signature='.$data['customer_salt']);
		
		$subject = 'Thank You for Subscribing at '.baseDomain();
		$mail_body = $this->load->view('templates/newsletter-subscribe',$data,TRUE);
		$mail_body .= $this->load->view('templates/footer-template',array( 'email_list_id'=>$email_list_id,'email_id'=>$data['email_address']),TRUE);
		sendMail($data['email_address'], $subject, $mail_body);
		
		/**
		 * send mail to admin, un comment it required to send email to admin on subscribe event
		 */
		//getTemplateDetailAndSendMail('NEWSLETTER_SUBSCRIBER',$data); //send newsletter template email
	}
	
/*
+--------------------------------------------------+
	Register a new account
+--------------------------------------------------+
*/	
	function saveNewAccount()
	{
		$data = $this->input->post();
		$lgnS = array(); 
		
		//$data['customer_dob'] = $data['birthday_year'].'-'.$data['birthday_month'].'-'.$data['birthday_day'];
		//$data['customer_anni_date'] = $data['anniversary_year'].'-'.$data['anniversary_month'].'-'.$data['anniversary_day'];
		$data['customer_password'] = md5($data['customer_password'].$this->config->item('encryption_key'));
		$data['customer_ip_address'] = @$this->input->ip_address();
		$data['customer_salt'] = md5(random_string('alnum','15'));		
		$data['customer_group_id'] = getField('customer_group_id','customer_group','customer_group_type','U');
		
		//unset($data['confirm_password']);
		//unset($data['birthday_year']);unset($data['birthday_month']);unset($data['birthday_day']);
		//unset($data['anniversary_year']);unset($data['anniversary_month']);unset($data['anniversary_day']);
		unset($data['agree']);
		
		$data['email_list_id'] = saveEmailList($data['customer_emailid'], 1, 'N', 'REGISTER', 1); //save email_list table
		/*
		 * $getCustData = $this->getCustomerData($data['customer_emailid']);
		if(@$getCustData['customer_id'] && count($getCustData) > 0)
			$this->db->where($this->cAutoId,$getCustData['customer_id'])->update($this->cTable,$data);
		else
			$this->db->insert($this->cTable,$data);
		*/
		$customer_id = saveCustomer($data['customer_emailid'], $data);
		
		/**
		 * Cloudwebs: added on 08-04-2015
		 * set login session after signup
		 */
		if( is_restClient() ) 
		{
			$lgnS = setLoginSessions($customer_id,"U",$data['customer_emailid']);
		}
		else 
		{
			setLoginSessions($customer_id,"U",$data['customer_emailid']);
		}
		 
		
		$data['email_address'] = $data['customer_emailid'];
		$data['text_password'] = $this->input->post('customer_password');
		$data['activation_link'] = base_url('activateAccount?signature='.$data['customer_salt']);
		
		//we are sending activation mail
		//getTemplateDetailAndSendMail('ACCOUNT_ACTIVATION',$data);
		$subject = 'Stationery.com - Welcome to Stationery.com';
		$mail_body = $this->load->view('templates/create-register',$data,TRUE);
		$mail_body .= $this->load->view('templates/footer-template',array('email_list_id'=>$data['email_list_id'],'email_id'=>$data['email_address']),TRUE);
		//sendMail($data['email_address'], $subject, $mail_body);
		
		//send notification to admin
		$data['email_address'] = getField('config_value','configuration','config_key','ADMIN_EMAIL');
		$data['first_name'] = $data['customer_firstname'];
		$data['phoneno'] = $data['customer_phoneno'];
		$data['user_email'] = $data['customer_emailid'];
		//getTemplateDetailAndSendMail('ADMIN_NOTI_CUSTOMER_ACCOUNT_CREATED',$data);
		
		//send sms	
		if( isSignupSMSOn() )
		{
			$mo_no = $data['customer_phoneno'];
			$msg = 'Thank you for registering at '.baseDomain().'. Browse through our exquisite range of diamond jewellery and experience a refined and new way of jewellery shopping.';
			sendSMS($mo_no,$msg);
		}
		
		return $lgnS; 
	}
/*
+-------------------------------------------------------+
	Function will check signature in database, if link is 
	already click or any invalid signature detected then
	will return false;
+-------------------------------------------------------+	
*/	
	function validSignature()
	{
		$sign = $this->input->get('signature');
		return $this->db->where('customer_salt',$sign)->get($this->cTable)->row_array();
	}
/*
+-----------------------------------------+
	Function will activate the customer account	
+-----------------------------------------+
*/	
	function activateCustomer($cData)
	{
		saveEmailList($cData['customer_emailid'], 2, 'S', 'SUBSCRIBED', 5); //save email_list table
		
		$cdata['customer_status'] = '0';
		$cdata['customer_approved'] = '1';
		//$cdata['customer_salt'] = '';
		$this->db->where($this->cAutoId,$cData['customer_id'])->update($this->cTable,$cdata);
	}
/*
+-----------------------------------------+
	Function will check emailid / password.
+-----------------------------------------+
*/	
	function getCustomerData($email='', $password='')
	{
		if($password)
			$this->db->where('customer_password',$password);
		
		return $this->db->join('customer_group','customer_group.customer_group_id='.$this->cTable.'.customer_group_id')
						->where('customer_emailid',$email)
						//->where('customer_status','0')
						->get($this->cTable)
						->row_array();
	}
/*
+-----------------------------------------+
	Function is send mail when customer is disabled.
+-----------------------------------------+
*/
	function isCustomerDisabled($response)
	{
		$data['email_address'] = $response['customer_emailid'];
		$data['first_name'] = $response['customer_firstname'];
		$data['last_name'] = $response['customer_lastname'];
		$data['phoneno'] = $response['customer_phoneno'];
		$data['activation_link'] = base_url('activateAccount?signature='.$response['customer_salt']);
		getTemplateDetailAndSendMail('CUSTOMER_RESEND_ACTIVATION_MAIL',$data);
	}

}