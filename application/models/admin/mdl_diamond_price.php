<?php
class mdl_diamond_price extends CI_Model
{
	var $cTableNameD = '';
	var $cTableNameM = '';
	var $cAutoIdD = ''; 	// primary key for diamond price table
	var $cAutoIdM = ''; 	  // primary key for metal price table
	var $cPrimaryIdD = array(); 				 //array of primary id for diamond price table
	var $cPrimaryIdM = array(); 				 //array of primary id for metal price table
	var $cCategory = '';
	
	function getData()
	{
		if(MANUFACTURER_ID !=7)
			$this->db->where('dp_manufacturer_id',MANUFACTURER_ID);
		
		$this->db->where('dp_rapnet_lot_no',0);
		$this->db->order_by($this->cAutoIdD,'ASC');
		$resD = $this->db->get($this->cTableNameD);	

		if(MANUFACTURER_ID !=7)
			$this->db->where('mp_manufacturer_id',MANUFACTURER_ID);
			
		$this->db->order_by($this->cAutoIdM,'ASC');
		$resM = $this->db->get($this->cTableNameM);	

		
		return array('D'=>$resD,'M'=>$resM);
		
	}
	
	function saveData()
	{
		
		$diamond_price_name = $this->input->post('diamond_price_name');
		$diamond_price_key = $this->input->post('diamond_price_key');
		$diamond_price_labour_charge = $this->input->post('diamond_price_labour_charge');
		$diamond_type_id = $this->input->post('diamond_type_id');
		$diamond_purity_id = $this->input->post('diamond_purity_id');
		$diamond_color_id = $this->input->post('diamond_color_id');
		$dp_price = $this->input->post('dp_price');
		//$dp_clarity = $this->input->post('dp_clarity');
		//$dp_color = $this->input->post('dp_color');
		$dp_weight_diff = $this->input->post('dp_weight_diff');
		$dp_icon = $this->input->post('dp_icon');
		$dp_desc = $this->input->post('dp_desc');
		$dp_iconfile = $_FILES['dp_iconfile'];
		
		$metal_type_id = $this->input->post('metal_type_id');
		$metal_purity_id = $this->input->post('metal_purity_id');
		$metal_price_labour_charge = $this->input->post('metal_price_labour_charge');
		$metal_color_id = $this->input->post('metal_color_id');
		$mp_icon = $this->input->post('mp_icon');
		$mp_price_difference = $this->input->post('mp_price_difference');
		$mp_iconfile = $_FILES['mp_iconfile'];
		$mp_desc = $this->input->post('mp_desc');

		$diamond_data = array();
		for($i =0; $i<sizeof($diamond_price_name);$i++)
		{
			$diamond_data[$i]['diamond_price_name'] = $diamond_price_name[$i];
			$diamond_data[$i]['diamond_price_key'] = strtoupper($diamond_price_key[$i]);
			$diamond_data[$i]['diamond_price_labour_charge'] = $diamond_price_labour_charge[$i];
			$diamond_data[$i]['diamond_type_id'] = $diamond_type_id[$i];
			$dp_calculated_cost = $dp_price[$i] * (1 * ($dp_weight_diff[$i]/100));
			$diamond_data[$i]['dp_calculated_cost'] = round($dp_calculated_cost + ($dp_calculated_cost * ($diamond_price_labour_charge[$i]/100)) ,2);
			$diamond_data[$i]['dp_price'] = $dp_price[$i];
			$diamond_data[$i]['diamond_purity_id'] = $diamond_purity_id[$i];
			$diamond_data[$i]['diamond_color_id'] = $diamond_color_id[$i];
			$diamond_data[$i]['dp_weight_diff'] = $dp_weight_diff[$i];
			$diamond_data[$i]['dp_icon'] = $dp_icon[$i];
			$diamond_data[$i]['dp_desc'] = $dp_desc[$i];
			$diamond_data[$i]['dp_manufacturer_id'] = MANUFACTURER_ID;
			
		}
		//pr($diamond_data); die;
		$metal_data = array();
		for($i =0; $i<sizeof($metal_purity_id);$i++)
		{ 
			$metal_data[$i]['metal_purity_id'] = $metal_purity_id[$i];
			$metal_data[$i]['metal_price_labour_charge'] = $metal_price_labour_charge[$i];
			$metal_data[$i]['metal_color_id'] = $metal_color_id[$i];
			$metal_data[$i]['metal_type_id'] = $metal_type_id[$i];
			$metal_data[$i]['mp_price_difference'] = $mp_price_difference[$i];
			$metal_data[$i]['mp_icon'] = $mp_icon[$i];
			$metal_data[$i]['mp_desc'] = $mp_desc[$i];
			$metal_data[$i]['mp_manufacturer_id'] = MANUFACTURER_ID;
		}
		
		//if primary id set then we have to make update query
		foreach($diamond_data as $k=>$ar)
		{
			$getImg = "";
			if(isset($this->cPrimaryIdD[$k]) && (int)$this->cPrimaryIdD[$k] > 0)
				$getImg = getField('dp_icon', $this->cTableNameD, $this->cAutoIdD, @$this->cPrimaryIdD[$k]);
			else 
				$getImg = "";

			if($ar['dp_icon'] != "" && $dp_iconfile['name'][$k] != "")
			{
				$_FILES['dp_iconfile']['name'] = $dp_iconfile['name'][$k];
				$_FILES['dp_iconfile']['type']= $dp_iconfile['type'][$k];
				$_FILES['dp_iconfile']['tmp_name']= $dp_iconfile['tmp_name'][$k];
				$_FILES['dp_iconfile']['error']= $dp_iconfile['error'][$k];
				$_FILES['dp_iconfile']['size']= $dp_iconfile['size'][$k];    

				$ar['dp_icon'] = $this->resizeUploadImage("dp_iconfile","d"); //upload and resize image		
				if($getImg != "")
				{
					//echo $getImg . " kjkj inside " .$k. " kjkj " . $ar."<br>tset".sizeof($diamond_data);
					@unlink($getImg);
				}
			}
			else if($ar['dp_icon'] != "" && $dp_iconfile['name'][$k] == '')
			{
				//$data['category_image'] = $this->input->post('category_image');
			}
			else if($ar['dp_icon'] == '' &&  $dp_iconfile['name'][$k] == '')
			{
				//echo "tt".$k;
				@unlink($getImg);
			}
			
			if(@$this->cPrimaryIdD[$k] != '')
			{
				unset($ar['diamond_price_key']);
				$this->db->where($this->cAutoIdD,$this->cPrimaryIdD[$k])->update($this->cTableNameD,$ar);
				$last_id = $this->cPrimaryIdD[$k];
				$logType = 'E';
			}
			else // insert new row
			{
				$this->db->insert($this->cTableNameD,$ar);
				$last_id = $this->db->insert_id();
				$logType = 'A';
			}
		}

		foreach($metal_data as $k=>$ar)
		{
			$getImg = "";
			if(isset($this->cPrimaryIdM[$k]) && (int)$this->cPrimaryIdM[$k] > 0)
				$getImg = getField('mp_icon', $this->cTableNameM, $this->cAutoIdM, @$this->cPrimaryIdM[$k]);
			else 
				$getImg = "";
				
			if($ar['mp_icon'] != "" && @$mp_iconfile['name'][$k] != "")
			{
				$_FILES['mp_iconfile']['name'] = $mp_iconfile['name'][$k];
				$_FILES['mp_iconfile']['type']= $mp_iconfile['type'][$k];
				$_FILES['mp_iconfile']['tmp_name']= $mp_iconfile['tmp_name'][$k];
				$_FILES['mp_iconfile']['error']= $mp_iconfile['error'][$k];
				$_FILES['mp_iconfile']['size']= $mp_iconfile['size'][$k];    
				$ar['mp_icon'] = $this->resizeUploadImage("mp_iconfile","m"); //upload and resize image		
				if($getImg != "")
				{
					//echo $getImg . " kjkj inside " .$k. " kjkj " . $ar."<br>tset".sizeof($metal_data);
					@unlink($getImg);
				}
			}
			else if($ar['mp_icon'] != "" && @$mp_iconfile['name'][$k] == '')
			{
				//$data['category_image'] = $this->input->post('category_image');
			}
			else if($ar['mp_icon'] == '' &&  @$mp_iconfile['name'][$k] == '')
			{
				//echo "tt".$k;
				unlink($getImg);
			}
			
			if(@$this->cPrimaryIdM[$k] != '')
				$this->db->where($this->cAutoIdM,$this->cPrimaryIdM[$k])->update($this->cTableNameM,$ar);
			else // insert new row
				$this->db->insert($this->cTableNameM,$ar);
		}
		
		saveAdminLog($this->router->class, "Diamond/Metal Save/Upd", $this->cTableNameD, $this->cAutoIdD, $last_id, $logType); //class name, item name, tablename, fieldname, primary id, type A/E/D
		setFlashMessage('success','Data has been submitted successfully.');
	}
	
/*
+----------------------------------------------------------+
	Deleting category. hadle both request get and post.
	with single delete and multiple delete.
	@prams : $ids -> integer or array
+----------------------------------------------------------+
*/	
	function deleteCategory()
	{
		$type = $this->input->get('type');
		$key = _de($this->input->get('key'));
 		if(!$this->checkIfCategoryExist($type,$key))
		{
			if($type == "dp")
			{
				$getImg = getField('dp_icon', $this->cTableNameD, $this->cAutoIdD, $key);
				@unlink($getImg);
				$this->db->where($this->cAutoIdD,$key)->delete($this->cTableNameD);
			}
			else if($type == "mp")
			{
				$getImg = getField('mp_icon', $this->cTableNameM, $this->cAutoIdM, $key);
				@unlink($getImg);
				$this->db->where($this->cAutoIdM,$key)->delete($this->cTableNameM);
			}
			saveAdminLog($this->router->class, @$type, $this->cTableName, $this->cAutoId, $key, 'D');
			echo  json_encode(array('type'=>'success','success'=>" record has been deleted successfully."));
		}
		else 
		{
			echo json_encode(array('type'=>'error','error'=>" has some category used in product tables. Remove that record first."));
		}
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
		
		$data['diamond_price_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}

/*
+-----------------------------------------+
 * @author Cloudwebs
	Update metal prices in master table
	@params : get array of metal price text
+-----------------------------------------+
*/	
	function updateMetalPrice()
	{
		$metal_type_price = $this->input->get('metal_type_price');
		$metal_type_idhid = $this->input->get('metal_type_idhid');
		for($i =0; $i<sizeof($metal_type_price);$i++)             // updates metal type price in master table metal_type
		{
			$this->db->where('metal_type_id',$metal_type_idhid[$i]);
			$this->db->update('metal_type',array('metal_type_price'=>$metal_type_price[$i]));
		}

		$query = $this->db->query('SELECT m.metal_purity_name, t.metal_type_price, p.metal_type_id, p.metal_price_id, p.metal_price_labour_charge
								   FROM metal_price p INNER JOIN metal_purity m ON m.metal_purity_id=p.metal_purity_id 
								   INNER JOIN metal_type t ON t.metal_type_id=p.metal_type_id WHERE p.mp_manufacturer_id = '.MANUFACTURER_ID);
		
		foreach ($query->result_array() as $row)                 // update metal price table
		{
			$mp_price_diff = 0;
			if(defined('G_'.strtoupper($row['metal_purity_name']).''))        // if constant defined
			{
				$mp_price_diff =  (constant("G_".strtoupper($row['metal_purity_name'])) * $row['metal_type_price']); //without labour
				$mp_price_diff = $mp_price_diff + ( $mp_price_diff * ($row['metal_price_labour_charge']/100));                         // add labour 
			}
			else															  // if constant not defined for silver
			{
				$mp_price_diff =  ($row['metal_type_price'] + ($row['metal_type_price'] * ($row['metal_price_labour_charge']/100) ) ); // add labour 
			}
			$this->db->where('metal_price_id',$row['metal_price_id']);
			$this->db->update('metal_price',array('mp_price_difference'=>round($mp_price_diff)));
		}
		echo json_encode(array('success'=>" Metal prices has been updated successfully."));
	}
/*
+-----------------------------------------+
 * @author Cloudwebs
	Update company profit or labour charge
	@params : get field of key and value
+-----------------------------------------+
*/	
	function updateChargeProfit()
	{
		$key = $this->input->get('type');
		$keyval = $this->input->get($key);

		$this->db->where('config_key',$key);    //update config value in configuration table
		$this->db->update('configuration',array('config_value'=>$keyval));
		
		return json_encode(array('success'=>' has been updated successfully.'));
	}
/*
+-----------------------------------------+
	author Cloudwebs
	Update product pricing table the function might take long time as all combination of every products will be calculated
+-----------------------------------------+
*/	
	function updateProductPrices()
	{
		setTimeLimit();

		$res = update_insertProductPrice(0,1,false,true);
		if($res)
		{
			echo json_encode(array('type'=>'success','msg'=>"Product Prices updated successfully."));
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>"Error occured while updating prices."));
		}
	}
/*
+------------------------------------------------------+
	Function will resize image size.
	small icon size : 30x30
+------------------------------------------------------+
*/	
	function resizeUploadImage($input,$folder)
	{
		$image = uploadFile($input,'image','diamond_price'); //input file, type, folder
		if(@$image['error'])
		{
			setFlashMessage('error',$image['error']);
			redirect('admin/diamond_price');
		}
		$path = $image['path'];
		$dest = getResizeFileNameByPath($path,'s',$folder); //image path, type(s,m), folder
		$returnFlag = resize_image($path, $dest, 30,30); //source, destination, width, height
		@unlink($path); //delete old image
		return $dest;
	}
/*
+-----------------------------------------+
 * @author Cloudwebs
	Checks if diamond or metal category exist in child tables
	@param : key index for primary key
	@param $type specifies whether it is diamond or metal category
   +-----------------------------------------+
*/	
	function checkIfCategoryExist($type,$key)
	{
		if($type == "dp")
		{
			$c = $this->db->where("category_id",$key)->get("product_center_stone")->num_rows();
			if($c > 0) return true;

			$c = $this->db->where("category_id",$key)->get("product_side_stone1")->num_rows();
			if($c > 0) return true;

			$c = $this->db->where("category_id",$key)->get("product_side_stone2")->num_rows();
			if($c > 0) return true;
		}
		else if($type == "mp")
		{
			$c = $this->db->where("category_id",$key)->get("product_metal")->num_rows();
			if($c > 0) return true;
		}
		return false;
	}

}

