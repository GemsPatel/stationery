<?php
class mdl_report_reffer_bonus extends CI_Model
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
			
			$order_id_filter = $this->input->get('order_id_filter');
			$from_member = $this->input->get('from_member');//invoice
			$customer_emailid_filter = $this->input->get('customer_emailid_filter');//gender
			$to_range_pr = $this->input->get('to_range_pr');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
			if(isset($order_id_filter) && $order_id_filter != "")
				$this->db->where('orders.order_id LIKE \''.$order_id_filter.'%\' ');
			if(isset($customer_emailid_filter) && $customer_emailid_filter != "")
				$this->db->where('customer.customer_emailid LIKE \''.$customer_emailid_filter.'%\'');
			if(isset($from_member) && $from_member != "")
					$this->db->where('customer.customer_firstname LIKE \''.$from_member.'%\' ');
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(orders.order_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			if(isset($to_range_pr) && $to_range_pr != "")
					$this->db->where('affiliate_campaign.c_discount_amt LIKE \''.$to_range_pr.'%\' ');
			
						
			if($f !='' && $s != '')
			{
				if($f=='order_total_amt')
					$this->db->order_by("(order_total_amt*1)",$s);
				else
					$this->db->order_by($f,$s);
			}
			else
				$this->db->order_by('orders.order_id','DESC');
			
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
			
		
			$this->db->select('orders.order_id,orders.customer_id,
							   orders.order_id,orders.affiliate_campaign_id,
							   orders.order_total_amt,orders.order_created_date,
							   customer.customer_firstname,customer.customer_id,
							   customer.customer_lastname,customer.customer_emailid, 
							   affiliate_campaign.customer_partner_id,affiliate_campaign.c_discount_amt ');
			$this->db->join('customer','customer.customer_id=orders.customer_id');
			$this->db->join('affiliate_campaign','affiliate_campaign.affiliate_campaign_id=orders.affiliate_campaign_id');
				
			//$this->db->group_by('order_id');
			$res = $this->db->get($this->cTableName);
			//echo $this->db->last_query();
		
		return $res;
		
	}
	/*
+----------------------------------------------------------+
	Function will get all users details.
+----------------------------------------------------------+
*/		
	function getCustomerDetails()
	{
		$userId = _de($this->input->get('item_id'));
		$this->db->select('customer_firstname,customer_lastname,customer_emailid,customer_phoneno,customer_gender,customer_group_id,customer_created_date,customer_modified_date');
		$userArr = $this->db->where('customer_id',$userId)->get('customer')->row_array();
		
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
	}
	
	
	
     function getFullDetails() 
	 {
		 	$userId = _de($this->input->get('item_id'));
			if(@$userId)
			{
				$this->db->select('orders.order_id,customer.customer_firstname,
								   orders.customer_id,orders.order_id,
								   orders.order_created_date,orders.order_created_date');		
			
				$this->db->join('customer','customer.customer_id=orders.customer_id');
				$this->db->join('order_details','order_details.order_id=orders.order_id');
				$userArr = $this->db->where('orders.order_id',$userId)->get($this->cTableName)->result_array();
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
		
	 }
	 
  }


}