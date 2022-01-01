<?php
class mdl_admin_user extends CI_Model
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
			$status_filter = $this->input->get('status_filter');
			$text_firstname = $this->input->get('text_firstname');
			$text_lastname = $this->input->get('text_lastname');
			$text_emailid = $this->input->get('text_emailid');
			
			if(isset($text_firstname) && $text_firstname != "")
				$this->db->where('admin_user_firstname LIKE \''.$text_firstname.'%\' ');
				
			if(isset($text_lastname) && $text_lastname != "")
				$this->db->where('admin_user_lastname LIKE \''.$text_lastname.'%\' ');
				
			if(isset($text_emailid) && $text_emailid != "")
				$this->db->where('admin_user_emailid LIKE \''.$text_emailid.'%\' ');	
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('admin_user_status LIKE \''.$status_filter.'\' ');
				
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);				
			else
				$this->db->order_by($this->cAutoId,'ASC');
				
			if( MANUFACTURER_ID != 7 )
				$this->db->where('manufacturer_id', MANUFACTURER_ID );
				
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
		
		$this->db->join('admin_user_group', 'admin_user_group.admin_user_group_id = admin_user.admin_user_group_id');
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
	
	function saveData()
	{
		$data = $this->input->post();
		
		unset($data['item_id']);
		unset($data['admin_user_password_confirm']);
		if($this->input->post('admin_user_password') !='')
			$data['admin_user_password'] = (md5($this->input->post('admin_user_password').$this->config->item('encryption_key')));
		else
			$data['admin_user_password'] = getField('admin_user_password', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
			
	    $getImg = getField('admin_profile_image', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
		if($this->input->post('admin_profile_image') && $_FILES['admin_profile_image']['name'])
		{
			$data['admin_profile_image'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != '')
				@unlink($getImg);
		}
		if($this->input->post('admin_profile_image') && $_FILES['admin_profile_image']['name'] == '')
			$data['admin_profile_image'] = $this->input->post('admin_profile_image');
		if($this->input->post('admin_profile_image') == '' && $_FILES['admin_profile_image']['name'] == '')
			@unlink($getImg);
			
		$data['manufacturer_id'] = MANUFACTURER_ID;
		//if primary id set then we have to make update query
		if($this->cPrimaryId != '')
		{
			$this->db->set('admin_user_modified_date', 'NOW()', FALSE);
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
		saveAdminLog($this->router->class, @$data['admin_user_firstname'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','User has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				$getName = getField('admin_user_firstname', $this->cTableName, $this->cAutoId, $id);
				saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
				$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
				$this->db->where_in('admin_user_id',$id)->delete('permission'); // all permissions deleted when the user is deleted
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
		$data['admin_user_status'] = $status;
		
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
		//echo $this->db->last_query();
		
	}
	/*
+------------------------------------------------------+
	uploads product image folder
+------------------------------------------------------+
*/	
	function resizeUploadImage()
	{
		/*$file_size = str_replace('M','',ini_get('upload_max_filesize'));
		$object_size = convertToMb($_FILES['article_image']['size']);
		
		if($file_size < $object_size)
			setFlashMessage('error','Upload limit exceed.');
		else*/ 
		{
			$image = uploadFile('admin_profile_image','image','admin_profile'); //input file, type, folder
			if(@$image['error'])
			{
				setFlashMessage('error',$image['error']);
				redirect('admin/'.$this->router->class);
				
			}
			/*$width = getField('image_size_width','image_size','image_size_id',$this->input->post('image_size_id'));
			$height = getField('image_size_height','image_size','image_size_id',$this->input->post('image_size_id'));
			$path = $image['path'];*/
			//$sizeArr = $this->db->where('image_size_id',$this->input->post('image_size_id'))->where('image_size_status','0')->get('image_size')->row_array();
			$path = $image['path'];
			$dest = getResizeFileNameByPath($path,'m',''); //image path, type(s,m), folder
			$returnFlag = resize_image($path, $dest, 60, 60); //source, destination, width, height
			@unlink($path); //delete old image
			return $dest;
		}
	}


}