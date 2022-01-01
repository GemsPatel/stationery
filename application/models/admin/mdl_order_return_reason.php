<?php
class mdl_order_return_reason extends CI_Model
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
			$staus_name = $this->input->get('orr_name');
			$staus_key = $this->input->get('orr_key');
			
			if(isset($staus_name) && $staus_name != "")
				$this->db->where('orr_name LIKE \''.$staus_name.'%\' ');
				
			if(isset($staus_key) && $staus_key != "")
				$this->db->where('orr_key LIKE \'%'.$staus_key.'%\' ');
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('orr_status LIKE \''.$status_filter.'\' ');
				
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
		unset($data['orr_icon']);
		
		$data['orr_key'] = strtoupper($data['orr_key']);
// 		$getImg = getField('order_status_icon', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
		
// 		if($this->input->post('orr_icon') && $_FILES['orr_icon']['name'])
// 		{
// 			$data['orr_icon'] = $this->resizeUploadImage(); //upload and resize image		
// 			if($getImg != '')
// 				@unlink($getImg);
// 		}
		
// 		if($this->input->post('orr_icon') && $_FILES['orr_icon']['name'] == '')
// 			$data['orr_icon'] = $this->input->post('orr_icon');
			
// 		if($this->input->post('orr_icon') == '' && $_FILES['orr_icon']['name'] == '')
// 			@unlink($getImg);
		//if primary id set then we have to make update query
		if($this->cPrimaryId != '')
		{
			unset($data['orr_key']);
			$this->db->set('orr_modified_date', 'NOW()', FALSE);
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
		saveAdminLog($this->router->class, @$data['orr_name'], $this->cTableName, $this->cAutoId, $last_id, $logType);
		setFlashMessage('success','Order Status has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
// 				$getImg = getField('order_status_icon', $this->cTableName, $this->cAutoId, $id);
// 				@unlink($getImg);
				$getName = getField('orr_name', $this->cTableName, $this->cAutoId, $id);
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
		
		$data['orr_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
		//echo $this->db->last_query();
		
	}
	
/*
+------------------------------------------------------+
	Function will resize image size.
	small icon size : 30x30
+------------------------------------------------------+
*/	
	function resizeUploadImage()
	{
		/*$file_size = str_replace('M','',ini_get('upload_max_filesize'));
		$object_size = convertToMb($_FILES['category_image']['size']);
		
		if($file_size < $object_size)
			setFlashMessage('error','Upload limit exceed.');
		else*/ 
		{
			$image = uploadFile('order_status_icon','image','order_status'); //input file, type, folder
			if(@$image['error'])
			{
				setFlashMessage('error',$image['error']);
				redirect('admin/'.$this->router->class);
				
			}
			/*$width = getField('image_size_width','image_size','image_size_id',$this->input->post('image_size_id'));
			$height = getField('image_size_height','image_size','image_size_id',$this->input->post('image_size_id'));
			$path = $image['path'];*/
			$sizeArr = $this->db->where('image_size_id',$this->input->post('image_size_id'))->where('image_size_status','0')->get('image_size')->row_array();
			$path = $image['path'];
			$dest = getResizeFileNameByPath($path,'s',''); //image path, type(s,m), folder
			$returnFlag = resize_image($path, $dest, @$sizeArr['image_size_width'], @$sizeArr['image_size_height']); //source, destination, width, height
			@unlink($path); //delete old image
			return $dest;
		}
	}

}