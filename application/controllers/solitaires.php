<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class solitaires extends CI_Controller {

	var $controller = 'solitaires';
	var $currentEvent = '';
	var $pick_design_id = 0;
	var $ring_size = '';
	var $choose_diamond_id = '';
	
	//parent constructor will load model inside it
	function solitaires()
	{
		parent::__construct();
		$this->load->model('mdl_solitaires','sol');	
		
		$this->currentEvent = $this->input->get('ev');	
		if( $this->currentEvent == '' )	
		{
			$this->currentEvent = newSolitaireEvent();	
		}
		
		$this->pick_design_id = $this->session->userdata($this->currentEvent.'pick_design_id');
		$this->ring_size = $this->session->userdata($this->currentEvent.'ring_size');
		
		//cache driver
// 		$this->load->driver( 'cache', array( 'adapter' => 'apc', 'backup' => 'file'));
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

	function index()
	{
		$data = $this->input->get();
		
		//ev: event and pg: page will render particular solitaire event to correspondent page
		if( isset($data['ev'] ) && isset($data['pg'] ))
		{
			//render event	
		}
		
		$seoArr = getCmsPages('SOLITAIRE');
		
		$data['custom_page_title'] = strReplaceIndToAus(@$seoArr['custom_page_title']);
		$data['meta_description'] = strReplaceIndToAus(@$seoArr['meta_description']);
		$data['meta_keyword'] = strReplaceIndToAus(@$seoArr['meta_keyword']);

		$data['sort_by'] = $this->input->get('sort_by');
		$data['pageName'] = 'solitaires';
		$this->load->view('site-layout',$data);					
	}
	
/**
 * @abstract Function will display solitaires jewellery
 */
	function solitairesJewellery()
	{
		$res = $this->sol->getProducts();
		$data['listArr'] = $res['data']['result_array'];
		$data['total_records'] = $res['data']['Count'];

		/*if( isset( $_GET['pty'] ) && $_GET['pty'] == 'rin' )
		{
			$data['custom_page_title'] = 'Latest Solitaire Jewellery Certified Diamond Rings Designs | Perrian Online Diamond Jewellery Store';
			$data['meta_keyword'] = strReplaceIndToAus('solitaire diamond rings, diamond solitaire rings, solitaire diamond ring, mens solitaire rings, platinum solitaire ring, solitaire rings, solitaire rings india, solitaire ring, diamond solitaire ring, solitaire engagement ring, ring solitaire, solitaire ring settings, solitaire wedding rings, engagement rings solitaire');
			$data['meta_description'] = strReplaceIndToAus('Buy GIA certified latest solitaire diamond rings in india. Search the wide range of mens solitaire rings or the platinum solitaire ring from our solitaire engagement ring collections with latest solitaire wedding rings');
		}*/
		
		$seoArr = getCmsPages('SOLITAIRE_JEWELLERY');
		
		$data['custom_page_title'] = strReplaceIndToAus(@$seoArr['custom_page_title']);
		$data['meta_description'] = strReplaceIndToAus(@$seoArr['meta_description']);
		$data['meta_keyword'] = strReplaceIndToAus(@$seoArr['meta_keyword']);
		
		$data['pageName'] = 'solitaires_jewellery';
		$this->load->view('site-layout',$data);					
	}
	
/**
 * Function will display solitaires diamonds
 */
	function solitairesDiamond()
	{
		$data = $this->input->get();
		if( isset($data['evsst']) )
		{
			$this->session->set_userdata( array($this->currentEvent.'pick_design_id'=>$data['evsst'], $this->currentEvent.'ring_size'=>@$data['ring_size']) );
			$this->pick_design_id = $data['evsst'];
			
			$proDetail = showProductsDetails($this->pick_design_id, false, false, true, '', '', '');
			$data['diamond_shape_id'] = $proDetail['diamond_shape_id_cs'];
		}
		
		$seoArr = getCmsPages('SOLITAIRE_DIAMOND');
		
		$data['custom_page_title'] = strReplaceIndToAus(@$seoArr['custom_page_title']);
		$data['meta_description'] = strReplaceIndToAus(@$seoArr['meta_description']);
		$data['meta_keyword'] = strReplaceIndToAus(@$seoArr['meta_keyword']);
		
		$data['pageName'] = 'solitaires_diamond';
		$data['dp_weight_start'] = 0.25;
		$data['diamond_purity_idArr'][] = SI1_ID;
		$this->load->view('site-layout',$data);					
	}
	
/** 
 * @author Cloudwebs
 * @abstract function will search for diamond and return result as per filter criteria
 */
	function searchDiamond()
	{
		$searchf = $this->input->get();//pr($searchf);die;
		$searchf['parent_category'] = '';
		if( $this->pick_design_id != 0 ) { $searchf['parent_category'] = fetchParentType( '', $this->pick_design_id ); }
		$data = $this->sol->searchDiamond( $searchf );
		$data['parent_category'] = $searchf['parent_category'];		
		$this->load->view('diamond_list', $data);
	}
	
/** 
 * @author Cloudwebs
 * @abstract function will used in diamond scroll pagination
 */
	function diamondScroll()
	{
		$data = $this->sol->diamondScroll(  );
		$data['parent_category'] = '';
		if( $this->pick_design_id != 0 ) { $data['parent_category'] = fetchParentType( '', $this->pick_design_id ); }
		if( isset( $data['listArr'] ) )
		{
			$this->load->view('scroll_pagination_list_diamonds', $data);
		}
		else
		{
			echo '';		
		}
	}

/** 
 * @author Cloudwebs
 * @abstract function will allow user to pick design for solitaire diamonds
 */
	function pickDesign() 
	{
		$product_price_id = 0;
		//add diamond to style if specified
		$this->choose_diamond_id = $this->input->get('adddid');

		$dataDia = array();
		if( $this->choose_diamond_id != '' )
		{
			$this->session->set_userdata( array($this->currentEvent.'choose_diamond_id'=>$this->choose_diamond_id) );	
			
			$diaArr = explode('|', $this->choose_diamond_id);
			foreach($diaArr as $k=>$ar)
			{
				$dataDia['dia_detail'][$ar] = fetchDiamondDetail( $ar );
			}
			
			$product_price_id = $this->session->userdata( $this->currentEvent.'pick_design_id' );	
		}
		else
		{ $product_price_id = $this->input->get('pid'); }
		
		$pageToken = pageToken();
		$ring_size = $this->session->userdata( $this->currentEvent.'ring_size' );
		$data = showProductsDetails( $product_price_id, false, false, true, $pageToken, $ring_size, '_mount');

		//update view count on each page load
		$this->db->query("UPDATE product SET product_view_buy=product_view_buy+1 WHERE product_id=".$data['product_id']."");

		//create page title
		$tabelName = ( MANUFACTURER_ID != 7 ) ? "product_categories_cctld": "product_categories";
		$resTitle = $this->db->query("SELECT custom_page_title 
									FROM product_category_map pcm INNER JOIN ".$tabelName." pc
									ON pc.category_id=pcm.category_id 
									WHERE pcm.product_id=".$data['product_id']." LIMIT 2,1")->row_array();
		
		$data['custom_page_title'] = $data['custom_page_title'] ." | ".@$resTitle['custom_page_title'] ;
		$data['pageName'] = 'solitaires-details';
		$data['pageToken'] = $pageToken;
		
		$data['parent_category'] = fetchParentType( $data['category_id'], 0 );
		
		//set canical url
		$canonical = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$rpos = strrpos($canonical, '/');
		$canonical = substr( $canonical, 0, $rpos);
		$data['canonical'] = $canonical;

		$this->load->view('site-layout', array_merge($data, $dataDia) );
	}

/** 
 * @author Cloudwebs
 * @abstract function will used in fetch complete diamond detail in hover event
 */
	function hoverDetailDiamond()
	{
		$diamond_price_id = $this->input->get('val');
		$data = fetchDiamondDetail( $diamond_price_id );
		$data['parent_category'] = '';
		if( $this->pick_design_id != 0 ) { $data['parent_category'] = fetchParentType( '', $this->pick_design_id ); }
		$this->load->view('elements/hover_details_diamond', $data);
	}

/** 
 * @author Cloudwebs
 * @abstract function will display diamond detail page
 */
	function diamondDetail()
	{
		if($this->input->get('did'))
		{
			$diamond_price_id = $this->input->get('did');
			$data = fetchDiamondDetail($diamond_price_id);
			$data['pageName'] = 'solitaires_diamond_detail';
			$this->load->view('site-layout',$data);
		}
		else
			redirect(site_url('solitaires'));
	}
	
}
