// *****************************************************************************
// ******
// ****** Common procedures
// ******
// *****************************************************************************
// Divs for waiting icon
document.write('<div class="grey_box"></div>');
document.write('<div class="white_box"></div>');
document.write('<div class="wait_slider" style="display:none"><center><img style="padding-top:8px;" src="/images/loading_32.gif?"></center></div>');
document.write('<div id="_show_dialog" style="display:none;"></div>');

var getResult, execQueryFinish;
var loading_icon="loading_32.gif";
var loading_icon_24="loading_24.gif";
var _info_frames=new Array();
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
	qid='#'+id;
	if ($(qid).length==0 || $('body').css('display')=='none')
		func();
	else
	{
		mask_width=$(qid).innerWidth();
		mask_height=$(qid).innerHeight();
		mask_top=$(qid).offset().top+1;
		mask_left=$(qid).offset().left+1;
		mask_border=$(qid).css('border');
		mask_border_radius=$(qid).css('borderRadius');
		$('.grey_box').css({top: mask_top+'px', left: mask_left+'px', width: mask_width+'px', height: mask_height+'px', opacity: '0.6', border: mask_border, borderRadius: mask_border_radius}).show();
		$('.wait_slider').elementCenter(id).fadeTo(300, '1', func);
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
String.prototype.trim = function () {
	return this.replace(/^\s*|\s*$/, '');
}
// -----------------------------------------------------------------------------
function get_unique_id()
{
	do {
		n=Math.floor(Math.random()*Math.random()*18954785965458745895);
		id='uid_'+Math.floor(Math.random()*Math.random()*18954785965458745895).toString(36).substr(2, 8);
		l=$('#'+id).length;
	} while(l!=0)
	return id;
}
// -----------------------------------------------------------------------------
function info_frame_show(title, html, info_width)
{
	__info_show_id=get_unique_id();
	_info_frames.push(__info_show_id);

	w=$(window).width();
	h=$(window).height();
	hd=$(document).height();
    $("body").append('<div class="info_white_box" id="white_box_'+__info_show_id+'"></div>');
	$('#white_box_'+__info_show_id).css({width: w+'px', height: hd+'px', opacity: '0.7'}).fadeIn();
	if (info_width==null)
	{
		info_width=w*2/3;
		if (info_width>600) info_width=600;
	}
	if (title!='')
		info_html="<h1 id='info_window_header_"+id+"'>"+title+"<div onClick='info_frame_close()'>&nbsp;</div></h1>";
	else
		info_html='';
	h=h*3/4;
	$("body").append('<div id="'+__info_show_id+'" class="info_frame"></div>');
	info_html=info_html+"<div id='info_window_container_"+__info_show_id+"' style='max-height: "+h+"px; overflow-y: auto'>"+html+"</div>";
	$('#'+__info_show_id).html(info_html);
	$('#'+__info_show_id).css('width', info_width+'px');
	$('#'+__info_show_id).screenCenter().fadeIn();
}
// -----------------------------------------------------------------------------
function info_frame_change(title, html, width)
{
	__info_change_id=_info_frames[_info_frames.length-1];
	$('#info_window_container_'+__info_change_id).html(html);
	if (width!=null) $('#'+__info_change_id).css('width', width+'px');
	if(title!='') $("#info_window_header_"+__info_change_id).html(title+"<div onClick='info_frame_close()'>&nbsp;</div>");
	$('#'+__info_change_id).screenCenter();
}
// -----------------------------------------------------------------------------
function info_frame_close()
{
	__info_close_id=_info_frames[_info_frames.length-1];
	_info_frames.pop();
	$('#white_box_'+__info_close_id).fadeOut(function(){ $('#white_box_'+__info_close_id).remove(); });
	$('#'+__info_close_id).fadeOut(function(){ $('#'+__info_close_id).html('').remove(); });
}
// -----------------------------------------------------------------------------
function info_frame_id()
{
	return _info_frames[_info_frames.length-1];
}
// -----------------------------------------------------------------------------
function info_frame_inner_id()
{
	return "info_window_container_"+_info_frames[_info_frames.length-1];
}
// -----------------------------------------------------------------------------
function info_frame_center()
{
	__info_center_id=_info_frames[_info_frames.length-1];
	$('#'+__info_center_id).screenCenter();
}
// -----------------------------------------------------------------------------
function confirm_box(title, html, func)
{
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog( "destroy" );
	$("#_show_dialog").attr("title", title);
	$("#_show_dialog").html(html);
	$("#_show_dialog").dialog({
		modal: true,
		draggable: true,
		resizable: false,
		zIndex: 999999,
		buttons: {
				"Да": function() {
					$( this ).dialog( "close" );
					func();
				},
				"Нет": function() {
					$( this ).dialog( "close" );
				}
			}
	});
}
// -----------------------------------------------------------------------------
function _show_warning(title, text)
{
	$("#_show_dialog:ui-dialog").dialog("close");
	$("#_show_dialog:ui-dialog").dialog( "destroy" );
	$("#_show_dialog").attr("title", title);
	html="\
<img style='float:left' src='/images/warning_icon_48.png'>\
<div style='width: 300px; margin-left: 20px; float:right'>"+text+"</div>\
<br>\
";
	$("#_show_dialog").html(html);
	$("#_show_dialog").dialog({
		modal: true,
		draggable: true,
		resizable: false,
		width: 400,
		dialogClass: "_info_dialogs",
		buttons: {
				"Ok": function() {
						$( this ).dialog( "close" );
					}
		}
	});
}
// -----------------------------------------------------------------------------
function isValidEmail (email)
{
	return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}
// -----------------------------------------------------------------------------
function input_check_number(event)
{
	event = event || window.event;
	target = event.target || event.srcElement;
	if(window.event) key = window.event.keyCode; //IE
	else key = event.which; //firefox
	if (key<32) return true;
	if (key>=48 && key<=57) return true;
	if ((key==44 || key==46) && $(target).val().indexOf(String.fromCharCode(key))==-1) return true;   // запятая и точка
	return false;
}
// -----------------------------------------------------------------------------
function num_to_text(num)
{
	var i = 0, type = ['Bytes','Kb','Mb','Gb','Tb','Pb'];
	while((num / 1000 | 0) && i < type.length - 1) {
		num /= 1024;
		i++;
	}
	return num.toFixed(2) + ' ' + type[i];
}
// -----------------------------------------------------------------------------
var md5unicode=new function(){
	var l='length',
	h=[
		'0123456789abcdef',0x0F,0x80,0xFFFF,
		0x67452301,0xEFCDAB89,0x98BADCFE,0x10325476
	],
	x=[
		[0,1,[7,12,17,22]],
		[1,5,[5, 9,14,20]],
		[5,3,[4,11,16,23]],
		[0,7,[6,10,15,21]]
	],
	A=function(x,y,z){
		return(((x>>16)+(y>>16)+((z=(x&h[3])+(y&h[3]))>>16))<<16)|(z&h[3])
	},
	B=function(s){
		var n=((s[l]+8)>>6)+1,b=new Array(1+n*16).join('0').split('');
		for(var i=0;i<s[l];i++)b[i>>2]|=s.charCodeAt(i)<<((i%4)*8);
		return(b[i>>2]|=h[2]<<((i%4)*8),b[n*16-2]=s[l]*8,b)
	},
	R=function(n,c){return(n<<c)|(n>>>(32-c))},
	C=function(q,a,b,x,s,t){return A(R(A(A(a,q),A(x,t)),s),b)},
	F=function(a,b,c,d,x,s,t){return C((b&c)|((~b)&d),a,b,x,s,t)},
	G=function(a,b,c,d,x,s,t){return C((b&d)|(c&(~d)),a,b,x,s,t)},
	H=function(a,b,c,d,x,s,t){return C(b^c^d,a,b,x,s,t)},
	I=function(a,b,c,d,x,s,t){return C(c^(b|(~d)),a,b,x,s,t)},
	_=[F,G,H,I],
	S=(function(){
		with(Math)for(var i=0,a=[],x=pow(2,32);i<64;a[i]=floor(abs(sin(++i))*x));
		return a
	})(),
	X=function (n){
		for(var j=0,s='';j<4;j++)
			s+=h[0].charAt((n>>(j*8+4))&h[1])+h[0].charAt((n>>(j*8))&h[1]);
		return s
	};
	return function(s){
		var $=B(''+s),a=[0,1,2,3],b=[0,3,2,1],v=[h[4],h[5],h[6],h[7]];
		for(var i,j,k,N=0,J=0,o=[].concat(v);N<$[l];N+=16,o=[].concat(v),J=0){
			for(i=0;i<4;i++)
				for(j=0;j<4;j++)
					for(k=0;k<4;k++,a.unshift(a.pop()))
						v[b[k]]=_[i](
							v[a[0]],
							v[a[1]],
							v[a[2]],
							v[a[3]],
							$[N+(((j*4+k)*x[i][1]+x[i][0])%16)],
							x[i][2][k],
							S[J++]
						);
		for(i=0;i<4;i++)
			v[i]=A(v[i],o[i]);
		};
	return X(v[0])+X(v[1])+X(v[2])+X(v[3]);
}};
// -----------------------------------------------------------------------------
var trans = [];
for (var i = 0x410; i <= 0x44F; i++)
trans[i] = i - 0x350; // А-Яа-я
trans[0x401] = 0xA8; // Ё
trans[0x451] = 0xB8; // ё
function md5(str)
{
	var ret = [];
	for (var i = 0; i < str.length; i++)
	{
		var n = str.charCodeAt(i);
		if (typeof trans[n] != 'undefined')
		n = trans[n];
		if (n <= 0xFF)
		ret.push(n);
	}
	return md5unicode(String.fromCharCode.apply(null, ret));
}
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// image loader procedure
// -----------------------------------------------------------------------------
var _swfu, _uploaded_files=new Array();
// -----------------------------------------------------------------------------
function _load_image(func, file_mask, uniqname)
{
	delete _swfu;
	html="\
<div id='_loader_button_holder'></div>\
<div id='_loader_progress_block'>\
</div>\
";
    info_frame_show('Загрузка файла', html, 302);
	_swfu = new SWFUpload(
		{	upload_url : "/uploader/upload.php",
			flash_url : "/uploader/swfupload.swf",
			file_size_limit : "10 MB",

			file_types : file_mask,
			file_types_description: "Files",
			file_upload_limit : 1,

			button_placeholder_id : "_loader_button_holder",
			button_image_url : "/images/upload_button.png",
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
			file_queued_handler : _uploader_fileQueued,
			file_queue_error_handler : _uploader_fileQueueError,
//			file_dialog_complete_handler : fileDialogComplete,
			upload_start_handler : _uploader_uploadStart,
			upload_progress_handler : _uploader_uploadProgress,
			upload_error_handler : _uploader_uploadError,
//			upload_success_handler : upload_success_function,
			upload_complete_handler : _uploader_uploadComplete,
//			debug_handler : debug_function,
//			debug: true,

			custom_settings: {
				complete_func: func,
				uname: '',
			}
		}
	);
	if (uniqname)
	{
		rnd=Math.random()*1234567890+(new Date).getTime();
		rnd=md5(rnd.toString());
		_swfu.customSettings.uname=rnd;
	}
}
// -----------------------------------------------------------------------------
function _uploader_fileQueued(file)
{
   	$("#_loader_progress_block").html("\
<div class='_image_upload_progres_bar' id='_loader_progress_bar'></div>\
<span id='_loader_progress_text'></span>\
");
	uname=_swfu.customSettings.uname;
	if (uname=='')
	{
		_swfu.customSettings.uname=file.name;
		_swfu.addFileParam(file.id, "upload_file_real_name", file.name);
	}
	else
	{
		pos=file.name.lastIndexOf('.');
		if (pos!=-1)
		{
			ext=file.name.substring(pos);
			uname=uname+ext;
			_swfu.customSettings.uname=uname;
		}
		_swfu.addFileParam(file.id, "upload_file_real_name", uname);
	}
	_swfu.startUpload();
}
// -----------------------------------------------------------------------------
function debug_function(message)
{
	alert(message);
}
// -----------------------------------------------------------------------------
function _uploader_fileQueueError()
{}
function _uploader_uploadStart()
{}
// -----------------------------------------------------------------------------
function _uploader_uploadProgress(file, bytes, total)
{
	w100=300;
	w=parseInt(w100*bytes/total);
	if (bytes==total) w=w100;
    $("#_loader_progress_bar").css({"width": w+"px"});
    $("#_loader_progress_text").html(num_to_text(bytes)+" из "+num_to_text(total));
}
// -----------------------------------------------------------------------------
function _uploader_uploadError(file, error, message)
{
	alert(message);
}
// -----------------------------------------------------------------------------
function _uploader_uploadComplete(file)
{
	complete_function=_swfu.customSettings.complete_func;
    $("#_loader_progress_block").after("<p>Идет обработка загруженного файла ...</p>");
	complete_function(_swfu.customSettings.uname);
	_swfu.destroy();
}
// -----------------------------------------------------------------------------
