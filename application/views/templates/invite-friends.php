<?php 
	$this->load->view('templates/header-template');
	
	$data = $this->input->post();
	$cust_id = getField('customer_firstname','customer','customer_id',$this->session->userdata("customer_id"));
	
	$inviteFrnd = fetchRow( "SELECT article_name,article_image,article_description FROM article WHERE article_key='INVITE_FRIEND_MAIL' " );
	$content = '
				 <tr>
	                <td colspan="2">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
		                  <tbody>
							<tr>
			                    <td bgcolor="#ececec" width="620" height="400" valign="top">
									<div>
					                    <table width="96%" border="0" cellspacing="0" cellpadding="0">
					                      <tbody>
											<tr>
					                        	<br>
					                        	<td valign="top" style="line-height:25px;padding:15px;font-style:italic;font-size:16px;width:45%;font-family:Georgia,Times New Roman,Times,serif">
													From:'.$this->session->userdata("customer_emailid").'<br><br>'.$data['customer_note'].'<br><br>'.$inviteFrnd['article_description'].' <a href="'.getCampaignUrl($this->session->userdata("customer_id")).'">Click to join</a><br><br>Regards,<br>'.$cust_id.'
												</td>
												<td valign="top" style="padding:15px;" width="20%">
													<img src=" '.asset_url($inviteFrnd['article_image']).'" title="'.$inviteFrnd['article_name']. '" width="200px"/>
												</td>
											</tr>
										  </tbody>
									    </table>
				                    </div>
			                    </td>
		                    </tr>
		                 </tbody>
						</table>
					</td>
	              </tr>
				';
	echo $content;
?>