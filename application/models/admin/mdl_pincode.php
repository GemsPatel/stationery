<?php
class mdl_pincode extends CI_Model
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
			$text_state = $this->input->get('text_state');
			$text_city = $this->input->get('text_city');
			$text_area = $this->input->get('text_area');
			$text_pincode = $this->input->get('text_pincode');
			
			if(isset($text_state) && $text_state != "")
				$this->db->where('state_name LIKE \''.$text_state.'%\' ');
				
			if(isset($text_city) && $text_city != "")
				$this->db->where('cityname LIKE \''.$text_city.'%\' ');
				
			if(isset($text_area) && $text_area != "")
				$this->db->where('areaname LIKE \''.$text_area.'%\' ');
				
			if(isset($text_pincode) && $text_pincode != "")
				$this->db->where('pincode ', $text_pincode);		
			
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('pincode_status LIKE \''.$status_filter.'\' ');
			
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);				
			else
				$this->db->order_by($this->cAutoId,'ASC');
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
		
		$this->db->join('state','state.state_id=pincode.state_id');
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
	
	function saveData()
	{
		$data = $this->input->post();
		unset($data['item_id']);
		unset($data['country_id']);
		$data['state_id'] = $this->input->post('state_id');
		$data['cityname'] = $this->input->post('cityname');
		$data['areaname'] = $this->input->post('areaname');
		$data['pincode'] = $this->input->post('pincode');	
		$data['pincode_status'] = $this->input->post('pincode_status');	
		//if primary id set then we have to make update query
		if($this->cPrimaryId != '')
		{
			$this->db->set('pn_modified_date', 'NOW()', FALSE);
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
		saveAdminLog($this->router->class, @$data['pincode'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Pincode has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				$tabNameArr = array('0'=>'shipping_pincodes','1'=>'customer_address');
				$fieldNameArr = array('0'=>'pincode_id','1'=>'customer_address_zipcode');
				$res=isImageIdExist($tabNameArr,$fieldNameArr,$id);// this function call for un delete field
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				else
				{
					$getName = getField('pincode', $this->cTableName, $this->cAutoId, $id);
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
		
		$data['pincode_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}
/*
 * @abstract fetch state as per country id passed
 * 
*/
	function getState()
	{
		$countryid = $this->input->post('country_id');
		$name = $this->input->post('name');
		echo loadStateDropdown($name,$countryid);
	}
	
/*
 * @abstract insert data into pincode table in format
 	format:: pincode,areaname,cityname,state_id	
 * 
*/
	function importPincode()
	{
		$image = uploadFile('import_csv','All','importdata');
		
		if(isset($_FILES['import_csv']['name']))
		{
			$extArr = explode(".",$_FILES['import_csv']['name']);
			$path = $image['path'];
			$resArr = readCsvNew($path);
			die;
			foreach($resArr as $k=>$ar)
			{
				$this->db->query('INSERT INTO pincode(pincode, areaname, cityname, state_id) VALUES(\''.$ar[0].'\',\''.$ar[1].'\',\''.$ar[2].'\','.$ar[3].')');				
			}
		}
	}
}