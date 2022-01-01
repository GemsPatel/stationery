<?php
class mdl_banner extends CI_Model
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
			$cat_filter = $this->input->get('cat_filter');
			$status_filter = $this->input->get('status_filter');
			$text_name = $this->input->get('text_name');
						
			if(isset($text_name) && $text_name != "")
				$this->db->where('banner_name LIKE \''.$text_name.'%\' ');
			
			if(isset($cat_filter) && $cat_filter != "")
				$this->db->where('(banner_category_map.category_id = \''.$cat_filter.'\')');
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('banner_status LIKE \''.$status_filter.'\' ');
			
			if($f !='' && $s != '' && check_db_column($this->cTableName,$f))
				$this->db->order_by($f,$s);
			else
				$this->db->order_by('banner.banner_id','ASC');
				
				
			if( MANUFACTURER_ID != 7 )
			{
				/**
				 * Gautam: commented on 16-04-2015
				 * do not display proper data.
				 */
				$this->db->select( " banner_cctld.*,banner_category_map.category_id " );
				$this->db->join('banner_cctld', 'banner_cctld.banner_id=banner.banner_id', 'INNER');
				$this->db->join('banner_category_map','banner_category_map.banner_id=banner_cctld.banner_id',"LEFT");
				$this->db->join('product_categories_cctld','product_categories_cctld.category_id=banner_category_map.category_id',"LEFT");
				$this->db->where( 'banner_cctld.manufacturer_id', MANUFACTURER_ID);
				$this->db->group_by('banner_cctld.banner_id');
			}
				
		}
		else if($this->cPrimaryId != '')
		{
			/**
			 * Cloudwebs: commented on 28-03-2015
			 */
			//$this->db->where('banner_cctld.banner_id',$this->cPrimaryId);
			
			if( MANUFACTURER_ID != 7 )
			{
				/**
				 * Gautam: commented on 16-04-2015
				 * do not display proper data.
				 */
				$this->db->select( " banner_cctld.*,banner_category_map.category_id " );
				$this->db->join('banner_cctld', 'banner_cctld.banner_id=banner.banner_id', 'INNER');
				$this->db->join('banner_category_map','banner_category_map.banner_id=banner_cctld.banner_id',"LEFT");
				$this->db->join('product_categories_cctld','product_categories_cctld.category_id=banner_category_map.category_id',"LEFT");
				$this->db->where( 'banner_cctld.manufacturer_id', MANUFACTURER_ID);
				$this->db->group_by('banner_cctld.banner_id');
				
				//$this->db->where( $this->cTableName."_cctld.".$this->cAutoId, $this->cPrimaryId);
			}
			else
			{
				//$this->db->where( $this->cTableName.".".$this->cAutoId, $this->cPrimaryId);
			}
			
			$this->db->where( $this->cTableName.".".$this->cAutoId, $this->cPrimaryId);
		}
		
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		//die;
		return $res;
		
	}
	
	function saveData()
	{
		// post data for insert and edit
		$data = $this->input->post();
		
		// unset item id 
		unset($data['item_id']);
		$category_idArr = @$data['category_id'];
		$banner_name = @$data['banner_name'];
		
		unset($data['category_id']);
		$getImg = '';
		
		if( $this->cPrimaryId != '' )
		{
			if( MANUFACTURER_ID == 7 )
				$getImg = exeQuery( " SELECT banner_image FROM ".$this->cTableName." WHERE ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'banner_image' ); 
			else
				$getImg = exeQuery( " SELECT banner_image FROM ".$this->cTableName."_cctld WHERE manufacturer_id=".MANUFACTURER_ID." AND ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'banner_image' ); 			
		}
		if($this->input->post('banner_image') && $_FILES['banner_image']['name'])
		{
			$data['banner_image'] = $this->resizeUploadImage(); //upload and resize image		
			if(!empty($getImg))
			{
				//@unlink($getImg);
			}
		}		
		if($this->input->post('banner_image') && $_FILES['banner_image']['name'] == '')
			$data['banner_image'] = $this->input->post('banner_image');			
		if($this->input->post('banner_image') == '' && $_FILES['banner_image']['name'] == ''){
			//@unlink($getImg);
		}
		
		$last_id =0;
		if($this->cPrimaryId != '')
		{
			//UML: ccTLD -> specific feature
			$this->bannerCcTld( true, $this->cPrimaryId, $data );
			
			$this->db->set('banner_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
			
			//UML: ccTLD -> specific feature
			$this->bannerCcTld( false, $last_id, $data );
		}
		
		//first delete old mapping then insert into banner_category_map table
		if($this->cPrimaryId != '')
		{
			$this->db->where("banner_id",$last_id)->delete("banner_category_map");
			if(!empty($category_idArr))
			{
				foreach($category_idArr as $k=>$ar)
				{
					$this->db->insert("banner_category_map",array('banner_id'=>$last_id,'category_id'=>$ar));
				}
			}
		}
		else // insert into banner_category_map table
		{
			if(!empty($category_idArr))
			{
				foreach($category_idArr as $k=>$ar)
				{
					$this->db->insert("banner_category_map",array('banner_id'=>$last_id,'category_id'=>$ar));
				}
			}
		}
		
		saveAdminLog($this->router->class, @$banner_name, $this->cTableName, $this->cAutoId, $last_id, $logType);
		setFlashMessage('success','Banner has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
		$returnArr = array();
		if($ids)
		{	
			foreach($ids as $img)
			{	//image path delete on folder
			
			}
			foreach($ids as $id)
			{
				$tabNameArr = array(0=>'module_manager');
				$fieldNameArr = array(0=>array('0'=>'module_manager_table_name','1'=>'module_manager_primary_id'));
				$valArr = array(0=>array('0'=>'banner','1'=>$id));
				$res=isFieldIdExistMul($tabNameArr,$fieldNameArr,$valArr);
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				else
				{
					$getImg = getField('banner_image', $this->cTableName, $this->cAutoId, $id);
					$getName = getField('banner_name', $this->cTableName, $this->cAutoId, $id);
					saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
					
					// records of banner_id is deleted from  banner_category_map when banner is deleted
					$this->db->where_in('banner_id',$id)->delete('banner_category_map');  
					
					//ccTLD					
					$this->db->where_in( $this->cAutoId, $id)->delete( $this->cTableName."_cctld" );
					
					$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);

					//delete Images
					@unlinkFile($getImg);
					
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
		$data['banner_status'] = $status;
		
		if(  MANUFACTURER_ID == 7 )	
		{
			$this->db->where($this->cAutoId,$cat_id);
			$this->db->update($this->cTable,$data);
		}
		else	//ccTLDs
		{
			$this->bannerCcTld( true, $cat_id, $data );
		}		
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
		$object_size = convertToMb($_FILES['article_image']['size']);
		
		if($file_size < $object_size)
			setFlashMessage('error','Upload limit exceed.');
		else*/ 
		{
			$image = uploadFile('banner_image','image','banner'); //input file, type, folder
			if(@$image['error'])
			{
				setFlashMessage('error',$image['error']);
				redirect('admin/'.$this->router->class);
				
			}
		/*	$width = getField('image_size_width','image_size','image_size_id',$this->input->post('image_size_id'));
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
	function bannerCcTld( $is_update, $banner_id, &$data )
	{
		$ccTldData = array();
		
		//ccTLD data
		$ccTldData['banner_id'] = $banner_id;

		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )	
			{
				//ccTLD data
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID; 
				$ccTldData['banner_status'] = $data['banner_status'];
				
				if( isset($data['banner_name']) )
				{
					$ccTldData['banner_name'] = $data['banner_name'];
					$ccTldData['banner_image'] = $data['banner_image'];
					$ccTldData['banner_image_alt_text'] = $data['banner_image_alt_text'];
					$ccTldData['image_size_id'] = $data['image_size_id'];
					$ccTldData['banner_description'] = $data['banner_description'];
					$ccTldData['banner_link'] = $data['banner_link'];
					$ccTldData['banner_sort_order'] = $data['banner_sort_order'];
					//$ccTldData['banner_status'] = $data['banner_status'];
			
					unset( $data['banner_name'] );
					unset( $data['banner_image'] );
					unset( $data['banner_image_alt_text'] );
					unset( $data['image_size_id'] );
					unset( $data['banner_description'] );
					unset( $data['banner_link'] );
					unset( $data['banner_sort_order'] );
					//unset( $data['banner_status'] );
				}
				
				$this->saveupdbannerCcTld( $ccTldData );
				unset( $data['banner_status'] );
			}
		}
		else
		{
			$resManuf = getManufacturers();
			
			if( isset($data['banner_name']) )
			{
				$ccTldData['banner_name'] = $data['banner_name'];
				$ccTldData['banner_key'] = $data['banner_key'];
				$ccTldData['banner_image'] = $data['banner_image'];
				$ccTldData['banner_image_alt_text'] = $data['banner_image_alt_text'];
				$ccTldData['image_size_id'] = $data['image_size_id'];
				$ccTldData['banner_description'] = $data['banner_description'];
				$ccTldData['banner_link'] = $data['banner_link'];
				$ccTldData['banner_sort_order'] = $data['banner_sort_order'];
				//$ccTldData['banner_status'] = $data['banner_status'];
		
				unset( $data['banner_name'] );
				unset( $data['banner_key'] );
				unset( $data['banner_image'] );
				unset( $data['banner_image_alt_text'] );
				unset( $data['image_size_id'] );
				unset( $data['banner_description'] );
				unset( $data['banner_link'] );
				unset( $data['banner_sort_order'] );
				//unset( $data['banner_status'] );
			}
			
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 0; //to resolve bug 374 by default enable
				if( $ar['manufacturer_id'] == 7 )	//primary Key
				{
					if( MANUFACTURER_ID != 7 )
					{
						$this->db->where( 'banner_id', $banner_id)->update( $this->cTableName, array( 'banner_status' => $statusTemp ) );
					}
				}
				else
				{
					if(  $ar['manufacturer_id'] == MANUFACTURER_ID )	
					{
						$statusTemp = $data['banner_status'];
					}
					
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					$ccTldData['banner_status'] = $statusTemp;
					$this->saveupdbannerCcTld( $ccTldData );
				}
			}

			unset( $data['banner_status'] );
		}
	}

	/**
	 * function will return dia filter price and weight min and max 
	 */
	function saveupdbannerCcTld( $data )
	{
		$update="";
		foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
		{
			$val = ( $val != '' ) ? $val : '';
			$update .= $key."='".$val."', ";
		}
		$update .= "banner_cctld_modified_date=NOW()";
 		
		$this->db->query( $this->db->insert_string( $this->cTableName."_cctld", $data).' ON DUPLICATE KEY UPDATE '.$update );
	}

}