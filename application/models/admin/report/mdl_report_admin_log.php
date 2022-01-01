<?php
class mdl_report_admin_log extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cCategory = '';
	
	function getData()
	{
		$f = $this->input->get('f');
		$s = $this->input->get('s');
		$username_filter = $this->input->get('username_filter');
		$module_filter = $this->input->get('module_filter');
		$item_filter = $this->input->get('item_filter');
		$log_type_filter = $this->input->get('log_type_filter');
		$ip_filter = $this->input->get('ip_filter');
		$fromDate = $this->input->get('fromDate');
		$toDate = $this->input->get('toDate');
		
		if(isset($username_filter) && $username_filter != "")
			$this->db->where('admin_user_firstname LIKE \'%'.$username_filter.'%\' OR admin_user_lastname LIKE \'%'.$username_filter.'%\' ');
		
		if(isset($module_filter) && $module_filter != "")
			$this->db->where('am_name LIKE \'%'.$module_filter.'%\' ');
			
		if(isset($item_filter) && $item_filter != "")
			$this->db->where('module_item_name LIKE \'%'.$item_filter.'%\' ');
			
		if(isset($log_type_filter) && $log_type_filter != "")
			$this->db->where('admin_log_type LIKE \''.$log_type_filter.'\' ');
				
		if(isset($ip_filter) && $ip_filter != "")
			$this->db->where('admin_log_ip LIKE \''.$ip_filter.'%\' ');

		if($fromDate && $toDate)
			$this->db->where('DATE_FORMAT('.$this->cTableName.'.admin_log_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
		
		if($f !='' && $s != '')
			$this->db->order_by($f,$s);
		else
			$this->db->order_by($this->cAutoId,'DESC');

		$this->db->select($this->cTableName.'.*, admin_user_firstname, admin_user_lastname, am_name');
		$this->db->join('admin_user', 'admin_user.admin_user_id='.$this->cTableName.'.admin_user_id','left');
		$this->db->join('admin_menu', 'admin_menu.am_class_name='.$this->cTableName.'.admin_class_name','left');
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
/*
+----------------------------------------------------------+
	Deleting category. hadle both request get and post.
	with single delete and multiple delete.
	@prams : $ids -> integer or array
+----------------------------------------------------------+
*/	
	function deleteData($ids)
	{
		if($ids)
		{	
			foreach($ids as $id) //delete auto id
				$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
			
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
+----------------------------------------------------------+
	Function will get all item details.
+----------------------------------------------------------+
*/		
	function getItemDetails()
	{
		$itemId = _de($this->input->get('item_id'));
		$itemArr = $this->db->where($this->cAutoId,$itemId)->get($this->cTableName)->row_array();
		//pr($itemArr); die;
		if($itemArr['admin_log_type'] == 'D')
		{
			$resArr['error'] = getErrorMessageFromCode('01006');
		}
		else if($itemArr['admin_log_type'] == 'V')
		{
			if( $itemArr['admin_class_name'] != 'lgs')
			{
				$resArr = $this->db->query( " SELECT admin_user_firstname, module_item_name, 'View' as admin_log_type, admin_log_created_date 
											FROM admin_log al INNER JOIN admin_user au 
											ON au.admin_user_id=al.admin_user_id
											WHERE admin_log_id=".$itemId." " )->row_array();
			}
			else
			{
				$resArr = array('Admin User'=> 'Anonymous', 'Module Name'=>$itemArr['module_item_name'], 'IP Address' => $itemArr['admin_log_ip'], 'Access Time'=>$itemArr['admin_log_created_date'] );
			}
		}
		else
			$resArr = $this->db->where($itemArr['module_table_field'], $itemArr['module_primary_id'])->get($itemArr['module_table_name'])->row_array();
		
		return $resArr;
	}
/*
+----------------------------------------------------------+
	Function will get all users details.
+----------------------------------------------------------+
*/		
	function getUserDetails()
	{
		$userId = _de($this->input->get('user_id'));
		$userArr = $this->db->where('admin_user_id',$userId)->get('admin_user')->row_array();
		
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
	}	
}