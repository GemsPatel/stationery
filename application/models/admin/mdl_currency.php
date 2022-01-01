<?php
class mdl_currency extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $cCategory = '';
	
	function getData()
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			
			if($f !='' && $s != '' && check_db_column($this->cTableName,$f))
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoId,'ASC');
				
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
					
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
	
	function saveData()
	{
		$data = $this->input->post();
		unset($data['item_id']);
		
		//if primary id set then we have to make update query
		if($this->cPrimaryId != '')
		{
			$this->db->set('currency_modified_date', 'NOW()', FALSE);
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
		saveAdminLog($this->router->class, @$data['currency_name'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Currency has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
	}
/*
+----------------------------------------------------------+
	Deleting item. hadle both request get and post.
	with single delete and multiple delete.
	@prams : $ids -> integer or array
+----------------------------------------------------------+
*/	
	function deleteData($ids)
	{
		$returnArr = array();
		if($ids)
		{
			foreach($ids as $id)
			{
				$tabNameArr = array('0'=>'product');
				$fieldNameArr = array('0'=>'product_currency_id');
				$res=isImageIdExist($tabNameArr,$fieldNameArr,$id);// this function call for un delete field
			if(sizeof($res)>0)
			{
				echo json_encode($res);	
				return;
			}
			else
			{
				$getName = getField('currency_name', $this->cTableName, $this->cAutoId, $id);
				saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
				$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
			}
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
		
		$data['currency_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}

}