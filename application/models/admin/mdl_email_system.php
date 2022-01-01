<?php
class mdl_email_system extends CI_Model
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
			
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoId,'ASC');
				
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
		
		if(checkIsSuperAdmin() == FALSE)
			$this->db->where($this->cTableName.'.admin_user_id',@$this->session->userdata('admin_id'));
			
		$this->db->select($this->cTableName.'.*,admin_user_firstname,admin_user_lastname,admin_user_group_id');
		$this->db->join('admin_user','admin_user.admin_user_id='.$this->cTableName.'.admin_user_id', 'left');
		
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
	
	function sendEmails()
	{
		$data = $this->input->post();
		unset($data['item_id']);
		
		$data['admin_user_id'] = @$this->session->userdata('admin_id');
		$es_to_emails = "";

		//store entry in private_message if private_email value availale
		if(isset($data['private_email']))
		{
			$pri_msg_data['pm_email'] = _de($data['private_email']);
			$res = executeQuery("SELECT * FROM private_message WHERE pm_parent_id=0 AND pm_email='".$pri_msg_data['pm_email']."'");
			$pri_msg_data['pm_parent_id'] = $res[0]['private_message_id'];
			$pri_msg_data['admin_user_id'] = $this->session->userdata('admin_id');
			$pri_msg_data['pm_name'] = $res[0]['pm_name'];
			$pri_msg_data['pm_phone'] = $res[0]['pm_phone'];
			$pri_msg_data['pm_question'] = $data['es_subject'];
			$pri_msg_data['pm_message'] = $data['es_message'];
			$pri_msg_data['pm_ip_address'] = $this->input->ip_address();
			$pri_msg_data['pm_status'] = "O";
			$pri_msg_data['manufacturer_id'] = MANUFACTURER_ID;
			$this->db->insert("private_message",$pri_msg_data);
		}
		unset($data['private_email']);
		
		//fetch all send to email id and store in variable $es_to_emails
		if($data['es_to_emails'] == "newsletter")
		{			
			$res = $this->db->query("SELECT newsletter_email FROM newsletter_subscriber WHERE newsletter_status=0");
			foreach ($res->result_array() as $row)
				$es_to_emails .= $row['newsletter_email'].",";
		
			$data['es_module_name'] = "Newsletter Subscriber";
		}
		else if($data['es_to_emails'] == "customer_all")
		{
		
			$res = $this->db->query("SELECT customer_emailid FROM customer WHERE customer_status=0 AND customer_approved='A'");
			foreach ($res->result_array() as $row)
				$es_to_emails .= $row['customer_emailid'].",";
		
			$data['es_module_name'] = "Customer";
		}
		else if($data['es_to_emails'] == "manufacturer_all")
		{
		
			$res = $this->db->query("SELECT manufacturer_email_id FROM manufacturer WHERE manufacturer_status=0");
			foreach ($res->result_array() as $row)
				$es_to_emails .= $row['manufacturer_email_id'].",";
		
			$data['es_module_name'] = "Manufacturer";
		}
		else if($data['es_to_emails'] == "customer_group" && isset($data['es_module_primary_id']))
		{
		
			$where = "( ";
			foreach($data['es_module_primary_id'] as $k=>$ar)
				$where .= "customer_group_id=".$ar." OR ";
			
			$res = $this->db->query("SELECT customer_emailid FROM customer WHERE customer_status=0 AND customer_approved='A' AND ".substr($where,0,-4)." )");
			foreach ($res->result_array() as $row)
				$es_to_emails .= $row['customer_emailid'].",";

			$data['es_module_name'] = "Customer";
		}
		else if($data['es_to_emails'] == "customer" && isset($data['es_module_primary_id']))
		{
		
			$where = "( ";
			foreach($data['es_module_primary_id'] as $k=>$ar)
				$where .= "customer_id=".$ar." OR ";
			
			$res = $this->db->query("SELECT customer_emailid FROM customer WHERE customer_status=0 AND customer_approved='A' AND ".substr($where,0,-3)." )");
			foreach ($res->result_array() as $row)
				$es_to_emails .= $row['customer_emailid'].",";
		
			$data['es_module_name'] = "Customer";
		}
		else if($data['es_to_emails'] == "manufacturer" && isset($data['es_module_primary_id']))
		{
		
			$where = "( ";
			foreach($data['es_module_primary_id'] as $k=>$ar)
				$where .= "manufacturer_id=".$ar." OR ";
			
			$res = $this->db->query("SELECT manufacturer_email_id FROM manufacturer WHERE manufacturer_status=0 AND ".substr($where,0,-3)." )");
			foreach ($res->result_array() as $row)
				$es_to_emails .= $row['manufacturer_email_id'].",";
		
			$data['es_module_name'] = "Manufacturer";
		}
		
		if($_FILES['email_listfile']['name'] != "")
		{
			$path = $this->uploadFile();
			$res = readCsv($path);
			foreach($res as $k=>$ar)
				$es_to_emails .= $ar.",";
		}
		if($data['custom_email_address'] != "")
		{
			$es_to_emails .= $data['custom_email_address'].",";
		}
		if(isset($data['es_product_id']) && sizeof($data['es_product_id'])>0)
		{
			$data['es_product_id'] = implode("|",$data['es_product_id']);
		}
		
				
		unset($data['products']);
		unset($data['custom_email_address']);
		unset($data['es_product_idC']);
		$es_to_emails = substr($es_to_emails,0,-1);
		if($es_to_emails != "")
		{
			sendMail($es_to_emails,$data['es_subject'],@$data['es_message'],$data['es_from_emails']);
			$data['es_to_emails'] = str_replace(",","|",$es_to_emails);
			$data['es_module_primary_id'] =  (isset($data['es_module_primary_id']))?implode("|",$data['es_module_primary_id']):"";
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
			saveAdminLog($this->router->class, @$data['es_from_emails'], $this->cTableName, $this->cAutoId, $last_id, $logType); 
			setFlashMessage('success','Email send has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		}
		else 
		{
			setFlashMessage('error','No email address specified.');
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
			foreach($ids as $id)
			{
				$getName = getField('es_from_emails', $this->cTableName, $this->cAutoId, $id);
				saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
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
		
		$data['es_status'] = $status;
		$this->db->where($this->cAutoId,$cat_id);
		$this->db->update($this->cTable,$data);
	}

/*
+------------------------------------------------------+
	uploads product image folder
+------------------------------------------------------+
*/	
	function uploadFile()
	{
		$image = uploadFile('email_listfile','doc','email_system'); //input file, type, folder
		if(@$image['error'])
		{
			setFlashMessage('error',$image['error']);
			$path = $image['path'];
			redirect('admin/'.$this->router->class);	
		}
		return $image['path'] ;
	}
	
}