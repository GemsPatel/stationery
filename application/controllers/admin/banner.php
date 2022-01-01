<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class banner extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'banner_id';
	var $cPrimaryId = '';
	var $cTable = 'banner';
	var $controller = 'banner';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function banner()
	{
		parent::__construct();
		$this->load->model('admin/mdl_banner','bnr');
		$this->bnr->cTableName = $this->cTable;
		$this->bnr->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->bnr->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
		$this->chk_permission();	
	}
/**
+----------------------------------------------------+
	check permission for user
+----------------------------------------------------+
*/
	function chk_permission()
	{
		$per =  fetchPermission($this->controller);
		if(!empty($per))
		{
			$this->per_add = @$per['permission_add'];		
			$this->per_edit = @$per['permission_edit'];		
			$this->per_delete = @$per['permission_delete'];		
			$this->per_view = @$per['permission_view'];		
		}
		else 
		{
			showPermissionDenied();
		}
	}	
/*
+-----------------------------------------+
	This function will remap url for admin,
	and remove unnecesary name from url.
	For example : if we don't want index
	strgin in url while listin item, we can 
	remove it using this function
+-----------------------------------------+
*/	
	function _remap($method,$params)
	{
		if(method_exists($this,$method))
			return call_user_func_array(array($this, $method), $params);
		else
		{
			$para[0] = $method;
			
			if(count($params) > 0)
				$para = array_merge($para,$params);
			
			//here we are going to call out custom function for load specific menu.
			call_user_func_array(array($this,'index'),$para);
		}
	}
	
	
/*
+--------------------------------------------------+
	function will check banner name, return error
	if name already exist.
+--------------------------------------------------+
*/	
	function bannerName($str)
	{
		if($this->cPrimaryId)
		$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
		$c = $this->db->where('banner_name',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('bannerName', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}
	
	function index($stbnr = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Banner', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->bnr->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$stbnr,3);
		//echo $this->db->last_query();
		
		$data['stbnr'] = $stbnr; //stbnring position of records
		$data['total_records'] = $num->num_rows(); // total num of records
		$data['per_page_drop'] = per_page_drop(); // per page dropdown
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['bnr_filter'] = $this->input->get('bnr_filter'); // filter by banner name
		$data['text_name'] = $this->input->get('text_name'); // sort field name
		$data['cat_filter'] = $this->input->get('cat_filter'); // sort field name
		$data['status_filter'] = ($this->input->get('status_filter') != '')?$this->input->get('status_filter'):'-1'; // filter by status

		if($this->is_ajax)
		{
			$this->load->view('admin/'.$this->controller.'/ajax_html_data',$data); // this view loaded on ajax call
		}
		else
		{
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list';
			$this->load->view('admin/layout',$data);
		}
	}
/*
+-----------------------------------------+
	Function will save data, all parameters 
	will be in post method.
+-----------------------------------------+
*/
	function bannerCategoryKey($str)
	{
		if($this->cPrimaryId)
			$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
		$c = $this->db->where('banner_key',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('bannerCategoryKey', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
			return true;
	}
	
	
	function imageSize($str)
	{
		/**
		 * get uploaded file size in KB
		 */
		$filesize = round($_FILES['banner_image']['size'] / 1024);
	
		$allowedSize = getField("config_value", "configuration", "config_key","BANNER_UPL_SIZE");
	
		$allowedrec = getField("config_value", "configuration", "config_key","BANNER_REC_IMG");
			
		if($filesize > $allowedSize)
		{
			$brk = "\n";
			$this->form_validation->set_message('imageSize', '(Maximum allowed size is : '.$allowedSize.' KB,'.$allowedrec.', your uploaded file size is '.$filesize.' KB.)');
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function bannerForm()
	{
		if($this->cPrimaryId != '')
		{
			if($this->per_edit != 0)
			{
				setFlashMessage('error',getErrorMessageFromCode('01008'));
				showPermissionDenied();
			}
		}
		else if($this->per_add != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01007'));
			showPermissionDenied();
		}
		$data = array();
		if(!$this->cPrimaryId)
		{
			$this->form_validation->set_rules('banner_key','Banner Key','trim|required|callback_bannerCategoryKey');
		}
		$this->form_validation->set_rules('banner_name','Banner  Name','trim|required|callback_bannerName');
		$this->form_validation->set_rules('banner_image','Banner Image','trim|required|callback_imageSize');
		$this->form_validation->set_rules('banner_description','Banner Description','trim|required');
	
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->bnr->getData();
				$dt = $dtArr->row_array();
				
			}
			$dt['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
			$this->load->view('admin/layout',$dt);
		}
		else
		{
			if($this->form_validation->run() == FALSE )
			{
				$data['error'] = $this->form_validation->get_errors();
				if($data['error'])
					setFlashMessage('error',getErrorMessageFromCode('01005'));
				
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
				$this->load->view('admin/layout',$data);
			}
			else // saving data to database
			{
				$this->bnr->cPrimaryId = $this->cPrimaryId; // setting variable to model
				
  			 	$this->bnr->saveData();
				redirect('admin/'.$this->controller);
			}
		}
		
	}
	
/*
+-----------------------------------------+
	Delete bnregory, single bnregory and multiple
	bnregory from single function call.
	@params : Item id. OR post array of ids
+-----------------------------------------+
*/		
	function deleteData()
	{	
		if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->bnr->deleteData($ids);
		}
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01009')));
	}
/*
+-----------------------------------------+
	Update status for enabled/disabled
	@params : post array of ids,status
+-----------------------------------------+
*/	
	function updateStatus()
	{
		if($this->per_edit == 0)
			$this->bnr->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));		
	}
/**
 * @author Cloudwebs
 * @abstract Update sort order
 * @param  $sort_order 
 * @param  $id
 */	
	function updateSortOrder()
	{
		$id = $this->input->post('id');
		$sort_order = $this->input->post('sort_order');
		
		if($id!=''  && $sort_order!='')
		{
			$tableName = ( MANUFACTURER_ID != 7 ) ? $this->cTable."_cctld" : $this->cTable;
				
			$this->db->where($this->cAutoId,$id)->update($tableName,array('banner_sort_order'=>$sort_order));
			echo json_encode(array('type'=>'success','msg'=>'Sort order updated successfully.'));		
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Specify sort order.'));		
		}
	}	

}