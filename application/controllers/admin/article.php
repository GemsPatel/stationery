<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class article extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'article_id';
	var $cPrimaryId = '';
	var $cTable = 'article';
	var $controller = 'article';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function article()
	{
		parent::__construct();
		$this->load->model('admin/mdl_article','art');
		$this->art->cTableName = $this->cTable;
		$this->art->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->art->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
/*
+--------------------------------------------------+
	function will check artegory alias, return error
	if alias already exist.
+--------------------------------------------------+
*/	
	function checkAlias($str)
	{
		$al  = url_title($str);
		if($this->cPrimaryId)
			$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
		
		$c = $this->db->where('article_alias',$al)->where('article_status','0')->get($this->cTable)->num_rows();
		
		if($c > 0)
		{
			$this->form_validation->set_message('checkAlias','This alias of Article is already exist.');
			return false;
		}
		else
			return true;
	}
	/*
+-----------------------------------------+
	Callback function from form validation will
	check config key duplication in database
	$str - > string we are going to check in database
+-----------------------------------------+
*/
function checkArticleKey($str)
{
	
	if($this->cPrimaryId)
		$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
	$c = $this->db->where('article_key',$str)->get($this->cTable)->num_rows();
	if($c > 0)
	{
		$this->form_validation->set_message('checkArticleKey', 'This key already exist in database, please try different.');
		return false;
	}
	else
		return true;
}
	
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Article', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->art->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start; //starting position of records
		$data['total_records'] = $num->num_rows(); // total num of records
		$data['per_page_drop'] = per_page_drop(); // per page dropdown
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['cat_filter'] = $this->input->get('cat_filter'); // filter by artegory name
		$data['article_name_filter'] = $this->input->get('article_name_filter'); // search article name
		$data['article_key_filter'] = $this->input->get('article_key_filter'); // search article key
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
	function imageSize($str)
	{
		/**
		 * get uploaded file size in KB
		 */
		$filesize = round($_FILES['article_image']['size'] / 1024);
	
		$allowedSize = getField("config_value", "configuration", "config_key","MANAGE_ARTICAL_IMG_UPLOAD_SIZE");
	
		$allowedrec = getField("config_value", "configuration", "config_key","ARTICAL_REC_IMG");
		
		if($filesize > $allowedSize)
		{
			$this->form_validation->set_message('imageSize', '(Maximum allowed size is : '.$allowedSize.' KB,'.$allowedrec.', your uploaded file size is '.$filesize.' KB.)');
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function articleForm()
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
		$this->form_validation->set_rules('article_name','Article  Name','trim|required|callback_checkAlias');
		if(!$this->cPrimaryId)
			$this->form_validation->set_rules('article_key','Article Key','trim|required|callback_checkArticleKey');
		$this->form_validation->set_rules('article_description','Article Description','trim|required');
		$this->form_validation->set_rules('article_image','Article Image','trim|required|callback_imageSize');
/*		$this->form_validation->set_rules('custom_page_title','Article Custom Page Title','trim|required');
		$this->form_validation->set_rules('meta_keyword','Article Meta keyword','trim|required');
		$this->form_validation->set_rules('meta_description','Article Meta Description','trim|required');
		$this->form_validation->set_rules('author','Article Author Name','trim|required');
		$this->form_validation->set_rules('content_rights','Article Contents Rights','trim|required');
*/		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->art->getData();
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
				$this->art->cPrimaryId = $this->cPrimaryId; // setting variable to model
				
  			 	$this->art->saveData();
				redirect('admin/'.$this->controller);
			}
		}
		
	}
	
/*
+-----------------------------------------+
	Delete artegory, single artegory and multiple
	artegory from single function call.
	@params : Item id. OR post array of ids
+-----------------------------------------+
*/		
	function deleteData()
	{	
		if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->art->deleteData($ids);
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
			$this->art->updateStatus();
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
			$this->db->where($this->cAutoId,$id)->update($this->cTable,array('article_sort_order'=>$sort_order));
			echo json_encode(array('type'=>'success','msg'=>'Sort order updated successfully.'));		
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Specify sort order.'));		
		}
	}	

}