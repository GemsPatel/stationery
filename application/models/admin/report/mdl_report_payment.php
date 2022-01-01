<?php
class mdl_report_payment extends CI_Model
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
			$payment_method_id = $this->input->get('payment_method_id');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
			
			
			if(isset($payment_method_id) && $payment_method_id != "")
				$this->db->where('payment_method.payment_method_id LIKE \''.$payment_method_id.'%\' ');
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(payment_method.payment_method_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);	
			
						
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);				
			else
				$this->db->order_by($this->cAutoId,'ASC');
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
		
			$this->db->select('payment_method.payment_method_id,payment_method.payment_method_name,payment_method.payment_method_description,payment_method.payment_method_icon,payment_method.payment_method_created_date');		
			
			/*$this->db->join('customer','customer.customer_id=order.customer_id');
			$this->db->join('order_details','order_details.order_id=order.order_id');
			$this->db->join('product','product.product_id=order_details.product_id');*/
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
		$this->db->select('customer_firstname,customer_lastname,customer_emailid,customer_phoneno,customer_group_id,customer_created_date');
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
		$this->db->select('product_name,category_id,product_sku,product_alias,product_short_description,product_description,product_view_buy');
		$userArr = $this->db->where('product_id',$userId)->get('product')->row_array();
		
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
	}
	
	


}