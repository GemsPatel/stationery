<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class languages extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'languages_id';
	var $cPrimaryId = '';
	var $cTable = 'languages';
	var $controller = 'languages';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function languages()
	{		
		parent::__construct();
		$this->load->model('admin/mdl_languages','lan');
		$this->lan->cTableName = $this->cTable;
		$this->lan->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->lan->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
+-----------------------------------------+
	Callback function from form validation will
	check duplication in database
	$str - > string we are going to check in database
+-----------------------------------------+
*/
	function checkLanguagesName($str)
	{
		if($this->cPrimaryId)
			$this->db->where($this->cAutoId." !=",$this->cPrimaryId);	
					
		$c = $this->db->where('l_name',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('checkLanguagesName', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
			return true;
	}
	
	function checkLanguagesKey($str)
	{
		if($this->cPrimaryId)
			$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
				
		$c = $this->db->where('l_key',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('checkLanguagesKey', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
			return true;
	}
	
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Languages', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->lan->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
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
	function languagesForm()
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
		$this->form_validation->set_rules('l_name','languages name','trim|required|callback_checkLanguagesName');
		if(!$this->cPrimaryId)
			$this->form_validation->set_rules('l_key','languages Key','trim|required|callback_checkLanguagesKey');
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') //if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->lan->getData();
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
				$this->lan->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->lan->saveData();
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
			$this->lan->deleteData($ids);
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
			$this->lan->updateStatus();
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
		
		if($id!='' && $sort_order!='')
		{
			$this->db->where($this->cAutoId,$id)->update($this->cTable,array('l_sort_order'=>$sort_order));
			echo json_encode(array('type'=>'success','msg'=>'Sort order updated successfully.'));		
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Specify sort order.'));		
		}
	}	
}