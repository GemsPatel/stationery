<?php
class mdl_product_review extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $article = '';
	
	function getData()
	{
		if($this->cPrimaryId == "")
		{
			
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			
			$status_filter = $this->input->get('status_filter');
			$text_product = $this->input->get('text_product');
			
			if(isset($text_product) && $text_product != "")
				$this->db->where('product_name LIKE \''.$text_product.'%\' ');
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('product_review_status LIKE \''.$status_filter.'\' ');
			
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);				
			else
				$this->db->order_by($this->cAutoId,'ASC');
				
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
					
		//$this->db->where('article_status','0');
		$this->db->join('product', 'product.product_id = product_review.product_id');	
		$res = $this->db->get($this->cTableName);
		
		//echo $this->db->last_query();
		return $res;
		
	}
	
	function saveData()
	{
		// post data for insert and edit
		$data = $this->input->post();
		$data['customer_id']= $this->session->userdata('admin_id');
		$data['user_type']= "A";
		// unset item id 
		unset($data['item_id']);
		
		
		if($this->cPrimaryId != '')
		{
			$ip=$_SERVER['REMOTE_ADDR'];
			$this->db->set('product_review_modified_date', 'NOW()', FALSE);
			$this->db->set('product_review_ipaddress', "'$ip'" , FALSE);
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
		saveAdminLog($this->router->class, @$data['product_review_description'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Product Rating has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				$getName = getField('product_review_description', $this->cTableName, $this->cAutoId, $id);
				saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
				$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
			}
			$returnArr['type'] ='success';
			$returnArr['msg'] = count($ids)." records has been deleted successfully.";
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
		
		$data['product_review_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}



}