<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class product_categories extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'category_id';
	var $cPrimaryId = '';
	var $cTable = 'product_categories';
	var $controller = 'product_categories';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function product_categories()
	{
		parent::__construct();
		$this->load->model('admin/mdl_product_categories','cat');
		$this->cat->cTableName = $this->cTable;
		$this->cat->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->cat->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
	function will check category alias, return error
	if alias already exist.
+--------------------------------------------------+
*/	
	function checkAlias($str)
	{
		$al  = url_title($str);
		if($this->cPrimaryId)
			$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
		
		$c = $this->db->where('category_alias',$al)->get($this->cTable)->num_rows();
		
		if($c > 0)
		{
			$this->form_validation->set_message('checkAlias','This alias of category is already exist.');
			return false;
		}
		else
			return true;
	}
	
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Product Categories', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->cat->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start; //starting position of records
		$data['total_records'] = $num->num_rows(); // total num of records
		$data['per_page_drop'] = per_page_drop(); // per page dropdown
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['cat_filter'] = $this->input->get('cat_filter'); // filter by category name
		$data['category_id'] = $this->input->get('category_id'); // filter by category id
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
	function bannerSize($str)
	{
		/**
		 * get uploaded file size in KB
		*/
		$imgfilesize = round($_FILES['category_banner']['size'] / 1024);
	
		$imgallowedSize = getField("config_value", "configuration", "config_key","PRODUCT_CATEGORIES_BANNER_UPLOAD_SIZE");
	
		$allowedrec = getField("config_value", "configuration", "config_key","PRODUCT_REC_BANNER");
		
		if($imgfilesize > $imgallowedSize)
		{
			$this->form_validation->set_message('imageSize', '(Maximum allowed size is : '.$imgallowedSize.' KB,'.$allowedrec.', your uploaded file size is '.$filesize.' KB.)');
			return false;
		}
		else
		{
			return true;
		}
	
	}
	
	function imageSize($str)
	{
		/**
		 * get uploaded file size in KB
		 */
		$filesize = round($_FILES['category_image']['size'] / 1024);
	
		$allowedSize = getField("config_value", "configuration", "config_key","PRODUCT_CATEGORIES_IMG_UPLOAD_SIZE");
		
		$allowedrec = getField("config_value", "configuration", "config_key","PRODUCT_REC_IMAGE");
		
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
	
	function categoryForm()
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
		
		if( INVENTORY_TYPE_ID === 0 )
			$this->form_validation->set_rules('inventory_type_id','Inventory Type','trim|required');
		
		$this->form_validation->set_rules('category_name','Category Name','trim|required');
		$this->form_validation->set_rules('category_alias','Category Alias','trim|required|callback_checkAlias');		
		/*$this->form_validation->set_rules('category_royalty','Category Royalty','trim|numeric');*/
		$this->form_validation->set_rules('category_description','Category Description','trim|required');
		$this->form_validation->set_rules('category_image','Category Icon','trim|callback_imageSize');
		$this->form_validation->set_rules('category_banner','Category Banner','trim|callback_bannerSize');
/*		$this->form_validation->set_rules('custom_page_title','Custom Page Title','trim|required');
		$this->form_validation->set_rules('meta_keyword','Category Meta keyword','trim|required');
		$this->form_validation->set_rules('meta_description','Category Meta Description','trim|required');
		$this->form_validation->set_rules('author','Category Author Name','trim|required');
		$this->form_validation->set_rules('content_rights','Category Contents Rights','trim|required');
*/		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->cat->getData();
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
				$this->cat->cPrimaryId = $this->cPrimaryId; // setting variable to model
				
  			 	$this->cat->saveData();
				redirect('admin/'.$this->controller);
			}
		}
		
	}
	
/*
+-----------------------------------------+
	Delete Category, single category and multiple
	category from single function call.
	@params : Item id. OR post array of ids
+-----------------------------------------+
*/		
	function deleteData()
	{	
		if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->cat->deleteData($ids);
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
			$this->cat->updateStatus();
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
			if( MANUFACTURER_ID != 7 )	
				$this->db->where($this->cAutoId,$id)->update($this->cTable.'_cctld',array('category_sort_order'=>$sort_order));
			else
				$this->db->where($this->cAutoId,$id)->update($this->cTable,array('category_sort_order'=>$sort_order));
				
			echo json_encode(array('type'=>'success','msg'=>'Sort order updated successfully.'));		
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Specify sort order.'));		
		}
	}

	/*
	 * Function will list item listing with languages in reference.
	*/
	function itemLanguages()
	{
		if($this->input->get('edit') == 'true')
		{
			$data['listArr'] = $this->cat->getLanguagesForListing();
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list_sl';
			$this->load->view('admin/layout',$data);
		}
		else
			redirect('admin/'.$this->controller);
	}
	
	/*
	 * Function will list item listing with languages in reference.
	*/
	function inventoryType()
	{
		if($this->input->get('insert') == 'true')
		{
			$data['listArr'] = $this->cat->getInventoryListing();
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list_it';
			$this->load->view('admin/layout',$data);
		}
		else
			redirect('admin/'.$this->controller);
	}
}