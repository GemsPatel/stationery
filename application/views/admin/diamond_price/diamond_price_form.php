<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons">
       <?php if($this->per_add == 0 && $this->per_edit == 0):?>
    	  <a class="button" onclick="updateProductPrices()">Update Product Price</a>
          <a class="button" onclick="$('#form').submit();">Save</a>
       <?php endif;?>
      <a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
	<?php $this->load->view('admin/'.$this->controller.'/ajax_html_formdata'); ?>    
    </div>
  </div>
  
</div>

<script type="text/javascript">
<!--
function addDiamondPrice() {
	($('#nores_d').length>0) ? $('#nores_d').remove():'';
	html = ' <tr id="dp-row'+dp_row+'">';
	html += ' <td class="left top"><input type="text" size="25" name="diamond_price_name[]" value=""></td>';
	html += ' <td class="left top"><input type="text" size="25" name="diamond_price_key[]" value="" style="text-transform:uppercase"></td>';
	html += ' <td class="left top">'+dropdownDt+'</td>';
	html += ' <td class="left top"><input type="text" size="10" name="dp_price[]" value=""></td>';
	/*html += ' <td class="left top"><input type="text" size="3" name="dp_clarity[]" value=""></td>';
	html += ' <td class="left top"><input type="text" size="1" name="dp_color[]" value=""></td>';*/
	html += ' <td class="left top">'+dropdownDp+'</td>';
	html += ' <td class="left top">'+dropdownDc+'</td>';
    html += ' <td class="left top"><input type="text" size="2" name="diamond_price_labour_charge[]" value=""><span>%</span></td>';
	html += ' <td class="left top"><input type="text" size="5" name="dp_weight_diff[]" value=""></td>';
	html += ' <td class="left top"><div class="image" style="padding:2px;" align="center"><img src="'+asset_url+'images/admin/no_image.jpg" width="45" height="30" id="dpPrevImage_'+dp_row+'"+ style="padding:1px;margin-bottom: -5px;" class="image" /><br /><input type="file" name="dp_iconfile[]" id="dpImg_'+dp_row+'" onchange="readURL(this,\''+dp_row+'\');" style="display: none;"><input type="hidden" value="" name="dp_icon[]" id="hiddenDPImg" /><small class="small_text"><a onclick="$(\'#dpImg_'+dp_row+'\').trigger(\'click\');">Browse</a>|<a style="clear:both;" onclick="clear_image(\'dpPrevImage_'+dp_row+'\');">Clear</a></small></div></td>';
	html += ' <td class="left top"><textarea name="dp_desc[]" rows="2" cols="45"></textarea></td>';
	html += ' <td class="left top"><a onclick="$(\'#dp-row'+dp_row+'\').remove();dp_row = dp_row -1" class="button">Remove</a></td>';
	html += ' </tr>';
	$('#diamond_body').append(html);
	dp_row++;
} 

function addMetalPrice() {
	($('#nores_m').length>0) ? $('#nores_m').remove():'';
	html = ' <tr id="mp-row'+mp_row+'">';
	html += ' <td class="left top"><select id="metal_type_id_'+mp_row+'" onchange="return countMetalPrDiff(\''+mp_row+'\')" name="metal_type_id[]">'+dropdownMt+'</td>';
	html += ' <td class="left top"><select id="metal_purity_id_'+mp_row+'" onchange="return countMetalPrDiff(\''+mp_row+'\')" name="metal_purity_id[]">'+dropdownMp+'</td>';
	html += ' <td class="left top">'+dropdownMc+'</td>';
	html += ' <td class="left top"><input type="text" size="5" name="metal_price_labour_charge[]" value="" id="mp_lbrtext_'+mp_row+'" onkeyup="countMetalPrDiff(\''+mp_row+'\')"><span>%</span></td>';
	html += ' <td class="left top"><input type="hidden" value="" name="mp_price_difference[]" id="hiddenMpricedif" /><span id="mp_diffspan_'+mp_row+'"></span></td>';
	html += ' <td class="left top"><div class="image" style="padding:2px;" align="center"><img src="'+asset_url+'images/admin/no_image.jpg" width="45" height="30" id="mpPrevImage_'+mp_row+'" style="padding:1px; margin-bottom: -5px;" class="image" /><br /><input type="file" name="mp_iconfile[]" id="mpImg_'+mp_row+'" onchange="readURL(this,\''+mp_row+'\');" style="display: none;"><input type="hidden" value="" name="mp_icon[]" id="hiddenMPImg" /><small class="small_text"><a onclick="$(\'#mpImg_'+mp_row+'\').trigger(\'click\');">Browse</a>|<a style="clear:both;" onclick="clear_image(\'mpPrevImage_'+mp_row+'\');">Clear</a></small></div></td>';
	html += ' <td class="left top"><textarea name="mp_desc[]" rows="2" cols="50"></textarea></td>';
	html += ' <td class="left top"><a onclick="$(\'#mp-row'+mp_row+'\').remove();mp_row = mp_row -1;" class="button">Remove</a></td>';
	html += ' </tr>';
	$('#metal_body').append(html);
	mp_row++;
} 

/*
+------------------------------------------+
	author Cloudwebs
	count metal price as per purity ratio
	@param : rowid  id of table row
+------------------------------------------+
*/
function countMetalPrDiff(rowid)
{
	var met_type_id = $('#metal_type_id_'+rowid).val();
	var met_purity = $('#metal_purity_id_'+rowid+' option:selected').text();
	var met_type = $('#metal_type_id_'+rowid+' option:selected').text();
	var mp_labour = Number($('#mp_lbrtext_'+rowid).val());
	if(met_purity == 'None' || met_type == 'Silver') 						// for computing silver price only add labour charge
	{
		var mp_diff = parseInt(this['mt_price'+met_type_id]);
		mp_diff = Math.round(mp_diff + (mp_diff * (mp_labour/100)));
		$('#metal_purity_id_'+rowid).val('None');	
		$('#mp_diffspan_'+rowid).text(mp_diff);
		$('#mp-row'+rowid).find('#hiddenMpricedif').val(mp_diff);
		return false;
	}
	else 																	// for computing metal price except silver
	{
		var mp_diff = this['mt_price'+met_type_id] * this['G_'+met_purity.toUpperCase()];
		mp_diff = Math.round(mp_diff + (mp_diff * (mp_labour/100)));
		$('#mp_diffspan_'+rowid).text(mp_diff);
		$('#mp-row'+rowid).find('#hiddenMpricedif').val(mp_diff);
		return false;	
	}
}

/*
+------------------------------------------+
	author Cloudwebs
	update metal price
+------------------------------------------+
*/
function updateMetalPrice()
{
	showLoader();
	form_data = $('.metal_price_update').serialize();
	console.log(form_data);
	var loc = (base_url+'admin/'+lcFirst(controller)+'/updateMetalPrice');
	$.get(loc, form_data, function (data) {
		data = $.parseJSON(data);
		setTimeout(function() {window.location.reload()}, 1300);
		$('#content').before(getNotificationHtml('success',data.success));
	});
}

/*
+------------------------------------------+
	author Cloudwebs
	update labourcharge / company profit
+------------------------------------------+
*/
function updateChargeProfit(key)
{
	showLoader();
	var keyval = $('[name="'+key+'"]').serialize(); 
	
	form_data = keyval;
	form_data += "&type=" + key;
	var loc = (base_url+'admin/'+lcFirst(controller)+'/updateChargeProfit');
	$.get(loc, form_data, function (data) {
		data = $.parseJSON(data);
		$('#content').before(getNotificationHtml('success',((key=='LABOUR_CHARGE')?'Labour Charge':'Company Profit')+data.success));
		hideLoader();
	});
		
}

/*
+------------------------------------------+
	author Cloudwebs
	deletes diampnd or metal category
	@param rowid of table
	@param Isdp_mp is category diamond or metal  diamond for 1 and 2 for metal
+------------------------------------------+
*/
function deleteDiamondMetal(rowid,Isdp_mp)
{
	if(confirm('Are you sure want to delete?'))
	{
		showLoader();
		var keyval ='';
		if(Isdp_mp == 1)
		{
			form_data = 'type=dp';
		}
		else
		{
			form_data = 'type=mp';
		}
		form_data += '&key='+$('#'+(Isdp_mp==1?'item_idD_':'item_idM_')+rowid).val();
		
		var loc = (base_url+'admin/'+lcFirst(controller)+'/deleteCategory');
		$.get(loc, form_data, function (data) {
			data = $.parseJSON(data);
			if(data.type == "success")
			{
				$('#'+(Isdp_mp==1?'dp-row':'mp-row')+rowid).remove();
				$('#'+(Isdp_mp==1?'item_idD_':'item_idM_')+rowid).remove();
				$('#content').before(getNotificationHtml('success',((Isdp_mp==1)?'Diamond Category':'Metal Category')+data.success));
			}
			else
			{
				$('#content').before(getNotificationHtml('error',((Isdp_mp==1)?'Diamond Category':'Metal Category')+data.error));
			}
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			hideLoader();
		});		
		
	}
}

/*
+------------------------------------------+
	author Cloudwebs
	update product prices
+------------------------------------------+
*/
function updateProductPrices()
{
	showLoader();
	var loc = (base_url+'admin/'+lcFirst(controller)+'/updateProductPrices');
	$.get(loc, '', function (data) {
		data = $.parseJSON(data);
		$('#content').before(getNotificationHtml(data['type'],data['msg']));
		hideLoader();
	});	
}

//-->
</script>

<script type="text/javascript">
<!--
$('#tabs a').tabs();
$('.htabs a').tabs();
//-->
</script>
