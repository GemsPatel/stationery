<?php
class mdl_global_configuration extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cCategory = '';
	
	function getData()
	{
		$admin_user_id=$this->session->userdata('admin_id');
		$res = $this->db->where('manufacturer_id',MANUFACTURER_ID)->get($this->cTableName)->row_array();
		$res_admin_user = $this->db->select('admin_user_order_noti_status, admin_user_customer_noti_status, admin_user_message_noti_status')->where('admin_user_id',$admin_user_id)->where('manufacturer_id',MANUFACTURER_ID)->get('admin_user')->row_array();
		return array_merge($res,$res_admin_user);;
	}
	
	function saveData()
	{
		// post data for insert and edit
		$data = $this->input->post();
		unset($data['item_id']);
		
		$data['manufacturer_id'] = MANUFACTURER_ID;
		//unset($data['setccTLD']);
		//unset($data['ccTLD']);
		
		//save in admin_user table and unset from data
		$admin_user_id=$this->session->userdata('admin_id');
		$data_admin_user['manufacturer_id'] = MANUFACTURER_ID;
		$data_admin_user['admin_user_order_noti_status'] = $data['admin_user_order_noti_status'];
		$data_admin_user['admin_user_customer_noti_status'] = $data['admin_user_customer_noti_status'];
		$data_admin_user['admin_user_message_noti_status'] = $data['admin_user_message_noti_status'];
		$this->db->where('admin_user_id',$admin_user_id)->update('admin_user',$data_admin_user);
		unset($data['admin_user_order_noti_status']);
		unset($data['admin_user_customer_noti_status']);
		unset($data['admin_user_message_noti_status']);
		
		$primaryId = $this->input->post('item_id');
		
		$getImg = getField('offline_image', $this->cTableName, $this->cAutoId, $primaryId);		
		if($this->input->post('offline_image') && $_FILES['offline_image']['name'])
		{
			$data['offline_image'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != '')
				@unlink($getImg);
		}
		
		if($this->input->post('offline_image') && $_FILES['offline_image']['name'] == '')
			$data['offline_image'] = $this->input->post('offline_image');
			
		if($this->input->post('offline_image') == '' && $_FILES['offline_image']['name'] == '')
			@unlink($getImg);
		
		
		
		if((int)$primaryId != '')
		{
			$noOfModify = $this->getData();
			if($noOfModify){
				$modified_by = ($noOfModify['modified_by']+1);
				$this->db->set('modified_by', $modified_by);
			}
			$this->db->set('modified_date', 'NOW()', FALSE);			
			$this->db->where($this->cAutoId,$primaryId)->update($this->cTableName,$data);
			$last_id = $this->input->post('item_id');
			$logType = 'E';
		}
		else // insert new row
		{
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}
		saveAdminLog($this->router->class, @$data['custom_message'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Global configuration has been '.(($primaryId != '') ? 'updated': 'inserted').' successfully.');
				
	}

/*
+------------------------------------------------------+
	Function will resize image size.
	image size : 150x100
+------------------------------------------------------+
*/	
	function resizeUploadImage()
	{
		$image = uploadFile('offline_image','image','site_config'); //input file, type, folder
		
		if(@$image['error'])
		{
			setFlashMessage('error',$image['error']);
			redirect('admin/'.$this->router->class);	
		}
		$path = $image['path'];
		
		return $path;
	}

}