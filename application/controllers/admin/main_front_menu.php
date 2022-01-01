<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class main_front_menu extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'front_menu_type_id';
	var $cPrimaryId = '';
	var $cTable = 'front_menu_type';
	var $cAutoIdM = 'front_menu_id';
	var $cPrimaryIdM = '';
	var $cTableM = 'front_menu';
	var $controller = 'main_front_menu';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function main_front_menu()
	{
		parent::__construct();
		$this->load->model('admin/mdl_main_front_menu','fmt');
		$this->fmt->cTableName = $this->cTable;
		$this->fmt->cAutoId = $this->cAutoId;
		$this->fmt->cTableNameM = $this->cTableM;
		$this->fmt->cAutoIdM = $this->cAutoIdM;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->fmt->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));

		if($this->input->get('m_id') != '' || $this->input->post('m_id') != '')
			$this->cPrimaryIdM  = $this->fmt->cPrimaryIdM = _de($this->security->xss_clean($_REQUEST['m_id']));
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
	function frontMenuName($str)
	{
		if($this->cPrimaryId)
		$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
		$c = $this->db->where('front_menu_type_name',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('frontMenuName', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Front Menu', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		if(isset($_GET['m_id']) || isset($_GET['front_menu_name_filter']))
		{
			$num = $this->fmt->getDataMenuItem();
			$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
			//echo $this->db->last_query();
			
			$data['start'] = $start; //starting position of records
			$data['total_records'] = $num->num_rows(); // total num of records
			$data['per_page_drop'] = per_page_drop(); // per page dropdown
			$data['srt'] = $this->input->get('s'); // sort order
			$data['field'] = $this->input->get('f'); // sort field name
			$data['front_menu_name_filter'] = $this->input->get('front_menu_name_filter'); // filter by category name
			$data['status_filter'] = ($this->input->get('status_filter') != '')?$this->input->get('status_filter'):'-1'; // filter by status
	
			if($this->is_ajax)
			{
				$this->load->view('admin/'.$this->controller.'/menu_ajax_html_data',$data); // this view loaded on ajax call
			}
			else
			{
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list_menu';
				$this->load->view('admin/layout',$data);
			}
		}
		else
		{
			$num = $this->fmt->getData();
			$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
			//echo $this->db->last_query();
			
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
	}
/*
+-----------------------------------------+
	Function will save data, all parameters 
	will be in post method.
+-----------------------------------------+
*/
	function frontMenuForm()
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
		$this->form_validation->set_rules('front_menu_type_name','Menu name','trim|required|callback_frontMenuName');
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->fmt->getData();
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
				$this->fmt->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->fmt->saveData();
				redirect('admin/'.$this->controller);
			}
		}
		
	}

/*
+-----------------------------------------+
	Function will save data, all parameters 
	will be in post method.
+-----------------------------------------+
*/
	function menuItemForm()
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
		$this->form_validation->set_rules('front_menu_item_type','Menu Item Type','trim|required');
		$this->form_validation->set_rules('front_menu_name','Menu Name','trim|required');
		$this->form_validation->set_rules('front_hook_alias','Controller ','trim|required');
		$this->form_validation->set_rules('front_layout_id','Layout','trim|callback_checkClassname');
		$this->form_validation->set_rules('fm_icon','Menu Icon','trim|required');
		$this->form_validation->set_rules('custom_page_title','Custom Page Title','trim');
		$this->form_validation->set_rules('meta_description','Category meta Description','trim');
		$this->form_validation->set_rules('meta_keyword','Category Meta keyword','trim');
		$this->form_validation->set_rules('author','Category Author Name','trim');
		$this->form_validation->set_rules('content_rights','Category Contents Rights','trim');

		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryIdM != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->fmt->getDataMenuItem();
				$dt = $dtArr->row_array();
				
				$dt['hidden_page_param'] = $dt['front_menu_item_type']."|".$dt['front_menu_table_name']."|".$dt['front_menu_table_field_name']."|".$dt['front_menu_primary_id'];
			}
			$dt['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form_menu';
			$this->load->view('admin/layout',$dt);
		}
		else
		{
			if($this->form_validation->run() == FALSE )
			{
				$data['error'] = $this->form_validation->get_errors();
				if($data['error'])
					setFlashMessage('error',getErrorMessageFromCode('01005'));
				
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form_menu';
				$this->load->view('admin/layout',$data);
			}
			else // saving data to database
			{
				$this->fmt->cPrimaryIdM = $this->cPrimaryIdM; // setting variable to model
				$this->fmt->saveDataMenuItem();
				redirect('admin/'.$this->controller.'?item_id='._en($this->cPrimaryId).'&m_id=');
			}
		}
		
	}

/*
+-----------------------------------------+
	Function will call menu with passing get data 
	will be in post method.
+-----------------------------------------+
*/
	function popupPageForm()
	{
		$data = array();

		$data = $this->input->post();
		if($this->cPrimaryIdM != '')
		{
			$dtArr = $this->fmt->getDataMenuItem();
			$dt = $dtArr->row_array();
			$data = array_merge($data,$dt);
		}
		$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form_menu';
		$this->load->view('admin/layout',$data);
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
			$this->fmt->deleteData($ids);
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
			$this->fmt->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));	
	}
	
	function popupSitePages()
	{
		$data['res'] = $this->fmt->GetSitePages();
		$data['item_id'] = _en($this->cPrimaryId);
		$data['m_id'] = _en($this->cPrimaryIdM);
 		$this->load->view('admin/'.$this->controller.'/viewPopupSitePages',$data);
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
			$this->db->where('front_menu_id',$id)->update('front_menu',array('fm_sort_order'=>$sort_order));
			echo json_encode(array('type'=>'success','msg'=>'Sort order updated successfully.'));		
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Specify sort order.'));		
		}
	}	

}