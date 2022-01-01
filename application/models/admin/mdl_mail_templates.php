<?php
class mdl_mail_templates extends CI_Model
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
			$text_name = $this->input->get('text_name');
			$text_key = $this->input->get('text_key');
			$text_subject = $this->input->get('text_subject');
			
		
			if(isset($text_name) && $text_name != "") // text box value search
				$this->db->where('template_name LIKE \''.$text_name.'%\' ');
			if(isset($text_key) && $text_key != "") // text box value search
				$this->db->where('template_key LIKE \''.$text_key.'%\' ');
			if(isset($text_subject) && $text_subject != "") // text box value search
				$this->db->where('template_subject LIKE \''.$text_subject.'%\' ');	
					
			if(isset($status_filter) && $status_filter != "") // status wise fiter 
				$this->db->where('payment_method_status LIKE \''.$status_filter.'\' ');
			
			if($f !='' && $s != '' )
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
		// post data for insert and edit
		$data = $this->input->post();
		// unset item id 
		unset($data['item_id']);
		
			
		if($this->cPrimaryId != '')
		{
			unset($data['template_key']);
			$this->db->set('modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			$data['template_key'] = strtoupper($data['template_key']);
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}
		saveAdminLog($this->router->class, @$data['template_name'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Mail Template has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				$getName = getField('template_name', $this->cTableName, $this->cAutoId, $id);
				saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
				$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
				$returnArr['type'] ='success';
				$returnArr['msg'] = count($ids)." records has been deleted successfully.";
			}
		}
		else
		{
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
		
		$data['template_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}



}