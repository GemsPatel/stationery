<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sm extends CI_Controller 
{

	var $is_ajax = false;
	//parent constructor will load model inside it
	function sm()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->model('mdl_home','hom');
		$this->is_ajax = $this->input->is_ajax_request();
	}
	
	function index()
	{
		
	}
	
	function login()
	{
		$data['pageName'] = 'login';
		$this->load->view('site-layout',$data);
	}
	
	function register()
	{
		$data['pageName'] = 'register';
		$this->load->view('site-layout',$data);
	}
	
	function forgotPassword()
	{
		$data['pageName'] = 'elements/forgot-password';
		$this->load->view('site-layout',$data);
	}
	
	function account()
	{
		$data['pageName'] = 'account/index.php';
		$this->load->view('site-layout',$data);
	}
	
	function info()
	{
		$data['pageName'] = 'account/edit-account.php';
		$this->load->view('site-layout',$data);
	}
	
	function addBook()
	{
		$data['pageName'] = 'account/address-book.php';
		$this->load->view('site-layout',$data);
	}
	
	function myOrder()
	{
		$data['pageName'] = 'account/index.php';
		$this->load->view('site-layout',$data);
	}
	
	function billAgre()
	{
		$data['pageName'] = 'account/index.php';
		$this->load->view('site-layout',$data);
	}
	function recProfile()
	{
		$data['pageName'] = 'account/index.php';
		$this->load->view('site-layout',$data);
	}
	function productReview()
	{
		$data['pageName'] = 'account/index.php';
		$this->load->view('site-layout',$data);
	}
	function tags()
	{
		$data['pageName'] = 'account/index.php';
		$this->load->view('site-layout',$data);
	}
	function wishllist()
	{
		$data['pageName'] = 'account/wishlist.php';
		$this->load->view('site-layout',$data);
	}
	function application()
	{
		$data['pageName'] = 'account/index.php';
		$this->load->view('site-layout',$data);
	}
	function newsletter()
	{
		$data['pageName'] = 'account/newsletter.php';
		$this->load->view('site-layout',$data);
	}
	function products()
	{
		$data['pageName'] = 'account/index.php';
		$this->load->view('site-layout',$data);
	}
}