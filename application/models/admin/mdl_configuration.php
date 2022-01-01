<?php
class mdl_configuration extends CI_Model
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
				
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( $this->cTableName."_cctld.*, ".$this->cTableName.".created_date as created_date, ".$this->cTableName.".modified_date as modified_date " );
	 		    $this->db->join($this->cTableName.'_cctld', $this->cTableName.'_cctld.config_id = '.$this->cTableName.'.config_id', 'INNER');	
				$this->db->where( $this->cTableName.'_cctld.manufacturer_id', MANUFACTURER_ID);
			}
				
		}
		else if($this->cPrimaryId != '')
		{
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( $this->cTableName."_cctld.*, ".$this->cTableName.".created_date as created_date, ".$this->cTableName.".modified_date as modified_date " );
	 		    $this->db->join($this->cTableName.'_cctld', $this->cTableName.'_cctld.config_id = '.$this->cTableName.'.config_id', 'INNER');	
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
		$data = $this->input->post();
		unset($data['item_id']);
		
		$this->db->set('modified_date', 'NOW()', FALSE);
		/**
		 * removed on 02-04-2015 when extended as per cctld
		 */
// 		if( MANUFACTURER_ID != 7 )
// 		{
// 			$this->cTableName = $this->cTableName.'_cctld';
// 			$data['manufacturer_id'] = MANUFACTURER_ID;
// 		}
		
		//if primary id set then we have to make update query
		$log_name = ( isset( $data["config_key"] ) ? $data["config_key"] : "config_id: ". $this->cPrimaryId );
		if($this->cPrimaryId != '')
		{
			//UML: ccTLD -> specific feature =>Gautam Change Code
			$this->configuration_Cctld( true, $this->cPrimaryId, $data );
				
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
			
			/*
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';*/
		}
		else // insert new row
		{
			$data['config_key'] = strtoupper($data['config_key']);
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
				
			//UML: ccTLD -> specific feature
			$this->configuration_Cctld( false, $last_id, $data );
			/*
			$data['config_key'] = strtoupper($data['config_key']);
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';		*/	
		}
		
		//echo $this->db->last_query();die;
		saveAdminLog($this->router->class, $log_name, $this->cTableName, $this->cAutoId, $last_id, $logType);
		
		setFlashMessage('success','Configuration has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');		
	}
	
	//Gautam Change code
	
	function configuration_Cctld( $is_update, $config_id, &$data )
	{
		$ccTldData = array();
	
		//ccTLD data
		$ccTldData['config_id'] = $config_id;
		
		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )
			{
				//ccTLD data
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID;
				//$ccTldData['config_status'] = $data['config_status'];	
	
				if( isset($data['config_value']) )
				{
					$ccTldData['config_value'] = $data['config_value'];
					$ccTldData['config_display_name'] = $data['config_display_name'];
					
					unset( $data['config_value'] );
					unset( $data['config_display_name'] );
				}
	
				$this->saveupdconfiguration_Cctld( $ccTldData );
				//unset( $data['config_status'] );
			}
		}
		else
		{
			$resManuf = getManufacturers();
				
			if( isset($data['config_value']) )
			{
				$ccTldData['config_key'] = $data['config_key'];
				$ccTldData['config_value'] = $data['config_value'];
				$ccTldData['config_display_name'] = $data['config_display_name'];
				
				unset( $data['config_key'] );
				unset( $data['config_value'] );
				unset( $data['config_display_name'] );
				
			}
				
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 0; //to resolve bug 374 by default enable
				if( $ar['manufacturer_id'] == 7 )	//primary Key
				{
					if( MANUFACTURER_ID != 7 )
					{
						//$this->db->where( 'config_id', $config_id)->update( $this->cTableName, array( 'config_status' => $statusTemp ) );
					}
				}
				else
				{
					if(  $ar['manufacturer_id'] == MANUFACTURER_ID )
					{
						//$statusTemp = $data['config_status'];
					}
						
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					//$ccTldData['config_status'] = $statusTemp;
					$this->saveupdconfiguration_Cctld( $ccTldData );
				}
			}
	
			unset( $data['config_status'] );
		}
	}
	
	function saveupdconfiguration_Cctld( $data )
	{
		$update="";
		foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
		{
			$val = ( $val != '' ) ? $val : '';
			$update .= $key."='".$val."', ";
		}
		$update .= "modified_date=NOW()";
			
		$this->db->query( $this->db->insert_string( "configuration_cctld", $data).' ON DUPLICATE KEY UPDATE '.$update );
	}
	//Gautam Change code

}