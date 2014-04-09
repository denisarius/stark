// *****************************************************************************
// ******
// ****** Common procedures
// ******
// *****************************************************************************
$(window).load(function(){
	$("#_admin_loading_icon").css("display", "none");
	$('body').fadeIn('slow');
});
// Divs for waiting icon
document.write('<div id="_admin_loading_icon" style="text-align:center;"><img style="padding-top:8px;" src="/admin/images/loading_32.gif"></div>');
document.write('<div class="grey_box"></div>');
document.write('<div class="white_box"></div>');
document.write('<div class="wait_slider" style="display:none"><center><img style="padding-top:8px;" src="/admin/images/loading_32.gif"></center></div>');
document.write('<div id="_show_dialog" style="display:none;"></div>');
// -----------------------------------------------------------------------------
var getResult, execQueryFinish;
var adminDataManagerURL="/admin/admin_data_manager.php";
var loading_icon="loading_32.gif";
var loading_icon_24="loading_24.gif";
var _admin_info_frames=new Array(), _admin_info_frames_scroller;
// -----------------------------------------------------------------------------
jQuery.fn.screenCenter = function()
{
	w = $(window);
	this.css("position", "absolute");
	this.css("top",(w.height()-this.height())/2+w.scrollTop() + "px");
	this.css("left",(w.width()-this.width())/2+w.scrollLeft() + "px");
	return this;
}
// -----------------------------------------------------------------------------
jQuery.fn.elementCenter = function(id)
{
	e = $('#'+id);
	this.css("position", "absolute");
	this.css("top",(e.innerHeight()-this.height())/2+e.offset().top + "px");
	this.css("left",(e.innerWidth()-this.width())/2+e.offset().left + "px");
	return this;
}
// -----------------------------------------------------------------------------
function mask_screen(func)
{
	mask_width=$(window).width();
	mask_height=$(document).height();
	$('.grey_box').css({top: '0px', left: '0px', width: mask_width+'px', height: mask_height+'px', opacity: '0.5'}).fadeIn();
	$(".wait_slider").screenCenter().fadeTo('slow', '1', func);
}
// -----------------------------------------------------------------------------
function mask_element(id, func)
{
	if ($('#'+id).length==0 || $('body').css('display')=='none')
		func()
	else
	{
		mask_width=$('#'+id).innerWidth();
		mask_height=$('#'+id).innerHeight();
		mask_top=$('#'+id).offset().top+1;
		mask_left=$('#'+id).offset().left+1;
		mask_border=$('#'+id).css('border');
		$('.grey_box').css({top: mask_top+'px', left: mask_left+'px', width: mask_width+'px', height: mask_height+'px', opacity: '0.5', border: mask_border}).show();
		$(".wait_slider").elementCenter(id).fadeTo(300, '0.5', func);
	}
}
// -----------------------------------------------------------------------------
function mask_element_noicon(id, func)
{
	mask_width=$('#'+id).innerWidth();
	mask_height=$('#'+id).innerHeight();
	mask_top=$('#'+id).offset().top+1;
	mask_left=$('#'+id).offset().left+1;
	mask_border=$('#'+id).css('border');
	$('.grey_box').css({top: mask_top+'px', left: mask_left+'px', width: mask_width+'px', height: mask_height+'px', opacity: '0.5', border: mask_border}).show();
	func();
}
// -----------------------------------------------------------------------------
function mask_screen_noicon(func)
{
	mask_width=$(window).width();
	mask_height=$(document).height();
	$('.grey_box').offset({top:0, left:0}).css({top: '0px', left: '0px', width: mask_width+'px', height: mask_height+'px', opacity: '0.5'}).fadeIn(300);
	func();
}
// -----------------------------------------------------------------------------
function clear_mask()
{
	$('.wait_slider').fadeOut();
	$('.grey_box').fadeOut();//.css({width: '0px', height: '0px'});
}
// -----------------------------------------------------------------------------
function execQueryCallback(data)
{
	getResult=data;
}
// -----------------------------------------------------------------------------
function execQuery(url, params)
{
	$.ajaxSetup({async: false});
	$.post(url, params, execQueryCallback, 'text');
	$.ajaxSetup({async: true});
	return getResult;
}
// -----------------------------------------------------------------------------
function confirm_box(title, html, func)
{
	var res=false;
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog( "destroy" );
	$("#_show_dialog").attr("title", title);
	$("#_show_dialog").html(html);
	$("#_show_dialog").dialog({
		modal: true,
		draggable: false,
		resizable: false,
		dialogClass: "_admin_dialogs",
		buttons: {
				"Да": function() {
					$( this ).dialog( "close" );
					func();
					res=true;
				},
				"Нет": function() {
					$( this ).dialog( "close" );
					res=true;
				}
			}
	});
}
// -----------------------------------------------------------------------------
function show_message(title, html)
{
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog( "destroy" );
	$("#_show_dialog").attr("title", title);
	$("#_show_dialog").html(html);
	$("#_show_dialog").dialog({
		modal: true,
		draggable: false,
		resizable: false,
		dialogClass: "_admin_dialogs",
		buttons: {
				"Ok": function() {
						$( this ).dialog( "close" );
					}
		}
	});
}
// -----------------------------------------------------------------------------
function show_message_warning(title, text)
{
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog( "destroy" );
	$("#_show_dialog").attr("title", title);
	html="\
<img style='float:left' src='images/admin_stop_icon_48.png'>\
<div style='width: 300px; margin-left: 20px; float:right'>"+text+"</div>\
<br>\
";
	$("#_show_dialog").html(html);
	$("#_show_dialog").dialog({
		modal: true,
		draggable: false,
		resizable: false,
		width: 400,
		dialogClass: "_admin_dialogs",
		buttons: {
				"Ok": function() {
						$( this ).dialog( "close" );
					}
		}
	});
}
// -----------------------------------------------------------------------------
String.prototype.trim = function () {
//    return this.replace(/^\s*/, "").replace(/\s*$/, "");
	return this.replace(/^\s*|\s*$/, '');
}
// -----------------------------------------------------------------------------
function show_wait_image(element)
{
	if (element=='')
		$(".wait_slider").screenCenter().fadeTo('slow','1', func);
    else
		$(element).html("<br><br><center><img src='images/"+loading_icon+"'></center><br>");
}
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// Admin procedure
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
function admin_go_to_url(url)
{
	window.open(url);
}
// -----------------------------------------------------------------------------
function admin_get_unique_id()
{
	do {
		n=Math.floor(Math.random()*Math.random()*18954785965458745895);
		id='admin_'+Math.floor(Math.random()*Math.random()*18954785965458745895).toString(36).substr(2, 8);
		l=$('#'+id).length;
	} while(l!=0)
	return id;
}
// -----------------------------------------------------------------------------
function admin_info_show(title, html, info_width)
{
	if (!_admin_info_frames.length)
	{
		_admin_info_frames_scroller=$('body').css('overflow-y');
		w=$(window).width();
		$('body').css('overflow-y', 'hidden');
		w=$(window).width()-w;
		$('body').css('margin-right', w+'px');
	}

	__info_show_id=admin_get_unique_id();
	_admin_info_frames.push(__info_show_id);

	w=$(window).width();
	h=$(window).height();
	hd=$(document).height();
    $("body").append('<div class="white_box" id="white_box_'+__info_show_id+'"></div>');
	$('#white_box_'+__info_show_id).css({width: w+'px', height: hd+'px', opacity: '0.7'}).fadeIn();
	if (info_width==null)
	{
		info_width=w*2/3;
		if (info_width>600) info_width=600;
	}
	if (title!='')
		info_html="\
<h1 id='info_window_header_'+id>"+title+"<input type='button' value='Закрыть' onClick='admin_info_close()'>\
<br></h1>";
	else
		info_html='';
	h=h*4/5;
	$("body").append('<div id="'+__info_show_id+'" class="info_frame"></div>');
	info_html=info_html+"<div id='info_window_container_"+__info_show_id+"' style='max-height: "+h+"px; overflow-y: auto'>"+html+"</div>";
	$('#'+__info_show_id).html(info_html);
	$('#'+__info_show_id).css('width', info_width+'px');
	$('#'+__info_show_id).screenCenter().fadeIn();
}
// -----------------------------------------------------------------------------
function admin_info_change(title, html, width)
{
	__info_change_id=_admin_info_frames[_admin_info_frames.length-1];
	$('#info_window_container_'+__info_change_id).html(html).scrollTop(0);
	if (width!=null) $('#'+__info_change_id).css('width', width+'px');
	if(title!='') $("#info_window_header_"+__info_change_id).html(title+"<input type='button' value='Закрыть' onClick='admin_info_close()'><br>");
	$('#'+__info_change_id).screenCenter();
}
// -----------------------------------------------------------------------------
function admin_info_frame_id()
{
	return _admin_info_frames[_admin_info_frames.length-1];
}
// -----------------------------------------------------------------------------
function admin_info_frame_inner_id()
{
	return "info_window_container_"+_admin_info_frames[_admin_info_frames.length-1];
}
// -----------------------------------------------------------------------------
function admin_info_center()
{
	__info_center_id=_admin_info_frames[_admin_info_frames.length-1];
	$('#'+__info_center_id).screenCenter();
}
// -----------------------------------------------------------------------------
function admin_info_set_width(w)
{
	__info_center_id=_admin_info_frames[_admin_info_frames.length-1];
	$('#'+__info_center_id).css('width', w+'px');
}
// -----------------------------------------------------------------------------
function admin_info_close()
{
	__info_close_id=_admin_info_frames[_admin_info_frames.length-1];
	_admin_info_frames.pop();
	$('#white_box_'+__info_close_id).fadeOut(function(){ $('#white_box_'+__info_close_id).remove(); });
//	$('#'+__info_close_id).fadeOut(function(){ $('#'+__info_close_id).html(''); });
	$('#'+__info_close_id).fadeOut(function(){ $('#'+__info_close_id).html('').remove(); });
	if (!_admin_info_frames.length)
	{
		$('body').css('margin-right', '0');
		$('body').css('overflow-y', _admin_info_frames_scroller);
	}
}
// -----------------------------------------------------------------------------
function admin_num_to_text(num)
{
	var i = 0, type = ['Bytes','Kb','Mb','Gb','Tb','Pb'];
	while((num / 1000 | 0) && i < type.length - 1) {
		num /= 1024;
		i++;
	}
	return num.toFixed(2) + ' ' + type[i];
}
// -----------------------------------------------------------------------------
function admin_show_datepicker(id)
{
	w = $(window);
	d=$("#datepicker");
	d.css("position", "absolute").css("top",(w.height()-d.height())/2+w.scrollTop() + "px").css("left",(w.width()-d.width())/2+w.scrollLeft() + "px").css("display", "");
	$("#"+id).datepicker("show");
}
//------------------------------------------------------------------------------
function inner_full_height(id)
{
	var h;
	h=0;
	$('#'+id).children().each(function(){
		h+=$(this).height();
	});
	return h;
}
// -----------------------------------------------------------------------------
// image loader procedure
// -----------------------------------------------------------------------------
var _admin_swfu, _admin_uploaded_files=new Array();
// -----------------------------------------------------------------------------
function admin_load_image(func, file_mask)
{
	delete _admin_swfu;
	html="\
<div id='_admin_loader_button_holder'></div>\
<div id='_admin_loader_progress_block'>\
</div>\
";
    admin_info_show('Загрузка файла', html, 302);
	_admin_swfu = new SWFUpload(
		{	upload_url : "{@admin_url@}/uploader/upload.php",
			flash_url : "{@admin_url@}/uploader/swfupload.swf",
			file_size_limit : "10 MB",

			file_types : file_mask,
			file_types_description: "Files",
			file_upload_limit : 1,

			button_placeholder_id : "_admin_loader_button_holder",
			button_image_url : "{@admin_url@}/images/upload_button.png",
			button_width : 148,
			button_height : 27,
			button_text : "<span class='uploadButtonText'>Выберите файл</span>",
			button_text_style : ".uploadButtonText { color: #666666; font-size: 11px; font-weight: bold; font-family: Sans-Serif;}",
			button_text_left_padding : 30,
			button_text_top_padding : 4,
			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
			button_disabled : false,
			button_cursor : SWFUpload.CURSOR.HAND,
			button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,

//			swfupload_loaded_handler : swfupload_loaded_function,
//			file_dialog_start_handler : file_dialog_start_function,
			file_queued_handler : admin_uploader_fileQueued,
			file_queue_error_handler : admin_uploader_fileQueueError,
//			file_dialog_complete_handler : fileDialogComplete,
			upload_start_handler : admin_uploader_uploadStart,
			upload_progress_handler : admin_uploader_uploadProgress,
			upload_error_handler : admin_uploader_uploadError,
//			upload_success_handler : upload_success_function,
			upload_complete_handler : admin_uploader_uploadComplete,
//			debug_handler : debug_function,
//			debug: true,

			custom_settings: {
				complete_func: func,
			}
		}
	);
}
// -----------------------------------------------------------------------------
function admin_uploader_fileQueued(file)
{
   	$("#_admin_loader_progress_block").html("\
<div class='admin_image_upload_progres_bar' id='_admin_loader_progress_bar'></div>\
<span id='_admin_loader_progress_text'></span>\
");
//	_admin_swfu.addPostParam("upload_file_real_name", file.name)
	_admin_swfu.addFileParam(file.id, "upload_file_real_name", file.name)
	_admin_swfu.startUpload()
}
// -----------------------------------------------------------------------------
function debug_function(message)
{
	alert(message);
}
// -----------------------------------------------------------------------------
function admin_uploader_fileQueueError()
{}
function admin_uploader_uploadStart()
{}
// -----------------------------------------------------------------------------
function admin_uploader_uploadProgress(file, bytes, total)
{
	w100=300;
	w=parseInt(w100*bytes/total);
	if (bytes==total) w=w100;
    $("#_admin_loader_progress_bar").css({"width": w+"px"});
    $("#_admin_loader_progress_text").html(admin_num_to_text(bytes)+" из "+admin_num_to_text(total));
}
// -----------------------------------------------------------------------------
function admin_uploader_uploadError(file, error, message)
{
	alert(message);
}
// -----------------------------------------------------------------------------
function admin_uploader_uploadComplete(file)
{
	complete_function=_admin_swfu.customSettings.complete_func;
    $("#_admin_loader_progress_block").after("<p>Идет обработка загруженного файла ...</p>");
	admin_info_close();
	_admin_swfu.destroy();
	complete_function(file.name);
}
// -----------------------------------------------------------------------------
