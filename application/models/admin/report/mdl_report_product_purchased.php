<?php
class mdl_report_product_purchased extends CI_Model
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
			$product_name = $this->input->get('product_name_filter');
			$fromDate = $this->input->get('fromDate');
			$toDate =$this->input->get('toDate');
			$from_range_qty = $this->input->get('from_range_qty');
			$to_range_qty =$this->input->get('to_range_qty');
			$from_range_pr = $this->input->get('from_range_pr');
			$to_range_pr =$this->input->get('to_range_pr');
			
			if(isset($product_name) && $product_name != "")
				$this->db->where('product_name LIKE \''.$product_name.'%\' ');
			if(!empty($from_range_qty) && !empty($to_range_qty))
				$this->db->where('(order_details.order_details_product_qty*1) BETWEEN '.$from_range_qty.' and '.$to_range_qty.'');	
			if(!empty($from_range_pr) && !empty($to_range_pr))
				$this->db->where('(order_details.order_details_product_price*1) BETWEEN '.$from_range_pr.' and '.$to_range_pr.'');		
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(order_details.order_details_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			
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
			$this->db->select('product.product_id,product.product_name,order_details_id,order_details_product_qty,order_details_product_price,order_details_created_date,product.product_price,product_value.product_value_quantity');
			$this->db->join('product','product.product_id=order_details.product_id');
			$this->db->join('product_value','product_value.product_id=order_details.product_id');
			
			$res = $this->db->get($this->cTableName);
			//r($res);
			//echo $this->db->last_query();
			//die;
		return $res;
		
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