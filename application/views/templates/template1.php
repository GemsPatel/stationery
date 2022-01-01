<?php 
$this->load->view('templates/header-template');

$content = '
		     <tr>
                <td colspan="2">
					<a href="'.site_url("search").'" target="_blank"><img src="'.site_url("images/email-template/template1.jpg").'" width="640" alt="" border="0"></a>
				</td>
              </tr>
			';
echo $content;

//textarea for html code
echo '<textarea id="ebayHtml" name="ebayHtml" rows="10" cols="80">'.$content.'</textarea>';

$this->load->view('templates/footer-template');
?>