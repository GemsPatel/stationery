<?php
class mdl_report_customer_account extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cCategory = '';
	var $cPrimaryId ='';
	
	function getData()
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			
			$customer_name = $this->input->get('customer_name_filter');
			$customer_emailid = $this->input->get('customer_emailid_filter');
			$customer_phoneno = $this->input->get('customer_phoneno_filter');
			$customer_group_id=$this->input->get('customer_group_id');
			$ipaddress = $this->input->get('ipaddress_filter');
			$fromDate = $this->input->get('fromDate');
			$toDate =$this->input->get('toDate');
			$from_range_cr = $this->input->get('from_range_cr');
			$to_range_cr =$this->input->get('to_range_cr');
			$from_range_db = $this->input->get('from_range_db');
			$to_range_db =$this->input->get('to_range_db');
			$from_range_bal = $this->input->get('from_range_bal');
			$to_range_bal =$this->input->get('to_range_bal');
		
			if(isset($customer_name) && $customer_name != "")
				$this->db->where('customer_firstname LIKE \''.$customer_name.'%\' or customer_lastname LIKE \''.$customer_name.'%\'' );
			if(isset($customer_phoneno) && $customer_phoneno != "")
				$this->db->where('customer_phoneno LIKE \''.$customer_phoneno.'%\'' );
			if(isset($customer_emailid) && $customer_emailid != "")
				$this->db->where('customer_emailid LIKE \''.$customer_emailid.'%\'' );	
			if(isset($customer_group_id) && $customer_group_id != "")
				$this->db->where('customer_group.customer_group_id LIKE \''.$customer_group_id.'%\'' );			
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(customer_account_manage.customer_account_manage_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			if(!empty($from_range_cr) && !empty($to_range_cr))
				$this->db->where('(customer_account_manage.customer_account_manage_credit*1) BETWEEN '.$from_range_cr.' and '.$to_range_cr.'');
			if(!empty($from_range_db) && !empty($to_range_db))
				$this->db->where('(customer_account_manage.customer_account_manage_debit*1) BETWEEN '.$from_range_db.' and '.$to_range_db.'');
			if(!empty($from_range_bal) && !empty($to_range_bal))
				$this->db->where('(customer_account_manage.customer_account_manage_balance*1) BETWEEN '.$from_range_bal.' and '.$to_range_bal.'');		
			if($f !='' && $s != '')
			{
				if($f=='customer_account_manage_credit')
					$this->db->order_by("(customer_account_manage.customer_account_manage_credit*1)",$s);
				if($f=='customer_account_manage_debit')
					$this->db->order_by("(customer_account_manage.customer_account_manage_debit*1)",$s);
				if($f=='customer_account_manage_balance')
					$this->db->order_by("(customer_account_manage.customer_account_manage_balance*1)",$s);	
				else
					$this->db->order_by($f,$s);
			}
			else
				$this->db->order_by('customer.customer_id','DESC');
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
		$this->db->select('customer.customer_id,customer.customer_firstname,customer.customer_lastname,customer.customer_emailid,customer.customer_phoneno,customer.customer_group_id,customer_group.customer_group_name,customer_account_manage.customer_account_manage_credit,customer_account_manage.customer_account_manage_debit,customer_account_manage.customer_account_manage_balance,customer_account_manage.customer_account_manage_created_date,customer_account_manage.customer_account_manage_id');
		$this->db->join('customer','customer.customer_id=customer_account_manage.customer_id');
		$this->db->join('customer_group','customer_group.customer_group_id=customer.customer_group_id');
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
	function getCustomerDetails()
	{
		$userId = _de($this->input->get('item_id'));
		$this->db->select('customer_firstname,customer_lastname,customer_gender,customer_emailid,customer_phoneno,customer_group_id,customer_created_date,customer_modified_date');
		$userArr = $this->db->where('customer_id',$userId)->get('customer')->row_array();
		
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
	}
	
	


}