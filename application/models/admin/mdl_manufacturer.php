<?php
class mdl_manufacturer extends CI_Model
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
			$text_filter = $this->input->get('text_filter');
			
			if(isset($text_filter) && $text_filter != "")
				$this->db->where('manufacturer_name LIKE \''.$text_filter.'%\' ');
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('manufacturer_status LIKE \''.$status_filter.'\' ');
			
			if($f !='' && $s != '' && check_db_column($this->cTableName,$f))
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoId,'ASC');
				
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
					
		//$this->db->where('article_status','0');
			
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
		
		$getImg = getField('manufacturer_image', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
		
		if($this->input->post('manufacturer_image') && $_FILES['manufacturer_image']['name'])
		{
			$data['manufacturer_image'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != '')
				@unlink($getImg);
		}
		
		if($this->input->post('manufacturer_image') && $_FILES['manufacturer_image']['name'] == '')
			$data['manufacturer_image'] = $this->input->post('manufacturer_image');
			
		if($this->input->post('manufacturer_image') == '' && $_FILES['manufacturer_image']['name'] == '')
			@unlink($getImg);
		if($this->cPrimaryId != '')
		{
			$this->db->set('manufacturer_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			createFolderWithFile(strtolower($data['manufacturer_key']), $this->cPrimaryId); //create manufacturer folder			
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}
		saveAdminLog($this->router->class, @$data['manufacturer_name'], $this->cTableName, $this->cAutoId, $last_id, $logType);
		setFlashMessage('success','Manufacturer has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				$tabNameArr = array('0'=>'product');
				$fieldNameArr = array('0'=>'product_manufacturer_id');
				$res=isImageIdExist($tabNameArr,$fieldNameArr,$id);// this function call for un delete field
				
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				else
				{
					$getImg = getField('manufacturer_image', $this->cTableName, $this->cAutoId, $id);
					@unlink($getImg);
					$getName = getField('manufacturer_name', $this->cTableName, $this->cAutoId, $id);
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
		
		$data['manufacturer_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}
/*

/*
+------------------------------------------------------+
	Function will resize image size.
	small icon size : 30x30
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
			$image = uploadFile('manufacturer_image','image','manufacturer'); //input file, type, folder
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
			$dest = getResizeFileNameByPath($path,'m',''); //image path, type(s,m), folder
			$returnFlag = resize_image($path, $dest, @$sizeArr['image_size_width'], @$sizeArr['image_size_height']); //source, destination, width, height
			@unlink($path); //delete old image
			return $dest;
		}
	}


}