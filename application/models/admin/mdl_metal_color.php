<?php
class mdl_metal_color extends CI_Model
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
		
		$getImg = getField('metal_color_icon', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
		
		if($this->input->post('metal_color_icon') && $_FILES['metal_color_icon']['name'])
		{
			$data['metal_color_icon'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != '')
				@unlink($getImg);
		}
		
		if($this->input->post('metal_color_icon') && $_FILES['metal_color_icon']['name'] == '')
			$data['metal_color_icon'] = $this->input->post('metal_color_icon');
			
		if($this->input->post('metal_color_icon') == '' && $_FILES['metal_color_icon']['name'] == '')
			@unlink($getImg);

		//if primary id set then we have to make update query
		if($this->cPrimaryId != '')
		{
			unset($data['metal_color_key']);
			$this->db->set('metal_color_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			$data['metal_color_key'] = strtoupper($data['metal_color_key']);
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}
		saveAdminLog($this->router->class, @$data['metal_color_name'], $this->cTableName, $this->cAutoId, $last_id, $logType);
		setFlashMessage('success','Metal Color has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				$tabNameArr = array('0'=>'metal_price');
				$fieldNameArr = array('0'=>'metal_color_id');
				$res=isImageIdExist($tabNameArr,$fieldNameArr,$id);// this function call for un delete field
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				else
				{
					$getImg = getField('metal_color_icon', $this->cTableName, $this->cAutoId, $id);
					@unlink($getImg);
					$getName = getField('metal_color_name', $this->cTableName, $this->cAutoId, $id);
					saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
					$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
					$returnArr['type'] ='success';
					$returnArr['msg'] = count($ids)." records has been deleted successfully.";
			}
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
		
		$data['metal_color_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}
/*
+------------------------------------------------------+
	Function will resize image size.
	small icon size : 30x30
+------------------------------------------------------+
*/	
	function resizeUploadImage()
	{
		$image = uploadFile('metal_color_icon','image','metal_color'); //input file, type, folder
		if(@$image['error'])
		{
			setFlashMessage('error',$image['error']);
			redirect('admin/'.$this->router->class);
		}
		$width = getField('image_size_width','image_size','image_size_id',$this->input->post('image_size_id'));
		$height = getField('image_size_height','image_size','image_size_id',$this->input->post('image_size_id'));
		$path = $image['path'];
		$dest = getResizeFileNameByPath($image['path'],'s',''); //image path, type(s,m), folder
		$returnFlag = resize_image($path, $dest, $width,$height); //source, destination, width, height
		@unlink($path); //delete old image
		return $dest;
	}

}