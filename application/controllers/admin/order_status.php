<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class order_status extends CI_controller {
	
	var $is_ajax = false;
	var $cAutoId = 'order_status_id';
	var $cPrimaryId = '';
	var $cTable = 'order_status';
	var $controller = 'order_status';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function order_status()
	{
		parent::__construct();
		$this->load->model('admin/mdl_order_status','ors');
		$this->ors->cTableName = $this->cTable;
		$this->ors->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->ors->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
	For exaconle : if we don't want index
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
0+-----------------------------------------+
	Function will return  unique value
	and checks order key is value unique
+-----------------------------------------+
*/
	function orderStatusName($str)
	{
		if($this->cPrimaryId)
		$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
		$c = $this->db->where('order_status_name',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('orderStatusName', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}
	/*
0+-----------------------------------------+
	Function will return  unique value
	and checks order key is value unique
+-----------------------------------------+
*/
	function orderStatuskey($str)
	{
		if($this->cPrimaryId)
		$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
		$c = $this->db->where('order_status_key',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('orderStatuskey', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Order Status', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->ors->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['staus_name'] = $this->input->get('staus_name');
		$data['staus_key'] = $this->input->get('staus_key');
		$data['status_filter'] = ($this->input->get('status_filter') != '')?$this->input->get('status_filter'):'-1'; // filter by status
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
	function orderStatusForm()
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
		$this->form_validation->set_rules('order_status_name','Order Status Name','trim|required|callback_orderStatusName');
		$this->form_validation->set_rules('order_status_icon','Order Status Icon','trim|required');
		if( empty( $this->cPrimaryId ) )
		{
			$this->form_validation->set_rules('order_status_key','Order Status Key','trim|required|callback_orderStatuskey');
		}
		
		
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->ors->getData();
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
				$this->ors->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->ors->saveData();
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
			$this->ors->deleteData($ids);
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
			$this->ors->updateStatus();
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
			$this->db->where($this->cAutoId,$id)->update($this->cTable,array('order_status_sort_order'=>$sort_order));
			echo json_encode(array('type'=>'success','msg'=>'Sort order updated successfully.'));		
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Specify sort order.'));		
		}
	}	

}