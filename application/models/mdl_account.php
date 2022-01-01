<?php
class mdl_account extends CI_Model
{
	var $cTable = '';
	var $cAutoId = '';
	var $is_ajax = false;
/**
 * @author Cloudwebs
 * @abstract function will fetch and display order details 
 *	
 */
	function disOrderDetails($id)
	{
		return orderEmail( $id, '', '', '', 0, true ); 
	}
	
/*
+-----------------------------------------+
	Fecth addresses of user
+-----------------------------------------+
*/	
	function addressBook()
	{
		return $this->db->query('SELECT * FROM customer_address WHERE customer_id='.$this->customerId.'');
	}
/*
+-----------------------------------------+
	Deletes addresses of user
+-----------------------------------------+
*/	
	function deleteAddress()
	{
		$resArr = array();
		if((int)$this->customerId==0)	//check for security reason if customer is logged in else redirect to login page
		{
			if($this->is_ajax)
			{
				$resArr = array('type'=>'error','msg'=>'Error: You are not logged in please <a href="'.site_url('login').'">login</a> first.');
			}
			else
			{
				setFlashMessage('error','Error: You are not logged in please login first.');
				redirect('login');
				exit;
			}
		}
		else
		{
			$customer_id = $this->input->post('id');
			$customer_address_id = $this->input->post('add_id');
			
			$tabNameArr = array('0'=>'orders','1'=>'orders');
			$fieldNameArr = array('0'=>'customer_shipping_address_id','1'=>'customer_billing_address_id');
			$res1=isFieldIdExist($tabNameArr,$fieldNameArr,$customer_address_id,true);
			if(sizeof($res1)>0)
			{
					return $res1;
			}
			else
			{
				$res = $this->db->query('SELECT COUNT(customer_address_id) AS \'Count\' FROM customer_address WHERE customer_id='.$customer_id.' AND customer_address_id='.$customer_address_id.'')->row_array();	
			
				if(isset($res) && $res['Count']>=1)
				{
					$this->db->query('DELETE FROM customer_address WHERE customer_address_id='.$customer_address_id.'');
					$resArr = array('type'=>'success','msg'=>'Success: Address deleted successfully.');
				}
				else
				{
					$resArr = array('type'=>'error','msg'=>'Error: The address you are attempting to delete is not available.');
				}
			}
		}
		return ($resArr);
	}
	
/*
+-----------------------------------------+
	Display addresses of user
+-----------------------------------------+
*/	
	function displayAddress()
	{
		$segArr = $this->uri->segment_array();
		if(end($segArr)=='add-address')
		{
			//Return mode => add if mode is add new address.
			return array('mode'=>'add');
		}
	
		if((int)$this->customerId==0)	//check for security reason if customer is logged in else redirect to login page
		{
			if($this->is_ajax)
			{
				setFlashMessage('error','Error: You are not logged in please <a href="'.site_url('login').'">login</a> first.');
				return false;
			}
			else
			{
				setFlashMessage('error','Error: You are not logged in please login first.');
				redirect('login');
				exit;
			}
		}
		else
		{
			$customer_address_id = _de($this->input->get('add_id'));
			if((int)$customer_address_id!=0)
			{
				$res = $this->db->query('SELECT COUNT(customer_address_id) AS \'Count\' FROM customer_address WHERE customer_id='.$this->customerId.' AND customer_address_id='.$customer_address_id.'')->row_array();	
				
				if(isset($res) && $res['Count']>=1)
				{
					$data = getAddress( $customer_address_id );
					$data['mode'] = 'edit';
					return $data; 
				}
				else
				{
					setFlashMessage('error','Error: Address is not available.');
					return false;
				}
			}
			else
			{
				setFlashMessage('error','Error: Address is not available.');
				return false;
			}
		}
	}
	
/*
+-----------------------------------------+
	Save addresses of user
+-----------------------------------------+
*/	
	function saveAddress()
	{
		if((int)$this->customerId==0)	//check for security reason if customer is logged in else redirect to login page
		{
			if($this->is_ajax)
			{
				if( is_restClient() )
				{
					setFlashMessage('error','Error: You are not logged in please login first.');
				
					rest_redirect("login",""); 
					$data["type"] = "_redirect";
					return $data;
				}
				else 
				{
					return array('type'=>'error','msg'=>'Error: You are not logged in please <a href="'.site_url('login').'">login</a> first.');
				}
			}
			else
			{
				setFlashMessage('error','Error: You are not logged in please login first.');
				
				if( is_restClient() )
				{
					rest_redirect("login",""); 
					
					$data["type"] = "_redirect";
					return $data;
				}
				else 
				{
					redirect('login');
					exit(1);
				}
				
			}
		}
		else
		{
			$data = $this->input->post();
			
			$customer_address_id = _de($data['customer_address_id']);
			
			$data['customer_address_zipcode'] = getPincodeId( $data );
			unset($data['customer_address_id']);
			unset($data['country_id']);
			unset($data['state_id']);
			unset($data['address_city']);
			unset($data['pincode']);

			$data['customer_id']  = $this->customerId; 

			if($customer_address_id!=0)	///edit mode
			{
				$res = $this->db->query('SELECT COUNT(customer_address_id) AS \'Count\' FROM customer_address WHERE customer_id='.$this->customerId.' AND customer_address_id='.$customer_address_id.'')->row_array();

				if(isset($res) && $res['Count']>=1)
				{
					$this->db->where('customer_address_id',$customer_address_id)->update('customer_address',$data); 
					
					setFlashMessage('success','Success: Address updated successfully.');
					
					if( is_restClient() )
					{
						rest_redirect("address_book","");
							
						$data["type"] = "_redirect";
						return $data;
					}
					else 
					{
						return true;
					}
				}
				else
				{
					setFlashMessage('error','Error: Address is not available.');
					
					if( is_restClient() )
					{
						rest_redirect("address_book","");
							
						$data["type"] = "_redirect";
						return $data;
					}
					else 
					{
						return true;
					}
				}
			}
			else
			{
				$this->db->insert('customer_address',$data);
				setFlashMessage('success','Success: Address inserted successfully.');

				if( is_restClient() )
				{
					rest_redirect("address_book","");
						
					$data["type"] = "_redirect";
					return $data;
				}
				else 
				{
					return true;
				}
			}
		}
	}
	
/*
+--------------------------------------------------+
	Collect Customer account information
+--------------------------------------------------+
*/		
	function getAccountInfo()
	{
		//return $this->db->where($this->cAutoId,$this->customerId)->where('customer_status','0')->get($this->cTable)->row_array();
		return $this->db->where($this->cAutoId,$this->customerId)->get($this->cTable)->row_array();
	}	
	
/*
+--------------------------------------------------+
	Register a new account with customer
+--------------------------------------------------+
*/	
	function saveEditedAccountInfo()
	{
		$data = $this->input->post();
		
		/*$data['customer_dob'] = $data['birthday_year'].'-'.$data['birthday_month'].'-'.$data['birthday_day'];
		$data['customer_anni_date'] = $data['anniversary_year'].'-'.$data['anniversary_month'].'-'.$data['anniversary_day']; */
		
		
		unset($data['customer_email']);
		/*unset($data['birthday_year']);unset($data['birthday_month']);unset($data['birthday_day']);
		unset($data['anniversary_year']);unset($data['anniversary_month']);unset($data['anniversary_day']); */
		
		if( isset( $_POST['change_password'] ) && $_POST['change_password'] == 1 )
		{
			$newPass = md5($this->input->post('password').$this->config->item('encryption_key'));
			$data['customer_password'] = $newPass;
		}
		
		unset($data['current_password']);
		unset($data['password']);
		unset($data['confirmation']);
		
		$this->db->where($this->cAutoId,$this->customerId)->update($this->cTable,$data);
		
	}
	
/*
+--------------------------------------------------+
	Function will reset the customer password and save 
	it in database..
+--------------------------------------------------+
*/	
	function saveChangedPassword()
	{
		$newPass = md5($this->input->post('new_password').$this->config->item('encryption_key'));
		$this->db->where($this->cAutoId,$this->customerId)->update($this->cTable, array('customer_password'=>$newPass));
	}
	
/*
+--------------------------------------------------+
	Function will get product detail from product id.
+--------------------------------------------------+
*/	
	function getOrderDetails( $limit=-1 )
	{
		//$this->db->select('orders.*');
		$orders =  $this->db->query("SELECT SQL_CALC_FOUND_ROWS o.order_id,order_total_amt,order_discount_amount, order_created_date 
									FROM orders o 
									WHERE o.customer_id=".$this->customerId." AND del_in=0 ORDER BY o.order_id DESC
									"
									.
										(
											$limit == -1
											? 
											""		
											:
											" LIMIT ".$limit.", ". PER_PAGE_FRONT		
										)
									.
									" "); 
		
		return $orders;
	}
	
/**
  * @author Cloudwebs	
  *	@abstract Function will get all transactions of user
  *
*/	
	function getOrderTracking($order_id)
	{
		if(!empty($order_id))
		{
			$orders =  $this->db->query("SELECT o.order_id, coupon_id, order_subtotal_amt, order_discount_amount, order_total_amt, order_created_date, customer_shipping_address_id  
										FROM orders o 
										WHERE o.order_id=".$order_id." AND o.customer_id=".$this->customerId." ");
			return $orders;
		}
		else
		{
			return false;	
		}
	}
	
/**
  * @author Cloudwebs	
  *	@abstract Function will get all transactions of user
  *
*/	
	function getTransactions()
	{
		$res = $this->db->query("SELECT cam.*
								FROM customer_account_manage cam WHERE cam.customer_id=".$this->customerId." ");
		return $res;
	}
	
/*
+--------------------------------------------------+
	Function will get product detail from product id.
+--------------------------------------------------+
*/	
	function getReturnDetails()
	{
		//$this->db->select('orders.*');
		//order-return alias used is ot instead of or beacause or treated as keyword
		$orders =  $this->db->query("SELECT o.order_id,DATE_FORMAT(order_return_created_date,'%d-%m-%Y') as order_return_created_date,ot.order_status_id,
									od.product_id, od.product_generate_code, od.product_price_id, od.order_details_amt
									FROM orders o INNER JOIN order_return ot ON ot.order_id=o.order_id 
									INNER JOIN order_details od ON od.order_details_id=ot.order_details_id  
									WHERE o.customer_id=".$this->customerId." ");
		return $orders;
	}
	
/*
+--------------------------------------------------+
	Newsletter subscriber with customer
+--------------------------------------------------+
*/	
	function saveNewsletter()
	{
		$data = $this->input->post();
		$this->db->where($this->cAutoId,$this->customerId)->update($this->cTable,$data);
	}

/*
 * @author   Cloudwebs
 * @abstract function will return customer's current balance
 */
	function currentBalance()
	{
		return getCustBalance($this->customerId);
	}

// 	function inviteFriend()
// 	{
// 		$data = $this->input->post();
// 		$data['customer_id'] = $this->ma->customerId = $this->customerId = $this->session->userdata('customer_id');
// 		pr($data['customer_id']);die;
// 		$cust_email_id = fetchRow("SELECT customer_emailid FROM customer WHERE customer_id = '".$data['customer_id']."' ");
// 		$mfg = 7;
// 		$inviteArr = array(
// 				'manufacturer_id' => @$mfg,
// 				'customer_partner_id' => @$cust_email_id,
// 				'c_code' => @$this->input->ip_address()
// 		);
// 		$this->db->insert('affiliate_campaign',$inviteArr);
// 	}
	
}