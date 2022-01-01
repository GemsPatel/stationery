<?php 
$this->load->view('templates/header-template');

$content = '
			 <tr>
			 	<td colspan="2"><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/cocktail_jewellery.jpg").'"></a></td>
			 </tr>
			 <tr>
                <td colspan="2">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_roman_leaf_ring.jpg").'"></a></td>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_amura_ring.jpg").'"></a></td>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_raymen_ring.jpg").'"></a></td>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_gilliflower_ring.jpg").'"></a></td>
				</tr>
				<tr>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_celsie_pendant.jpg").'"></a></td>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_sweetcory_pendant.jpg").'"></a></td>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_snowdrop_pendant.jpg").'"></a></td>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_parsley_pendant.jpg").'"></a></td>
				</tr>
				<tr>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_lierre_drop_earings.jpg").'"></a></td>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_ansernia_stud_earings.jpg").'"></a></td>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_harmony_drop_earings.jpg").'"></a></td>
					<td><a href="'.site_url("search").'"><img src="'.site_url("images/email-template/template2/the_gulliver_drop_earings.jpg").'"></a></td>
				</tr>
				</table>
				</td>
             </tr>			 
			 
		   ';
echo $content;

//textarea for html code
echo '<textarea id="ebayHtml" name="ebayHtml" rows="10" cols="80">'.$content.'</textarea>';

$this->load->view('templates/footer-template');
?>