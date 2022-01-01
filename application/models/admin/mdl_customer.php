<?php
class mdl_customer extends CI_Model
{
	var $cTableName = '';
	var $cTableNameA = '';
	var $cAutoId = '';
	var $cAutoIdA= '';
	var $cPrimaryId = '';
	var $cPrimaryIdA = array();
	
	function getData()
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$status_filter = $this->input->get('status_filter');
			$name_filter = $this->input->get('name_filter');
			$emailid_filter = $this->input->get('emailid_filter');
			$phoneno_filter = $this->input->get('phoneno_filter');
			$group_filter = $this->input->get('group_filter');
			$reference_filter = $this->input->get('reference_filter');
			$ip_filter = $this->input->get('ip_filter');
			$approved_filter = $this->input->get('approved_filter');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
			if(isset($name_filter) && $name_filter != "")
				$this->db->where('customer_firstname LIKE \''.$name_filter.'%\' OR customer_lastname LIKE \''.$name_filter.'%\' ');
			
			if(isset($emailid_filter) && $emailid_filter != "")
				$this->db->where('customer_emailid LIKE \'%'.$emailid_filter.'%\' ');
				
			if(isset($phoneno_filter) && $phoneno_filter != "")
				$this->db->where('customer_phoneno LIKE \''.$phoneno_filter.'%\' ');
				
			if(isset($group_filter) && $group_filter != "")
				$this->db->where('customer_group_name LIKE \''.$group_filter.'%\' ');
				
			if(isset($reference_filter) && $reference_filter != "")
				$this->db->where('el_reference_source LIKE \''.$reference_filter.'%\' ');
				
			if(isset($ip_filter) && $ip_filter != "")
				$this->db->where('customer_ip_address LIKE \''.$ip_filter.'%\' ');
				
			if(isset($approved_filter) && $approved_filter != "")
				$this->db->where('customer_approved LIKE \''.$approved_filter.'\' ');
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('customer_status LIKE \''.$status_filter.'\' ');
			
			if($fromDate && $toDate)
				$this->db->where('DATE_FORMAT('.$this->cTableName.'.customer_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			if($f !='' && $s != '' )
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoId,'DESC');
				
			if( MANUFACTURER_ID != 7 )
				$this->db->where('manufacturer_id', MANUFACTURER_ID );
				
			$this->db->join('customer_group','customer_group.customer_group_id='.$this->cTableName.'.customer_group_id');
			$this->db->join('email_list el','el.email_list_id='.$this->cTableName.'.email_list_id','LEFT');
			$res = $this->db->get($this->cTableName);
			//echo $this->db->last_query();
			return $res;
		}
		else if($this->cPrimaryId != '')
		{
			$this->db->where($this->cAutoId,$this->cPrimaryId);
			$res = $this->db->get($this->cTableName);
				
			$cus_add = $this->db->query("SELECT c.customer_address_firstname as customer_address_firstname, c.customer_address_lastname as customer_address_lastname, 
						  CONCAT(c.customer_address_firstname, ' ', c.customer_address_lastname) as customer_name, 
						  c.customer_address_address as customer_address_address, c.customer_address_landmark_area as customer_address_landmark_area, c.customer_address_company as customer_address_company, 
						  c.customer_address_phone_no as customer_address_phone_no, c.customer_address_city as customer_address_city, c.customer_address_zipcode as customer_address_zipcode, 
						  p.pincode as pincode, p.state_id as state_id, s.state_name as state_name, p.cityname as cityname, p.areaname as areaname, 
						  co.country_id as country_id, co.country_name as country_name  
						  FROM customer_address c 
						  INNER JOIN pincode p ON p.pincode_id=c.customer_address_zipcode 
						  INNER JOIN state s ON s.state_id=p.state_id  
						  INNER JOIN country co ON co.country_id=s.country_id
						  WHERE customer_id=".$this->cPrimaryId." ");
			
			return array('res'=>$res,'cus_add'=>$cus_add);
		}
					
		//echo $this->db->last_query();
		return $res;
	}
	
	function saveData()
	{
		$data['customer_firstname'] = $this->input->post('customer_firstname');
		$data['customer_lastname'] = $this->input->post('customer_lastname');
		$data['customer_group_id'] = $this->input->post('customer_group_id');
		$data['customer_emailid'] = $this->input->post('customer_emailid');
		$data['customer_phoneno'] = $this->input->post('customer_phoneno');
		$data['customer_fax'] = $this->input->post('customer_fax');
		$data['customer_newsletter'] = $this->input->post('customer_newsletter');
		$data['customer_status'] = $this->input->post('customer_status');
		$data['customer_approved'] = $this->input->post('customer_approved');
		$data['manufacturer_id'] = MANUFACTURER_ID;

/*		unset($data['item_id']);
		unset($data['item_idA']);
		unset($data['customer_confirm_password']);
*/	  
		if($this->input->post('customer_password') !='')
			$data['customer_password'] = (md5($this->input->post('customer_password').$this->config->item('encryption_key')));
		else
			$data['customer_password'] = getField('customer_password', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
		
		$cust_id = 0;
		$data['customer_ip_address'] = $_SERVER['REMOTE_ADDR'];
		
		//if primary id set then we have to make update query
		if($this->cPrimaryId != '')
		{
			$this->db->set('customer_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$cust_id = $this->cPrimaryId;
			$last_id = $this->cPrimaryId;
			$logType = 'E';
			
		}
		else // insert new row
		{
			
			$this->db->insert($this->cTableName,$data);
			$cust_id = $this->db->insert_id();
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}

		$customer_address_first_name=$this->input->post('customer_address_firstname');
		$customer_address_last_name=$this->input->post('customer_address_lastname');
		$customer_address_address=$this->input->post('customer_address_address');
		$customer_address_company=$this->input->post('customer_address_company');
		//$country_id=$this->input->post('country_id');
		//$customer_address_state_id=$this->input->post('customer_address_state_id');
		//$customer_address_city=$this->input->post('customer_address_city');
		$customer_address_landmark_area=$this->input->post('customer_address_landmark_area');
		$customer_address_zipcode=$this->input->post('pincode');
		$radio_A=$this->input->post('radio_A');
		$customer_address_is_default=$this->input->post('customer_address_is_default');	//set first address as default if no address selected
		if((int)$customer_address_is_default == 0)
		{
			$customer_address_is_default = 0;
		}

		//delete addresses if exist
		$this->db->where_in($this->cAutoId,$cust_id)->delete($this->cTableNameA);

		$cust_add= array();
		$cust_add['customer_id'] = $cust_id;
		$cust_add['customer_address_modified_date'] = 'NOW()';
		
		$size = sizeof($customer_address_first_name);
		for( $i =0; $i<$size; $i++ )
		{ 
			unset($cust_add['customer_address_is_default']);
			$cust_add['customer_address_firstname'] = $customer_address_first_name[$i];
			$cust_add['customer_address_lastname'] = $customer_address_last_name[$i];
			$cust_add['customer_address_address'] = $customer_address_address[$i];
			$cust_add['customer_address_company'] = $customer_address_company[$i];
			//$cust_add['country_id'] = $country_id[$i];
			//$cust_add['customer_address_state_id'] = $customer_address_state_id[$i];
			//$cust_add['customer_address_city'] = $customer_address_city[$i];
			
			$cust_add['customer_address_landmark_area'] = $customer_address_landmark_area[$i];
			if($cust_add['customer_id'])
			{
				$cust_add['customer_address_zipcode'] = getField('pincode_id', 'pincode', 'pincode', $customer_address_zipcode[$i]); 
			}
			
			if($customer_address_is_default==0 && (int)$customer_address_is_default == $i)
			{
				$cust_add['customer_address_is_default'] = 0;
			}
			else if($radio_A[$i] != "" && $radio_A[$i] == 0)
			{//pr($radio_A);echo $i;die;
				$cust_add['customer_address_is_default'] = 0;
			}
			
			$this->db->insert($this->cTableNameA,$cust_add);
		}

		saveAdminLog($this->router->class, @$data['customer_firstname'], $this->cTableName, $this->cAutoId, $last_id, $logType);
		setFlashMessage('success','Customer has been '.(($this->cPrimaryId != '' && $this->cPrimaryIdA != '') ? 'updated': 'inserted').' successfully.');
	}
/*
+----------------------------------------------------------+
	Deleting item. hadle both request get and post.
	with single delete and multiple delete.
	@prams : $ids -> integer or array
+----------------------------------------------------------+
*/	
	function deleteData($ids)
	{
		$returnArr = array();
		if($ids)
		{
			foreach($ids as $id)
			{
				$tabNameArr = array('0'=>'orders','1'=>'customer_account_manage','2'=>'private_message');
				$fieldNameArr = array('0'=>'customer_id','1'=>'customer_id','2'=>'customer_id');
				$res=isImageIdExist($tabNameArr,$fieldNameArr,$id);
				
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				else
				{
				
					$getName = getField('customer_firstname', $this->cTableName, $this->cAutoId, $id);
					saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
					$this->db->where_in($this->cAutoId,$id)->delete($this->cTableNameA);
					$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
					$returnArr['type'] ='success';
					$returnArr['msg'] = count($ids)." records has been deleted successfully.";
				}
			}
		
		}
		else{
			$returnArr['type'] ='error';
			$returnArr['msg'] = "Please select at least 1 item.";
		}
		echo json_encode($returnArr);
	}
/*
+-----------------------------------------+
	Update status for enabled/disabled
	@params : post array of ids, status
+-----------------------------------------+
*/	
	function updateStatus()
	{
		$status = $this->input->post('status');
		$cat_id = $this->input->post('cat_id');
		
		$data['customer_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
		//echo $this->db->last_query();
		
	}

}