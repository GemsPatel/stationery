<?php
class mdl_admin_permission extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $cCategory = '';
	
	function getData()
	{
		$res = $this->db->query("SELECT m.am_name,m.admin_menu_id,a.admin_user_id,a.admin_user_firstname,g.admin_user_group_name 
								 FROM admin_menu m ,admin_user a INNER JOIN admin_user_group g ON g.admin_user_group_id=a.admin_user_group_id 
								 WHERE m.am_parent_id=0 AND m.am_status=0 AND a.admin_user_status=0 
								 GROUP BY a.admin_user_id,m.admin_menu_id ORDER BY a.admin_user_id,m.admin_menu_id");
								 
		//echo $this->db->last_query();
		return $res;
	}

/*
+-----------------------------------------+
	Function will update or insert permissions for particular admin user
	will be in post method.
+-----------------------------------------+
*/
	function update_insertPermission()
	{
		$val =  $this->input->post('val');
		$type = $this->input->post('type');
		
		$valArr = explode("||",$val);
		//pr($valArr);echo $type;
		$data = array();
		$is_data_set = false;
		$sql="";
		$update="";
		foreach($valArr as $k=>$ar)
		{
			$arArr = explode("|",$ar);
			$arArr[3] = ((int)$arArr[3] == 0)? 1 : 0;

			if(!$is_data_set)
			{
				$is_data_set = true;
				if($type == "all" || $type == "allall")
				{
					$data['permission_view'] = $arArr[3];
					$data['permission_add'] = $arArr[3];
					$data['permission_edit'] = $arArr[3];
					$data['permission_delete'] = $arArr[3];
				}
				else if($type == "viewall" || $type == "view")
					$data['permission_view'] = $arArr[3];
				else if($type == "addall" || $type == "add")
					$data['permission_add'] = $arArr[3];
				else if($type == "editall" || $type == "edit")
					$data['permission_edit'] = $arArr[3];
				else if($type == "deleteall" || $type == "delete")
					$data['permission_delete'] = $arArr[3];

				foreach($data as $key=>$val)
				{
					$update .= $key."=".$val.", ";
				}
				$update .= "permission_modified_date=NOW()";

			}

			$data['admin_user_id'] = $arArr[1];
			$data['admin_menu_id'] = $arArr[2];
			$sql = $this->db->insert_string($this->cTableName,$data).' ON DUPLICATE KEY UPDATE '.$update;
			$this->db->query($sql);
		}
		$returnArr['type'] ='success';
		$returnArr['msg'] = "Records has been updated successfully.";

		return $returnArr;
 	}

}