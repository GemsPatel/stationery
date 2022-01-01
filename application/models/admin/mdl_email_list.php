<?php
class mdl_email_list extends CI_Model
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
			$email_filter = $this->input->get('email_filter');
			$optlevel_filter = $this->input->get('optlevel_filter');
			$status_filter = $this->input->get('status_filter');
			$refsource_filter = $this->input->get('refsource_filter');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
			if(isset($email_filter) && $email_filter != "")
				$this->db->where('email_id LIKE \'%'.$email_filter.'%\' ');
				
			if(isset($optlevel_filter) && $optlevel_filter != "")
				$this->db->where('el_status LIKE \''.$optlevel_filter.'\' ');
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('el_status LIKE \''.$status_filter.'\' ');
				
			if(isset($refsource_filter) && $refsource_filter != "")
				$this->db->where('el_reference_source LIKE \'%'.$refsource_filter.'%\' ');
			
			if($fromDate && $toDate)
				$this->db->where('DATE_FORMAT('.$this->cTableName.'.el_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			if($f !='' && $s != '' && check_db_column($this->cTableName,$f))
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoId,'DESC');
				
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
			$this->db->set('el_modified_date', 'NOW()', FALSE);
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
		saveAdminLog($this->router->class, @$data['email_id'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Email list has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				$res = checkIfForeignKeyExist( array( "customer", "email_list", "email_list_id" ), "customer_id", $id );
				
				if(sizeof($res)>0)
				{
					echo json_encode($res);
					return;
				}
				else
				{
					$getName = getField('email_id', $this->cTableName, $this->cAutoId, $id);
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

}