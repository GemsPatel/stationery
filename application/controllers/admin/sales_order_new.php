<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class sales_order_new extends CI_Controller 
{
	//parent constructor will load model inside it
	function sales_order_new()
	{
		parent::__construct();
		
		redirect( "admin/sales_order?order_status_id = 6" ); 
	}
}