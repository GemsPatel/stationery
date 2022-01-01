<?php
class mdl_checkout extends CI_Model
{
	var $customer_id =0;

/*
 * @author   Cloudwebs
 * @abstract function will get customer data if customer logged in
 */
function getCustomerData()
{
	
	return cart_hlp_getCustomerData( $this->customer_id );
}
	
/*
 * @author   Cloudwebs
 * @abstract function will register user as guest
 */
function guestSignup()
{
	
	
	$resArr = array();
	$first_name = 'Guest';
	$last_name = 'User';

	$customer_emailid = $this->input->post('login_email');
	//Change: date 23/10/2013 guest has given full access now user type id: U
	////Change: date 01/05/2015 again changed to gauest: G
	$customer_group_type = "G"; 
	$res = executeQuery("SELECT customer_group_id FROM customer_group WHERE customer_group_type='".$customer_group_type."'");	
	if(!empty($res))
	{
		$email_list_id = saveEmailList($customer_emailid, 1, 'N', 'GUEST_USER', 10); //save email_list table
		
		$pass = rand(1, 100000000);
		$customer_password = md5($pass.$this->config->item('encryption_key'));
		
		//saved to customer table
		$customerArr = array(
			'customer_firstname' => @$first_name,
			'customer_lastname' => @$last_name,
			'email_list_id' => @$email_list_id,
			'customer_emailid' => @$customer_emailid,
			'customer_group_id' => @$res[0]['customer_group_id'],
			'customer_password' => $customer_password,
			'customer_ip_address' => @$this->input->ip_address(),
			'customer_status' => 0
		);
		$customer_id = saveCustomer($customer_emailid, $customerArr);
		
		/*$this->db->query("INSERT INTO customer(customer_firstname, customer_lastname, customer_group_id, email_list_id, customer_emailid, customer_password, customer_ip_address, customer_status) values('".$first_name."','".$last_name."',".$res[0]['customer_group_id'].", '".$email_list_id."', '".$customer_emailid."', '".$customer_password."','".$this->input->ip_address()."', 0)");

		$customer_id = $this->db->insert_id();*/

		$resArr['type'] = 'success';
		$resArr['msg'] = 'Guest registered successfully.';
		$resArr['customer_id'] = $customer_id;
		$resArr['customer_group_type'] = $customer_group_type;
		$resArr['customer_emailid'] = $customer_emailid;
		
		//send mail
		$data['first_name'] = $first_name;
		$data['last_name'] = $last_name;
		$data['email_address'] = $customer_emailid;
		$data['text_password'] = $pass;
		$data['login_link'] = base_url('login');
		getTemplateDetailAndSendMail('GUEST_PASSWORD_EMAIL',$data);	
		
	}
	else
	{
		$resArr['type'] = 'error';
		$resArr['msg'] = 'Something wrong happen.';
	}

	return $resArr;
}
	
	
	
/*
 * @author   Cloudwebs
 * @abstract function will save edited address of user
 */
function editAddress()
{
	$resArr = array();
	$customer_address_id_shipp = $this->input->post('customer_address_id_shipp');

	$this->save_updAddress('shipp',$customer_address_id_shipp);	
	
	return array('type'=>'success','msg'=>'Address updated sucessfully.');
}

/*
 * @author   Cloudwebs
 * @abstract function will save shipp/bill adresses if required and proceed to next payment info
 * @param if $customer_address_id is 0 then save else edit
 */
function save_updAddress($type,$customer_address_id=0)
{
	
	$data['customer_id'] = $this->customer_id;
	$data['customer_address_firstname'] = $this->input->post('customer_address_firstname_'.$type);
	$data['customer_address_lastname'] = $this->input->post('customer_address_lastname_'.$type);
	$data['customer_address_phone_no'] = $this->input->post('customer_address_phone_no_'.$type);
	//$data['ca_phone_no_2'] = $this->input->post('ca_phone_no_2_'.$type);
	//$data['fax'] = $this->input->post('fax_'.$type);
	$data['customer_address_address'] = $this->input->post('customer_address_address_'.$type);
	//$data['ca_row_2'] = $this->input->post('ca_row_2_'.$type);
	$data['customer_address_landmark_area'] =  $this->input->post('customer_address_landmark_area_'.$type);
	$data['customer_address_company'] = $this->input->post('customer_address_company_'.$type);
	$data['customer_address_city'] = $this->input->post('customer_address_city_'.$type);
	
	$dataPin['address_city'] =  $this->input->post('address_city_'.$type);
	$dataPin['pincode'] =  $this->input->post('pincode_'.$type);
	$dataPin['customer_address_landmark_area'] =  $this->input->post('customer_address_landmark_area_'.$type);
	$dataPin['state_id'] =  $this->input->post('state_id_'.$type);
	$data['customer_address_zipcode'] = getPincodeId( $dataPin );

	$data['country_id'] = $this->input->post('country_'.$type);
	$data['customer_address_state_id'] = $this->input->post('state_id_'.$type);
	$data['customer_address_is_default'] = $this->input->post('customer_address_is_default_'.$type);
	
	
	if($customer_address_id==0)	//insert
	{
		$data['customer_address_id'] = $customer_address_id;
		$this->db->insert("customer_address",$data);
		return $this->db->insert_id();
	}
	else							//update
	{
		$this->db->where('customer_address_id',$customer_address_id)->update("customer_address",$data);
	}
}

/*
 * @author   Cloudwebs
 * @abstract function will save shipp/bill adresses if required and proceed to next payment info
 */
function applyShipInfo()
{
	
	$resArr = array();
	$customer_address_id_shipp = $this->input->post('customer_address_id_shipp');
	//$edit_shipp = $this->input->post('edit_shipp');
	$customer_address_id_bill = $this->input->post('customer_address_id_bill');
	//$edit_bill = $this->input->post('edit_bill');
	$same_as_billing_address = $this->input->post('same_as_billing_address');

	if($customer_address_id_shipp==0)	//save if id is 0
	{
		$customer_address_id_shipp = $this->save_updAddress('shipp');	
	}

	if((int)$same_as_billing_address!=1)
	{
		if($customer_address_id_bill==0)	//save if id is 0		//no edit mode beacause all addresses can be edited in shipping addresses
		{
			$customer_address_id_bill = $this->save_updAddress('bill');	
		}
	}
	else
	{
		$customer_address_id_bill = $customer_address_id_shipp;	
	}
	
	//set session variables
	$arr =  array('customer_shipping_address_id'=>$customer_address_id_shipp,'customer_billing_address_id'=>$customer_address_id_bill);
	$this->session->set_userdata($arr);
	
	return array('type'=>'success','msg'=>'Shipping information saved sucessfully.');
}
	
}