<?php
class mdl_scrapper extends CI_Model
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
			$name_filter = $this->input->get('name_filter');
			$year_filter = $this->input->get('year_filter');
			$site_filter = $this->input->get('site_filter');
			$status_filter = $this->input->get('status_filter');
			
			if(isset($name_filter) && $name_filter != "")
				$this->db->where('m_name LIKE \''.$name_filter.'%\' ');
				
			if(isset($year_filter) && $year_filter != "")
				$this->db->where('m_year LIKE \''.$year_filter.'%\' ');
				
			if(isset($site_filter) && $site_filter != "")
				$this->db->where('m_site_key LIKE \''.$site_filter.'%\' ');
				
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('m_status LIKE \''.$status_filter.'\' ');
							
			if($f !='' && $s != '')
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
	
/*
 * @abstract insert data into pincode table in format
 	format:: pincode,areaname,cityname,state_id	
 * 
*/
	function importMovies()
	{
		$image = uploadFile('import_csv','All','movies');
		
		if(isset($_FILES['import_csv']['name']))
		{
			$extArr = explode(".",$_FILES['import_csv']['name']);
			$path = $image['path'];
			
			$csvRowArr = readCsvNew($path);
			
			$keyArr = array_keys( $csvRowArr );
			$size = sizeof( $keyArr );
						
			for( $i=1; $i<$size; $i++ ) 
			{
				$cq_url =  validateInput( $csvRowArr[ $keyArr[$i] ][0] ."|". $csvRowArr[ $keyArr[$i] ][1]  );
				$this->db->query("INSERT INTO cron_que(cq_url, cq_key, cq_status) VALUES('".$cq_url."','MOVIE_INPUT',0)");
			}
			
			$this->db->query( " UPDATE temp SET t_value=0 WHERE t_name='MOVIE_SEARCH_INDEX' " );
			
		}
	}
/*
+----------------------------------------------------------+
	Deleting data. handle both request get and post.
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
				$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
				
			}
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
		
		$data['m_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}

}