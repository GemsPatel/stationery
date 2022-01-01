/* Admin Javascript */
var noti_id = window.setTimeout(hideNotification, 5000);

//creating class object which is usefull to every admin forms
var admin  = { bind_datepicker: function(){ $('.datepicker').datepicker({dateFormat :'yy-mm-dd'}); } };

var prev_sort_order = 0;		//specifies sort_order when user had first clicked td of sort_order
var is_sort_order_clicked = false; // specifies if sort order td clicked	
var sort_order_id = false; // specifies if sort order field pid
var is_sort_order_fun_called = false; //specofies if fun called

$(document).ready(function() {


	//bindTooltip(); //bind tooltip
	
	//Close button:		
	$(".close").live('click',function () {
		$(this).parent().slideUp(400);
			return false;
		}
	);
	// Initialise Facebox Modal window:
	$('a[rel*=modal]').each(function(){
		$(this).facebox();
	});
			
	/* Resize popup box*/		
	$(window).resize(function(){
		setOverlayPos('#facebox');
	});
		
	
	/* FOR SORTING ORDER*/
	$('.list thead #heading_tr th').live('click',function(){
		showLoader();
		var field = $(this).attr('f'); //name of table field
		var srt = $(this).attr('s'); //name of sorting table field

		form_data = $('#form').serialize(); 
		form_data += "&f=" + encodeURIComponent(field);
		form_data += "&s=" + encodeURIComponent(srt);
		var loc = (base_url+'admin/'+lcFirst(controller));
		$.get(loc, form_data, function (data) {
			$('.content').html(data);
			hideLoader();
		});
		
	});
	
	/* FOR SEARCH FILTER*/
	$('a#searchFilter').live('click',function(){
		showLoader();
		var field = $('#hidden_field').attr('value'); //name of table field
		var srt = $('#hidden_srt').attr('value'); //name of sorting table field
					
		form_data = $('#form').serialize();
		form_data += "&f=" + encodeURIComponent(field);
		form_data += "&s=" + encodeURIComponent(srt);
		var loc = (base_url+'admin/'+lcFirst(controller));
		/*$.ajax({
                type: 'GET',
                url: loc,
                cache: false,
                data: form_data,
                success: function(response) {
                    $('.content').html(response);
                },
                error: function() {
                }
            });*/
		$.get(loc, form_data, function(data){
			$('.content').html(data);
			hideLoader();
		});
		
		return false;
	});
	
	/*
		status enabled/disabled at listing table.
	*/
	$('#ajaxStatusEnabled').live('click',function(){
		showLoader();
		var status = $(this).attr('rel');
		var cat_id = $(this).attr('data-');
		
		form_data = {status : status, cat_id : cat_id};
		var loc = (base_url+'admin/'+lcFirst(controller))+'/updateStatus';
		$.post(loc, form_data, function (data) {
		if(typeof(data) != 'undefined' && typeof(data) != null && data != '')
		{
			var arr = $.parseJSON(data);
			if(arr['type'] == "error")
			{
				$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
				return false;
			}
		}
		var selector = $('a[data-="' + cat_id + '"]');
		selector.attr('title',(status == 1) ? "Disabled" : "Enabled"); //change title attr
		selector.attr('rel', (status == 1) ? "0" : "1"); //change image status attr rel
		selector.parent().find('img').attr("src", (status == 1 ? asset_url+"images/admin/disabled.gif" : asset_url+"images/admin/enabled.gif")); //change image src attr
		
			hideLoader();
		});
		
	});

	/**
	 * @abstract Ajax set textbox in sort order td for any module
	 * @author Cloudwebs
	 */
	$('.sort_order').live('click',function()
	{
		if(!is_sort_order_clicked)
		{
			is_sort_order_clicked=true;
			prev_sort_order = $(this).attr('rel');
			sort_order_id = $(this).attr('data-');
			var width =$(this).width();
			var height =$(this).height();
			var htm = '<input type="text" class="ajax_sort_order_in" id="numeric" name="ajax_sort_order" value="'+prev_sort_order+'" size="3"/>';
			$(this).html(htm);
			
			$('.ajax_sort_order_in').focus();
			$('.ajax_sort_order_in').prop("selectionStart", $('.ajax_sort_order_in').val().length);
			
			$('#loading_img_adm').css({position:"absolute", left: $(this).position().left,top:$(this).position().top})	//set loader image position as per td
		}
	});
	
	$('.ajax_sort_order_in').live('blur',function()
	{
		if(!is_sort_order_fun_called)
		{
			is_sort_order_fun_called = true;
			updateSortOrder();
			is_sort_order_fun_called = false;
			is_sort_order_clicked=false;
		}
	});
	

	/*
		get html data on pagination links click using ajax 
	*/
	$('.pagination .links a').live('click',function(){
		showLoader();
		var url = $(this).attr('href');
		form_data = {};

		if(url.indexOf('perPage') == -1)
		{
			form_data = {perPage : $('.perPageDropdown').val()};
		}
		$.get(url, form_data, function (data) {
			$('.content').html(data);
			hideLoader();
		});
		
		return false;	
	});
	
	/* Toggle hide/show */
	$('.toggle').live('click',function(){
		//console.log($(this).attr('class'));
		if($(this).attr('class') == 'toggle minus'){
			$(this).next().toggle();
			$(this).attr('class','toggle plus');	
		} else {
			$(this).next().toggle();
			$(this).attr('class','toggle minus');
		}
				
	});
});

/**
 *  Item listing any module add check box multi select using shift key
 *  - Gautam Kakadiya
 */
var lastChecked = null;

$(document).ready(function() 
{
    var $chkboxes = $('.chkbox');
    $chkboxes.click(function(e) 
    {
        if(!lastChecked) 
        {
            lastChecked = this;
            return;
        }

        if(e.shiftKey) {
            var start = $chkboxes.index(this);
            var end = $chkboxes.index(lastChecked);

            $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);

        }

        lastChecked = this;
    });
});

/*
+--------------------------------------------------+
	Common ajax function
+--------------------------------------------------+
*/
function ajaxLoad_json(url, postData, getVal)
{
	var getData = '';
	$.ajax({
		type: "POST",
		url: url,
		data: postData, //{key1: 'value1', key2: 'value2'}
//		dataType: "json",
		async:false,
		success: function(html) {
			if(getVal)
			  getData = html; 
			 
		}
	});
	return getData;
}
/*
 +-------------------------------------------+
	delete records using ajax call
 +-------------------------------------------+
*/
function deleteAjaxData()
{
	if($("input:checkbox[name=selected[]]:checked").size() == 0)
	{
		$('#content').before(getNotificationHtml('error','Please select at least 1 item'));
		return false;
	}
	
	if(confirm('Are you sure want to delete?'))
	{
		var loc = (base_url+'admin/'+lcFirst(controller))+'/deleteData';
		form_data = $('#form').serialize();
		$.post(loc, form_data, function (data) {
			var arr = $.parseJSON(data);
			if(arr['type'] == "success")
			{
				$("input:checkbox[name=selected[]]:checked").each(function()
				{
					var row = document.getElementById($(this).val());
					if(row != '' && row != null && typeof(row) !== 'undefined')
						row.parentNode.removeChild(row);
				});
			}
			$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
		});
	}
}
/*+---------------------------------------------+
	get state form country id
+---------------------------------------------+
*/
function getStateFromCountry(id)
{
	var i=0;
	$('select[name="state_id"] option:first').text('Please Wait...') // displaying loading texts;
	var loc = (base_url+'admin/'+lcFirst(controller))+'/getState';
	form_data = {country_id : id};
	$.post(loc, form_data, function (data) {
		$('.state-'+(i+1)).html(data);
		i++;
	});
}

/*+---------------------------------------------+
	New 
	@author Cloudwebs 
	get state form country id
	@param id country id
	@param name state select box name
+---------------------------------------------+
*/
function getState(id,name)
{
	$('select[name="'+name+'"] option:first').text('Please Wait...') // displaying loading texts;
	var loc = (base_url+'admin/'+lcFirst(controller))+'/getState';
	form_data = {country_id : id, name : name};
	$.post(loc, form_data, function (data) {
		$('select[name="'+name+'"]').html(data);
	});
}

function loadCity(state_id,class_name,con_url)
{
	$("."+class_name).html('Loading...');
	form_data={state_id : state_id};
	var loc = (base_url+con_url);
	$.post(loc, form_data, function (data)
	{
		$("."+class_name).html(data);
	});
}




/*
+----------------------------------------------+
	This function will return notification html
	@params : type -> type of notification.
			  message - > message you want dispaly 
			  				as error.
+----------------------------------------------+
*/
function getNotificationHtml(type,message)
{
	var ht = '<div class="notification '+type+' png_bg"><a href="#" class="close"><img src="'+base_url+'images/admin/cross_grey_small.png" title="Close this notification" alt="close"></a><div>'+message+'</div></div>';
	clearInterval(noti_id);
	noti_id = window.setTimeout(hideNotification, 5000);
			
	return ht;
}
/*
+--------------------------------------------------+
	Function will remove all special character from
	string and append - for URL optimization
+--------------------------------------------------+
*/
function getUrlName(str)
{
	var st = str.replace(/[^a-zA-Z0-9]+/g,'-');
	$('#display_alias').val(st.toLowerCase());
	//$('#display_alias li').html(st.toLowerCase());
}
/*
+---------------------------------------------+
	AutoHide notification div. function call
	from timeout every 7 seconds.
+---------------------------------------------+
*/
function hideNotification()
{
	$('.notification').slideUp();
}
/*
+---------------------------------------------+
	first charachet lower
+---------------------------------------------+
*/ 
function lcFirst(str)
{
	str+= '';
	var f = str.charAt(0).toLowerCase();
	return f+ str.substr(1);
}

/*
+---------------------------------------------+
	Datepicker
+---------------------------------------------+
*/
/*function MyDatepicker() {
	 $( ".datepicker" ).datepicker({dateFormat :'yy-mm-dd'}); 
}*/

/*
+---------------------------------------------+
	Per page dropdown
+---------------------------------------------+
*/
function perPageManage(obj)
{
	showLoader();
	var field = $('#hidden_field').attr('value'); //name of table field
	var srt = $('#hidden_srt').attr('value'); //name of sorting table field

	form_data = $('#form').serialize(); 
	form_data += "&f=" + encodeURIComponent(field);
	form_data += "&s=" + encodeURIComponent(srt);
	var loc = (base_url+'admin/'+lcFirst(controller));
	$.get(loc, form_data, function (data) {
		$('.content').html(data);
		hideLoader();
	});
}

/*
+---------------------------------------------+
	clear on set no image display
+---------------------------------------------+
*/
function clear_image(para1)
{
	$("#"+para1).attr('src', base_url+"images/admin/no_image.jpg");
	clearHiddenImage(para1);
}
/*
+---------------------------------------------+
	hidden clear image path
+---------------------------------------------+
*/
function clearHiddenImage(para1)
{
	var hideInput = $("#"+para1).nextAll('input:[type=hidden]')[0];//next('input:hidden').val(''); //empty hidden value
	//var hideNextInput = $(".image").nextAll('input:[type=hidden]')[1];
	$(hideInput).val('');
	//$(hideNextInput).val('');
}
/*
+---------------------------------------------+
	display image preview
+---------------------------------------------+
*/

function readURL(input,position) 
{
	var inputId = input.id;
	var prevImgId = $('#'+inputId).parent().find('img').attr('id'); //find parent img id
	strInput = inputId.substring(0,inputId.indexOf("_") + 1);
	strPrevImg = prevImgId.substring(0,prevImgId.indexOf("_") + 1);
	//alert(strInput+"=="+strPrevImg);
	var imgName = $('#'+strInput+position).val();
	var ext = imgName.split('.').pop().toLowerCase();
	
	if($.inArray(ext, ['gif','png','jpg','jpeg'])) 
	{
		if (input.files && input.files[0]) 
		{
			var reader = new FileReader();
			reader.onload = function (e) 
			{
				$('#'+strPrevImg+position).attr('src', e.target.result);
				$('#'+inputId).next().val(imgName);
			}
			reader.readAsDataURL(input.files[0]);
		 }
	}
	else
	{
		$('#'+strPrevImg+position).attr('src','');
	}
}

/*
+---------------------------------------------+
	show preloader at listing table.
+---------------------------------------------+
*/
function showLoader()
{
	$('.pre_loader').show();
}
/*
+---------------------------------------------+
	hide preloader at listing table.
+---------------------------------------------+
*/
function hideLoader()
{
	$('.pre_loader').hide();
}
/*
+---------------------------------------------+
	Bind Tooltip with a:href in table.
+---------------------------------------------+
*/
function bindTooltip()
{
	//remove previously created tooltip so, it will not bind second time.
	$('.qtip').remove();
	
	$('.content a[title], .content th[s]').qtip({
		position: { corner: {target : 'topLeft',tooltip : 'topRight'}, target : 'mouse' },
		adjust:{ mouse: true},
		style: {name : 'cream',tip: true} // Give it some style
	 });
	 
	// $('.content tr:even').addClass("alt-row"); // Add class "alt-row" to even table rows
}

function setOverlayPos(selector){//update by hitesh 30 - 08 - 2012 (xp ie7 bug fixed).
   
   if(typeof(selector) == 'undefined')
		selector = '.blockPage';
		
   // Get window sizes
   var winhgt = parseInt($(window).height());
   var winwth = parseInt($(window).width());
   
   
   var blockstyle = $(selector).css("display");
   if(blockstyle == "block"){
		   // Get Block sizes
		   var overlayhgt = parseInt($(selector).height());
		   var overlaywth = parseInt($(selector).width());
		   
				   if(overlayhgt != null && overlayhgt!="" && overlaywth != null && overlaywth!=""){
						   // Get Document sizes
						   var dochgt = ($(document).height()>900)?$(document).height():900; //$(document).height(); //900;
						   var docwth = $(document).width();
						   var mytop = '', myleft='';
				   
						   if(winhgt > dochgt){
								   mytop = (dochgt - overlayhgt) / 2;
								   myleft = (docwth - overlaywth) / 2;
						   }
						   else{
								   mytop = (winhgt - overlayhgt) / 2;
								   myleft = (winwth - overlaywth) / 2;
						   }
						   mytop += parseInt($(window).scrollTop());
						   
						   if(winhgt<overlayhgt){
								   mytop = 0;
						   }
						   if(winwth<overlaywth){
								   myleft = 0;
						   }
						   var tp = (mytop >0) ? mytop-140 : mytop;
						   $(selector).css({
								   top: ((tp < 0) ? 5 : tp)+'px',
								   left: myleft+'px'
						   });
				   }
		   
   }
}
/*
+---------------------------------------------+
	Function is displaying form error beside the
	input area.
	@params : errorArray : 2 dimensional array with
							name of input.
+---------------------------------------------+
*/
function displayErrors(errorArray)
{
	//hide all previous notifications
	$('.input-notification').hide();
	
	//setting error into form
	for(x in errorArray)
		$('.input-notification[for="'+x+'"]').text(errorArray[x]).show();
}
/*
+---------------------------------------------+
	Save admin account data using ajax
+---------------------------------------------+
*/
function saveAccountSettings(obj)
{
	showLoader(obj);
	
	$.post(base_url+'admin/lgs/accountSettings',$(obj).serialize(),function(response){ 
		var cts = $.parseJSON(response);
		if(typeof(cts.success) == 'undefined')
			displayErrors(cts);
		else
		{
			$('.input-notification',obj).hide();
			$('.password_fields').val('');
			$(obj).find('.notification_area').html(getNotificationHtml('success','You Account settings has been saved.'));
		}
		hideLoader(obj);
	});
	return false;
}

/*
+------------------------------------------------------------+
	gives permission denied message 
+------------------------------------------------------------+
*/
  	function permissionDenied(per)
	{
		alert('Sorry! you don\'t have '+per+' permission.');
	}

/*
+------------------------------------------------------------+
	@author Cloudwebs
	@abstract function will render price with price symbol as per currency type specified Note: Currency type still not implemented in admin panel
+------------------------------------------------------------+
*/
  	function lp(val)
	{
		if(val != '' && val != null && typeof val != 'undefined')
			return 'Rs.'+val;
		else
			return 'Rs.0';
		
	}
	
	/**
	 * @abstract Ajax update sort order in any module
	 * @author Cloudwebs
	 */
	function updateSortOrder()
	{
		$('#loading_img_adm').css({display:"inline"})
		var value = $('.ajax_sort_order_in').val();
		var selector = $('td[data-="' + sort_order_id + '"]');
		if(value=='' || $.isNumeric(value)==false)
			value=0;

		if(value!=prev_sort_order)
		{
			form_data = {sort_order : value, id : sort_order_id};
			var loc = (base_url+'admin/'+lcFirst(controller))+'/updateSortOrder';
			
			$.post(loc, form_data, function (data)
			{
				var arr = $.parseJSON(data);
				if(arr['type']=='success')
				{
					selector.attr('rel',value);
					selector.html(value);
				}
				$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
			});
		}
		else
		{
			selector.html(value);
		}

		$('#loading_img_adm').css({display:"none"})
	}

/**	
 * function will remove prod in Admin cart
 */
 function remProductAdmin(form_data)
 {
	var loc = (base_url+'admin/sales_order/removeProduct');
	$.post(loc, form_data, function (data)
	{
		var arr = $.parseJSON(data);
		if(arr['type']=='success')
		{
			/**
			 * location reload commented on 13-04-2015
			 */
			//window.location.reload();
			$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
		}
		else 
		{
			alert( 'does not allowed in order deletion' );
			return false;	
		}
	});
 }
 
/*	
 * function will update order item of specific order: it is post order administraion to update orders
 */
 function updateOrderItemAdmin(form_data)
 {
	var loc = (base_url+'admin/sales_order/updateOrderItemAdmin');
	$.post(loc, form_data, function (data)
	{
		var arr = $.parseJSON(data);
		if(arr['type']=='success')
		{
			window.location.reload();
		}
		else 
		{
			return false;	
		}
	});
 }
/*
 * Function will saved product to ebay product data
 */
function ajaxAddEbayListing()
{
	if($("select[name=ebay_site_id]").val() == "")
	{
		$('#content').before(getNotificationHtml('error','Please select at least 1 country'));
		return false;
	}
	
	showLoader();
	var ebay_site_id = $("select[name=ebay_site_id]").val();
	
	form_data = $('#form').serialize();
	form_data += "&ebay_site_id="+ebay_site_id;
	
	var loc = (base_url+'admin/'+lcFirst(controller))+'/ajaxAddEbayListing';
	$.post(loc, form_data, function (data) {
		
		var arr = $.parseJSON(data);
		console.log(arr.success);
		if(arr.success)
		{
			$('#content').before(getNotificationHtml('success',arr.success));
			return false;
		}
		
		hideLoader();
	});
	
}
/*
* Function will update add listing button text
*/
function changeAddListBtnText(obj)
{
	var optStr = "";
	var optVal = $(obj).val();
	if(optVal == '0')
		optStr = "US";
	else if(optVal == '3')
		optStr = "UK";
	else if(optVal == '15')
		optStr = "AU";
	else
		optStr = "";
	
	$('#addListingBtn').html("Transfer to "+optStr+" listing inventory");
}
/*
* Function will update add listing button text
*/
function showDeleteBtn(obj)
{
	var optVal = $(obj).val();
	if(optVal != -1)
		$('#deleteBtn').show();
	else
		$('#deleteBtn').hide();
		
	$('#searchFilter').click();
}
/*
* Function send email newsletter in product form
*/
function productSendNewsletter()
{
	var toEmail = $("input[name=product_email_toemails]").val();
	var toSubject = $("input[name=product_email_subject]").val();
	var toMsg = CKEDITOR.instances['product_email_message'].getData();
	
	if(toEmail == "")
	{
		$('#content').before(getNotificationHtml('error','Please enter to emails'));
		return false;
	}
	else if(toSubject == "")
	{
		$('#content').before(getNotificationHtml('error','Please enter to email subject'));
		return false;
	}
	
	showLoader();
	
	form_data = {toEmail : toEmail, toSubject : toSubject, toMsg : toMsg};
	
	var loc = (base_url+'admin/'+lcFirst(controller))+'/productSendNewsletter';
	$.post(loc, form_data, function (data) {
		
		var arr = $.parseJSON(data);
		$('#content').before(getNotificationHtml(arr.type,arr.msg));
		$("input[name=product_email_toemails]").val('');
		$("input[name=product_email_subject]").val('');
		CKEDITOR.instances['product_email_message'].setData('');
		hideLoader();
	});
}
