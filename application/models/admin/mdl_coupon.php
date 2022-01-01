<?php
class mdl_coupon extends CI_Model
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
			$cat_filter = $this->input->get('cat_filter');
			$text_name = $this->input->get('text_name');
			$text_code = $this->input->get('text_code');
			
			if(isset($text_name) && $text_name != "")
				$this->db->where('coupon_name LIKE \''.$text_name.'%\' ');
				
			if(isset($text_code) && $text_code != "")
				$this->db->where('coupon_code LIKE \''.$text_code.'%\' ');
			if(isset($cat_filter) && $cat_filter != "")
				$this->db->where('(coupon_category_map.category_id = \''.$cat_filter.'\')');		
			
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('coupon_status LIKE \''.$status_filter.'\' ');
			
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);				
			else
				$this->db->order_by('coupon.coupon_id','ASC');
		}
		else if($this->cPrimaryId != '')
			$this->db->where('coupon.coupon_id',$this->cPrimaryId);
			
		$this->db->select('coupon.coupon_id,coupon.coupon_name,coupon.coupon_code,coupon.coupon_maximum_use,coupon.coupon_above_amount,coupon.coupon_is_above_amount_currencywise,coupon.coupon_type,coupon.coupon_discount_amt,coupon.coupon_expiry_date,coupon.coupon_desc,coupon.coupon_status,coupon_category_map.category_id');
		$this->db->join('coupon_category_map','coupon_category_map.coupon_id=coupon.coupon_id','LEFT');
		$this->db->join('product_categories','product_categories.category_id=coupon_category_map.category_id','LEFT');
		$this->db->group_by('coupon.coupon_id');
		
		if( MANUFACTURER_ID != 7 )
				$this->db->where('manufacturer_id', MANUFACTURER_ID );
				
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
	
	function saveData()
	{
		$data = $this->input->post();
		$category_idArr = $data['category_id'];
		unset($data['item_id']);
		unset($data['category_id']);
		$last_id =0;
		$data['manufacturer_id'] = MANUFACTURER_ID;
		
		//if primary id set then we have to make update query
		if($this->cPrimaryId != '')
		{
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
		
		if($this->cPrimaryId != '')
		{
			$this->db->where("coupon_id",$last_id)->delete("coupon_category_map");
			foreach($category_idArr as $k=>$ar)
			{
				$this->db->insert("coupon_category_map",array('coupon_id'=>$last_id,'category_id'=>$ar));
			}
		}
		else // insert into coupon_category_map table
		{
			foreach($category_idArr as $k=>$ar)
			{
				$this->db->insert("coupon_category_map",array('coupon_id'=>$last_id,'category_id'=>$ar));
			}
		}
		saveAdminLog($this->router->class, @$data['coupon_name'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Coupon has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
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
				//$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);				
				$tabNameArr = array('0'=>'orders');
				$fieldNameArr = array('0'=>'coupon_id');
				$res=isImageIdExist($tabNameArr,$fieldNameArr,$id);// this function call for un delete field
			if(sizeof($res)>0)
			{
				echo json_encode($res);	
				return;
			}
			else
			{
				$getName = getField('coupon_name', $this->cTableName, $this->cAutoId, $id);
				saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
				$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
				$this->db->where_in('coupon_id',$id)->delete('coupon_category_map');
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
		
		$data['coupon_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}

}