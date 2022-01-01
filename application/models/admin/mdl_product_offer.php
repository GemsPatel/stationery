<?php
class mdl_product_offer extends CI_Model
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
			$text_filter = $this->input->get('text_filter');
			$key_filter = $this->input->get('key_filter');
		
			if(isset($text_filter) && $text_filter != "") // text box value search
				$this->db->where('product_offer_name LIKE \''.$text_filter.'%\' ');
			if(isset($key_filter) && $key_filter != "") // text box value search
				$this->db->where('product_offer_key LIKE \''.$key_filter.'%\' ');	
								
			if(isset($status_filter) && $status_filter != "") // status wise fiter 
				$this->db->where('product_offer_status LIKE \''.$status_filter.'\' ');
			
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
		
		$getImg = getField('product_offer_icon', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
		
		if($this->input->post('product_offer_icon') && $_FILES['product_offer_icon']['name'])
		{
			$data['product_offer_icon'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != '')
				@unlink($getImg);
		}
		
		if($this->input->post('product_offer_icon') && $_FILES['product_offer_icon']['name'] == '')
			$data['product_offer_icon'] = $this->input->post('product_offer_icon');
			
		if($this->input->post('product_offer_icon') == '' && $_FILES['product_offer_icon']['name'] == '')
			@unlink($getImg);
			
		if($this->cPrimaryId != '')
		{
			$this->db->set('product_offer_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			$data['product_offer_key'] = strtoupper($data['product_offer_key']);
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}
		saveAdminLog($this->router->class, @$data['product_offer_name'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Product Offer  has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				
				//$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
				$res = $this->db->query("SELECT product_id FROM product where product_offer_id = '".$id."'");
				$res = $res->num_rows();
				
				if(isset($res) && $res > 0)
				{
					
					$returnArr['type'] ='error';
					$returnArr['msg'] = " This product offer cannot be deleted as it is currently assigned to <b>".$res."</b> products!";
					echo json_encode($returnArr);
					return;		
				}
				else
				{
					$getName = getField('product_offer_name', $this->cTableName, $this->cAutoId, $id);
					saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
					$getImg = getField('product_offer_icon', $this->cTableName, $this->cAutoId, $id);
					@unlink($getImg);
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
		
		$data['product_offer_status'] = $status;
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
			$image = uploadFile('product_offer_icon','image','product_offer'); //input file, type, folder
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