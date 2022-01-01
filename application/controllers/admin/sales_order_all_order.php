<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class sales_order_all_order extends CI_Controller 
{
//parent constructor will load model inside it
	function sales_order_all_order()
	{
		parent::__construct();
		
		redirect( "admin/sales_order" ); 
	}
}