<?php
class mdl_article extends CI_Model
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
			$cat_filter = $this->input->get('cat_filter');
			$status_filter = $this->input->get('status_filter');
			$article_name_filter = $this->input->get('article_name_filter');
			$article_key_filter = $this->input->get('article_key_filter');
			
			if(isset($article_name_filter) && $article_name_filter != "")
			{
				if( MANUFACTURER_ID != 7 )
					$this->db->where($this->cTableName.'_cctld.article_name LIKE \''.$article_name_filter.'%\' ');
				else
					$this->db->where($this->cTableName.'.article_name LIKE \''.$article_name_filter.'%\' ');
			}
			if(isset($article_key_filter) && $article_key_filter != "")
			{
				if( MANUFACTURER_ID != 7 )
					$this->db->where($this->cTableName.'_cctld.article_key LIKE \''.$article_key_filter.'%\' ');
				else
					$this->db->where($this->cTableName.'.article_key LIKE \''.$article_key_filter.'%\' ');
			}
			if(isset($cat_filter) && $cat_filter != "")
			{
				if( MANUFACTURER_ID != 7 )
					$this->db->where($this->cTableName.'_cctld.article_category_id',$cat_filter);
				else
					$this->db->where($this->cTableName.'.article_category_id',$cat_filter);
			}
			if(isset($status_filter) && $status_filter != "")
			{
				if( MANUFACTURER_ID != 7 )
					$this->db->where($this->cTableName.'_cctld.article_status='.$status_filter.' ');
				else
					$this->db->where($this->cTableName.'.article_status='.$status_filter.' ');
			}
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoId,'ASC');
				
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( $this->cTableName."_cctld.*, ".$this->cTableName.".article_created_date as article_created_date, ".$this->cTableName.".article_modified_date as article_modified_date " );
	 		    $this->db->join( $this->cTableName.'_cctld', $this->cTableName.'_cctld.article_id = '.$this->cTableName.'.article_id', 'INNER');	
				$this->db->where( $this->cTableName.'_cctld.manufacturer_id', MANUFACTURER_ID);
			}
				
		}
		else if($this->cPrimaryId != '')
		{
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( $this->cTableName."_cctld.*, ".$this->cTableName.".article_created_date as article_created_date, ".$this->cTableName.".article_modified_date as article_modified_date " );
	 		    $this->db->join( $this->cTableName.'_cctld', $this->cTableName.'_cctld.article_id = '.$this->cTableName.'.article_id', 'INNER');	
				$this->db->where( $this->cTableName.'_cctld.manufacturer_id', MANUFACTURER_ID);
	
				$this->db->where( $this->cTableName."_cctld.".$this->cAutoId, $this->cPrimaryId);
			}
			else
			{
				$this->db->where( $this->cTableName.".".$this->cAutoId, $this->cPrimaryId);
			}			
		}
			
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
		$data['article_alias'] = strtolower(url_title($data['article_name']));
		$getImg="";
		if( $this->cPrimaryId != '' )
		{
			if( MANUFACTURER_ID == 7 )
				$getImg = exeQuery( " SELECT article_image FROM ".$this->cTableName." WHERE ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'article_image' ); 
			else
				$getImg = exeQuery( " SELECT article_image FROM ".$this->cTableName."_cctld WHERE manufacturer_id=".MANUFACTURER_ID." AND ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'article_image' ); 			
		}		
		if($this->input->post('article_image') && $_FILES['article_image']['name'])
		{
			$data['article_image'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != ''){
				//@unlink($getImg);
			}
		}
		
		if($this->input->post('article_image') && $_FILES['article_image']['name'] == '')
			$data['article_image'] = $this->input->post('article_image');
			
		if($this->input->post('article_image') == '' && $_FILES['article_image']['name'] == ''){
			//@unlink($getImg);
		}
		$article_name = @$data['article_name'];
		if($this->cPrimaryId != '')
		{
			//UML: ccTLD -> specific feature
			$this->articleCcTld( true, $this->cPrimaryId, $data );
			
			$this->db->set('article_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			$data['article_key'] = strtoupper($data['article_key']);
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';	
			
			//UML: ccTLD -> specific feature
			$this->articleCcTld( false, $last_id, $data );
		}
		saveAdminLog($this->router->class, @$article_name, $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Article has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				$tabNameArr = array(0=>'module_manager');
				$fieldNameArr = array(0=>array('0'=>'module_manager_table_name','1'=>'module_manager_primary_id'));
				$valArr = array(0=>array('0'=>'article','1'=>$id));
				$res=isFieldIdExistMul($tabNameArr,$fieldNameArr,$valArr);
				
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				else
				{	
					$getImg = getField('article_image', $this->cTableName, $this->cAutoId, $id);
					 @unlinkFile($getImg);
					
					$getName = getField('article_name', $this->cTableName, $this->cAutoId, $id);
					saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');

					//ccTLD
					$this->db->where_in( $this->cAutoId, $id)->delete( $this->cTableName."_cctld" );
						
					//
					$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
					
					//HELD FOR removal
					//$this->db->where_in('article_category_id',$id)->delete($this->cTableName);
					
					$returnArr['type'] ='success';
					$returnArr['msg'] = count($ids)." records has been deleted successfully.";
				}
				
			}
		
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
		
		$data['article_status'] = $status;
		if(  MANUFACTURER_ID == 7 )	
		{
			$this->db->where($this->cAutoId,$cat_id);
			$this->db->update($this->cTable,$data);
		}
		else	//ccTLDs
		{
			$this->articleCcTld( true, $cat_id, $data );
		}		
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
			$image = uploadFile('article_image','image','article'); //input file, type, folder
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
	
	/**
	 * function will return dia filter price and weight min and max 	 
	 */
	function articleCcTld( $is_update, $article_id, &$data )
	{
		$ccTldData = array();
		
		//ccTLD data
		$ccTldData['article_id'] = $article_id;

		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )	
			{
				//ccTLD data
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID; 
				$ccTldData['article_status'] = $data['article_status'];
				
				if( isset($data['article_name']) )
				{
					$ccTldData['article_name'] = $data['article_name'];
					$ccTldData['article_alias'] = $data['article_alias'];
					$ccTldData['article_key'] = $data['article_key'];
					$ccTldData['article_category_id'] = $data['article_category_id'];
					$ccTldData['article_image'] = $data['article_image'];
					$ccTldData['image_size_id'] = $data['image_size_id'];
					$ccTldData['article_description'] = mysql_real_escape_string($data['article_description']);
					$ccTldData['article_sort_order'] = $data['article_sort_order'];
					$ccTldData['custom_page_title'] = $data['custom_page_title'];
					$ccTldData['meta_keyword'] = $data['meta_keyword'];
					$ccTldData['meta_description'] = mysql_real_escape_string($data['meta_description']);
					$ccTldData['robots'] = $data['robots'];
					$ccTldData['author'] = $data['author'];
					$ccTldData['content_rights'] = $data['content_rights'];
						
					unset( $data['article_name'] );
					unset( $data['article_alias'] );
					unset( $data['article_key'] );
					unset( $data['article_category_id'] );
					unset( $data['article_image'] );
					unset( $data['image_size_id'] );
					unset( $data['article_description'] );
					unset( $data['article_sort_order'] );
					unset( $data['custom_page_title'] );
					unset( $data['meta_keyword'] );
					unset( $data['meta_description'] );
					unset( $data['robots'] );
					unset( $data['author'] );
					unset( $data['content_rights'] );
					
				}
				
				$this->saveupdarticleCcTld( $ccTldData );
				unset( $data['article_status'] );
			}
		}
		else
		{
			$resManuf = getManufacturers();
			$ccTldData['article_status'] = $data['article_status'];
			
			if( isset($data['article_name']) )
			{
				$ccTldData['article_name'] = $data['article_name'];
					$ccTldData['article_alias'] = $data['article_alias'];
					$ccTldData['article_key'] = $data['article_key'];
					$ccTldData['article_category_id'] = $data['article_category_id'];
					$ccTldData['article_image'] = $data['article_image'];
					$ccTldData['image_size_id'] = $data['image_size_id'];
					$ccTldData['article_description'] = mysql_real_escape_string($data['article_description']);
					$ccTldData['article_sort_order'] = $data['article_sort_order'];
					$ccTldData['custom_page_title'] = $data['custom_page_title'];
					$ccTldData['meta_keyword'] = $data['meta_keyword'];
					$ccTldData['meta_description'] = mysql_real_escape_string($data['meta_description']);
					$ccTldData['robots'] = $data['robots'];
					$ccTldData['author'] = $data['author'];
					$ccTldData['content_rights'] = $data['content_rights'];
						
					unset( $data['article_name'] );
					unset( $data['article_alias'] );
					unset( $data['article_key'] );
					unset( $data['article_category_id'] );
					unset( $data['article_image'] );
					unset( $data['image_size_id'] );
					unset( $data['article_description'] );
					unset( $data['article_sort_order'] );
					unset( $data['custom_page_title'] );
					unset( $data['meta_keyword'] );
					unset( $data['meta_description'] );
					unset( $data['robots'] );
					unset( $data['author'] );
					unset( $data['content_rights'] );
			}
			
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 0; //to resolve bug 374 by default enable
				if( $ar['manufacturer_id'] == 7 )	//primary Key
				{
					if( MANUFACTURER_ID != 7 )
					{
						$this->db->where( 'article_id', $article_id)->update( $this->cTableName, array( 'article_status' => $statusTemp ) );	
					}
				}
				else
				{
					if(  $ar['manufacturer_id'] == MANUFACTURER_ID )	
					{
						$statusTemp = $data['article_status'];
					}
					else 
					{
						$statusTemp = 0; 
					}
					
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					$ccTldData['article_status'] = $statusTemp;
					$this->saveupdarticleCcTld( $ccTldData );
				}
			}

			unset( $data['article_status'] );
		}
	}

	/**
	 * function will return dia filter price and weight min and max 
	 */
	function saveupdarticleCcTld( $data )
	{
		$update="";
		foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
		{
			$val = ( $val != '' ) ? $val : 0;
			$update .= $key."='".$val."', ";
		}
		$update .= "article_cctld_modified_date=NOW()";

		$this->db->query( $this->db->insert_string( $this->cTableName."_cctld", $data).' ON DUPLICATE KEY UPDATE '.$update );
	}


}