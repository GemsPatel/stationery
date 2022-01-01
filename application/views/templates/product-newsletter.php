<?php 
	
	$this->load->view('templates/header-template');
	
	$content = '
			     <tr>
	                <td colspan="2">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
		                  <tbody>
							<tr>
			                    <td width="620" valign="top">
									<table>
										'.$product_email_message.'
									</table>
			                    </td>
		                    </tr>
		                 </tbody>
						</table>
					</td>
	              </tr>
				';
	echo $content;
	
?>