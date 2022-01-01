<?php
class mdl_import_export extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cCategory = '';
	
	function getData()
	{
		$res = $this->db->get($this->cTable);
		//echo $this->db->last_query();
		return $res->result_array();
		/*$sql='select * from product';
		$res=$this->db->query($sql);
		echo $sql;
		return $res=$res->result_array();*/
		
	}
	
		
	function getBackup()
	{
		if($this->input->post('select_db')=='perrian_geo')
		$this->load->database($this->input->post('select_db'),TRUE);
		$res=$this->db->query('SHOW TABLES');
		$res=$res->result_array();
		foreach($res as $k=>$v)
		{
		 
			foreach($v as $key=>$val)
			{
			//pr($val);
  			// GET ALL TABLES
				$tables=$val;
			  if($tables == '*')
			  {
				$tables = array();
					
				$result = mysql_query('SHOW TABLES');
				while($row = mysql_fetch_row($result))
				{
				  $tables[] = $row[0];
				}
			  }
			  else
			  {
				$tables = is_array($tables) ? $tables : explode(',',$tables);
			  }
			  
			  $return = 'SET FOREIGN_KEY_CHECKS=0;' . "\r\n";
			  $return.= 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";' . "\r\n";
			  $return.= 'SET AUTOCOMMIT=0;' . "\r\n";
			  $return.= 'START TRANSACTION;' . "\r\n";
			  
			  foreach($tables as $table)
			  {
				$result = mysql_query('SELECT * FROM `'.$table.'`') or die(mysql_error());
				$num_fields = mysql_num_fields($result) or die(mysql_error());
				 @$data.= 'DROP TABLE IF EXISTS `'.$table.'` ;';
				$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE `'.$table.'`'));
				
				@$data.= "\n\n".$row2[1].";\n\n";
				
				for ($i = 0; $i<$num_fields; $i++) 
				{
				  while($row = mysql_fetch_row($result))
				  {
					$data.= 'INSERT INTO `'.$table.'` VALUES(';
					for($x=0; $x<$num_fields; $x++) 
					{
					  $row[$x] = addslashes($row[$x]);
					  //$row[$x] = ereg_replace("\n","\\n",$row[$x]);
					  //$row[$x] = clean($row[$x]);// CLEAN QUERIES
					  if (isset($row[$x])) { 
						$data.= '"'.$row[$x].'"' ; 
					  } else { 
						$data.= '""'; 
					  }
					  
					  if ($x<($num_fields-1)) { 
						$data.= ','; 
					  }
					}  // end of the for loop 2
					$data.= ");\n";
				  } // end of the while loop 
				} // end of the for loop 1
				
				$data.="\n\n\n";
			  }  // end of the foreach*/
			  
			  }
			}
				$return .= 'SET FOREIGN_KEY_CHECKS=1;' . "\r\n";
				$return.= 'COMMIT;';
				
				return $data;
		
	}
	function importDatabase()
	{
		
		
		//$this->load->database('mis',TRUE);
		if($this->input->post('select_db')=='perrian_geo')
			$this->load->database($this->input->post('select_db'),TRUE);
		else
				$this->load->database($this->input->post('select_db'),TRUE);
					
			$image = uploadFile('export_file','All','import'); //input file, type, folder
			
			if(isset($_FILES['export_file']['name']))
			{
				$extArr = explode(".",$_FILES['export_file']['name']);
				
					$path = $image['path'];
					$templine = '';
					// Read in entire file
					$lines = file($path);
					// Loop through each line
					
				
					foreach ($lines as $line)
					{
						
						// Skip it if it's a comment
						if (substr($line, 0, 2) == '--' || $line == '')
							continue;
					 
						// Add this line to the current segment
						$templine .= $line;
						
					
						// If it has a semicolon at the end, it's the end of the query
						if (substr(trim($line), -1, 1) == ';')
						{
							// Perform the query
							$this->db->query($templine);
						 	// Reset temp variable to empty
							$templine = '';
							
						}
					
					}
			
			}
			 
			 
			
	}

}