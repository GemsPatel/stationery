<?php
class mdl_report_order extends CI_Model
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
			$invoice_number_filter = $this->input->get('invoice_number_filter');
			$gender_filter = $this->input->get('gender_filter');
			$customer_name_filter = $this->input->get('customer_name_filter');
			$product_name_filter = $this->input->get('product_name_filter');
			$gift_name_filter = $this->input->get('gift_name_filter');
			$coupon_name_filter = $this->input->get('coupon_name_filter');
			$payment_method_id = $this->input->get('payment_method_id');
			$shipping_method_id = $this->input->get('shipping_method_id');
			$from_range_pr = $this->input->get('from_range_pr');
			$to_range_pr = $this->input->get('to_range_pr');
			$from_range_tp = $this->input->get('from_range_tp');
			$to_range_tp = $this->input->get('to_range_tp');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
			if(isset($gender_filter) && $gender_filter != "")
				$this->db->where('customer.customer_gender LIKE \''.$gender_filter.'%\' ');
			if(isset($invoice_number_filter) && $invoice_number_filter != "")
					$this->db->where('invoice_number LIKE \''.$invoice_number_filter.'%\' ');
			if(isset($order_id_filter) && $order_id_filter != "")
					$this->db->where('orders.order_id',$order_id_filter);		
			
			if(isset($customer_name_filter) && $customer_name_filter != "")
				$this->db->where('customer.customer_firstname LIKE \''.$customer_name_filter.'%\' or customer.customer_lastname LIKE \''.$customer_name_filter.'%\'' );		
		
			if(isset($product_name_filter) && $product_name_filter != "")
				$this->db->where('product.product_name LIKE \''.$product_name_filter.'%\' ');
			if(isset($gift_name_filter) && $gift_name_filter != "")
				$this->db->where('gift.gift_name LIKE \''.$gift_name_filter.'%\' ');	
			if(isset($coupon_name_filter) && $coupon_name_filter != "")
				$this->db->where('coupon.coupon_name LIKE \''.$coupon_name_filter.'%\' ');
			if(isset($payment_method_id) && $payment_method_id != "")
				$this->db->where('payment_method.payment_method_id LIKE \''.$payment_method_id.'%\' ');	
			if(isset($shipping_method_id) && $shipping_method_id != "")
				$this->db->where('shipping_method.shipping_method_id LIKE \''.$shipping_method_id.'%\' ');	
					
					
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(orders.order_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			if(!empty($from_range_pr) && !empty($to_range_pr))
				$this->db->where('(orders.order_total_amt*1)  BETWEEN '.$from_range_pr.' and '.$to_range_pr.'');
			
			
						
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
		
			$this->db->select('orders.order_id,orders.invoice_number,orders.invoice_number,orders.customer_id,orders.payment_method_id,orders.shipping_method_id,orders.order_id,orders.order_created_date,order_details.product_id,orders.order_total_amt,product.product_name,customer.customer_firstname,customer.customer_lastname,customer.customer_gender,payment_method.payment_method_id,payment_method.payment_method_name,shipping_method.shipping_method_id,shipping_method.shipping_method_name');
			$this->db->join('customer','customer.customer_id=orders.customer_id');
			$this->db->join('order_details','order_details.order_id=orders.order_id');
			//$this->db->join('gift','gift.gift_id=order_details.gift_id');
			//$this->db->join('coupon','coupon.coupon_id=orders.coupon_id');
			$this->db->join('product','product.product_id=order_details.product_id');
			$this->db->join('payment_method','payment_method.payment_method_id=orders.payment_method_id');
			$this->db->join('shipping_method','shipping_method.shipping_method_id=orders.shipping_method_id');
			$this->db->group_by('order_id');
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
				$this->db->select('orders.order_id,customer.customer_firstname,customer.customer_lastname,customer.customer_gender,orders.invoice_number,orders.invoice_number,orders.customer_id,orders.payment_method_id,orders.shipping_method_id,orders.order_id,order_details.product_id,order_details.order_details_product_price,order_details.order_details_product_qty,product.product_name,payment_method.payment_method_id,payment_method.payment_method_name,shipping_method.shipping_method_id,shipping_method.shipping_method_name,orders.order_created_date,orders.order_modified_date');		
			
				$this->db->join('customer','customer.customer_id=orders.customer_id');
				$this->db->join('order_details','order_details.order_id=orders.order_id');
				//$this->db->join('gift','gift.gift_id=order_details.gift_id');
				//$this->db->join('coupon','coupon.coupon_id=orders.coupon_id');
				$this->db->join('product','product.product_id=order_details.product_id');
				$this->db->join('payment_method','payment_method.payment_method_id=orders.payment_method_id');
				$this->db->join('shipping_method','shipping_method.shipping_method_id=orders.shipping_method_id');
				$userArr = $this->db->where('orders.order_id',$userId)->get($this->cTableName)->result_array();
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
		
	 }
	 
  }


}