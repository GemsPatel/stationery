<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class configuration extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'config_id';
	var $cPrimaryId = '';
	var $cTable = 'configuration';
	var $controller = 'configuration';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function configuration()
	{
		parent::__construct();
		$this->load->model('admin/mdl_configuration','conf');
		$this->conf->cTableName = $this->cTable;
		$this->conf->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->conf->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
		$num = $this->conf->getData();
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
	Callback function from form validation will
	check config key duplication in database
	$str - > string we are going to check in database
+-----------------------------------------+
*/
function checkConfigKey($str)
{
	
	if($this->cPrimaryId)
		$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
	$c = $this->db->where('config_key',$str)->get($this->cTable)->num_rows();
	if($c > 0)
	{
		$this->form_validation->set_message('checkConfigKey', 'This key already exist in database, please try different.');
		return false;
	}
	else
		return true;
}/*
+-----------------------------------------+
	Function will save data, all parameters 
	will be in post method.
+-----------------------------------------+
*/
	function configurationForm()
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
		$this->form_validation->set_rules('config_display_name','Display Name','trim|required');
		$this->form_validation->set_rules('config_value','Configuration Value','trim|required');
		if(!$this->cPrimaryId)
			$this->form_validation->set_rules('config_key','Configuration key','trim|required|callback_checkConfigKey');
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->conf->getData();
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
				$this->conf->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->conf->saveData();
				redirect('admin/'.$this->controller);
			}
		}
		
	}

	

}