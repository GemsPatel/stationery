<?php
class mdl_report_product_review extends CI_Model
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
			$ipaddress = $this->input->get('ipaddress_filter');
			$fromDate = $this->input->get('fromDate');
			$toDate =$this->input->get('toDate');
			$from_range = $this->input->get('from_range');
			$to_range =$this->input->get('to_range');	
			if(isset($product_name) && $product_name != "")
				$this->db->where('product_name LIKE \''.$product_name.'%\' ');
			if(isset($ipaddress) && $ipaddress != "")
				$this->db->where('product_review_ipaddress LIKE \''.$ipaddress.'%\' ');		
			
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(product_review.product_review_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			if(!empty($from_range) && !empty($to_range))
				$this->db->where('(product.product_price*1) BETWEEN '.$from_range.' and '.$to_range.'');
			if($f !='' && $s != '')
			{
				if($f=='product_price')
					$this->db->order_by("(product.product_price*1)",$s);
				
				else
					$this->db->order_by($f,$s);
			}
			else
				$this->db->order_by($this->cAutoId,'DESC');
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
			$this->db->select('product.product_id,product.product_name,product.product_price,product_review.product_review_id,product_review.product_review_rating,product_review.product_review_ipaddress,
			product_review.product_review_created_date');
			$this->db->join('product','product.product_id=product_review.product_id');
			$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
		
	function getproductDetails()
	{
		$userId = _de($this->input->get('item_id'));
		$this->db->select('product_name,category_id,product_sku,product_alias,product_short_description,product_description,product_view_buy,,product_created_date,product_modified_date');
		$userArr = $this->db->where('product_id',$userId)->get('product')->row_array();
		
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
	}
	
	


}