<?php
class mdl_main_front_menu extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $cTableNameM = '';
	var $cAutoIdM = '';
	var $cPrimaryIdM = '';
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
		{
			$this->db->where( $this->cAutoId, $this->cPrimaryId);
		}
					
		//$this->db->where('category_status','0');
		
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}

	function getDataMenuItem()
	{ 
		if($this->cPrimaryIdM == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$front_menu_name_filter = $this->input->get('front_menu_name_filter');
			$status_filter = $this->input->get('status_filter');
			
			if(isset($front_menu_name_filter) && $front_menu_name_filter != "")
			{
				if( MANUFACTURER_ID != 7 )
					$this->db->where('('.$this->cTableNameM.'_cctld.front_menu_name LIKE \'%'.$front_menu_name_filter.'%\' )');
				else
					$this->db->where('('.$this->cTableNameM.'.front_menu_name LIKE \'%'.$front_menu_name_filter.'%\' )');
			}
			if(isset($status_filter) && $status_filter != "")
			{
				if( MANUFACTURER_ID != 7 )
					$this->db->where($this->cTableNameM.'_cctld.fm_status LIKE \''.$status_filter.'\' ');
				else
					$this->db->where($this->cTableNameM.'.fm_status LIKE \''.$status_filter.'\' ');
			}
			$this->db->where('fm_parent_id',0);			
			$this->db->where('front_menu_type_id',$this->cPrimaryId);			
			if($f !='' && $s != '' && check_db_column($this->cTableNameM,$f))
			{
				$this->db->order_by($f,$s);
			}
			else
			{
				$this->db->order_by($this->cAutoIdM,'ASC');
			}
			
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( " front_menu_cctld.*, front_menu.fm_parent_id, front_menu.front_menu_type_id, front_menu.front_menu_item_type, front_menu.front_hook_alias, front_menu.front_menu_table_name, front_menu.front_menu_table_field_name, front_menu.fm_sort_order, front_menu.front_menu_primary_id, front_menu_cctld.fm_status as fm_status " );
	 		    $this->db->join('front_menu_cctld', 'front_menu_cctld.front_menu_id=front_menu.front_menu_id', 'INNER');	
				$this->db->where( 'front_menu_cctld.manufacturer_id', MANUFACTURER_ID );
			}
			
		}
		else if($this->cPrimaryIdM != '')
		{
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( " front_menu_cctld.*, front_menu.fm_parent_id, front_menu.front_menu_type_id, front_menu.front_menu_item_type, front_menu.front_hook_alias, front_menu.front_menu_table_name, front_menu.front_menu_table_field_name, front_menu.fm_sort_order, front_menu.front_menu_primary_id, front_menu_cctld.fm_status as fm_status " );
	 		    $this->db->join('front_menu_cctld', 'front_menu_cctld.front_menu_id=front_menu.front_menu_id', 'INNER');	
				$this->db->where( 'front_menu_cctld.manufacturer_id', MANUFACTURER_ID);
			}
			
			$this->db->where( "front_menu.".$this->cAutoIdM,$this->cPrimaryIdM);
			
		}
		
		$res = $this->db->get($this->cTableNameM);
		//echo $this->db->last_query();
		return $res;
	}

	function saveData()
	{
		// post data for insert and edit
		$data = $this->input->post();
		
		// unset item id 
		unset($data['item_id']);
		
		if($this->cPrimaryId != '')
		{
			
			$this->db->set('front_menu_type_modified_date', 'NOW()', FALSE);
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
		saveAdminLog($this->router->class, @$data['front_menu_type_name'], $this->cTableName, $this->cAutoId, $last_id, $logType);
		setFlashMessage('success','Menu has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
		if( IS_CACHE )
		{
			removeCacheKey( '', 'header' );
		}
	}

	function saveDataMenuItem()
	{

		// post data for insert and edit
		$data = $this->input->post();
		$data['front_menu_type_id'] = $this->cPrimaryId;
		$hidden_page_param = explode("|",_de($data['hidden_page_param']));
		$data['front_menu_table_name'] = $hidden_page_param[1]; 
		$data['front_menu_table_field_name'] = $hidden_page_param[2]; 
		$data['front_menu_primary_id'] = $hidden_page_param[3]; 
		
		unset($data['hidden_page_param']);
		unset($data['item_id']);
		unset($data['m_id']);
		
		//$getImg = getField('fm_icon', $this->cTableNameM, $this->cAutoIdM, $this->cPrimaryIdM);
		$getImg = ""; 
		if( !empty($this->cPrimaryIdM) )
		{
			if( MANUFACTURER_ID == 7 )
				$getImg = exeQuery( " SELECT fm_icon FROM ".$this->cTableNameM." WHERE ".$this->cAutoIdM."=".$this->cPrimaryIdM." ", true, 'fm_icon' ); 
			else
				$getImg = exeQuery( " SELECT fm_icon FROM ".$this->cTableNameM."_cctld WHERE manufacturer_id=".MANUFACTURER_ID." AND ".$this->cAutoIdM."=".$this->cPrimaryIdM." ", true, 'fm_icon' ); 			
		}
		
		if($this->input->post('fm_icon')!='' && $_FILES['fm_icon_file']['name']!='')
		{
			$data['fm_icon'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != ''){
				//@unlink($getImg);
			}
		}		
		if($this->input->post('fm_icon') && $_FILES['fm_icon_file']['name'] == '')
			$data['fm_icon'] = $this->input->post('fm_icon');
			
		if($this->input->post('fm_icon') == '' && $_FILES['fm_icon_file']['name'] == ''){
			//@unlink($getImg);
		}
		
		if($this->cPrimaryIdM != '')
		{
			//UML: ccTLD -> specific feature
			$this->front_menuCcTld( true, $this->cPrimaryIdM, $data );
			
			$this->db->set('fm_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoIdM,$this->cPrimaryIdM)->update($this->cTableNameM,$data);
			$last_id = $this->cPrimaryIdM;
			$logType = 'E';
		}
		else // insert new row
		{
			$this->db->insert($this->cTableNameM,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
			
			//UML: ccTLD -> specific feature
			$this->front_menuCcTld( false, $last_id, $data );
		}
		saveAdminLog($this->router->class, $this->input->post('front_menu_name'), $this->cTableNameM, $this->cAutoIdM, $last_id, $logType);
		setFlashMessage('success','Menu Item has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
		if( IS_CACHE )
		{
			removeCacheKey( '', 'header' );
		}
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
			$ids = array_reverse($ids);
			
			foreach($ids as $id)
			{
				
				if(strpos(" ".$id,"m") != false)
				{
					$id = substr($id,1);
					$resField = $this->db->select('front_menu_type_id,front_menu_name,fm_icon')->where($this->cAutoIdM,$id)->get($this->cTableNameM)->row_array();
					
					$res = $this->checkIfMenuExist($id,$resField['front_menu_type_id']);
					
					
					if(sizeof($res)>0)
					{
						echo json_encode($res);	
						return;
					}
					else
					{
						$tabNameArr = array(0=>'front_menu');
						$fieldNameArr = array(0=>array('0'=>'fm_parent_id'));
						$valArr = array(0=>array('0'=>$id));
						$res=isFieldIdExistMul($tabNameArr,$fieldNameArr,$valArr);
						//pr($res); die;
						if(sizeof($res)>0)
						{
							echo json_encode($res);	
							return;
						}
						else
						{
							//records delete log
							saveAdminLog($this->router->class, @$resField['front_menu_name'], $this->cTableName, $this->cAutoId, $id, 'D');

							@unlink($resField['fm_icon']);
							
							$this->db->where_in($this->cAutoIdM,$id)->delete($this->cTableNameM."_cctld");
							$this->db->where_in($this->cAutoIdM,$id)->delete($this->cTableNameM);
							$returnArr['type'] ='success';
							$returnArr['msg'] = count($ids)." records has been deleted successfully.";
						}
					}
				}
				else
				{	
					$tabNameArr = array(0=>'module_manager',1=>'front_menu');
					$fieldNameArr = array(0=>array('0'=>'module_manager_table_name','1'=>'module_manager_primary_id'),1=>array('0'=>'front_menu_type_id'));
					$valArr = array(0=>array('0'=>'front_menu_type','1'=>$id),1=>array('0'=>$id));
					$res=isFieldIdExistMul($tabNameArr,$fieldNameArr,$valArr);
					
					if(sizeof($res)>0)
					{
						echo json_encode($res);	
						return;
					}
					else
					{
						//records delete log
						$getName = getField('front_menu_type_name', $this->cTableName, $this->cAutoId, $id);
						saveAdminLog($this->router->class, @$getName, $this->cTableNameM, $this->cAutoIdM, $id, 'D');
						
					   $this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
					   $returnArr['type'] ='success';
					   $returnArr['msg'] = count($ids)." records has been deleted successfully.";
					}
				}
				
			}

			/**
			 * 
			 */
			if( IS_CACHE )
			{
				removeCacheKey( '', 'header' );
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
		if(strpos(" ".$cat_id,"m") != false)
		{
			$data['fm_status'] = $status;
			if(  MANUFACTURER_ID == 7 )	
			{
				$this->db->where($this->cAutoIdM,substr($cat_id,1));
				$this->db->update($this->cTableM,$data);
			}
			else	//ccTLDs
			{
				$this->front_menuCcTld( true, substr($cat_id,1), $data );
			}
		}
		else
		{
			$data['fmt_status'] = $status;
			$this->db->where($this->cAutoId,$cat_id);
			$this->db->update($this->cTable,$data);
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
		$image = uploadFile('fm_icon_file','image','front_menu'); //input file, type, folder
		
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
	
/*
+------------------------------------------------------+
	Function will get data of site pages.
	small icon size : 30x30
+------------------------------------------------------+
*/	
	function GetSitePages()
	{
		$data = array();
		
		$data[0]['name'] = "Product Category";
		$data[0]['table'] = "product_categories";
		$data[0]['field'] = "category_id";
		$data[0]['data'] = getMultiLevelMenuDropdown(0,'');

		$data[1]['name'] = "Articles";
		$data[1]['table'] = "article_category";
		$data[1]['field'] = "article_category_id";
		$data[1]['data'] = getMultiLevelMenuDropdownArticle(0,'');

		$data[2]['name'] = "Dynamic Page";
		$data[2]['table'] = "front_hook";
		$data[2]['field'] = "front_hook_id";
		$sql = "SELECT front_hook_id, front_hook_name FROM front_hook WHERE front_hook_status=0 AND front_hook_type='C'";
		$data[2]['data'] = getDropDownAry($sql,"front_hook_id", "front_hook_name", '', false);

		return $data;
	}
	
/*
+------------------------------------------------------+
	Function will check if menu exist module manager serialized menu assignment.
+------------------------------------------------------+
*/	
	function checkIfMenuExist($id,$menu_type_id)
	{
		$res = $this->db->select('module_manager_serialize_menu')->get("module_manager")->result_array();
		foreach($res as $key=>$val)
		{
			$unser_menu = unserialize($val['module_manager_serialize_menu']);
			if(array_key_exists($menu_type_id,$unser_menu))
			{
				if(in_array($id,$unser_menu[$menu_type_id]))
				{
					$returnArr['type'] ='error';
					$returnArr['msg'] = "This menu is Assigned in module manager.";
					return $returnArr;
				}
			}
		}
		return array();
	}
	
	/**
	 * function will return dia filter price and weight min and max 
	 */
	function front_menuCcTld( $is_update, $front_menu_id, &$data )
	{
		$ccTldData = array();

		//ccTLD data
		$ccTldData['front_menu_id'] = $front_menu_id;
		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )	
			{
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID;
				$ccTldData['fm_status'] = $data['fm_status'];
				
				if( isset($data['front_menu_name']) )
				{
					$ccTldData['front_menu_name'] = $data['front_menu_name'];
					$ccTldData['fm_icon'] = $data['fm_icon'];
					$ccTldData['image_size_id'] = $data['image_size_id'];
					$ccTldData['custom_page_title'] = $data['custom_page_title'];
					$ccTldData['meta_keyword'] = $data['meta_keyword'];
					$ccTldData['meta_description'] = $data['meta_description'];
					$ccTldData['robots'] = $data['robots'];
					$ccTldData['author'] = $data['author'];
					$ccTldData['content_rights'] = $data['content_rights'];
					$ccTldData['fm_sort_order'] = $data['fm_sort_order'];
					
					unset( $data['front_menu_name'] );
					unset( $data['fm_icon'] );
					unset( $data['image_size_id'] );
					unset( $data['custom_page_title'] );
					unset( $data['meta_keyword'] );
					unset( $data['meta_description'] );
					unset( $data['robots'] );
					unset( $data['author'] );
					unset( $data['content_rights'] );
					unset( $data['fm_sort_order'] );
				}

				$this->saveupdFrontMenuCcTld( $ccTldData );
				unset( $data['fm_status'] );
			}
		}
		else
		{
			$resManuf = getManufacturers();

			if( isset($data['front_menu_name']) )
			{
				$ccTldData['front_menu_name'] = $data['front_menu_name'];
				$ccTldData['fm_icon'] = $data['fm_icon'];
				$ccTldData['image_size_id'] = $data['image_size_id'];
				$ccTldData['custom_page_title'] = $data['custom_page_title'];
				$ccTldData['meta_keyword'] = $data['meta_keyword'];
				$ccTldData['meta_description'] = $data['meta_description'];
				$ccTldData['robots'] = $data['robots'];
				$ccTldData['author'] = $data['author'];
				$ccTldData['content_rights'] = $data['content_rights'];
				$ccTldData['fm_sort_order'] = $data['fm_sort_order'];
				
				unset( $data['front_menu_name'] );
				unset( $data['fm_icon'] );
				unset( $data['image_size_id'] );
				unset( $data['custom_page_title'] );
				unset( $data['meta_keyword'] );
				unset( $data['meta_description'] );
				unset( $data['robots'] );
				unset( $data['author'] );
				unset( $data['content_rights'] );
				unset( $data['fm_sort_order'] );
			}
			
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 0; //to resolve bug 374 by default enable
				if( $ar['manufacturer_id'] == 7 )	//primary perrian.com
				{
					if( MANUFACTURER_ID != 7 )
					{
						$this->db->where( 'front_menu_id', $front_menu_id)->update( $this->cTableNameM, array( 'fm_status' => $statusTemp ) );	
					}
				}
				else
				{
					if(  $ar['manufacturer_id'] == MANUFACTURER_ID )	
					{
						$statusTemp = $data['fm_status'];
					}
	
					//ccTLD data
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					$ccTldData['fm_status'] = $statusTemp;				
	
					$this->saveupdFrontMenuCcTld( $ccTldData );
				}
			}
			
			unset( $data['fm_status'] );
		}
		
		//moved to save and delete functions
// 		if( IS_CACHE )
// 		{
// 			removeCacheKey( '', 'header' );
// 		}
	}

	/**
	 * function will return dia filter price and weight min and max 
	 */
	function saveupdFrontMenuCcTld( $data )
	{
		$update="";
		foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
		{
			$val = ( $val != '' ) ? $val : 0;
			$update .= $key."='".$val."', ";
		}
		$update .= "fmc_modified_date=NOW()";

		$this->db->query( $this->db->insert_string( $this->cTableNameM."_cctld", $data).' ON DUPLICATE KEY UPDATE '.$update );
	}
	
}