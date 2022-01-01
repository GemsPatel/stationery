<?php
class mdl_product_categories extends CI_Model
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
			$category_id = $this->input->get('category_id');
			
			if(isset($category_id) && $category_id != "")
				$this->db->where( $this->cTableName.'.category_id = '.(int)$category_id );
			
			if(isset($cat_filter) && $cat_filter != "")
			{
				if( MANUFACTURER_ID != 7 )
					$this->db->where('('.$this->cTableName.'_cctld.category_id LIKE \''.$cat_filter.'\' OR '.$this->cTableName.'_cctld.parent_id LIKE \''.$cat_filter.'\' )');
				else
					$this->db->where('('.$this->cTableName.'.category_id LIKE \''.$cat_filter.'\' OR '.$this->cTableName.'.parent_id LIKE \''.$cat_filter.'\' )');
			}
				
			if(isset($status_filter) && $status_filter != "")
			{
				if( MANUFACTURER_ID != 7 )
					$this->db->where( $this->cTableName.'_cctld.category_status LIKE \''.$status_filter.'\' ');
				else
					$this->db->where( $this->cTableName.'.category_status LIKE \''.$status_filter.'\' ');
				
			}
			
			
			if($f !='' && $s != '' )
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoId,'ASC');
				
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( " product_categories_cctld.*, product_categories.category_created_date as category_created_date, product_categories.category_modified_date as category_modified_date " );
	 		    $this->db->join('product_categories_cctld', 'product_categories_cctld.category_id = product_categories.category_id', 'INNER');	
				$this->db->where( 'product_categories_cctld.manufacturer_id', MANUFACTURER_ID);
			}
		}
		else if($this->cPrimaryId != '')
		{
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( " product_categories_cctld.*, product_categories.category_created_date as category_created_date, product_categories.category_modified_date as category_modified_date " );
	 		    $this->db->join('product_categories_cctld', 'product_categories_cctld.category_id=product_categories.category_id', 'INNER');	
				$this->db->where( 'product_categories_cctld.manufacturer_id', MANUFACTURER_ID);
	
				$this->db->where( "product_categories_cctld.".$this->cAutoId, $this->cPrimaryId);
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
		pr($data);
// 		die;
		unset($data['item_id']);
		
		//$data['category_alias'] = strtolower(url_title($data['category_name']));
		//category image
		$getImg = ""; 
		if( $this->cPrimaryId != '' )
		{
			if( MANUFACTURER_ID == 7 )
				$getImg = exeQuery( " SELECT category_image FROM ".$this->cTableName." WHERE ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'category_image' ); 
			else
				$getImg = exeQuery( " SELECT category_image FROM ".$this->cTableName."_cctld WHERE manufacturer_id=".MANUFACTURER_ID." AND ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'category_image' ); 			
		}
		
		if($this->input->post('category_image') && @$_FILES['category_image']['name'])
		{
			$data['category_image'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != ''){
				//@unlink($getImg);
			}
		}
		
		if($this->input->post('category_image') && @$_FILES['category_image']['name'] == '')
			$data['category_image'] = $this->input->post('category_image');			
		if($this->input->post('category_image') == '' && @$_FILES['category_image']['name'] == ''){
			//@unlink($getImg);
		}
		
		// banner		
		if( $this->cPrimaryId != '' )
		{
			if( MANUFACTURER_ID == 7 )
				$getBanner = exeQuery( " SELECT category_banner FROM ".$this->cTableName." WHERE ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'category_banner' ); 
			else
				$getBanner = exeQuery( " SELECT category_banner FROM ".$this->cTableName."_cctld WHERE manufacturer_id=".MANUFACTURER_ID." AND ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'category_banner' ); 			
		}
		
		if($this->input->post('category_banner') && @$_FILES['category_banner']['name'])
		{
			$data['category_banner'] = $this->resizeUploadImageBanner(); //upload and resize image		
			if($data['category_banner'] != '')
			{ 
				//@unlink($getBanner);
			}
		}	
		
		if($this->input->post('category_banner') && @$_FILES['category_banner']['name'] == '')
			$data['category_banner'] = $this->input->post('category_banner');			
		if($this->input->post('category_banner') == '' && @$_FILES['category_banner']['name'] == ''){
			//@unlink($getBanner);
		}
			
		//mobile image
		if( $this->cPrimaryId != '' )
		{
			if( MANUFACTURER_ID == 7 )
				$getImgM = exeQuery( " SELECT m_category_image FROM ".$this->cTableName." WHERE ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'm_category_image' ); 
			else
				$getImgM = exeQuery( " SELECT m_category_image FROM ".$this->cTableName."_cctld WHERE manufacturer_id=".MANUFACTURER_ID." AND ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'm_category_image' ); 			
		}
		
		if($this->input->post('m_category_image') && @$_FILES['m_category_image']['name'])
		{
			$data['m_category_image'] = $this->resizeUploadMobileImage('m_category_image'); //upload and resize image		
			if($getImgM != ''){
				//@unlink($getImgM);
			}
		}
		
		if($this->input->post('m_category_image') && @$_FILES['m_category_image']['name'] == '')
			$data['m_category_image'] = $this->input->post('m_category_image');	
		if($this->input->post('m_category_image') == '' && @$_FILES['m_category_image']['name'] == ''){
			//@unlink($getImgM);
		}
		
		$categoryName = @$data['category_name'];
		$this->db->set('category_modified_date', 'NOW()', FALSE);
		if($this->cPrimaryId != '')
		{
			//UML: ccTLD -> specific feature
			$this->categoryCcTld( true, $this->cPrimaryId, $data );
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
			$this->categoryCcTld( false, $last_id, $data );
		}
		
		$data['category_brand_code'] = generateBrandCode($last_id); // this function generate unique category brand code
		//echo $data['category_brand_code'];
		
		$this->db->set('category_brand_code', $data['category_brand_code'], FALSE);
			$this->db->where($this->cAutoId,$last_id)->update($this->cTableName,$data);
		
		saveAdminLog($this->router->class, @$categoryName, $this->cTableName, $this->cAutoId, $last_id, $logType); //class name, item name, tablename, fieldname, primary id, type A/E/D
		setFlashMessage('success','Category has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
		return $last_id;
				
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
				
				$tabNameArr = array('0'=>'product','1'=>'product','2'=>'gift','3'=>'banner_category_map');
				$fieldNameArr = array('0'=>'category_id','1'=>'product_related_category_id','2'=>'category_id','3'=>'category_id');
				$res=isFieldIdExist($tabNameArr,$fieldNameArr,$id,true);
				
				$tabNameArr = array(0=>'front_menu');
				$fieldNameArr = array(0=>array('0'=>'front_menu_table_name','1'=>'front_menu_primary_id'));
				$valArr = array(0=>array('0'=>'product_categories','1'=>$id));
				$res2=isFieldIdExistMul($tabNameArr,$fieldNameArr,$valArr);
				
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				if(sizeof($res2)>0)
				{
					echo json_encode($res2);	
					return;
				}
				else
				{
					$getImg = getField('category_image', $this->cTableName, $this->cAutoId, $id);
					@unlink($getImg);
					
					$getBanner = getField('category_banner', $this->cTableName, $this->cAutoId, $id);
					@unlink($getBanner);
					
					$getImgM = getField('m_category_image', $this->cTableName, $this->cAutoId, $id);
					@unlink($getImgM);
					
					$getName = getField('category_name', $this->cTableName, $this->cAutoId, $id);
					saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
					
					$this->db->where_in('category_id',$id)->delete('banner_category_map'); // records of category_id is deleted form banner_category_map when caterory is deleted 

					/**
					 * Cloudwebs: commented on 04-07-2015
					 */
					//foreach($ids as $id) 
					//delete parent id
					//$this->db->where_in('parent_id',$id)->delete($this->cTableName);
								
					//ccTLD					
					$this->db->where_in( $this->cAutoId, $id)->delete( $this->cTableName."_cctld" );
					
					$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
					
					$returnArr['type'] ='success';
					$returnArr['msg'] = count($ids)." records has been deleted successfully.";
				}
			}

			/**
			 * Cloudwebs: commented on 04-07-2015
			 */
			//foreach($ids as $id) 
			//delete parent id
			//$this->db->where_in('parent_id',$id)->delete($this->cTableName);
			
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
		
		$data['category_status'] = $status;
		
		if(  MANUFACTURER_ID == 7 )	
		{
			$this->db->where($this->cAutoId,$cat_id);
			$this->db->update($this->cTable,$data);
		}
		else	//ccTLDs
		{
			$this->categoryCcTld( true, $cat_id, $data );
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
		$object_size = convertToMb($_FILES['category_image']['size']);
		
		if($file_size < $object_size)
			setFlashMessage('error','Upload limit exceed.');
		else*/ 
		{
			$image = uploadFile('category_image','image','category'); //input file, type, folder
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
	
	
	function resizeUploadImageBanner()
	{
		/*$file_size = str_replace('M','',ini_get('upload_max_filesize'));
		$object_size = convertToMb($_FILES['category_image']['size']);
		
		if($file_size < $object_size)
			setFlashMessage('error','Upload limit exceed.');
		else*/ 
		{
			$image = uploadFile('category_banner','image','category/banner/'); //input file, type, folder
			if(@$image['error'])
			{
				setFlashMessage('error',$image['error']);
				redirect('admin/'.$this->router->class);	
			}
			$sizeArr = $this->db->where('image_size_id',$this->input->post('banner_size_id'))->where('image_size_status','0')->get('image_size')->row_array();
			$path = $image['path'];
			
			$dest = getResizeFileNameByPath($path,'s',''); //image path, type(s,m), folder
			$returnFlag = resize_image($path, $dest, @$sizeArr['image_size_width'], @$sizeArr['image_size_height']); //source, destination, width, height
			@unlink($path); //delete old image
			return $dest;
			
		}
	}
	
	function resizeUploadMobileImage($inputName='')
	{
		$image = uploadFile($inputName,'image','category'); //input file, type, folder
		if(@$image['error'])
		{
			setFlashMessage('error',$image['error']);
			redirect('admin/'.$this->router->class);	
		}
		$sizeArr = $this->db->where('image_size_id',$this->input->post('m_image_size_id'))->where('image_size_status','0')->get('image_size')->row_array();
		$path = $image['path'];
		
		$dest = getResizeFileNameByPath($path,'s','m'); //image path, type(s,m), folder
		$returnFlag = resize_image($path, $dest, @$sizeArr['image_size_width'], @$sizeArr['image_size_height']); //source, destination, width, height
		@unlink($path); //delete old image
		return $dest;
	}
	
	/**
	 * function will return dia filter price and weight min and max 	 
	 */
	function categoryCcTld( $is_update, $category_id, &$data )
	{
		$ccTldData = array();
		
		//ccTLD data
		$ccTldData['category_id'] = $category_id;

		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )	
			{
				//ccTLD data
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID; 
				$ccTldData['category_status'] = $data['category_status'];
				
				if( isset($data['category_name']) )
				{
					$ccTldData['category_name'] = $data['category_name'];
					$ccTldData['category_alias'] = $data['category_alias'];
					$ccTldData['category_meta_name'] = $data['category_meta_name'];
					$ccTldData['parent_id'] = $data['parent_id'];
					$ccTldData['category_image'] = $data['category_image'];
					$ccTldData['image_size_id'] = $data['image_size_id'];
					$ccTldData['category_banner'] = $data['category_banner'];
					$ccTldData['banner_size_id'] = $data['banner_size_id'];
					$ccTldData['m_category_image'] = $data['m_category_image'];		
					$ccTldData['category_description'] = $data['category_description'];			
					$ccTldData['category_sort_order'] = $data['category_sort_order'];
					$ccTldData['custom_page_title'] = $data['custom_page_title'];
					$ccTldData['meta_keyword'] = $data['meta_keyword'];
					$ccTldData['meta_description'] = $data['meta_description'];
					$ccTldData['author'] = $data['author'];
					$ccTldData['content_rights'] = $data['content_rights'];
					$ccTldData['category_status'] = $data['category_status'];

					unset( $data['category_name'] );
					unset( $data['category_alias'] );
					unset( $data['category_meta_name'] );
					unset( $data['parent_id'] );
					unset( $data['category_image'] );
					unset( $data['image_size_id'] );
					unset( $data['category_banner'] );
					unset( $data['banner_size_id'] );
					unset( $data['m_category_image'] );
					unset( $data['category_description'] );
					unset( $data['category_sort_order'] );
					unset( $data['custom_page_title'] );
					unset( $data['meta_keyword'] );
					unset( $data['meta_description'] );
					unset( $data['author'] );
					unset( $data['content_rights'] );
				}
				
				$this->saveupdcategoryCcTld( $ccTldData );
				unset( $data['category_status'] );
			}
		}
		else
		{
			$resManuf = getManufacturers();
			
			if( isset($data['category_name']) )
			{
				$ccTldData['category_name'] = $data['category_name'];
				$ccTldData['category_alias'] = $data['category_alias'];
				$ccTldData['category_meta_name'] = $data['category_meta_name'];
				$ccTldData['parent_id'] = $data['parent_id'];
				$ccTldData['category_image'] = $data['category_image'];
				$ccTldData['image_size_id'] = $data['image_size_id'];
				$ccTldData['category_banner'] = $data['category_banner'];
				$ccTldData['banner_size_id'] = $data['banner_size_id'];
				$ccTldData['m_category_image'] = $data['m_category_image'];	
				$ccTldData['category_description'] = $data['category_description'];
				$ccTldData['category_sort_order'] = $data['category_sort_order'];
				$ccTldData['custom_page_title'] = $data['custom_page_title'];
				$ccTldData['meta_keyword'] = $data['meta_keyword'];
				$ccTldData['meta_description'] = $data['meta_description'];
				$ccTldData['author'] = $data['author'];
				$ccTldData['content_rights'] = $data['content_rights'];
				$ccTldData['category_status'] = $data['category_status'];
		
				unset( $data['category_name'] );
				unset( $data['category_alias'] );
				unset( $data['category_meta_name'] );
				unset( $data['parent_id'] );
				unset( $data['category_image'] );
				unset( $data['image_size_id'] );
				unset( $data['category_banner'] );
				unset( $data['banner_size_id'] );
				unset( $data['m_category_image'] );
				unset( $data['category_description'] );
				unset( $data['category_sort_order'] );
				unset( $data['custom_page_title'] );
				unset( $data['meta_keyword'] );
				unset( $data['meta_description'] );
				unset( $data['author'] );
				unset( $data['content_rights'] );
			}
			
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 0; //to resolve bug 374 by default enable
				if( $ar['manufacturer_id'] == 7 )	//primary perrian.com
				{
					if( MANUFACTURER_ID != 7 )
					{
						$this->db->where( 'category_id', $category_id)->update( $this->cTableName, array( 'category_status' => $statusTemp ) );	
					}
				}
				else
				{
					if(  $ar['manufacturer_id'] == MANUFACTURER_ID )	
					{
						$statusTemp = $data['category_status'];
					}
					
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					$ccTldData['category_status'] = $statusTemp;
					$this->saveupdcategoryCcTld( $ccTldData );
				}
			}

			unset( $data['category_status'] );
		}
	}

	/**
	 * function will return dia filter price and weight min and max 
	 */
	function saveupdcategoryCcTld( $data )
	{
		$update="";
		foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
		{
			$val = ( $val != '' ) ? $val : '';
			$update .= "`".$key."`='". addslashes( $val ) ."', ";
		}
		$update .= "category_cctld_modified_date=NOW()";
 		
		$this->db->query( $this->db->insert_string( "product_categories_cctld", $data).' ON DUPLICATE KEY UPDATE '.$update );
		
	}

	/*
	 * Function will get all sites language
	*/
	function getLanguagesForListing()
	{
		if($this->input->get('edit') == 'true')
		{
			$row = fetchRow("SELECT category_id, category_name FROM ".$this->cTableName." WHERE category_id=".$this->cPrimaryId." ");
			if( !isEmptyArr($row) )
			{
				$sel_query = " '".$row["category_id"]."' as item_id, '".$row["category_name"]."' as item_name ";
				return getLanguagesForItemListing( $sel_query );
			}
			else
			{
				return array();
			}
		}
		else if($this->input->get('insert') == 'true')
		{
			return getInventoryListing();
		}
	}
	
	/**
	 * Function will get all Inventory Type
	 */
	function getInventoryListing()
	{
		$CI =& get_instance();
		$res = $CI->db->query("SELECT inventory_type_id, it_name, it_key FROM inventory_type WHERE it_status=0 ");
		return $res->result_array();
	}
}