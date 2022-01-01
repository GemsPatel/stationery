<?php
class mdl_report_order_return extends CI_Model
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
			$customer_name_filter = $this->input->get('customer_name_filter');
			$product_name_filter = $this->input->get('product_name_filter');
			$from_range_pr = $this->input->get('from_range_pr');
			$to_range_pr = $this->input->get('to_range_pr');
			$from_range_tq = $this->input->get('from_range_tq');
			$to_range_tq = $this->input->get('to_range_tq');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
			
			if(isset($order_id_filter) && $order_id_filter != "")
				$this->db->where('order_details.order_id', $order_id_filter);			
		
			if(isset($product_name_filter) && $product_name_filter != "")
				$this->db->where('product.product_name LIKE \''.$product_name_filter.'%\' ');	
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(order_return.order_return_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			if(!empty($from_range_pr) && !empty($to_range_pr))
				$this->db->where('(order_details.order_details_product_price*1) BETWEEN '.$from_range_pr.' and '.$to_range_pr.'');
				
			if(!empty($from_range_tq) && !empty($to_range_tq))
				$this->db->where('(order_details.order_details_product_qty*1) BETWEEN '.$from_range_tq.' and "'.$to_range_tq.'');
			
						
			
			if($f !='' && $s != '')
			{
				if($f=='order_details_product_price')
					$this->db->order_by("(order_details.order_details_product_price*1)",$s);
				else
					$this->db->order_by($f,$s);
			}
			else
				$this->db->order_by($this->cAutoId,'DESC');
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
		
			$this->db->select('order_return.order_return_id,order_return.order_details_id,order_return.order_return_created_date,
					order_return.order_return_reason_key,orders.customer_id,customer.customer_firstname,customer.customer_lastname,
					customer.customer_gender,order_details.order_id,order_details.product_id,order_details.order_details_product_price,
					order_details.order_details_product_qty,product.product_name');		
			
			$this->db->join('order_details','order_details.order_details_id=order_return.order_details_id');
			$this->db->join('orders','orders.order_id=order_details.order_id','left');
			$this->db->join('customer','customer.customer_id=orders.customer_id','left');
			$this->db->join('product','product.product_id=order_details.product_id');
			
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
		$this->db->select('customer_firstname,customer_lastname,customer_emailid,customer_gender,customer_phoneno,customer_group_id,customer_created_date,customer_modified_date');
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