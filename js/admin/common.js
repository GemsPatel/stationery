//admin notifications variable
var ord_last_id = 0;
var cus_last_id = 0;
var msg_last_id = 0;

$(document).ready(function()
{
	updateNotifications();
	
/**
 * @author Cloudwebs
 * @abstract Function will call updateNotifications after specfied time of interval
 */	
	window.setInterval(function()
	{
	 	updateNotifications();
	}, 60000);
	
	$('#menu > ul').superfish({
		pathClass	 : 'overideThisToUse',
		delay		 : 0,
		animation	 : {height: 'show'},
		speed		 : 'normal',
		autoArrows   : false,
		dropShadows  : false, 
		disableHI	 : false, /* set to true to disable hoverIntent detection */
		onInit		 : function(){},
		onBeforeShow : function(){},
		onShow		 : function(){},
		onHide		 : function(){}
	});
	
	$('#menu > ul').css('display', 'block');

	//header notifications
	var wrapper_id = "";
	$(".notifs").live("click", function()
	{
		$('.notifs').removeClass('open_notifs');
		$(this).addClass('open_notifs');
		
		wrapper_id = $(this).attr("id");
		if(!$("#" + wrapper_id + "_wrapper").is(":visible"))
		{
			$(".notifs_wrapper").hide();
			$("#" + wrapper_id + "_number_wrapper").hide();
			var html = '<span id="customers_notif_wrapper_loader" >';
            html += '<img style="padding:5px;" src="'+base_url+'images/preloader.gif">';
	        html += '</span>';

			$("#" + wrapper_id + "_wrapper").html(html);
			$("#" + wrapper_id + "_wrapper").show();
			var loc = (base_url+'admin/lgs/listNotifications');
			var last_id = 0;
			
			if(wrapper_id=='orders_notif')
			{
				last_id = ord_last_id;
			}
			else if(wrapper_id=='customers_notif')
			{
				last_id = cus_last_id;
			}
			else if(wrapper_id=='customer_messages_notif')
			{
				last_id = msg_last_id;
			}
			
			form_data = {type : wrapper_id,last_id : last_id};
			$.post(loc, form_data, function (data)
			{
				$("#" + wrapper_id + "_wrapper").html(data);
			});
		}
		else
		{
			$("#" + wrapper_id + "_wrapper").hide();
		}
	});
	
	$("#content").click(function(){
		$(".notifs_wrapper").hide();
		$('.notifs').removeClass('open_notifs');
	});

	//route = getURLVar('route');
	route = 'admin/'+lcFirst(controller);
	
	if (!route) {
		$('#dashboard').addClass('selected');
	} else {
		part = route.split('/');
		
		url = part[0];
		
		if (part[1]) {
			url += '/' + part[1];
		}
		
		$('a[href*=\'' + url + '\']').parents('li[id]').addClass('selected');
	}
	
	$('#menu ul li').on('click', function() {
		$(this).addClass('hover');
	});

	$('#menu ul li').on('mouseout', function() {
		$(this).removeClass('hover');
	});	

});
 
function getURLVar(key) {
	var value = [];
	
	var query = String(document.location).split('?');
	
	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');
			
			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}
		
		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
	
} 

/*
+------------------------------------------------------------+
	@author Cloudwebs
	@abstract function will show various nitifications to admin users
+------------------------------------------------------------+
*/
function updateNotifications()
{
	var loc = (base_url+'admin/lgs/updateNotifications');
	$.post(loc, '', function (data)
	{
		var arr = $.parseJSON(data);
		if(arr['type']=='success')
		{
			ord_last_id = arr['ord_last_id'];
			if(arr['ord_cnt']>0)
			{
				$('#orders_notif_value').text(arr['ord_cnt']);
				$('#orders_notif_number_wrapper').show();				
			}

			cus_last_id = arr['cus_last_id'];
			if(arr['cus_cnt']>0)
			{
				$('#customers_notif_value').text(arr['cus_cnt']);
				$('#customers_notif_number_wrapper').show();				
			}

			msg_last_id = arr['msg_last_id'];
			if(arr['msg_cnt']>0)
			{
				$('#customer_messages_notif_value').text(arr['msg_cnt']);
				$('#customer_messages_notif_number_wrapper').show();				
			}
		}
		else
		{
			$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
		}
	});
}

/**
 * updates language session  
 */
function updateLang(obj)
{
	if( $(obj).val() != "" )
	{
		var loc = (base_url+'home/setLangSession');
		$.get(loc, { set : "lang",  lang : $(obj).val() }, function (data)
		{
			var arr = $.parseJSON(data);
			if(arr['type']=='success')
			{
				window.location.reload();	
			}
			else
			{
				$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
			}
		});
	}
}

/**
 * this function is called in edit item call if required, function will check if implicit inventory session switch is required 
 */
function switchInventorySessionIfRequired( IT_KEYLcl, url )
{
	if( IT_KEYLcl == IT_KEY )
	{
		location.href = url; 
	}
	else 
	{
		updateInventorySession(IT_KEYLcl, url)
	}
}

/**
 * updates inventory session  
 */
function updateInventorySession(IT_KEYLcl, url)
{
	showLoader();
	if( IT_KEYLcl != "" )
	{
		var loc = (base_url+'home/setInventorySession');
		$.get(loc, { it_key : IT_KEYLcl }, function (data)
		{
			var arr = $.parseJSON(data);
			if(arr['type']=='success')
			{
				if( url == "" )
				{
					window.location.reload();	
				}
				else 
				{
					location.href = url; 	
				}
			}
			else
			{
				$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
			}
			
			hideLoader();
		});
	
	}
}

/**
 * updates seller session  
 */
function importDataProcess( path, start )
{
	var loc = (base_url+root_dir+'/'+controller_org+'/importDataProcess');
	$.get(loc, { path : path, start : start }, function (data)
	{
		jQuery('#import_process_loader').before( data );
	});
}
