<?php
class mdl_admin_menu extends CI_Model
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
			$menu_name_filter = $this->input->get('menu_name_filter');
			$class_name_filter = $this->input->get('class_name_filter');
			$status_filter = $this->input->get('status_filter');
			
			if(isset($menu_name_filter) && $menu_name_filter != "")
				$this->db->where('(am_name LIKE \'%'.$menu_name_filter.'%\' )');
				
			if(isset($class_name_filter) && $class_name_filter != "")
				$this->db->where('(am_class_name LIKE \'%'.$class_name_filter.'%\' )');
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('am_status LIKE \''.$status_filter.'\' ');

			$this->db->where('am_parent_id',0);			
			if($f !='' && $s != '' && check_db_column($this->cTableName,$f))
				$this->db->order_by($f,$s);
			else
			{

				$this->db->order_by($this->cAutoId,'ASC');
			}
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
		$am_class_name = $data['am_class_name'];
		//unset($data['am_class_name']);
		unset($data['item_id']);
	
//		pr($data);die;	
		$getImg = getField('am_icon', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
		
		if($this->input->post('am_icon')!='' && $_FILES['am_icon_file']['name']!='')
		{
			$data['am_icon'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != '')
				@unlink($getImg);
		}
		
		if($this->input->post('am_icon') && $_FILES['am_icon_file']['name'] == '')
			$data['am_icon'] = $this->input->post('am_icon');
			
		if($this->input->post('am_icon') == '' && $_FILES['am_icon_file']['name'] == '')
			@unlink($getImg);
		
		
		if($this->cPrimaryId != '')
		{
			unset($data['am_class_name']);
			$this->db->set('am_modified_date', 'NOW()', FALSE);
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
		
		saveAdminLog($this->router->class, $am_class_name, $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Admin menu has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
				
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
			foreach($ids as $img)
			{	//image path delete on folder
				$getImg = getField('am_icon', $this->cTableName, $this->cAutoId, $img);
				@unlinkFile($getImg);
			}
			
			foreach($ids as $id) //delete auto id
			{
				$getName = getField('am_class_name', $this->cTableName, $this->cAutoId, $id);
				saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
				$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
			}
			foreach($ids as $id) //delete parent id
				$this->db->where_in('am_parent_id',$id)->delete($this->cTableName);
			
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
		
		$data['am_status'] = $status;
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
		$image = uploadFile('am_icon_file','image','admin_menu'); //input file, type, folder
		
		if(@$image['error'])
		{
			setFlashMessage('error',$image['error']);
			redirect('admin/'.$this->router->class);	
		}
		$sizeArr = $this->db->where('image_size_id',$this->input->post('image_size_id'))->where('image_size_status','0')->get('image_size')->row_array();
		$path = $image['path'];
		
		$dest = getResizeFileNameByPath($path,'s',''); //image path, type(s,m), folder
		$returnFlag = resize_image($path, $dest, @$sizeArr['image_size_width'], @$sizeArr['image_size_height']); //source, destination, width, height
		@unlink($path); //delete old image
		return $dest;
	}

}