<?php
class mdl_customer_private_message extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cCategory = '';
	
	function getData($is_view)
	{
		if($this->input->get('pm_email') == '')
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$username_filter = $this->input->get('username_filter');
			$email_filter = $this->input->get('email_filter');
			$phone_filter = $this->input->get('phone_filter');
			$status_filter = $this->input->get('status_filter');
			$ip_filter = $this->input->get('ip_filter');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
			if(isset($username_filter) && $username_filter != "")
				$this->db->where('customer_firstname LIKE \'%'.$username_filter.'%\' OR customer_lastname LIKE \'%'.$username_filter.'%\' ');
				
			if(isset($email_filter) && $email_filter != "")
				$this->db->where('pm_email LIKE \''.$email_filter.'%\' ');
			
			if(isset($phone_filter) && $phone_filter != "")
				$this->db->where('pm_phone LIKE \''.$phone_filter.'%\' ');
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('pm_status LIKE \''.$status_filter.'\' ');
				
			if(isset($ip_filter) && $ip_filter != "")
				$this->db->where('pm_ip_address LIKE \''.$ip_filter.'%\' ');
	
			if($fromDate && $toDate)
				$this->db->where('DATE_FORMAT('.$this->cTableName.'.pm_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			$this->db->select($this->cTableName.'.*, customer_firstname, customer_lastname');
			$this->db->join('customer', 'customer.customer_id='.$this->cTableName.'.customer_id','left');
			$this->db->join('(select pm_created_date,customer_id  from  private_message WHERE pm_parent_id = 0 ) as p','p.pm_created_date=private_message.pm_created_date','inner');
			if($f !='' && $s != '')
			{
				$this->db->order_by($f,$s);
			}
			else
			{
			}
		}
		else
		{
			$this->db->select($this->cTableName.'.*, customer_firstname, customer_lastname');
			$this->db->join('customer', 'customer.customer_id='.$this->cTableName.'.customer_id','left');
			$pm_email = _de($this->input->get('pm_email'));
			$this->db->where('pm_email',$pm_email);
		}
		
		if( MANUFACTURER_ID != 7 )
			$this->db->where($this->cTableName.'.manufacturer_id', MANUFACTURER_ID );
		
		$this->db->order_by("pm_created_date",'DESC');
		$res = $this->db->get($this->cTableName);
// 		echo $this->db->last_query();
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
			{
				//save log : class name, item name, tablename, fieldname, primary id, type A/E/D
				$getName = getField('pm_email', $this->cTableName, $this->cAutoId, $id);
				saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
				$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
			}
			foreach($ids as $id) //delete parent id
				$this->db->where_in('pm_parent_id',$id)->delete($this->cTableName);
			
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
	Function will get all customer details.
+----------------------------------------------------------+
*/		
	function getCustomerDetails()
	{
		$userId = _de($this->input->get('customer_id'));
		
		if($userId != 0)
			$resArr = $this->db->where('customer_id',$userId)->get('customer')->row_array();
		else 
			$resArr['error'] = "Guest User!";	
			
		return $resArr;
	}		


}