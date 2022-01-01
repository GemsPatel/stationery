<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class scrapper extends CI_Controller {
	
	function scrapper()
	{
		parent::__construct();
	}
	
	function index()
	{
		$data['pageName'] = 'scrapper';
		$this->load->view('site-layout',$data);	
	}
	
}