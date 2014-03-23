// -----------------------------------------------------------------------------
var banners_widget_ajax_manager_url='{@widgets_url@}/{@widget_path@}/{@widget_id@}_widget_dm.php';
var common_widget_ajax_manager_url='{@widgets_url@}/common_widget_dm.php';
// -----------------------------------------------------------------------------
function banners_language_changed()
{
	banners_show_banners_list();
}
// -----------------------------------------------------------------------------
function banners_show_banners_list()
{
	lang=$("#banners_language").val();
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersGetBannersListHtml", language: lang});
	if (res!='') $("#banners_banners_list").html(res);
}
// -----------------------------------------------------------------------------
function banners_banner_add()
{
	admin_info_show('Добавление нового изображения', '<center><img src="images/'+loading_icon+'"></center>', {@banners_image_sx@}+40);
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersGetBannerAddHtml"});
	admin_info_change('', res);
	$("#banners_image_img").load(function(){ admin_info_center(); });
}
// -----------------------------------------------------------------------------
function banners_banner_add_cancel()
{
	admin_info_close();
}
// -----------------------------------------------------------------------------
function banners_banner_add_image_load()
{
	admin_load_image(banners_banner_add_image_uploaded, "*.jpg;*.png");
}
// -----------------------------------------------------------------------------
function banners_banner_add_image_uploaded(file)
{
	res=execQuery(common_widget_ajax_manager_url, {section : "commonTempImageProcess", file : file, sx : {@banners_image_sx@}, sy : {@banners_image_sy@}, max: 1});
	html="\
<div class='banners_banner_add_crop_container'>\
<img src='"+res+"' id='objects_croped_image' /><br>\
<input type='button' value='Обработать изображение' onClick='banners_banner_add_store_image()' />\
<input type='hidden' id='banners_add_crop_file' value='"+res+"'/>\
<input type='hidden' id='banners_add_image_coor_x' />\
<input type='hidden' id='banners_add_image_coor_y' />\
<input type='hidden' id='banners_add_image_coor_w' />\
<input type='hidden' id='banners_add_image_coor_h' />\
<div>\
";
	admin_info_show('Изменение размеров изображения', html, {@banners_image_sx@}+40);
	$("#objects_croped_image").load(function(){
		admin_info_center();
		w=$("#objects_croped_image").width();
		h=$("#objects_croped_image").height();
		dx=w/{@banners_image_sx@};
		sx={@banners_image_sx@};
		sy={@banners_image_sy@}*dx;
		y=(h-sy)/2;
		$('#objects_croped_image').Jcrop({ allowSelect: false, allowResize: false, allowMove: true, setSelect: [0, y, w, y+sy], onChange: banners_banner_add_update_coords });
	});
}
// -----------------------------------------------------------------------------
function banners_banner_add_update_coords(c)
{
	$("#banners_add_image_coor_x").val(c.x);
	$("#banners_add_image_coor_y").val(c.y);
	$("#banners_add_image_coor_w").val(c.w);
	$("#banners_add_image_coor_h").val(c.h);
};
// -----------------------------------------------------------------------------
function banners_banner_add_store_image()
{
	x=$("#banners_add_image_coor_x").val();
	y=$("#banners_add_image_coor_y").val();
	w=$("#banners_add_image_coor_w").val();
	h=$("#banners_add_image_coor_h").val();
	file=$("#banners_add_crop_file").val();
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersCropImage", file: file, x:x, y:y, w:w, h:h});
	res=eval('(' + res + ')');
	$("#banners_add_banner_image").attr('src', res.img);
	$("#banners_add_banner_image_file").val(res.file);
	admin_info_close();
}
// -----------------------------------------------------------------------------
function banners_banner_add_save()
{
	lang=$("#banners_language").val();
	file=$("#banners_add_banner_image_file").val();
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersAddBannerSave", language: lang, file: file});
	admin_info_close();
	$("#banners_banners_list").html(res);
}
// -----------------------------------------------------------------------------
function banners_banner_delete(banner_id)
{
	lang=$("#banners_language").val();
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersDeleteBanner", language: lang, id: banner_id});
	$("#banners_banners_list").html(res);
}
// -----------------------------------------------------------------------------
