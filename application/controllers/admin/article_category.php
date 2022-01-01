<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class article_category extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'article_category_id';
	var $cPrimaryId = '';
	var $cTable = 'article_category';
	var $controller = 'article_category';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function article_category()
	{
		parent::__construct();
		$this->load->model('admin/mdl_article_category','art_cat');
		$this->art_cat->cTableName = $this->cTable;
		$this->art_cat->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->art_cat->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
	
	function articleCategoryKey($str)
	{
		if($this->cPrimaryId)
		$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
		$c = $this->db->where('article_category_key',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('articleCategoryKey', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}		
/*
+--------------------------------------------------+
	function will check art_category alias, return error
	if alias already exist.
+--------------------------------------------------+
*/	
	function checkAlias($str)
	{
		$al  = url_title($str);
		if($this->cPrimaryId)
			$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
		
		$c = $this->db->where('article_category_alias',$al)->where('article_category_status','0')->get($this->cTable)->num_rows();
		
		if($c > 0)
		{
			$this->form_validation->set_message('checkAlias','This alias of article category is already exist.');
			return false;
		}
		else
			return true;
	}
	
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Article Category', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->art_cat->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start; //starting position of records
		$data['total_records'] = $num->num_rows(); // total num of records
		$data['per_page_drop'] = per_page_drop(); // per page dropdown
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['cat_filter'] = $this->input->get('cat_filter'); // filter by art_category name
		$data['text_key'] = $this->input->get('text_key');
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
		$filesize = round($_FILES['article_category_image']['size'] / 1024);
	
		$allowedSize = getField("config_value", "configuration", "config_key","ARTICAL_CATEGORIES_IMG_UPLOAD_SIZE");
	
		$allowedrec = getField("config_value", "configuration", "config_key","ARTICAL_CAT_REC_ICON");
		
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
	
	function articleCategoryForm()
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
		$this->form_validation->set_rules('article_category_name','Article Category Name','trim|required|callback_checkAlias');
		if(!$this->cPrimaryId)
		{
			$this->form_validation->set_rules('article_category_key','Article Category Key','trim|required|callback_articleCategoryKey');
		}
		$this->form_validation->set_rules('article_category_image','Article Category Image','trim|required|callback_imageSize');
		$this->form_validation->set_rules('article_category_description','Article Category Description','trim|required');
		$this->form_validation->set_rules('custom_page_title','Article Category Custom Page Title','trim');
		$this->form_validation->set_rules('meta_keyword','Article Category Meta keyword','trim');
		$this->form_validation->set_rules('meta_description','Article Category Meta Description','trim');
		$this->form_validation->set_rules('author','Article Category Author Name','trim');
		$this->form_validation->set_rules('content_rights','Article Category Contents Rights','trim');
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->art_cat->getData();
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
				$this->art_cat->cPrimaryId = $this->cPrimaryId; // setting variable to model
				
  			 	$this->art_cat->saveData();
				redirect('admin/'.$this->controller);
			}
		}
		
	}
	
/*
+-----------------------------------------+
	Delete art_category, single art_category and multiple
	art_category from single function call.
	@params : Item id. OR post array of ids
+-----------------------------------------+
*/		
	function deleteData()
	{	
		if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->art_cat->deleteData($ids);
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
			$this->art_cat->updateStatus();
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
			$this->db->where($this->cAutoId,$id)->update($this->cTable,array('article_category_sort_order'=>$sort_order));
			echo json_encode(array('type'=>'success','msg'=>'Sort order updated successfully.'));		
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Specify sort order.'));		
		}
	}	

}