<?php
class mdl_diamond_shape extends CI_Model
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
		
		
		$getImg = getField('diamond_shape_icon', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
		
		if($this->input->post('diamond_shape_icon') && $_FILES['diamond_shape_icon']['name'])
		{
			$data['diamond_shape_icon'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != '')
				@unlink($getImg);
		}
		
		if($this->input->post('diamond_shape_icon') && $_FILES['diamond_shape_icon']['name'] == '')
			$data['diamond_shape_icon'] = $this->input->post('diamond_shape_icon');
			
		if($this->input->post('diamond_shape_icon') == '' && $_FILES['diamond_shape_icon']['name'] == '')
			@unlink($getImg);
		//if primary id set then we have to make update query
		if($this->cPrimaryId != '')
		{
			unset($data['diamond_shape_key']);
			$this->db->set('diamond_shape_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			$data['diamond_shape_key'] = strtoupper($data['diamond_shape_key']);
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}
		saveAdminLog($this->router->class, @$data['diamond_shape_name'], $this->cTableName, $this->cAutoId, $last_id, $logType);
		setFlashMessage('success','Diamond Shape has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				$tabNameArr = array('0'=>'product_center_stone','1'=>'product_side_stone1','2'=>'product_side_stone2');
				$fieldNameArr = array('0'=>'pcs_diamond_shape_id','1'=>'pss1_diamond_shape_id','2'=>'pss2_diamond_shape_id');
				$res=isImageIdExist($tabNameArr,$fieldNameArr,$id);// this function call for un delete field
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				else
				{
					$getImg = getField('diamond_shape_icon', $this->cTableName, $this->cAutoId, $id);
					@unlink($getImg);
					$getName = getField('diamond_shape_name', $this->cTableName, $this->cAutoId, $id);
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
		
		$data['diamond_shape_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}
	
	function resizeUploadImage()
	{
		
		{
			$image = uploadFile('diamond_shape_icon','image','diamond_shape'); //input file, type, folder
			if(@$image['error'])
			{
				setFlashMessage('error',$image['error']);
				redirect('admin/'.$this->router->class);
				
			}
			$width = getField('image_size_width','image_size','image_size_id',$this->input->post('image_size_id'));
			$height = getField('image_size_height','image_size','image_size_id',$this->input->post('image_size_id'));
			$path = $image['path'];
			$dest = getResizeFileNameByPath($path,'s',''); //image path, type(s,m), folder
			$returnFlag = resize_image($path, $dest, $width,$height); //source, destination, width, height
			@unlink($path); //delete old image
			return $dest;
		}
	}


}