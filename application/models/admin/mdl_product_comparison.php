<?php
class mdl_product_comparison extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $cCategory = '';
	
	
	function getData($srchKey = '')
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$product_name = $this->input->get('product_name_filter');
			$product_sku = $this->input->get('product_sku_filter');
			$product_status = $this->input->get('status_filter');
			$product_status_cctld = $this->input->get('status_cctld_filter');
			
			if(isset($product_name) && $product_name != "")
				$this->db->where('product_name LIKE \'%'.$product_name.'%\' ');
			
			if(isset($product_sku) && $product_sku != "")
				$this->db->where('product_sku LIKE \'%'.$product_sku.'%\' ');
				
			if(isset($product_status) && $product_status != "")
				$this->db->where('product.product_status LIKE \''.$product_status.'\' ');

			if(isset($product_status_cctld) && $product_status_cctld != "")
				$this->db->where('product_cctld.product_status LIKE \''.$product_status_cctld.'\' ');

			if($f !='' && $s != '')
				$this->db->order_by($f,$s);
			else
				$this->db->order_by("product.".$this->cAutoId,'ASC');

			$this->db->select( " product.product_id, product.product_name, product.product_sku, product.product_modified_date, product.product_status, product_cctld.product_cctld_modified_date, product_cctld.product_status as product_cctld_status " );
			$this->db->join('product_cctld', 'product_cctld.product_id=product.product_id', 'INNER');
			
			$res = $this->db->get($this->cTableName);			
			//echo $this->db->last_query();
			
			return $res;
		}
		
	}
	
	
	
}