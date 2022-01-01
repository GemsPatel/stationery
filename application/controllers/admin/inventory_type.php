<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class inventory_type extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'inventory_type_id';
	var $cPrimaryId = '';
	var $cTable = 'inventory_type';
	var $cAutoIdM = 'inventory_master_specifier_id';
	var $cPrimaryIdM = '';
	var $cTableM = 'inventory_master_specifier';
	var $controller = 'inventory_type';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function inventory_type()
	{
		parent::__construct();
		$this->load->model('admin/mdl_inventory_type','mit');
		$this->mit->cTableName = $this->cTable;
		$this->mit->cAutoId = $this->cAutoId;
		$this->mit->cTableNameM = $this->cTableM;
		$this->mit->cAutoIdM = $this->cAutoIdM;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->mit->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));

		if($this->input->get('m_id') != '' || $this->input->post('m_id') != '')
			$this->cPrimaryIdM  = $this->mit->cPrimaryIdM = _de($this->security->xss_clean($_REQUEST['m_id']));
		
		/**
		 * added on 16-04-2016
		 */
		checkDevPermission(); 
		
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
	
	/**
	 * @deprecated
	 * @param unknown $str
	 * @return boolean
	 */
	function inventoryKey($str)
	{
		if($this->cPrimaryId)
		$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
		$c = $this->db->where('it_key',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('inventoryKey', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}

	/**
	 * @deprecated
	 * @param unknown $str
	 * @return boolean
	 */
	function ims_input_labelCheck($str)
	{
		if(strpos($str, "|") !== FALSE || strpos($str, ":") !== FALSE) 
		{
			$this->form_validation->set_message('ims_input_label', 'Special character "|" and ":" is not allowed.');
			return false; 
		}
		else 
		{
			return true; 
		}
	}
	
	
	/**
	 * 
	 * @param number $start
	 */
	function index($start = 0)
	{
		/**		
		 * insert admin log  
		 */
		$logType = 'V';
		saveAdminLog($this->router->class, 'Inventory Type', $this->cTable, $this->cAutoId, 0, $logType);
		
		/**
		 * check and apply permission layer
		 */
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		
		
		if(isset($_GET['m_id']) || isset($_GET['inventory_master_specifier_name_filter']))
		{
			$num = $this->mit->getDataInventoryTypeItem();
			$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
			//echo $this->db->last_query();
			
			$data['start'] = $start; //starting position of records
			$data['total_records'] = $num->num_rows(); // total num of records
			$data['per_page_drop'] = per_page_drop(); // per page dropdown
			$data['srt'] = $this->input->get('s'); // sort order
			$data['field'] = $this->input->get('f'); // sort field name

			$data['ims_input_type_filter'] = $this->input->get('ims_input_type_filter'); // filter by category name
			$data['ims_name_filter'] = $this->input->get('ims_name_filter'); // filter by category name
			$data['status_filter'] = ($this->input->get('status_filter') != '')?$this->input->get('status_filter'):'-1'; // filter by status
	
			if($this->is_ajax)
			{
				$this->load->view('admin/'.$this->controller.'/ims_ajax_html_data',$data); // this view loaded on ajax call
			}
			else
			{
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list_ims';
				$this->load->view('admin/layout',$data);
			}
		}
		else
		{
			$num = $this->mit->getData();
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
	function inventoryTypeForm()
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
		$this->form_validation->set_rules('it_name','Inventory name','trim|required');
		
		if( empty( $this->cPrimaryId ) )
		{	
			$this->form_validation->set_rules('it_key','Inventory key','trim|required|callback_inventoryKey');		
		}
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->mit->getData();
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
				$this->mit->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->mit->saveData();
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
	function imsForm()
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
		$this->form_validation->set_rules('ims_input_type','Input Type','trim|required');
		$this->form_validation->set_rules('ims_tab_label','Name','trim|required');
		$this->form_validation->set_rules('ims_fieldset_label','Heading Title','trim|required');
		$this->form_validation->set_rules('ims_input_label','Input Label','trim|required|callback_ims_input_labelCheck');
		

		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryIdM != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->mit->getDataInventoryTypeItem();
				$dt = $dtArr->row_array();
			}
			$dt['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form_ims';
			$this->load->view('admin/layout',$dt);
		}
		else
		{
			if($this->form_validation->run() == FALSE )
			{
				$data['error'] = $this->form_validation->get_errors();
				if($data['error'])
					setFlashMessage('error',getErrorMessageFromCode('01005'));
				
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form_ims';
				$this->load->view('admin/layout',$data);
			}
			else // saving data to database
			{
				$this->mit->cPrimaryIdM = $this->cPrimaryIdM; // setting variable to model
				$this->mit->saveDataInventoryTypeItem();
				redirect('admin/'.$this->controller.'?item_id='._en($this->cPrimaryId).'&m_id=');
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
			$this->mit->deleteData($ids);
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
			$this->mit->updateStatus();
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
			$data = array('ims_sort_order'=>$sort_order);
			if(  MANUFACTURER_ID == 7 )
			{
				$this->db->where($this->cAutoIdM, $id);
				$this->db->update($this->cTableM,$data);
			}
			else	//ccTLDs
			{
				$this->db->where($this->cAutoIdM,  $id)->where("manufacturer_id", MANUFACTURER_ID);
				$this->db->update($this->cTableM."_cctld",$data);
			}
							
			echo json_encode(array('type'=>'success','msg'=>'Sort order updated successfully.'));		
		}
		else 
		{
			echo json_encode(array('type'=>'error','msg'=>'Specify sort order.'));		
		}
	}	

}