<?php
class mdl_report_customer_wish extends CI_Model
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
			$product_filter=$this->input->get('product_id');
			$product_code_filter=$this->input->get('product_code_filter');
			$fromDate = $this->input->get('fromDate');
			$toDate =$this->input->get('toDate');
			
		
			if(isset($customer_name) && $customer_name != "")
				$this->db->where('customer_firstname LIKE \''.$customer_name.'%\' or customer_lastname LIKE \''.$customer_name.'%\'' );
			if(isset($customer_phoneno) && $customer_phoneno != "")
				$this->db->where('customer_phoneno LIKE \''.$customer_phoneno.'%\'' );
			if(isset($customer_emailid) && $customer_emailid != "")
				$this->db->where('customer_emailid LIKE \''.$customer_emailid.'%\'' );	
				
			if(isset($product_filter) && $product_filter != "") // product wise filter
				$this->db->where('product_price.product_id LIKE \''.$product_filter.'%\'' );
				
			if(isset($product_code_filter) && $product_code_filter != "") // product wise filter
				$this->db->where('product_price.product_generated_code LIKE \''.$product_code_filter.'%\'' );	
				
				
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(customer_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);				
			else
				$this->db->order_by('customer.customer_id','DESC');
		}
		else if($this->cPrimaryId != '')
		$this->db->where($this->cAutoId,$this->cPrimaryId);
		$this->db->select('customer.customer_id,customer.customer_firstname,customer.customer_lastname,customer.customer_emailid,customer.customer_phoneno,customer.customer_created_date,customer_cartwish.product_price_id,product_price.product_price_id,product_price.product_id,product_price.product_generated_code,product.product_name,product.product_id');
		$this->db->join('customer_cartwish','customer_cartwish.customer_id=customer.customer_id');
		$this->db->join('product_price','product_price.product_price_id=customer_cartwish.product_price_id');
		$this->db->join('product','product.product_id=product_price.product_id');
		$this->db->where('customer_cartwish_type','W');
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
	function getCustomerDetails()
	{
		$userId = _de($this->input->get('item_id'));
		$this->db->select('customer_firstname,customer_lastname,customer_gender,customer_emailid,customer_phoneno,customer_group_id,customer_created_date,customer_modified_date');
		$userArr = $this->db->where('customer_id',$userId)->get('customer')->row_array();
		//pr($userArr);
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