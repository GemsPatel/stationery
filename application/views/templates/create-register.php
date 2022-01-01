<?php 
$this->load->view('templates/header-template'); 

$content = '
		     <tr>
                <td colspan="2" style="font-size:13px;">
					<p style="margin-top: 20px;">Dear customer,</p>
					<p>Thank you for registering at Stationery. Before we can activate your account and get you running.</p><p>Visit this url to activate your account:</p>
					<p style="margin-top: 30px; margin-bottom:30px;"><a href="'.$activation_link.'" target="_blank">'.$activation_link.'</a></p>
					
					<p>We are so glad to see you at Stationery. As a registered member of '.baseDomain().', you will enjoy many benefits such as first peek into our Promotions, faster checkout.</p>
					<p>Here is the account information you might want to save:</p>					
					<p><b>Email: </b> '.$email_address.' </p>
					<p><b>Password: </b> '.$text_password.' </p>
					<p>For detailed view of your Profile and Order information visit <a href="'.site_url("account").'" target="_blank">My Account.</a></p>
					<p>We hope you will enjoy shopping at <a href="'.site_url().'" target="_blank">Stationery.</a></p>
					
					
					
					
				</td>
              </tr>
			';
echo $content;

?>


