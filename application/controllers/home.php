<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home extends CI_Controller 
{

	var $is_ajax = false;
	//parent constructor will load model inside it
	function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->model('mdl_home','hom');
		$this->is_ajax = $this->input->is_ajax_request();
	}
	
	function index()
	{
		if( !getSysConfig("IS_ES") || $this->session->userdata('is_entersite_loaded') ) 
		{
			$this->main();
		}
		else
		{
			$this->load->view('enter_site');
		}
	}
	
	function main()
	{
		$this->session->set_userdata('is_entersite_loaded',1);
		
		
		$data['where'] = "WHERE front_hook_alias='home'";
// 		$scArr = $this->db->where('manufacturer_id',MANUFACTURER_ID)->get('site_config')->row_array();
// 		$data['custom_page_title'] = strReplaceIndToAus(@$scArr['custom_page_title']);
// 		$data['meta_description'] = strReplaceIndToAus(@$scArr['meta_description']);
// 		$data['meta_keyword'] = strReplaceIndToAus(@$scArr['meta_keyword']);
		
		/**
		 * Gautam: Featured products added on 11-04-2015
		 */
		$this->load->model('mdl_products','jew');
		$data['pageName'] = 'home';
		$this->load->view('site-layout',$data); 
	}
	
	function publications()
	{
		$this->load->view('publications');
	}
	
	
	/**
	 * function will sets inventory session currently under use
	 */
	function setInventorySession()
	{
		$it_key = $this->input->get("it_key");
		setInventorySession($it_key);
		echo json_encode( array("type"=>"success", "msg"=>"") );
	}
	
	/**
	 * function will sets language session currently under use
	 */
	function setLangSession()
	{
		/**
		 * if admin session then nothing to do, it's done :) <br>
		 * but if it is front end client session then redirect to that particular subdomain.
		 */
		echo json_encode( array("type"=>"success", "msg"=>"") );
	}
	
	//common article page	
	function article()
	{
		$articleAlias = end($this->uri->segment_array());
		if( MANUFACTURER_ID != 7 )
			$this->db->where('manufacturer_id', MANUFACTURER_ID);
			
		$tableName = ( MANUFACTURER_ID != 7 ) ? 'article_cctld' : 'article' ;
		$data = $this->db->where('article_alias',$articleAlias)->where('article_status',0)->get($tableName)->row_array();
		
		if(!empty($data))
		{
			//For use Australia seo hidden title
			if( MANUFACTURER_ID != 7 )
				$data['category_name'] = $data['article_name'];
			
			if($data['article_key'] == 'FAQ')
			{
				$data['pageName'] = ( MANUFACTURER_ID != 7 ) ? 'article_faq_au' : 'article_faq';
				$this->load->view('site-layout',$data);
			}
			else if($data['article_key'] == 'CONTACT_US')
			{				
				$data['pageName'] = 'contact-us';
				$this->load->view('site-layout', $data);
			}
			else if($data['article_key'] == 'BULK_ORDER')
			{				
				$data['pageName'] = 'bulk-order';
				$this->load->view('site-layout', $data);
			}
			else if($data['article_key'] == 'RING_SIZER')
			{				
				$data['pageName'] = 'ring-sizer';
				$this->load->view('site-layout', $data);
			}
			else if($data['article_key'] == 'GIVE_FEEDBACK')
			{
				if($this->is_ajax)
					$this->load->view('article_feedback', $data);
				else
					redirect(site_url());
			}
			else
			{
				$data['pageName'] = 'article';
				$this->load->view('site-layout',$data);
			}
		}
		else
		{
			redirect('my404');
		}
		
	}

//Jewellery Details page on request ring sizer popup
	function orderRingSizerPopup()
	{
		if($this->is_ajax)
		{
			$data = array();
			if($_POST)
			{
				$this->form_validation->set_rules('customer_firstname','Firstname','trim|required');		
				$this->form_validation->set_rules('customer_address','Address','trim|required');
				$this->form_validation->set_rules('customer_emailid','Email Id','trim|required|valid_email');
				$this->form_validation->set_rules('country_id','Country','trim|required');
				$this->form_validation->set_rules('state_id','State','trim|required');
				$this->form_validation->set_rules('address_city','City','trim|required');
				$this->form_validation->set_rules('customer_pincode','Pincode','trim|required');
				$this->form_validation->set_rules('customer_gender','Gender','trim|required');
				$this->form_validation->set_rules('customer_phoneno','Phone No.','trim|required|numeric');
				if($this->form_validation->run() == FALSE && @$this->input->post('YesBtn') == '')
				{
					$data = $this->form_validation->get_errors();
					echo json_encode($data);
					die;
				}
				else 
				{
					$this->hom->saveOrderRingSizer();
					$data['success'] = 1;
					echo json_encode($data);
					die;
				}
			}
			$data['customer_id'] = $this->session->userdata('customer_id');
			$this->load->view('order_ring_sizer_popup',$data);
		}
		else
			redirect(site_url());
	}
/*
 * @author   Cloudwebs
 * @abstract function will load city as per state selected
 */
	function loadCityAjax()
	{
		$state_id = $this->input->post('state_id');
		if(!empty($state_id))
		{
			echo loadCity($state_id);
		}
		else
		{
			echo '<option value="">- Select State First -</option>';	
		}
	}

//footer menu on feedback popup	
	public function feedback()
	{
		/**
		 * Change code 08-05-2015 to restAPI
		 */
		cmn_vw_contact();
	}
	
/*
+--------------------------------------------------+
	pop up email subscription
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
		
//pop up newsletter subscriber	
	public function newsletter()
	{
		$this->form_validation->set_rules('newsletter_email','Email Id','trim|required|valid_email|callback_existsEmailId');
		if($this->form_validation->run() == FALSE)
		{
			$data = $this->form_validation->get_errors();
			echo json_encode($data);
			die;
		}
		else 
		{
			$this->hom->newsletter();
			$data['success'] = 1;
			echo json_encode($data);
			die;
		}
		 $this->load->view('elements/onload_popup', $data);
	}
	
	//ASk The Expert
	function askTheExpert()
	{
		$data = $this->input->post();
		unset($data['AskExpertSubmit']);
		
		$this->form_validation->set_rules('customer_firstname','Name','trim|required');
		$this->form_validation->set_rules('customer_phoneno','Phone No.','trim|required');
		$this->form_validation->set_rules('customer_emailid','Email Id','trim|required|valid_email');
		
		if($this->form_validation->run() == FALSE )
		{
			$data['error'] = $this->form_validation->get_errors();
			if($data['error'])
				setFlashMessage('error',getErrorMessageFromCode('01005'));
			
			$sqlO = "SELECT product_offer_id,product_offer_name FROM product_offer WHERE product_offer_status=0 AND product_offer_key != 'RTS' AND product_offer_key != 'PARTYWEAR' ";
			$data['occassionArr'] = getDropDownAry($sqlO, 'product_offer_id', 'product_offer_name', array('' => "Select Occassion"));
		
			$data['pageName'] = 'ask-the-expert';
			$this->load->view('site-layout',$data);
		}
		else
		{
			$this->hom->saveAskTheExpert();
			setFlashMessage('success','Your request is successfully saved.');			
			redirect('home/thankyouExpert');
		}
	}	
	function thankyouExpert()
	{
		$data['pageName'] = 'thank-you-expert';
		$this->load->view('site-layout',$data);
	}
/*
+-------------------------------------------------------+
	Callback function While customer registering to the site.
	check email duplication in database.
+-------------------------------------------------------+
*/	
	function checkMailDuplication($str)
	{
		$d = $this->db->where('customer_emailid',$str)->get('customer')->num_rows();
		
		if($d > 0)
		{
			$this->form_validation->set_message('checkMailDuplication','This email address already registered with us. Check mail for password.');
			return false;
		}		
		else
		{
			return true;
		}
	}	
	//Function will customer signup for homepage
	function onloadCustomerSignup()
	{
		if($this->is_ajax)
		{
			$data = array();
			if($_POST)
			{
				$this->form_validation->set_rules('customer_firstname','Firstname','trim|required');
				$this->form_validation->set_rules('customer_lastname','Lastname','trim|required');
				$this->form_validation->set_rules('customer_emailid','Email','trim|required|valid_email|callback_checkMailDuplication');
				if($this->form_validation->run() == FALSE)
				{
					$data = $this->form_validation->get_errors();
					echo json_encode($data);
					die;
				}
				else 
				{
					$this->hom->saveCustomerSignup();
					$data['success'] = getErrorMessageFromCode('01012');
					$data['ref_url'] = $_SERVER['HTTP_REFERER'];
					echo json_encode($data);
					die;
				}
			}
		}
		else
			redirect(site_url());
		
		$this->load->view('elements/onloadCustomerSignup', $data);
	}
	
	function customerSignup()
	{
		$data['activation_link'] = asset_url('activateAccount?signature=3a2a511b42485e81b6087d5bc6304db7');
		$data['email_address'] = 'hi0001234d@gmail.com';//perriantech
		$data['text_password'] = '123456';
		$mail_body = $this->load->view('templates/home-customer-signup', $data);
	}
	
	//Facebook share post
	function shareWallPost()
	{	
		if(!empty($_POST['fb_facebook_id']))
		{
			$fb_config = array(
				'appId'  => FB_APP_ID,
				'secret' => FB_SECRET_KEY
			);
			$this->load->library('facebook', $fb_config);
						
			$fb_id = $this->input->post('fb_facebook_id'); //$this->facebook->getUser(); //"100003311498852";
			$access_token = $this->input->post('fb_facebook_offline_token'); //$this->facebook->getAccessToken();
			$email = $this->input->post('fb_user_email');
			$urlLink = $this->input->post('url');
			$ptitle = ( MANUFACTURER_ID != 7 ) ? baseDomain().'.au': baseDomain(); 
			
			// define your POST parameters (replace with your own values)
			$params = array(
			  "access_token" => $access_token, // see: https://developers.facebook.com/docs/facebook-login/access-tokens/
			  "message" => "Thank you for shopping at ".$ptitle,
			  "link" => $urlLink,
			  "picture" => $this->input->post('imgUrl'),
			  "name" => $this->input->post('title'),
			  "caption" => $ptitle,
			  "description" => @$this->input->post('description')
			);
			 
			// post to Facebook
			try 
			{
			  //$this->facebook->api('/'.$fb_id.'/feed', 'POST', $params);
			  $this->facebook->api('/218897488321252/feed', 'POST', $params);
			  //saveEmailList($email, 1, 'S', 'FACEBOOK_USER', 7); //save email_list table
			  redirect(site_url());  //echo 'Successfully posted to Facebook';
			} 
			catch(Exception $e) 
			{
			  echo $e->getMessage();
			}
		}
		else
		{
			$this->load->view('welcome_message');
			//redirect(site_url());
		}
	}
	
	
	function test_rewrite()
	{
		
		$this->load->view('test');
	}
		
	
	function fetch_fb_fans($fanpage_name, $no_of_retries = 10, $pause = 500000 /* 500ms */)
	{
		$ret = array();
		// get page info from graph
		$fanpage_data = json_decode(file_get_contents('http://graph.facebook.com/' . $fanpage_name), true);
		//pr($fanpage_data);die;
		
		if(empty($fanpage_data['id'])){
			// invalid fanpage name
			return $ret;
		}
		$matches = array();
		$url = 'http://www.facebook.com/plugins/fan.php?connections=100&id=' . $fanpage_data['id'];
		
		$context = stream_context_create(array('http' => array('header' => 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:22.0) Gecko/20100101 Firefox/22.0')));
		for($a = 0; $a < $no_of_retries; $a++){
			$like_html = file_get_contents($url, false, $context);
			preg_match_all('{href="http://www\.facebook\.com/([a-zA-Z0-9._-]+)" data-jsid="anchor" target="_blank"}', $like_html, $matches);
			
			if(empty($matches[1])){
				// failed to fetch any fans - convert returning array, cause it might be not empty
				return array_keys($ret);
			}else{
				// merge profiles as array keys so they will stay unique
				$ret = array_merge($ret, array_flip($matches[1]));
			}
			// don't get banned as flooder
			usleep($pause);
		}
		return array_keys($ret);
	}


	function changeCurrency()
	{
		echo $this->hom->getCurrencyData();
	}
	
	function onloadPopup()
	{
		$this->load->view('elements/onload_popup');
	}

//Newsletter email template
	function emailNewsletterTemplate()
	{
		$mo_no = '9374635067';
		$msg = 'Thank you for regCloudwebs.net at Cloudwebs.net. Browse through our exquisite range of diamond jewellery and experience a refined and new way of stationery shopping.';
		//sendSMS($mo_no,$msg);
	}
	
//Confirm order placed template
	function emailConfirmOrderTemplate()
	{
		$email_address = 'info@cloudwebs.net';
		$subject = 'Thank you for your order at '.baseDomain();
		$mail_body = $this->load->view('templates/confirm-order');
		//sendMail($email_address, $subject, $mail_body);
	}
	
//Create new register template	
	function emailRegisterTemplate()
	{
		$email_address = 'info@cloudwebs.net';
		$subject = baseDomain().' - Welcome to '.baseDomain();
		$data['activation_link'] = asset_url('activateAccount?signature=3a2a511b42485e81b6087d5bc6304db7');
		$data['email_address'] = 'info@cloudwebs.net';//perriantech
		$data['text_password'] = '123456';
		$mail_body = $this->load->view('templates/create-register', $data, true);
		$mail_body .= $this->load->view('templates/footer-template',array('email_list_id'=>$data['email_list_id'],'email_id'=>$data['email_address']), true);
		//sendMail($email_address, $subject, $mail_body);
		
	}
	
//forgot password template	
	function emailForgotPasswordTemplate()
	{
		$email_address = 'info@cloudwebs.net';
		$subject = 'Cloudwebs.net - Reset your password';
		$data['first_name'] = 'Hitesh';
		$data['last_name'] = 'patel';
		$data['email_address'] = 'info@cloudwebs.net';//perriantech
		$data['text_password'] = '123456';
		$mail_body = $this->load->view('templates/forgot-password', $data);
		$mail_body .= $this->load->view('templates/footer-template',array('email_list_id'=>$data['email_list_id'],'email_id'=>$data['email_address']));
		//sendMail($email_address, $subject, $mail_body);
	}
	
	function template1()
	{
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
		
		$email_address = 'info@cloudwebs.net';
		$subject = 'Cloudwebs.net';
		$mail_body = $this->load->view('templates/template1');
		
		/*if( mail( $email_address, $subject, $mail_body) )
		{
			echo 'Mail sent';	
		}*/
		//sendMail($email_address, $subject, $mail_body);
	}
	function template2()
	{
		$mail_body = $this->load->view('templates/template2');
	}
	function template3()
	{
		$mail_body = $this->load->view('templates/template3');
	}
	function template4()
	{
		$mail_body = $this->load->view('templates/template4');
	}
	
	function orderInvoice()
	{
		$this->load->view('templates/order-invoice');
	}
	
	function emailToFriend()
	{
		$this->load->view('templates/email-to-friend');
	}
	
/**
 * @author Cloudwebs
 * @abstract function will fetch product details for comparison
 */
	function productCompare()
	{
		$data['pageName'] = 'product_compare';
		$this->load->view('site-layout',$data);
	}

/**
 * @author Cloudwebs
 * @abstract function will fetch product details for comparison
 */
	function checkPinAvail()
	{
		$returnArr = array();
		$this->form_validation->set_rules('pincode','Pincode ','trim|required|numeric|min_length[6]|max_length[6]');
		
		if($this->form_validation->run() == FALSE)
		{
			$returnArr['type'] = 'error';
			$returnArr['error'] = $this->form_validation->get_errors();
		}
		else
		{
			$pincode = $this->input->post('pincode');
			$pincode_id = getField('pincode_id', 'pincode', 'pincode', $pincode);
			
			//Note*: currently pincode check is done for COD when the check is made out of checkout page, however in check is made for COD only because blue stone currently provides same parameters for COD and PREPAID
			$res = checkShipAvailability( 0, 0, $pincode_id, 1 );	
			
			if($res==0 || $res==-1)
			{
				if($res==-1)
				{
					$returnArr['type'] = 'warning';
					$returnArr['msg'] = '<span class="pincheck_error">Shipping not available in specified PINCODE please specify another Location.</span>';
				}
				else if($res == 0)
				{
					$returnArr['type'] = 'warning';
					$returnArr['msg'] = '<span class="pincheck_error">Shipping not available in specified PINCODE please specify another PINCODE.</span>';
				}
			}
			else if( !empty( $res ) )
			{
				//sequel shipping
				$returnArr['type'] = 'success';
				$returnArr['msg'] = '<span class="pincheck_succ">Shipping available in specified location.</span>';
			}
		}
		
		echo json_encode($returnArr);
	}
	
/**
 * @author Cloudwebs
 * @abstract function will fetch product details for comparison
 */
	function unsubscribe()
	{
		if( $_SERVER['REQUEST_METHOD']=='GET' )
		{
			$data = $this->input->get();
			
			$resCnt = executeQuery('SELECT 1 FROM email_list WHERE email_list_id='._de($data['email_list_id']).' AND email_id=\''._de($data['email_id']).'\' ');	
			if( empty($resCnt) )
			{
				setFlashMessage('error', 'Invalid input.');			
				redirect('');
			}
			else
			{
				$this->db->query("UPDATE email_list SET el_status='U',el_modified_date=NOW() WHERE email_list_id="._de($data['email_list_id'])." ");			
				setFlashMessage('success', 'Unsubscribed from newsletter! You will no more recieve any newsletter mails.');			
				redirect('');
			}
		}
		else
		{
			$data['pageName'] = 'unsubscribe';
			$this->load->view('site-layout',$data);
		}
	}
	
/**
 * @author Cloudwebs
 * @abstract function will insert callMeBack entry in database on post and return box on get
 */
	function callMeBack()
	{
		$data = array();
		if( $_SERVER['REQUEST_METHOD'] == 'POST' )
		{
			$this->form_validation->set_rules('ci_customer_phone_number','Mobile Number','trim|required|numeric|min_length[10]|max_length[10]');
			
			if($this->form_validation->run() == FALSE )
			{
				$data['error'] = $this->form_validation->get_errors();
			}
			else
			{
				$customer_id = (int)$this->session->userdata('customer_id');
				$ci_customer_phone_number = $this->input->post('ci_customer_phone_number');
				
				$this->db->query("INSERT INTO customer_interaction(customer_id, ci_interaction_type, ci_customer_phone_number)
								  VALUES(".$customer_id.", 'CALL_ME_BACK', '".$ci_customer_phone_number."') ");
				
				//get customer name if logged in else mark as anonymous user
				$customer_name = '';
				if($customer_id != 0)
				{
					$customer_name = getField('customer_firstname', 'customer', 'customer_id', $customer_id);	
					
					$customer_name = "Our customer named ".$customer_name."";
				}
				else
				{
					$customer_name = "Some anonymous user ";
				}
				
				//get admin contact email address
				$contact_email = getField('config_value', 'configuration', 'config_key', 'CONTACT_EMAIL');

				$data_email['es_to_emails'] = $contact_email;
				$data_email['es_subject'] = "Call me back request.";
				$data_email['es_message'] = $customer_name." has requested for call back at No. ".$ci_customer_phone_number.". ";
				$data_email['es_ip_address'] = $this->input->ip_address();
				sendMail($data_email['es_to_emails'], $data_email['es_subject'], $data_email['es_message']);	
				$this->db->insert("email_send_history",$data_email);
				
				$data['type'] = 'success';
				$data['msg'] = 'Success! You will shortly recieve a call.';				
			}
			
			echo json_encode( $data );
		}
		else
		{
			$data = $this->input->get();	
			echo $this->load->view('elements/call_me_back',$data);
		}
	}
	
/**
 * @author Cloudwebs
 * @abstract  function will retuirn box with skype ID on get
 */
	function skype()
	{
		$data = $this->input->get();	
		echo $this->load->view('elements/skype',$data);
	}
		
	/**
	 *	@abstrcat function will decide on ajax call if pro active chat is required and start pro active chat if respective OR any admin operator is online...
	 */
	function pf_isProActiveChat()
	{
		$data = $this->input->get();
		$proConfig = getProActiveChatConfig( $data['p_id'], $data['am_id'], $data['ct_id'] );
		
		if( !empty($proConfig) )
		{
			echo json_encode($proConfig);
		}
		else	
		{
			echo '';
		}
	}

	/**
	 *	@abstrcat function will register user came to chat as chat registered user
	 */
	function chatReg()
	{
		$returnArr = array();
		$chat_id = 0;
		$this->form_validation->set_rules('chat_email_in','Email','trim|required|valid_email');
		if( $this->session->userdata('chat_id') === FALSE )
			$this->form_validation->set_rules('chat_phoneno_in','Mobile','trim|required|numeric');
		
		if( (int)$this->session->userdata('customer_id') == 0 && $this->form_validation->run() == FALSE)
		{
			$returnArr['type'] = 'error';
			$returnArr['error'] = $this->form_validation->get_errors();
		}
		else
		{
			$data = $this->input->post();
			$returnArr['sessions_id'] = $this->session->userdata('sessions_id');
			
			$where = '';
			if( (int)$this->session->userdata('customer_id') == 0 )
			{
				$where = " customer_emailid='".$data['chat_email_in']."' ";
			}
			else	//if user logged in
			{
				$where = " customer_id=".(int)$this->session->userdata('customer_id')." ";	
			}
			
			$resCust = executeQuery(" SELECT * FROM customer WHERE ".$where);
			if( empty($resCust) )
			{
				if( !isset( $data['chat_name_in'] ) || empty( $data['chat_name_in'] ) )
				{
					 $data['chat_name_in'] = 'Guest';
				}
				
				$customer_id = emailListAndReg( 'C', $data['chat_name_in'], $data['chat_email_in'], $data['chat_phoneno_in']);
				setLoginSessions( $customer_id, 'C', $data['chat_email_in']);
				if( !empty($customer_id) )
				{
					$chat_id = $this->initilizeUserChat( $data, $customer_id, $data['chat_name_in'], $data['chat_message_in']);
					$returnArr['customer_id'] = $customer_id;
					$returnArr['customer_firstname'] = $data['chat_name_in'];
				}
			}
			else
			{
				//update user information if available 
				$dataUser = array();
				if( !empty($data['chat_name_in']) )
				{
					$resCust[0]['customer_firstname'] = $dataUser['customer_firstname'] = $data['chat_name_in'];
				}
				if( !empty($data['chat_phoneno_in']) )
				{
					$resCust[0]['customer_phoneno'] = $dataUser['customer_phoneno'] = $data['chat_phoneno_in'];
				}
				
				if( !empty($dataUser) )
				{
					$this->db->where("customer_id", $resCust[0]['customer_id'])->update( "customer", $dataUser);
				}

				//set session if user is not logged in
				if( (int)$this->session->userdata('customer_id') == 0 )
				{
					setLoginSessions( $resCust[0]['customer_id'], 'C', $resCust[0]['customer_emailid']);
				}
	
				$chat_id = $this->initilizeUserChat( $data, $resCust[0]['customer_id'], $resCust[0]['customer_firstname'], $data['chat_message_in']);
				$returnArr['customer_id'] = $resCust[0]['customer_id'];
				$returnArr['customer_firstname'] = $resCust[0]['customer_firstname'];
			}
			
			//if chat started right now then send chat configuration variables to browser 
			$returnArr = getChatHistory( $chat_id );
			$returnArr['type'] = 'success';
			$returnArr['chat_message_in'] = $data['chat_message_in'];

			//auto responder
			if( $returnArr['abstract_proactive_chat_invitation_id'] == 0 )
			{
				$resResp = getAutoResponder( $data['pos_id'] );
				$returnArr['auto_r'] = 0;
				if( !empty($resResp) )
				{
					$returnArr['auto_r'] = 1;
					$returnArr['aar_wait_message'] = $resResp[0]['aar_wait_message'];
					$returnArr['aar_wait_timeout'] = $resResp[0]['aar_wait_timeout'] * 1000;
					$returnArr['aar_timeout_message'] = $resResp[0]['aar_timeout_message'];
					
					//record msg
					chatMsg( $chat_id, 0, 'A', $returnArr['aar_wait_message']);	
				}
			}
			
		}
		echo json_encode( $returnArr );				
 	}

	/**
	 *	@abstrcat function has requested to not start pro chat when he/she navigates site 
	 */
	function noProChat()
	{
		$returnArr = array();
		$this->db->insert( "customer_interaction", array( 'customer_id'=> $this->session->userdata('customer_id'), 'sessions_id'=>$this->session->userdata('sessions_id'), 
						   'ci_ip'=>$_SERVER['REMOTE_ADDR'], 'ci_interaction_type'=>'NO_P_CHAT')); 

		$this->session->set_userdata( array( 'chat_id'=> FALSE ) );

		$returnArr['type'] = 'success';
		echo json_encode( $returnArr );
	}

	/**
	 *	@abstrcat function will insert msges between agent and user through ajax call
	 */
	function recMsg()
	{
		$data = $this->input->post();
		chatMsg( $data['chat_id'], $data['id'], $data['type'], $data['msg']);
		echo '';
	}

	/**
	 *	@abstrcat function will return user info for logged in user
	 */
	function getUser()
	{
		$resCust = $this->db->query("SELECT '".CH_DEF_AGE."' as admin, CONCAT( 'Hi ', customer_firstname, ' How can we help you?' ) as msg, 'success' as type FROM customer WHERE customer_id=".(int)$this->session->userdata('customer_id')." ")->row_array();
		echo json_encode( $resCust );
	}

	/**
	 *	@abstrcat function will return user info for logged in user
	 */
	function chatMinimize()
	{
		$data = $this->input->get();
		$chatArr = $this->session->userdata('chatArr');
		$chatArr[ $data['chat_id'] ]['is_minimized'] = 1;
		$this->session->set_userdata( array ( 'chatArr'=> $chatArr ) );
		echo '';
	}

	/**
	 *	@abstrcat function will return user info for logged in user
	 */
	function chatMaximize()
	{
		$data = $this->input->get();
		$chatArr = $this->session->userdata('chatArr');
		$chatArr[ $data['chat_id'] ]['is_minimized'] = 0;
		$this->session->set_userdata( array ( 'chatArr'=> $chatArr ) );
		echo '';
	}

	/**
	 *	@abstrcat function will set user session for chat invoked by admin
	 */
	function invokeChat()
	{
		$data = $this->input->post();
		$this->session->set_userdata( array('chat_id'=>$data['chat_id']) );
		chatMsg( (int)$data['chat_id'], (int)$this->session->userdata('customer_id'), 'U', '');
		echo '';
	}

	/**
	 *	@abstrcat function will load mobile chat
	 */
	function mobileChat()
	{
		$this->load->view('elements/m_hlive_chat');
	}
	
	/**
	 *	@abstrcat function will load mobile chat
	 */
	function initilizeUserChat( $data, $customer_id, $customer_firstname, $chat_message_in)
	{
		$chat_id = 0;
		if( $this->session->userdata('chat_id') !== FALSE )
		{
			$chat_id = $this->session->userdata('chat_id');
			chatMsg( $chat_id, $customer_id, 'U', $chat_message_in);	
		}
		else
		{
			//start chat
			$chat_id = startChat( $customer_id, $this->session->userdata('sessions_id'), $this->session->userdata('curr_page'), 0, 1);
			
			//if user had started chat then check if admin is online else ask though msg to admin
			sendChatSMSToAdmin( $customer_id, $chat_id);
			
			//record msg
			chatMsg( $chat_id, $customer_id, 'U', $chat_message_in);	

		}

		return $chat_id;		
	}

	/**
	 *	@abstrcat function send offline mail to admin
	 */
	function offlineMail()
	{
		$returnArr = array();
		$this->form_validation->set_rules('chat_email_in','Email','trim|required|valid_email');
		$this->form_validation->set_rules('chat_phoneno_in','Mobile','trim|required|numeric|min_length[10]|max_length[12]');
		
		if( $this->form_validation->run() == FALSE)
		{
			$returnArr['type'] = 'error';
			$returnArr['error'] = $this->form_validation->get_errors();
		}
		else
		{
			$data = $this->input->post();
			//register if not registered
			$this->regChatUser( $data );
			
			$mailTemp = fetchKeyArr( " SELECT * FROM ch_abstract_email_template WHERE aet_status=0 ", 'aet_key');
			//mail to admin
			$mail_body = $mailTemp['OFFLINE_ADM_MAIL']['aet_email_body']. " <br><br>Name: ".$data['chat_name_in']."<br><br> Email: ".$data['chat_email_in']."<br><br> Phone: ".$data['chat_phoneno_in']."<br><br> User Msg: ".$data['chat_message_in'];
			sendMail( $mailTemp['OFFLINE_ADM_MAIL']['aet_from_email'], $mailTemp['OFFLINE_ADM_MAIL']['aet_subject'], $mail_body,
					$mailTemp['OFFLINE_ADM_MAIL']['aet_from_email'], $mailTemp['OFFLINE_ADM_MAIL']['aet_from_name']);
			$this->db->insert( "email_send_history", array('email_campaign_id'=>4, 'es_from_emails'=>$mailTemp['OFFLINE_ADM_MAIL']['aet_from_email'], 
							    'es_from_emails'=>$mailTemp['OFFLINE_ADM_MAIL']['aet_from_email'], 'es_subject'=>$mailTemp['OFFLINE_ADM_MAIL']['aet_subject'], 
								'es_message'=> $mail_body, 'es_ip_address'=>$_SERVER['REMOTE_ADDR']));		
			

			//mail to user
			$mail_body = $mailTemp['OFFLINE_USER_MAIL']['aet_email_body']. "<br><br>Your Msg: ".$data['chat_message_in'];
			sendMail( $data['chat_email_in'], $mailTemp['OFFLINE_USER_MAIL']['aet_subject'], $mail_body,
					$mailTemp['OFFLINE_USER_MAIL']['aet_from_email'], $mailTemp['OFFLINE_USER_MAIL']['aet_from_name']);
			$this->db->insert( "email_send_history", array( 'email_campaign_id'=>5, 'es_from_emails'=>$mailTemp['OFFLINE_USER_MAIL']['aet_from_email'], 
							    'es_from_emails'=>$mailTemp['OFFLINE_USER_MAIL']['aet_from_email'], 'es_subject'=>$mailTemp['OFFLINE_USER_MAIL']['aet_subject'], 
								'es_message'=> $mail_body, 'es_ip_address'=>$_SERVER['REMOTE_ADDR']));		
			
			$returnArr['type'] = 'success';
			$returnArr['msg'] = '<span style="color:green;">Thank you for contacting us. Our support executive will revert back to you soon.</span>';
		}
		echo json_encode( $returnArr );				
 	}
	
	/**
	 * @abstract register user came to chat right now only used when OFFLine 
	 */
	function regChatUser( $data )
	{
		$where = '';
		if( (int)$this->session->userdata('customer_id') == 0 )
		{
			$where = " customer_emailid='".$data['chat_email_in']."' ";
		}
		else	//if user logged in
		{
			$where = " customer_id=".(int)$this->session->userdata('customer_id')." ";	
		}
		
		$resCust = executeQuery(" SELECT * FROM customer WHERE ".$where);
		if( empty($resCust) )
		{
			if( !isset( $data['chat_name_in'] ) || empty( $data['chat_name_in'] ) )
			{
				 $data['chat_name_in'] = 'Guest';
			}
			
			$customer_id = emailListAndReg( 'C', $data['chat_name_in'], $data['chat_email_in'], $data['chat_phoneno_in']);
			setLoginSessions( $customer_id, 'C', $data['chat_email_in']);
			if( !empty($customer_id) )
			{
				return $customer_id;
			}
		}
		else
		{
			//update user information if available 
			$dataUser = array();
			if( !empty($data['chat_name_in']) )
			{
				$resCust[0]['customer_firstname'] = $dataUser['customer_firstname'] = $data['chat_name_in'];
			}
			if( !empty($data['chat_phoneno_in']) )
			{
				$resCust[0]['customer_phoneno'] = $dataUser['customer_phoneno'] = $data['chat_phoneno_in'];
			}
			
			if( !empty($dataUser) )
			{
				$this->db->where("customer_id", $resCust[0]['customer_id'])->update( "customer", $dataUser);
			}
	
			//set session if user is not logged in
			if( (int)$this->session->userdata('customer_id') == 0 )
			{
				setLoginSessions( $resCust[0]['customer_id'], 'C', $resCust[0]['customer_emailid']);
			}
	
			return $resCust[0]['customer_id'];	
		}
	}
	/**
	 * Function will find and replace for " 's and append string from database"
	 */
	function commonCharacterReplace()
	{
		characterReplace("product_categories","meta_keyword","category_id");
	}
	/**
	 * Function will find and replace for " India to australia from database"
	 */
	function commonStringReplace()
	{
		//stringReplaceCommon("article_cctld","custom_page_title","article_cctld_id","India","Australia/NZ");
	}
	/**
	 * @abstract tell admin that there is new chat
	 */
	function adminNewChat( )
	{
		echo '<script type="text/javascript"> alert(\'Dear admin one user joined the chat.\'); </script>';
	}
	
	function mHome()
	{
		$this->load->view('mobile/home');
	}
	
	function ebayHtmlPage()
	{
		$this->load->view('templates/ebay-html-page');
	}
	
	/**
	 * @author Cloudwebs
	 * @abstract flip ccTLDs
	 */	
	function change_country()
	{
		$ccTLD = strtoupper( $this->input->get( 'country' ) );
		if( !empty( $ccTLD ) )
		{
			$manRes = exeQuery( " SELECT manufacturer_id, manufacturer_cctld FROM manufacturer WHERE manufacturer_key='".$ccTLD."' " );
			if( !empty($manRes) )
			{
				$this->session->set_userdata( array( 'MANUFACTURER_ID'=> $manRes['manufacturer_id'] ) );
				flip_ccTld( $manRes['manufacturer_cctld'], $ccTLD );
			}
		}
	}
	
	function amazonSESMail()
	{
		$toEmail = 'perrianstore@gmail.com';
		$subject = 'Invitation for JCK Las Vegas Show 2014';
		$mail_body = $this->load->view('templates/invitation-card', '', TRUE);
		
		//$this->amazonSesEmail($toEmail,$subject,$mail_body);
	}
	
	function amazonSesEmail($to, $subject, $message)
	{
		$fromEmail = 'info@Stationery.com';
		
		
		require_once APPPATH.'libraries/s3/sdk.class.php';
		
		$amazonSes = new AmazonSES();
		$response = $amazonSes->send_email($fromEmail,
			array('ToAddresses' => array($to)),
			array(
				'Subject.Data' => $subject,
				'Body.Html.Data' => $message,
			)
		);
		if (!$response->isOK())
		{
			echo "Mailer Error: " . $mail->ErrorInfo; 
			die;
		}
		else
		{
			echo "Message sent!";
			die;
		}		
	}
		
	/**
	 * 
	 */
	function aboutUs()
	{
		$data['pageName'] = 'about-us';
		$this->load->view('site-layout',$data);
	}
	/**
	 * 
	 */
	function dro()
	{
		$data['pageName'] = 'dro';
		$this->load->view('site-layout',$data);
	}
	/**
	 * 
	 */
	function contactUs()
	{
		$data['pageName'] = 'contact-us';
		$this->load->view('site-layout',$data);
	}
	
	/**
	 * 
	 */
	function privacyPolicy()
	{
		$data['pageName'] = 'privacy-policy';
		$this->load->view('site-layout',$data);
	}
	
	/**
	 * 
	 */
	function termsConditions()
	{
		$data['pageName'] = 'terms-conditions';
		$this->load->view('site-layout',$data);
	}
	
	/**
	 * 
	 */
	function returnPolicy()
	{
		$data['pageName'] = 'return-policy';
		$this->load->view('site-layout',$data);
	}
	
	/**
	 * 
	 */
	function faqs()
	{
		$data['pageName'] = 'faqs';
		$this->load->view('site-layout',$data);
	}
	/**
	 * 
	 */
	function productDetailsTest()
	{
		$data['pageName'] = 'products-details';
		$this->load->view('site-layout',$data);
	}
	
	function productListTest()
	{
		$data['pageName'] = 'products_list';
		$this->load->view('site-layout',$data);
	}
	
	/**
	 * 
	 */
	function testNewsletterSubscribe()
	{
		$data['email_address'] = "hitesh.mscit@gmail.com";
		$mail_body = $this->load->view('templates/newsletter-subscribe','');
		$mail_body .= $this->load->view('templates/footer-template',array('email_list_id'=>$data['email_list_id'],'email_id'=>$data['email_address']));
	}
	
	//Review Form popup
	public function review()
	{
		cmn_vw_review(); 
	}
	
	function facebook_login()
	{
		$this->session->set_userdata( array( "FB_HTTP_REFERER" => $_SERVER["HTTP_REFERER"] ) ); 
		$data['pageName'] = 'elements/login_fb';
		$this->load->view( 'site-layout', $data); 
	}
	
	
	/**
	 * invite friend and get discount.
	 */
	public function inviteFriend()
	{
		
		cmn_vw_invitefriend();
// 		$this->form_validation->set_rules('customer_partner_id','Email ID','trim|required|valid_email');
// 		$this->form_validation->set_rules('customer_note','Tell Massage','trim|required');
// 		if($this->form_validation->run() == FALSE)
// 		{
// 			$data['type'] = "error";
// 			$data['msg'] = "";
// 			$data["error"] = $this->form_validation->get_errors();
// 			echo json_encode($data);
// 		}
// 		else
// 		{
// 			$this->hom->inviteFriend();
// 			$data['type'] = "success";
// 			$data['msg'] = getLangMsg("invfr");
// 			echo json_encode($data);
// 		}
	}
	
	/**
	 * write on 28-Apr-2015
	 * to invited friend accept requist.
	 */
	function invitedFriends()
	{
		$ref_c_code = $this->input->get("ref");
		recordReferralLandingCode($ref_c_code);
		redirect( "register" );
	}	

	/**
	 * Added on 04-06-2015
	 * catches crash log from Apps
	 */
	function crash_log()
	{
// 		$data["dataPost"] = $this->input->post();
	
// 		/**
// 		 * turn of email when bugzilla RPC call is implemented
// 		*/
// // 		errorLog( "ALC_".$data["dataPost"]["package_name"]."_V_".$data["dataPost"]["package_version"], " From Version: ". getSysConfig( "APP_VER" ) . " \n\n<br><br> " . $data["dataPost"]["stacktrace"], true );
	
// 		/**
// 		 * do RPC to bugzilla domain on added 06-04-2016
// 		 * Create function to add caught PHP/javascript bug to bugzilla by doing RPC call
//  		 * $summary = Bug name, $op_sys = Windows, $comment = display error log, $rep_platform = PC
// 		 */
// 		//rest api set
// 		bugzilla_RPC_Call( "ALC_".$data["dataPost"]["package_name"]."_V_".$data["dataPost"]["package_version"], "", $data["dataPost"]["stacktrace"], "" );

		$data = $this->input->post();
		if( isEmptyArr( $data ) )
		{
			$data = $this->input->get();
		}
		
		/**
		 * do RPC to bugzilla domain on added 06-04-2016
		 * Create function to add caught PHP/javascript bug to bugzilla by doing RPC call
		 * $summary = Bug name, $op_sys = Windows, $comment = display error log, $rep_platform = PC
		 */
// 		$errorIPLocation = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
// 							<h3>Client side IP geolocation using <a href="http://ipinfo.io">ipinfo.io</a></h3>';

// 		$errorIPLocation = "";
// 		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
// 			$errorIPLocation = $_SERVER['HTTP_CLIENT_IP'];
// 		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
// 			$errorIPLocation = $_SERVER['HTTP_X_FORWARDED_FOR'];
// 		} else {
// 			$errorIPLocation = $_SERVER['REMOTE_ADDR'];
// 		}
		
		$on_url = site_url(he_url_hlp_reqUri());
		$traceError = "<br>Client side IP geolocation: ".date("Y-m-d H:i:s")." on ".$on_url." from ip ".$CI->input->ip_address()." sessions_id ".$CI->session->userdata("sessions_id")." <br>\n";
		
		$stacktrace = $data["stacktrace"];
		$stacktrace .= $traceError;
// 		$stacktrace .= "<br>Client side IP geolocation: <b>".$errorIPLocation."</b>";
		
		$comment = $data['comment'];
		$comment .= $traceError;
// 		$comment .= "<br>Client side IP geolocation: <b>".$errorIPLocation."</b>";
				
		if( is_restClient() )
		{
			bugzilla_RPC_Call( "ALC_".$data["package_name"]."_V_".$data["package_version"], "RESTApps", $stacktrace, "RESTApps" );
		}
		else
		{
			bugzilla_RPC_Call( $data["summary"]."_Version: 2.1.2", "WebApp", $comment, "WebApp" );
		}
		
		/**
		 * turn of email when bugzilla RPC call is implemented
		 * and synced properly with email notifications
		 */
		if( is_restClient() )
		{
			errorLog( "ALC_".$data["package_name"]."_V_".$data["package_version"], " From Phone model: ". $data["phone_model"] . " \n\n<br><br>Android version: ". $data["android_version"] . " \n\n<br><br>App Version: ". getSysConfig( "APP_VER" ) . " \n\n<br><br> " . $stacktrace, true, true, true, ",dhaval.kakadiya52@gmail.com;Cloudwebstechnology@gmail.com" );
		}
		else
		{
			errorLog( $data["summary"]."_Version: 2.1.2", " From Version: WebApp 2.1.2 \n\n<br><br>".$comment."\n\n<br><br>", true, true, true );
		}
		
	}
}