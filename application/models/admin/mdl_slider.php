<?php
class mdl_slider extends CI_Model
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
			$status_filter = $this->input->get('status_filter');
			$text_name = $this->input->get('text_name');
			
			
			if(isset($text_name) && $text_name != "")
			{
				if( MANUFACTURER_ID != 7 )
					$this->db->where($this->cTableName.'_cctld.slider_name LIKE \''.$text_name.'%\' ');
				else
					$this->db->where($this->cTableName.'.slider_name LIKE \''.$text_name.'%\' ');
			}
			if(isset($status_filter) && $status_filter != "")
			{
				if( MANUFACTURER_ID != 7 )
					$this->db->where($this->cTableName.'_cctld.slider_status LIKE \''.$status_filter.'\' ');
				else
					$this->db->where($this->cTableName.'.slider_status LIKE \''.$status_filter.'\' ');
			}
			if($f !='' && $s != '' )
				$this->db->order_by($f,$s);				
			else
				$this->db->order_by($this->cAutoId,'ASC');
			
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( " slider_cctld.*, slider.slider_created_date as slider_created_date, slider.slider_modified_date as slider_modified_date " );
	 		    $this->db->join('slider_cctld', 'slider_cctld.slider_id=slider.slider_id', 'INNER');	
				$this->db->where( 'slider_cctld.manufacturer_id', MANUFACTURER_ID);
				//$this->db->group_by( 'slider_id' );
			}
		}
		else if($this->cPrimaryId != '')
		{
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( " slider_cctld.*, slider.slider_created_date as slider_created_date, slider.slider_modified_date as slider_modified_date " );
	 		    $this->db->join('slider_cctld', 'slider_cctld.slider_id=slider.slider_id', 'INNER');	
				$this->db->where( 'slider_cctld.manufacturer_id', MANUFACTURER_ID);
				//$this->db->group_by( 'slider_cctld.slider_id' );
	
				$this->db->where( "slider_cctld.".$this->cAutoId, $this->cPrimaryId);
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
		$data = $this->input->post();
		unset($data['item_id']);
		$getImg = '';
		
		if( $this->cPrimaryId != '' )
		{
			if( MANUFACTURER_ID == 7 )
			{
				$getImg = exeQuery( " SELECT slider_image FROM slider WHERE ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'slider_image' ); 
			}
			else
			{
				$getImg = exeQuery( " SELECT slider_image FROM slider_cctld WHERE manufacturer_id=".MANUFACTURER_ID." AND ".$this->cAutoId."=".$this->cPrimaryId." ", true, 'slider_image' ); 
			}
		}
		
		if($this->input->post('slider_image') && $_FILES['slider_image']['name'])
		{
			$data['slider_image'] = $this->resizeUploadImage(); //upload and resize image		
			if($getImg != '')
			{
				//@unlink($getImg);
			}
		}
		
		if($this->input->post('slider_image') && $_FILES['slider_image']['name'] == '')
			$data['slider_image'] = $this->input->post('slider_image');
			
		if($this->input->post('slider_image') == '' && $_FILES['slider_image']['name'] == '')
		{
			//@unlink($getImg);
		}
		
		$slider_name = @$data['slider_name'];
		//if primary id set then we have to make update query
		if($this->cPrimaryId != '')
		{
			//UML: ccTLD -> specific feature
			$this->sliderCcTld( true, $this->cPrimaryId, $data );
			
			$this->db->set('slider_modified_date', 'NOW()', FALSE);
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
			$this->sliderCcTld( false, $last_id, $data );
		}
		
		saveAdminLog($this->router->class, $slider_name, $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Slider has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
			foreach($ids as $img)
			{	//image path delete on folder
				
			}
			foreach($ids as $id)
			{
				$tabNameArr = array(0=>'module_manager');
				$fieldNameArr = array(0=>array('0'=>'module_manager_table_name','1'=>'module_manager_primary_id'));
				$valArr = array(0=>array('0'=>'slider','1'=>$id));
				$res=isFieldIdExistMul($tabNameArr,$fieldNameArr,$valArr);
				
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				else
				{
					$getImg = getField('slider_image', $this->cTableName, $this->cAutoId, $id);
					$getName = getField('slider_name', $this->cTableName, $this->cAutoId, $id);
					saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
					
					//ccTLD					
					$this->db->where_in( $this->cAutoId, $id)->delete( $this->cTableName."_cctld" );

					$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);

					//Delete Images
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
		
		$data['slider_status'] = $status;
		if(  MANUFACTURER_ID == 7 )	
		{
			$this->db->where($this->cAutoId,$cat_id);
			$this->db->update($this->cTable,$data);
		}
		else	//ccTLDs
		{
			$this->sliderCcTld( true, $cat_id, $data );
		}
	}
	
	function resizeUploadImage()
	{
		/*$file_size = str_replace('M','',ini_get('upload_max_filesize'));
		$object_size = convertToMb($_FILES['article_image']['size']);
		
		if($file_size < $object_size)
			setFlashMessage('error','Upload limit exceed.');
		else*/ 
		{
			$image = uploadFile('slider_image','image','slider'); //input file, type, folder
			if(@$image['error'])
			{
				setFlashMessage('error',$image['error']);
				redirect('admin/'.$this->router->class);
				
			}
			// get width and height  from image  size table when image_size id is matched
			/*$width = getField('image_size_width','image_size','image_size_id',$this->input->post('image_size_id'));
			$height = getField('image_size_height','image_size','image_size_id',$this->input->post('image_size_id'));
			$path = $image['path'];*/
			//$sizeArr = $this->db->where('image_size_id',$this->input->post('image_size_id'))->where('image_size_status','0')->get('image_size')->row_array();
			$path = $image['path'];
			$dest = getResizeFileNameByPath($path,'m',''); //image path, type(s,m), folder
			//$returnFlag = resize_image($path, $dest, $width,$height); //source, destination, width, height
			$returnFlag = resize_image($path, $dest, '', ''); //source, destination, width, height
			@unlink($path); //delete old image
			return $dest;
		}
	}
	
	/**
	 * function will return dia filter price and weight min and max 
	 
	 */
	function sliderCcTld( $is_update, $slider_id, &$data )
	{
		$ccTldData = array();
		
		//ccTLD data
		$ccTldData['slider_id'] = $slider_id;

		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )	
			{
				//ccTLD data
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID; 
				$ccTldData['slider_status'] = $data['slider_status'];

				if( isset($data['slider_name']) )
				{
					$ccTldData['slider_name'] = $data['slider_name'];
					$ccTldData['slider_image'] = $data['slider_image'];
					$ccTldData['slider_url'] = $data['slider_url'];
					$ccTldData['image_size_id'] = $data['image_size_id'];
					$ccTldData['slider_sort_order'] = $data['slider_sort_order'];
					$ccTldData['slider_status'] = $data['slider_status'];
					$ccTldData['slider_display'] = $data['slider_display'];
					$ccTldData['slider_layout'] = $data['slider_layout'];
			
					unset( $data['slider_name'] );
					unset( $data['slider_image'] );
					unset( $data['slider_url'] );
					unset( $data['image_size_id'] );
					unset( $data['slider_sort_order'] );
					unset( $data['slider_display'] );
					unset( $data['slider_layout'] );
				}
				
				$this->saveupdsliderCcTld( $ccTldData );
				unset( $data['slider_status'] );
			}
		}
		else
		{
			$resManuf = getManufacturers();
			
			if( isset($data['slider_name']) )
			{
				$ccTldData['slider_name'] = $data['slider_name'];
				$ccTldData['slider_image'] = $data['slider_image'];
				$ccTldData['slider_url'] = $data['slider_url'];
				$ccTldData['image_size_id'] = $data['image_size_id'];
				$ccTldData['slider_sort_order'] = $data['slider_sort_order'];
				$ccTldData['slider_status'] = $data['slider_status'];
				$ccTldData['slider_display'] = $data['slider_display'];
				$ccTldData['slider_layout'] = $data['slider_layout'];
				
				unset( $data['slider_name'] );
				unset( $data['slider_image'] );
				unset( $data['slider_url'] );
				unset( $data['image_size_id'] );
				unset( $data['slider_sort_order'] );
				unset( $data['slider_display'] );
				unset( $data['slider_layout'] );
			}
			
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 0; //to resolve bug 374 by default enable
				if( $ar['manufacturer_id'] == 7 )	//primary perrian.com
				{
					if( MANUFACTURER_ID != 7 )
					{
						$this->db->where( 'slider_id', $slider_id)->update( $this->cTableName, array( 'slider_status' => $statusTemp ) );	
					}
				}
				else
				{
					if(  $ar['manufacturer_id'] == MANUFACTURER_ID )	
					{
						$statusTemp = $data['slider_status'];
					}
					
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					$ccTldData['slider_status'] = $statusTemp;
					$this->saveupdsliderCcTld( $ccTldData );
				}
			}

			unset( $data['slider_status'] );
		}
	}

	/**
	 * function will return dia filter price and weight min and max 
	 */
	function saveupdsliderCcTld( $data )
	{
		$update="";
		foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
		{
			$val = ( $val != '' ) ? $val : 0;
			$update .= $key."='".$val."', ";
		}
		$update .= "slider_cctld_modified_date=NOW()";

		$this->db->query( $this->db->insert_string( "slider_cctld", $data).' ON DUPLICATE KEY UPDATE '.$update );
	}

}