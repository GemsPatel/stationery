<?php
class mdl_report_coupon extends CI_Model
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
			$coupon_code_filter = $this->input->get('coupon_code_filter');
			$customer_name_filter = $this->input->get('customer_name_filter');
			$invoice_filter = $this->input->get('invoice_filter');
			$from_range_pr = $this->input->get('from_range_pr');
			$to_range_pr = $this->input->get('to_range_pr');
			$from_range_tq = $this->input->get('from_range_tq');
			$to_range_tq = $this->input->get('to_range_tq');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
		
			if(isset($invoice_filter) && $invoice_filter != "")
					$this->db->where('orders.invoice_number LIKE \''.$invoice_filter.'%\' ');
			if(isset($coupon_code_filter) && $coupon_code_filter != "")
				$this->db->where('coupon_code LIKE \''.$coupon_code_filter.'%\' ');
			if(isset($customer_name_filter) && $customer_name_filter != "")
				$this->db->where('customer.customer_firstname LIKE \''.$customer_name_filter.'%\' or customer.customer_lastname LIKE \''.$customer_name_filter.'%\'' );		
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(orders.orders_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			if(!empty($from_range_pr) && !empty($to_range_pr))
				$this->db->where('orders.orders_total_amt BETWEEN '.$from_range_pr.' and '.$to_range_pr.'');
			if(!empty($from_range_tq) && !empty($to_range_tq))
				$this->db->where('orders.orders_total_qty BETWEEN '.$from_range_tq.' and '.$to_range_tq.'');
					
				
				
						
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);				
			else
				$this->db->order_by($this->cAutoId,'ASC');
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
			$this->db->select('coupon.coupon_id,coupon.coupon_name,coupon.coupon_code,coupon.coupon_type,coupon.coupon_discount_amt,orders.customer_id,orders.order_id,orders.order_created_date,orders.order_total_amt,orders.order_total_qty,orders.invoice_number,customer.customer_firstname,customer.customer_lastname');		
			$this->db->join('orders','orders.coupon_id=coupon.coupon_id');
			$this->db->join('customer','customer.customer_id=orders.customer_id');
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
		$this->db->select('customer_firstname,customer_lastname,customer_gender,customer_emailid,customer_phoneno,customer_group_id,customer_created_date,customer_modified_date');
		$userArr = $this->db->where('customer_id',$userId)->get('customer')->row_array();
		
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
	}
	
	


}