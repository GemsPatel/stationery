<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class module_manager extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'module_manager_id';
	var $cPrimaryId = '';
	var $cTable = 'module_manager';
	var $controller = 'module_manager';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	//parent constructor will load model inside it
	function module_manager()
	{
		parent::__construct();
		$this->load->model('admin/mdl_module_manager','modm');
		$this->modm->cTableName = $this->cTable;
		$this->modm->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->modm->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
		if((int)$this->session->userdata('admin_id')!=0)
		{
			$res = checkIsSuperAdmin();
			if(!$res)
			{
				setFlashMessage('error',getErrorMessageFromCode('01023'));
				adminRedirect('admin/dashboard');
			}
		}
	
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
	
	function index($start = 0)
	{
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->modm->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start; //starting position of records
		$data['total_records'] = $num->num_rows(); // total num of records
		$data['per_page_drop'] = per_page_drop(); // per page dropdown
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		
		if($this->is_ajax)
		{
			$this->load->view('admin/'.$this->controller.'/ajax_html_data',$data);
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
	function moduleManagerForm()
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
		$this->form_validation->set_rules('module_manager_title','Module Title','trim|required');
		$this->form_validation->set_rules('position_id','Position','trim|required');
		$this->form_validation->set_rules('module_manager_table_name','Module','trim|required');
		$this->form_validation->set_rules('module_manager_field_name','Module','trim|required');
		$this->form_validation->set_rules('module_manager_primary_id','Module','trim|required');
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->modm->getData();
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
				
				$menu_assign = array();
				foreach($this->input->post('menu_assignment') as $k=>$ar)
				{
					$valArr = explode("|",$ar);
					$menu_assign[$valArr[0]][] = $valArr[1];
				}
				$data['module_manager_serialize_menu'] = serialize($menu_assign);

				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
				$this->load->view('admin/layout',$data);
			}
			else // saving data to database
			{
				$this->modm->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->modm->saveData();
				redirect('admin/'.$this->controller);
			}
		}
	}
	
/*
+-----------------------------------------+
	Delete data, single and multiple
	 from single function call.
	@params : Item id. OR post array of ids
+-----------------------------------------+
*/		
	function deleteData()
	{	
		if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->modm->deleteData($ids);
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
			$this->modm->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));	
	}
	
	function popupModuleType()
	{
		$data['menuArr'] = $this->db->select('front_menu_type_id,front_menu_type_name')->where('fmt_status','0')->get('front_menu_type')->result_array();
		//foreach($mtypeArr as $val)
			//$data['menuArr'][$val['front_menu_type_name']] = getMultiLevelFrontMenuDropdown($val['front_menu_type_id'],'0','');
		
		$data['articleArr'] = $this->db->select('article_id,article_name')->where('article_status','0')->get('article')->result_array();//getMultiLevelMenuDropdownArticle('0','');
		$data['bannersArr'] = $this->db->select('banner_id,banner_name')->where('banner_status','0')->get('banner')->result_array();
		$data['filtersArr'] = $this->db->select('filters_id,filters_name')->where('filters_status','0')->get('filters')->result_array();
		$data['catArr'] = getMultiLevelMenuDropdown(0,'');
		$data['elementsArr'] = $this->db->select('front_hook_alias,front_hook_name')->where('front_hook_status','0')->where('front_hook_type','E')->get('front_hook')->result_array();
		//$data['slidersArr'] = $this->db->select('slider_id,slider_name')->where('slider_status','0')->get('slider')->result_array();
		
		$this->load->view('admin/'.$this->controller.'/viewPopupModuleType',$data);
	}
	
	function addModuleForm()
	{
		$hideVal =  explode('|',$this->input->post('hidden_module_param'));
		$data['module_manager_table_name'] = (isset($hideVal[0]))?$hideVal[0]:'';
		$data['module_manager_field_name'] = (isset($hideVal[1]))?$hideVal[1]:'';
		$data['module_manager_primary_id'] = (isset($hideVal[2]))?$hideVal[2]:'';
		
		$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
		$this->load->view('admin/layout',$data);
	}
}