<?php
class mdl_payment_method extends CI_Model
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
			
		
			if(isset($text_name) && $text_name != "") // text box value search
				$this->db->where('payment_method_name LIKE \''.$text_name.'%\' ');
			
			if(isset($text_key) && $text_key != "") // text box value search
				$this->db->where('payment_method_key LIKE \''.$text_key.'%\' ');
					
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
		
		$getImg = getField('payment_method_icon', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
		
		if($this->input->post('payment_method_icon') && $_FILES['payment_method_icon']['name'])
		{
			$data['payment_method_icon'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != '')
				@unlink($getImg);
		}
		
		if($this->input->post('payment_method_icon') && $_FILES['payment_method_icon']['name'] == '')
			$data['payment_method_icon'] = $this->input->post('payment_method_icon');
			
		if($this->input->post('payment_method_icon') == '' && $_FILES['payment_method_icon']['name'] == '')
			@unlink($getImg);
			
		if($this->cPrimaryId != '')
		{
			$this->db->set('payment_method_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			$data['payment_method_key'] = strtoupper($data['payment_method_key']);
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}
		saveAdminLog($this->router->class, @$data['payment_method_name'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','payment Method has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				
				$tabNameArr = array('0'=>'orders');
				$fieldNameArr = array('0'=>'payment_method_id');
				$res=isImageIdExist($tabNameArr,$fieldNameArr,$id);// this function call for un delete field
				
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				else
				{	
						$getImg = getField('payment_method_icon', $this->cTableName, $this->cAutoId, $id);
						@unlink($getImg);
						$getName = getField('payment_method_name', $this->cTableName, $this->cAutoId, $id);
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
		
		$data['payment_method_status'] = $status;
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
			$image = uploadFile('payment_method_icon','image','payment_method'); //input file, type, folder
			if(@$image['error'])
			{
				setFlashMessage('error',$image['error']);
				redirect('admin/'.$this->router->class);
				
			}
			$sizeArr = $this->db->where('image_size_id',$this->input->post('image_size_id'))->where('image_size_status','0')->get('image_size')->row_array();
			$path = $image['path'];
			$dest = getResizeFileNameByPath($path,'m',''); //image path, type(s,m), folder
			$returnFlag = resize_image($path, $dest, @$sizeArr['image_size_width'], @$sizeArr['image_size_height']); //source, destination, width, height
			@unlink($path); //delete old image
			return $dest;
		}
	}


}