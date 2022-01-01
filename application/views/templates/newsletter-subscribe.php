<?php 
	if( MANUFACTURER_ID == 7 ):
	
		$artRow = fetchRow( "SELECT article_image FROM article WHERE article_key='ABOUT_US'  " );
		$thnks_sub = fetchRow( "SELECT article_description FROM article WHERE article_key='THANK_YOU'  " );
		
	else: 
	
		$artRow = fetchRow( "SELECT article_image FROM article_cctld WHERE article_key='ABOUT_US' AND manufacturer_id = ".MANUFACTURER_ID." " );
		$thnks_sub = fetchRow( "SELECT article_description FROM article_cctld WHERE article_key='THANK_YOU' AND manufacturer_id = ".MANUFACTURER_ID." " );
	endif;
	
 
	$this->load->view('templates/header-template');
	
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
					                        	<td valign="top" style="padding:15px;">
													<img width="100%" src="'.asset_url($artRow['article_image']).'" >
												</td><br>
					                        	<td valign="top" style="line-height:25px;padding:15px;font-style:italic;font-size:16px;width:45%;font-family:Georgia,Times New Roman,Times,serif">'
													.$thnks_sub["article_description"].'
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<p><a href="'.$activation_link.'" target="_blank">Click here to activate Subscription </a></p>
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
	$content;/*
<p>Your username and password details are given below.</p>					
<p><b>Username : </b> '.$email_address.' </p>
<p><b>Username : </b> '.$text_password.' </p>*/
?>