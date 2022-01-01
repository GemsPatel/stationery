<?php
class mdl_shipping_pincodes extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $gift = '';
	
	function getData()
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$status_filter = $this->input->get('status_filter');
			$shipping_method_id = $this->input->get('shipping_method_id');
			$text_pincode = $this->input->get('text_pincode');
			$text_city_name = $this->input->get('text_city_name');
			$text_service_type = $this->input->get('text_service_type');
			$text_service_type_code = $this->input->get('text_service_type_code');
			$from_range_cod = $this->input->get('from_range_cod');
			$to_range_cod = $this->input->get('to_range_cod');
			$from_range_pre = $this->input->get('from_range_pre');
			$to_range_pre = $this->input->get('to_range_pre');
		
			if(isset($shipping_method_id) && $shipping_method_id != "") // 
				$this->db->where('shipping_method.shipping_method_id LIKE \''.$shipping_method_id.'%\' ');
				
			if(isset($text_pincode) && $text_pincode != "") // text box value search
				$this->db->where('pincode.pincode LIKE \''.$text_pincode.'%\' ');
			if(isset($text_city_name) && $text_city_name != "") // text box value search
				$this->db->where('shipping_pincodes.city_name LIKE \''.$text_city_name.'%\' ');	
			if(isset($text_service_type) && $text_service_type != "") // text box value search
				$this->db->where('shipping_pincodes.service_type LIKE \''.$text_service_type.'%\' ');
			if(isset($text_service_type_code) && $text_service_type_code != "") // text box value search
				$this->db->where('shipping_pincodes.service_type_code LIKE \''.$text_service_type_code.'%\' ');
			
			if(!empty($from_range_cod) && !empty($to_range_cod))
				$this->db->where('(shipping_pincodes.cod_limit*1) BETWEEN '.$from_range_cod.' and '.$to_range_cod.'');
			if(!empty($from_range_pre) && !empty($to_range_pre))
				$this->db->where('(shipping_pincodes.prepaid_limit*1) BETWEEN '.$from_range_pre.' and '.$to_range_pre.'');	
						
			if(isset($status_filter) && $status_filter != "") // status wise fiter 
				$this->db->where('shipping_pincodes.shipping_pincodes_status LIKE \''.$status_filter.'\' ');
			
			if($f !='' && $s != '' )
			{
				if($f=='cod_limit')
					$this->db->order_by("(shipping_pincodes.cod_limit*1)",$s);
				if($f=='prepaid_limit')
					$this->db->order_by("(shipping_pincodes.prepaid_limit*1)",$s);
				else
					$this->db->order_by($f,$s);
			}
			else
				$this->db->order_by($this->cAutoId,'ASC');
				
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
			$this->db->select('shipping_pincodes.shipping_pincodes_id,shipping_pincodes.shipping_method_id,shipping_pincodes.pincode_id,shipping_pincodes.city_name,shipping_pincodes.service_type,shipping_pincodes.service_type_code,shipping_pincodes.cod_limit,shipping_pincodes.prepaid_limit,shipping_pincodes.shipping_pincodes_status,shipping_method.shipping_method_name,pincode.pincode');		
			$this->db->join('shipping_method','shipping_method.shipping_method_id=shipping_pincodes.shipping_method_id');
			$this->db->join('pincode','pincode.pincode_id=shipping_pincodes.pincode_id');
			$res = $this->db->get($this->cTableName);	
		//echo $this->db->last_query();
		return $res;
		
	}
	
	function saveData()
	{
		// post data for insert and edit
		$data = $this->input->post();
		// unset item id 
		unset($data['item_id']);
			
		if($this->cPrimaryId != '')
		{
			$this->db->set('shipping_pincodes_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}
		saveAdminLog($this->router->class, @$data['shipping_method_id'], $this->cTableName, $this->cAutoId, $last_id, $logType);
		setFlashMessage('success','shipping Method has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
	}
	
	
/*
+----------------------------------------------------------+
	Deleting article. hadle both request get and post.
	with single delete and multiple delete.
	@prams : $ids -> integer or array
+----------------------------------------------------------+
*/	
	function deleteData($ids)
	{
		if($ids)
		{		
			
			foreach($ids as $id)
			{
					   	 $getName = getField('shipping_method_id', $this->cTableName, $this->cAutoId, $id);
						 saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
						 $this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
						 $returnArr['type'] ='success';
						 $returnArr['msg'] = count($ids)." records has been deleted successfully.";
			}
		}
		else{
			$returnArr['type'] ='error';
			$returnArr['msg'] = "Please select at least 1 item.";
		}
		echo json_encode($returnArr);
	}
/*
+-----------------------------------------+
	Update status for enabled/disabled
	@params : post array of ids, status
+-----------------------------------------+
*/	
	function updateStatus()
	{
		$status = $this->input->post('status');
		$cat_id = $this->input->post('cat_id');
		
		$data['shipping_pincodes_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}




}