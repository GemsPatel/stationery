<?php
class mdl_report_product_view extends CI_Model
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
			$product_sku = $this->input->get('product_sku_filter');
			
		
		if(isset($product_sku) && $product_sku != "")
				$this->db->where('product_sku LIKE \''.$product_sku.'%\' ');
		if(isset($product_name) && $product_name != "")
				$this->db->where('product_name LIKE \''.$product_name.'%\' ');
					
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);				
			else
				$this->db->order_by($this->cAutoId,'DESC');
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
		
		$mfg = MANUFACTURER_ID;
 		if( $mfg == 7 )
  		{
 			$this->db->select('product_id,product_name,product_sku,product_view_buy');
 			$this->db->where('product_view_buy != 0');
 			
 			$res = $this->db->get($this->cTableName);
 		}
 		else 
 		{
 			$this->db->select( $this->cTableName.'.product_id, product_cctld.product_name, product_sku,product_view_buy');
 			$this->db->join( $this->cTableName."_cctld", $this->cTableName."_cctld.product_id=".$this->cTableName.".product_id");
 			$this->db->where('product_view_buy != 0');
 			$this->db->where('manufacturer_id = '.$mfg);
 			
 			$res = $this->db->get($this->cTableName);
 		}
		//echo $this->db->last_query();
		return $res;
		
	}
	/*
+----------------------------------------------------------+
	Function will get all users details.
+----------------------------------------------------------+
*/		
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
	function getManufacturerDetails()
	{
		$userId = _de($this->input->get('item_id'));
		$this->db->select('product_manufacturer_id,manufacturer_email_id,manufacturer_created_date,manufacturer_modified_date');
		$this->db->join('manufacturer','manufacturer.manufacturer_id=product.product_manufacturer_id');
		$userArr = $this->db->where('product_id',$userId)->get('product')->row_array();
		
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
	}
	


}