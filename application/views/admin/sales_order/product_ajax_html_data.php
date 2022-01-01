      <?php 
	  	$para = (isset($_GET['custid'])?'custid='.$_GET['custid']:'');
	  	$para .= (isset($_GET['edit']) && $_GET['edit']=="true")?(($para!="")?'&':'').'edit=true':'';
	  ?>
      <form id="form" method="get" action="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?prod=add&'.$para)?>" >
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
              <td width="10%" class="left">Product Name</td>
              <td width="5%" class="left">SKU</td>
              <td width="12%" align="center" >Product Generated Code</td>
              
              <?php
              	if( isSupportsJewelleryInventory() ): 
              ?>
              		<td width="7%" align="center" >Ring Size</td>
              <?php
              	endif;
              ?>		
              
              <td width="5%" align="center" >Price</td>
              <td width="5%" align="center" >Qty To Add</td>
              <td width="5%" align="center" >Action</td>
            </tr>
            
            <tr class="filter">
              <td class="left"></td>
              <td class="left"></td>
              <td align="center" ></td>
              
              <?php
              	if( isSupportsJewelleryInventory() ): 
              ?>
              		<td align="center" ></td>
              <?php
              	endif;
              ?>		
              
              <td align="center" ></td>
              <td align="center" ></td>
              <td align="center" >
              	<a class="button" href="javascript:void(0);" onclick="addProductTr();" style="float:right;">Add Product</a>
              </td>
            </tr>
          </thead>
          
          <tbody id="cart_body">
          <?php
		  		$cnt = 0;
				if( isset( $this->cust_order_id ) && (int) $this->cust_order_id !=0 && isset($cartArr[ $this->cust_order_id ]) && is_array($cartArr[ $this->cust_order_id ]) && sizeof($cartArr[ $this->cust_order_id ]) > 0):			
                  foreach($cartArr[ $this->cust_order_id ] as $k=>$ar):
				  	$cnt++;
				
					$dt['k'] = $k;
					$dt["is_new_row"] = FALSE;
					$dt["ar"] = array_merge($ar, $cart_prod[$k]);
					$this->load->view('admin/'.$this->controller.'/product_row',$dt);
                  endforeach;
		  		endif;		  
          ?>         
                       
				<script type="text/javascript">
					var prod_tr_cnt = <?php echo $cnt;?>;
				</script>
          </tbody>
      </table>

      </form>
      
<script type="text/javascript">
//cust_order_id for order
var custid = '<?php echo _en($this->cust_order_id)?>';

/**
 * @author Cloudwebs	
 * @param id will just identify sequence of row nothing else
 * @abstract function will caclulate product price whenever option changed
+------------------------------------------------+
*/
function calcProdPrice(obj, id)
{
	var prod_code = $(obj).val();
	var type = $('#product_type_'+id).val();
	var solitaire_diamond_code = 0;
	
	if(prod_code== '' || typeof prod_code === 'undefined' || prod_code=='0')
	{
		$('#span_prod_price_'+id).text('0');
		
		$('#checkbox_'+id).removeAttr('checked')
		return false;
	}

	var ring_size = ''; 
	if( $("select[name='ring_size_id_"+id+"']").length > 0 )
	{
		var ring_obj = $("select[name='ring_size_id_"+id+"']");
		var ring_size = $(ring_obj).val(); 
		if(ring_size == '' )
		{
			$( ring_obj ).css({'border' : '1px solid #FF0000'});
			$('#header').after(getNotificationHtml('error', 'Select ring size.'));
			
			$('#checkbox_'+id).removeAttr('checked')
			return false;
		}
	}
	else if( $( "#ring_size_id_f_"+id+"" ).length > 0 )
	{
		ring_size = $("#ring_size_id_f_"+id+"").val(); 
		if( ring_size == '' )
		{
			$("#ring_size_id_f_"+id+"").css({'border' : '1px solid #FF0000'});
			$('#header').after(getNotificationHtml('error', 'Select ring size.'));
			return false;
		}

		var temp = $("#ring_size_id_m_"+id+"").val(); 
		if( temp == '' )
		{
			$("#ring_size_id_m_"+id+"").css({'border' : '1px solid #FF0000'});
			$('#header').after(getNotificationHtml('error', 'Select ring size.'));
			return false;
		}

		ring_size = ring_size + '|' + temp;
	}
	
	var solitaire_diamond_code = $('#solitaire_diamond_code_'+id).val();	
	if( type == 'sol' && typeof solitaire_diamond_code !== 'undefined' && solitaire_diamond_code != null && solitaire_diamond_code != '' && solitaire_diamond_code != 0 )
	{
		$('#solitaire_diamond_code_'+id).css({'border' : '1px solid #000000'});
	}
	else if( type == 'sol' )
	{
		$('#solitaire_diamond_code_'+id).css({'border' : '1px solid #FF0000'});
		$('#header').after(getNotificationHtml('error', 'Specify diamond code.'));
		return false;
	}

	
	form_data = {prod_code : prod_code, id : id, ring_size : ring_size, custid : custid, type : type, solitaire_diamond_code : solitaire_diamond_code};
	var loc = (base_url+'admin/'+lcFirst(controller)+'/getProdPrice');
	$.post(loc, form_data, function(data)
	{
		data = $.parseJSON(data);
		if(data['type']!='success')
		{
			//msg
			$('#header').after(getNotificationHtml(data['type'], data['msg']));

			$('#span_prod_price_'+id).text('0');
			$('#td_product_name_'+id).html('');
			$('#td_product_sku_'+id).html('');
			$('#checkbox_'+id).val('0');

			//ring size
			$('#ring_size_'+id).html('Not applicable');				

			$('#checkbox_'+id).removeAttr('checked');
		}
		else
		{
			$('#span_prod_price_'+id).text(data['view_var']['product_discounted_price']);
			$('#td_product_name_'+id).html(data['view_var']['product_name']);
			$('#td_product_sku_'+id).html(data['view_var']['product_sku']);
			
			$('#checkbox_'+id).val(data['view_var']['product_price_id']).data('type', type);
			if( type == 'sol' && typeof data['d_detail'] !== 'undefined' )
			{
				$('#checkbox_'+id).data('diamond_price_id', data['d_detail']['diamond_price_id']);
			}

			//ring size
			if(data['view_var']['ring_size_drop_down'])
			{
				$('#ring_size_'+id).html(data['view_var']['ring_size_drop_down']);				
			}
			else
			{
				$('#ring_size_'+id).html('Not applicable');				
			}

			/**
			 * qty added On 13-04-2015 applicable to some products only
			 */
			if( typeof data['view_var']['qty_sel'] !== 'undefined' )
			{
				$("#qty_"+id).html( data['view_var']['qty_sel'] ); 
			} 
			
			//update cart database
			if( $('#checkbox_'+id).is(':checked') )
			{
				addRemProductAdmin($('#checkbox_'+id), data['view_var']['product_price_id'], $('#qty_'+id).val(), id);
			}
		}
		return false;
	});
}

/**	
 * function will add or remove prod in Admin cart
 */
function addRemProductAdmin( obj, pid, qty, id)
{
	if(pid==0 || pid=='0' || pid=='')
	{
		$(obj).removeAttr('checked');
		return false;	   
	}
	 
	if( $(obj).is(':checked') )
	{
		var ring_size = ''; 
		if( $("select[name='ring_size_id_"+id+"']").length > 0 )
		{
			var ring_obj = $("select[name='ring_size_id_"+id+"']");
			var ring_size = $( ring_obj ).val(); 
			if(ring_size == '' )
			{
				$( ring_obj ).css({'border' : '1px solid #FF0000'});
				$('#header').after(getNotificationHtml('error', 'Select ring size.'));
				
				$(obj).removeAttr('checked');
				return false;
			}
		}
		else if( $( "#ring_size_id_f_"+id+"" ).length > 0 )
		{
			ring_size = $("#ring_size_id_f_"+id+"").val(); 
			if( ring_size == '' )
			{
				$("#ring_size_id_f_"+id+"").css({'border' : '1px solid #FF0000'});
				$('#header').after(getNotificationHtml('error', 'Select ring size.'));
				return false;
			}
	
			var temp = $("#ring_size_id_m_"+id+"").val(); 
			if( temp == '' )
			{
				$("#ring_size_id_m_"+id+"").css({'border' : '1px solid #FF0000'});
				$('#header').after(getNotificationHtml('error', 'Select ring size.'));
				return false;
			}
	
			ring_size = ring_size + '|' + temp;
		}
		
		var type = $(obj).data('type');
		var diamond_price_id = $('#checkbox_'+id).data('diamond_price_id');
		if( type == 'sol' && typeof diamond_price_id !== 'undefined' && diamond_price_id != null && diamond_price_id != '' && diamond_price_id != 0 )
		{
			pid = pid + "=" + diamond_price_id;
			$('#solitaire_diamond_code_'+id).css({'border' : '1px solid #000000'});
		}
		else if( type=='sol' )
		{
			$('#solitaire_diamond_code_'+id).css({'border' : '1px solid #FF0000'});
			$('#header').after(getNotificationHtml('error', 'Specify diamond code.'));
			return false;
		}

		var loc = (base_url+'admin/sales_order/add');
		form_data = { pid : pid, qty : qty, ring_size : ring_size, custid : custid, type : type};
		$.post(loc, form_data, function (data)
		{
			var arr = $.parseJSON(data);
			if(arr['type']=='success')
			{
			}
		});
	}
	else
	{
		form_data = {pid : pid, custid : custid};
		remProductAdmin(form_data);
	}

 }

/*
* Function will change input text product generated code whenever select changed
*/
function dynamicAddInput(obj, id)
{
	var prodType = $(obj).val();
	$('.spanclass_'+id).hide();
	if(prodType == 'sol')
		$('#solitaire_code_span_'+id).show();
				
	if(prodType == 'dia')
		$('#diamond_code_span_'+id).show();
		
	if(prodType == 'cz')
		$('#cz_code_span_'+id).show();
		
	if(prodType == 'prod')
		$('#product_code_span_'+id).show();
}

/**
 * @author Cloudwebs	
 * adds product tr row 
 */
function addProductTr()
{
	prod_tr_cnt++;
	form_data = {prod_tr_cnt : prod_tr_cnt};
	var loc = (base_url+'admin/'+lcFirst(controller)+'/addProductTr');
	$.post(loc, form_data, function(data)
	{
		data = $.parseJSON(data);
		if(data['type']=='success')
		{
			$("#cart_body").append(data["html"]);
		}
		else
		{
			$('#header').after(getNotificationHtml(data['type'], data['msg']));
		}
		return false;
	});
}

/**
 * @author Cloudwebs	
 * function will remove product tr and also remove product from cart if it is in cart 
 */
function remProductTr( obj, pid, qty, id, tr_id )
{
	 $(obj).removeAttr('checked');
	 addRemProductAdmin( obj, pid, qty, id);
	 return $("#"+tr_id).remove();
}

</script>