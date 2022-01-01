<?php
class mdl_article_category extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $Article = '';
	
	function getData()
	{
		
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$cat_filter = $this->input->get('cat_filter');
			$status_filter = $this->input->get('status_filter');
			$text_key = $this->input->get('text_key');
			
			if(isset($cat_filter) && $cat_filter != "")
				$this->db->where('(article_category_id LIKE \''.$cat_filter.'\' OR article_category_parent_id LIKE \''.$cat_filter.'\' )');
				
			if(isset($text_key) && $text_key != "") // text box value search
				$this->db->where('article_category_key LIKE \''.$text_key.'%\' ');	
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('article_category_status LIKE \''.$status_filter.'\' ');
			
			if($f !='' && $s != '' && check_db_column($this->cTableName,$f))
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoId,'ASC');
				
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
					
		//$this->db->where('category_status','0');
			
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
	
	function saveData()
	{
		// post data for insert and edit
		$data = $this->input->post();
		unset($data['item_id']);
		
		$data['article_category_alias'] = strtolower(url_title($data['article_category_name']));
		$getImg="";
		$getImg = getField('article_category_image', $this->cTableName, $this->cAutoId, $this->cPrimaryId);
		
		if($this->input->post('article_category_image') && $_FILES['article_category_image']['name'])
		{
			$data['article_category_image'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != '')
				@unlink($getImg);
		}
		
		if($this->input->post('article_category_image') && $_FILES['article_category_image']['name'] == '')
			$data['article_category_image'] = $this->input->post('article_category_image');
			
		if($this->input->post('article_category_image') == '' && $_FILES['article_category_image']['name'] == '')
			@unlink($getImg);
		
		
		
		if($this->cPrimaryId != '')
		{
			$this->db->set('article_category_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			$data['article_category_key'] = strtoupper($data['article_category_key']);
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}
		saveAdminLog($this->router->class, @$data['article_category_name'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Article Category has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
				
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
				
					$tabNameArr = array(0=>'front_menu',1=>'article');
					$fieldNameArr = array(0=>array('0'=>'front_menu_table_name','1'=>'front_menu_primary_id'),1=>array('0'=>'article_category_id'));
					$valArr = array(0=>array('0'=>'article_category','1'=>$id),1=>array('0'=>$id));
					$res=isFieldIdExistMul($tabNameArr,$fieldNameArr,$valArr);
					
					if(sizeof($res)>0)
					{
						echo json_encode($res);	
						return;
					}
					else
					{
						$getImg = getField('article_category_image', $this->cTableName, $this->cAutoId, $id);
						@unlinkFile($getImg);
						$getName = getField('article_category_name', $this->cTableName, $this->cAutoId, $id);
						saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
						$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
						$returnArr['type'] ='success';
						$returnArr['msg'] = count($ids)." records has been deleted successfully.";
					}
				}
				
				foreach($ids as $id) //delete parent id
				$this->db->where_in('article_category_parent_id',$id)->delete($this->cTableName);
				
				$returnArr['type'] ='success';
				$returnArr['msg'] = count($ids)." records has been deleted successfully.";
			
			
		}
		else
		{
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
		
		$data['article_category_status'] = $status;
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
		/*$file_size = str_replace('M','',ini_get('upload_max_filesize'));
		$object_size = convertToMb($_FILES['category_image']['size']);
		
		if($file_size < $object_size)
			setFlashMessage('error','Upload limit exceed.');
		else*/ 
		{
			$image = uploadFile('article_category_image','image','article_category'); //input file, type, folder
			if(@$image['error'])
			{
				setFlashMessage('error',$image['error']);
				redirect('admin/'.$this->router->class);
				
			}
			/*$width = getField('image_size_width','image_size','image_size_id',$this->input->post('image_size_id'));
			$height = getField('image_size_height','image_size','image_size_id',$this->input->post('image_size_id'));*/
			$sizeArr = $this->db->where('image_size_id',$this->input->post('image_size_id'))->where('image_size_status','0')->get('image_size')->row_array();
			$path = $image['path'];
			//$path = $image['path'];
			$dest = getResizeFileNameByPath($path,'s',''); //image path, type(s,m), folder
			$returnFlag = resize_image($path, $dest, @$sizeArr['image_size_width'], @$sizeArr['image_size_height']); //source, destination, width, height
			@unlink($path); //delete old image
			return $dest;
		}
	}
}