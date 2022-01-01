<?php 

$this->load->view('templates/header-template');

$content = '
		     <tr>
                <td colspan="2" style="font-size:13px; padding:10px; background-image:url('.site_url("images/pattern.gif").')">
					<p style="margin-top: 20px;">Dear '.$first_name.' '.$last_name.',</p>
					<p>'.getLangMsg("reset_pass").'</p>
					<p style="margin-top:30px;"><b>'.getLangMsg("email").': </b> '.$email_address.' </p>
					<p style="margin-bottom:30px;"><b>'.getLangMsg("pass").': </b> '.$text_password.' </p>
					<p>Please <a href="'.site_url("login").'">'.getLangMsg("c_h").'</a> to login your account.</p>
					<p style="margin-bottom:50px;">'.getLangMsg("enjoy").'<a href="'.site_url().'" target="_blank">Stationery.</a></p>
					
				</td>
              </tr>
			';
echo $content;
?>


