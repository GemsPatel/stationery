<?php
class mdl_report_customer_order extends CI_Model
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
			$customer_name_filter = $this->input->get('customer_name_filter');
			$customer_emailid_filter = $this->input->get('customer_emailid_filter');
			$customer_phoneno_filter = $this->input->get('customer_phoneno_filter');
			$customer_group_id=$this->input->get('customer_group_id');
			$product_name_filter = $this->input->get('product_name_filter');
			$from_range = $this->input->get('from_range');
			$to_range =$this->input->get('to_range');
			$fromDate = $this->input->get('fromDate');
			$toDate =$this->input->get('toDate');
			
		
			if(isset($customer_name_filter) && $customer_name_filter != "")
				$this->db->where('customer_firstname LIKE \''.$customer_name_filter.'%\' or customer_lastname LIKE \''.$customer_name_filter.'%\' ');
				
			if(isset($customer_emailid_filter) && $customer_emailid_filter != "")
				$this->db->where('customer_emailid LIKE \''.$customer_emailid_filter.'%\'');
			if(isset($customer_phoneno_filter) && $customer_phoneno_filter != "")
				$this->db->where('customer_phoneno LIKE \''.$customer_phoneno_filter.'%\'');
			if(isset($customer_group_id) && $customer_group_id != "")
				$this->db->where('customer_group.customer_group_id LIKE \''.$customer_group_id.'%\' ');
			if(isset($product_name_filter) && $product_name_filter != "")
				$this->db->where('product_name LIKE \''.$product_name_filter.'%\'');
					
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(orders.orders_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			if(!empty($from_range) && !empty($to_range))
				$this->db->where('(orders.order_total_amt*1) BETWEEN '.$from_range.' and '.$to_range.'');
			
			
			if($f !='' && $s != '')
			{
				if($f=='order_total_amt')
					$this->db->order_by("(orders.order_total_amt*1)",$s);
				else
					$this->db->order_by($f,$s);
			}
			else
				$this->db->order_by('customer.customer_id','DESC');
		}
		else if($this->cPrimaryId != '')
		{
			$this->db->where($this->cAutoId,$this->cPrimaryId);
		}
			$this->db->select('count(orders.order_id) as orders,customer.customer_id,customer.customer_firstname,customer.customer_lastname,customer.customer_emailid,customer.customer_phoneno,customer_group.customer_group_name,product.product_name,COUNT(order_details.product_id) as products,SUM(orders.order_total_amt) as order_total_amt,orders.order_created_date');
			$this->db->join('orders','orders.customer_id=customer.customer_id');
			$this->db->join('customer_group','customer_group.customer_group_id=customer.customer_group_id');
			$this->db->join('order_details','order_details.order_id=orders.order_id');
			$this->db->join('product','product.product_id=order_details.product_id');
			$this->db->group_by('orders.customer_id');
			
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
	function getproductDetails()
	{
		$userId = _de($this->input->get('item_id'));
		$this->db->select('product_name,category_id,product_sku,product_alias,product_short_description,product_description,product_view_buy,product_created_date,product_modified_date');
		$userArr = $this->db->where('product_id',$userId)->get('product')->row_array();
		
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
	}
	
	


}