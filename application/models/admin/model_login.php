<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_login extends CI_Model {
	
/*
++++++++++++++++++++++++++++++++++++++++++++++++++++
	Function validate the user credential. and if exist 
	then return whole array.
++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
	public function validateLogin()
	{
		$username = $this->input->post('admin_user_emailid');
		$password = (md5($this->input->post('admin_user_password').$this->config->item('encryption_key')));
		//$this->db->where('manufacturer_id', MANUFACTURER_ID);
		
		$user = $this->db->where('admin_user_emailid',$username)->where('admin_user_password',$password)->get('admin_user')->row_array();
		//echo $this->db->last_query();pr($user);die;
		return $user;
	}
/*
++++++++++++++++++++++++++++++++++++++++++++++++++++
	Function Will save admin username and password.
++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
	public function saveSettings()
	{
		if($this->input->post('new_pass') != '') // if password inserted by admin 
			$data['admin_user_password'] = md5($this->input->post('new_pass').$this->config->item('encryption_key'));		
	
		//updating information to database
		$this->db->where('admin_user_id',$this->session->userdata('admin_id'))->update('admin_user',$data);
	}
	
/**
 * @author Cloudwebs
 * @abstract function will show various nitifications to admin users
 *	
 */
	function updateNotifications()
	{
		$resArr = array();
		$CI =& get_instance();
		$admin_user_id = $CI->session->userdata('admin_id');
		$res = $CI->db->query("SELECT admin_user_order_last_id, admin_user_customer_last_id, admin_user_message_last_id,admin_user_order_noti_status, admin_user_customer_noti_status, admin_user_message_noti_status FROM admin_user WHERE admin_user_id=".$admin_user_id." ")->row_array();
		
		if(!empty($res))
		{
			$resArr['type'] = 'success';	
			$resArr['msg'] = '';
			$resArr['ord_cnt'] = 0;
			$resArr['ord_last_id'] = $res['admin_user_order_last_id'];
			$resArr['cus_cnt'] = 0;
			$resArr['cus_last_id'] = $res['admin_user_customer_last_id'];
			$resArr['msg_cnt'] = 0;
			$resArr['msg_last_id'] = $res['admin_user_message_last_id'];

			if($res['admin_user_order_noti_status']==0)
			{
				$resOrd = $CI->db->query("SELECT COUNT(order_id) as Count,max(order_id) as Max from orders WHERE order_id>".$res['admin_user_order_last_id']."")->row_array();
				if(!empty($resOrd) && $resOrd['Count']!=0)
				{
					$resArr['ord_cnt'] = $resOrd['Count'];
					$resArr['ord_last_id'] = $resOrd['Max'];
				}
			}

			if($res['admin_user_customer_noti_status']==0)
			{
				$resCus = $CI->db->query("SELECT COUNT(customer_id) as Count,max(customer_id) as Max from customer WHERE customer_id>".$res['admin_user_customer_last_id']."")->row_array();
				if(!empty($resCus) && $resCus['Count']!=0)
				{
					$resArr['cus_cnt'] = $resCus['Count'];
					$resArr['cus_last_id'] = $resCus['Max'];
				}
			}

			if($res['admin_user_message_noti_status']==0)
			{
				$resCusMsg = $CI->db->query("SELECT COUNT(private_message_id) as Count,max(private_message_id) as Max from private_message WHERE private_message_id>".$res['admin_user_message_last_id']."")->row_array();
				if(!empty($resCusMsg) && $resCusMsg['Count']!=0)
				{
					$resArr['msg_cnt'] = $resCusMsg['Count'];
					$resArr['msg_last_id'] = $resCusMsg['Max'];
				}
			}
		}
		else
		{
			$resArr['type'] = 'error';
			$resArr['msg'] = 'Error: Something wrong happen while updating notifications.';	
		}
		unset($CI);
		return $resArr;
	}

/**
 * @author Cloudwebs
 * @abstract function will show various nitifications to admin users
 *	
 */
	function listNotifications($type,$last_id)
	{
		$data = array();
		$CI =& get_instance();
		$admin_user_id = $CI->session->userdata('admin_id');
		
		$res = $CI->db->query("SELECT admin_user_order_last_id, admin_user_customer_last_id, admin_user_message_last_id,admin_user_order_noti_status, admin_user_customer_noti_status, admin_user_message_noti_status FROM admin_user WHERE admin_user_id=".$admin_user_id." ")->row_array();

		if(!empty($res))
		{
			if($type=='orders_notif')
			{
				$data = $CI->db->query("SELECT order_id,customer_firstname FROM orders o INNER JOIN customer c
										ON c.customer_id=o.customer_id WHERE order_id>".$res['admin_user_order_last_id']." AND order_id<".($last_id+1)." ")->result_array();
										
				$CI->db->query("UPDATE admin_user SET admin_user_order_last_id=".$last_id." WHERE admin_user_id=".$admin_user_id." ");						
			}
			else if($type=='customers_notif')
			{
				$data = $CI->db->query("SELECT customer_id,CONCAT(customer_firstname,' ',customer_lastname) as name,customer_group_name FROM customer c 
										INNER JOIN customer_group g ON g.customer_group_id=c.customer_group_id WHERE customer_id>".$res['admin_user_customer_last_id']." AND customer_id<".($last_id+1)." ")->result_array();
										
				$CI->db->query("UPDATE admin_user SET admin_user_customer_last_id=".$last_id." WHERE admin_user_id=".$admin_user_id." ");						
			}
			else if($type=='customer_messages_notif')
			{
				$data = $CI->db->query("SELECT pm_email,pm_question,customer_firstname FROM  private_message p INNER JOIN customer c
										ON c.customer_id=p.customer_id WHERE admin_user_id=0 AND private_message_id>".$res['admin_user_message_last_id']." AND private_message_id<".($last_id+1)." ")->result_array();
										
				$CI->db->query("UPDATE admin_user SET admin_user_message_last_id=".$last_id." WHERE admin_user_id=".$admin_user_id." ");						
			}
		}
		
		unset($CI);
		return $data;
	}
/*
++++++++++++++++++++++++++++++++++++++++++++++++++++
	Function validate the user credential. and if exist 
	then return whole array.
++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
	public function validateEmail()
	{
		$username = $this->input->post('forgot_email');
		$user = $this->db->where('admin_user_emailid',$username)->get('admin_user')->row_array();
		//echo $this->db->last_query();pr($user);die;
		return $user;
	}

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */