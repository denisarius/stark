// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
jQuery.fn.imagecbox_set = function(state){
	return this.each(function(){
    	id="#"+$(this).attr("id");
		$(id).attr("checked", state);
		if ($(id).is(":checked"))
			set_image($(id), $(this).data("checked_image"));
		else
			set_image($(id), $(this).data("unchecked_image"));

	});
// -----------------------------------------------------------------------------
	function set_image(checkbox, image)
	{
		id="#"+checkbox.attr("id")+"_img";
		$(id).attr("src", image);
	}

};
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------

jQuery.fn.imagecbox = function(options){
	return this.each(function(){
		if ($(this).data("base_image")!==undefined) return;
		if ($(this).attr("id")===undefined)
		{
			do {
            	id=Math.floor(Math.random()*1024*1024);
			} while ($("#icb_id_"+id).length!=0);
            $(this).attr("id", "icb_id_"+id);
		}
		$(this).data("parent_event", options.track_parent);
		bi=options.image.replace(/\//g, '__').replace(/\./g, '_');
		$(this).data("base_image", bi);
		if (!document.getElementById("__image_check_box_checked_image_"+bi))
		{
			$("body").append("<img style='display:none;' id='__image_check_box_checked_image_"+bi+"' />");
			$("body").append("<img style='display:none;' id='__image_check_box_unchecked_image_"+bi+"' />");
			$("body").append("<img style='display:none;' id='__image_check_box_over_image_"+bi+"' />");
		}
	    p=options.image.lastIndexOf(".");
	    iName=options.image.substr(0, p);
	    iExt=options.image.substr(p, options.image.legth);
	    ci=iName+"_checked"+iExt;
	    uci=iName+"_unchecked"+iExt;
	    oi=iName+"_over"+iExt;
		$(this).data("checked_image", ci);
		$(this).data("unchecked_image", uci);
		$(this).data("over_image", oi);
		cin="#__image_check_box_checked_image_"+bi;
		ucin="#__image_check_box_unchecked_image_"+bi;
		oin="#__image_check_box_over_image_"+bi;
		$(this).data("checked_image_name", cin);
		$(this).data("unchecked_image_name", ucin);
		$(this).data("over_image_name", oin);
	    $(cin).attr('src',ci);
	    $(ucin).attr('src',uci);
	    $(oin).attr('src',oi);
	    init_image_checkbox($(this));
	});
// -----------------------------------------------------------------------------
	function init_image_checkbox(cb)
	{
		i_id=cb.attr("id")+"_img";
		image_id="#"+i_id;
		if (!document.getElementById(cb.attr("id")+"_img"))
		{
		    cb.css("display", "none");
	        if (!cb.is(":checked"))
	            cb.after("<img src='"+cb.data("unchecked_image")+"' id='"+i_id+"' class='_jquery_check_box_image' style='vertical-align: text-bottom;'>");
	        else
	            cb.after("<img src='"+cb.data("checked_image")+"' id='"+i_id+"' class='_jquery_check_box_image' style='vertical-align: text-bottom;'>");
			$(image_id).mousedown(_mousedown);
			$(image_id).mouseenter(_mouseenter);
			$(image_id).mouseleave(_mouseleave);
			if(cb.data("parent_event") && cb.parent() && cb.parent()[0].nodeName!='BODY')
			{
				cb.parent().mousedown(_parentmousedown);
				cb.parent().mouseenter(_parentmouseenter);
				cb.parent().mouseleave(_parentmouseleave);
			}
		}
		else
		{
	        if (cb.is(":checked"))
	            $(image_id).attr("src", cb.data("checked_image"));
	        else
	            $(image_id).attr("src", cb.data("unchecked_image"));
		}
	};
// -----------------------------------------------------------------------------

	function _mousedown(event)
	{
        id="#"+$(this).attr("id");
		id=id.substring(0, id.length-4);
		over_image_name=$(id).data("over_image_name");
        if ($(id).is(":checked"))
        {
			if ($(over_image_name).attr("height") && $(over_image_name).attr("width"))
	            $(this).attr("src", $(id).data("over_image"));
			else
	            $(this).attr("src", $(id).data("unchecked_image"));
        }
        else
            $(this).attr("src", $(id).data("checked_image"));
		if ($(id).attr('onclick'))
			$(id).click();
		else
			$(id).attr("checked", !$(id).attr("checked"));
		event.stopPropagation();
	};

	function _parentmousedown(event)
	{
		$(this).children("input[type=checkbox]").each(function() {
			over_image_name=$(this).data("over_image_name");
	        id="#"+$(this).attr("id");
			image_id=id+"_img";
	        if ($(id).is(":checked"))
	        {
				if ($(over_image_name).attr("height") && $(over_image_name).attr("width"))
		            $(image_id).attr("src", $(this).data("over_image"));
				else
		            $(image_id).attr("src", $(this).data("unchecked_image"));
	        }
	        else
	            $(image_id).attr("src", $(this).data("checked_image"));
			if ($(id).attr('onclick'))
				$(id).click();
			else
				$(id).attr("checked", !$(id).attr("checked"));
		})
		event.stopPropagation();
	};

// -----------------------------------------------------------------------------

	function _mouseenter()
	{
        id="#"+$(this).attr("id");
		id=id.substring(0, id.length-4);
		over_image_name=$(id).data("over_image_name");
		if ($(over_image_name).attr("height") && $(over_image_name).attr("width"))
		{
	        if (!$(id).is(":checked"))
	            $(this).attr("src", $(id).data("over_image"));
		}
	};

	function _parentmouseenter()
	{
		$(this).children("input[type=checkbox]").each(function() {
			over_image_name=$(this).data("over_image_name");
			if ($(over_image_name).attr("height") && $(over_image_name).attr("width"))
			{
		        id="#"+$(this).attr("id");
		        if (!$(id).is(":checked"))
		            $(id+"_img").attr("src", $(this).data("over_image"));
			}
		});
	};

// -----------------------------------------------------------------------------

	function _mouseleave()
	{
        id="#"+$(this).attr("id");
		id=id.substring(0, id.length-4);
		over_image_name=$(id).data("over_image_name");
		if ($(over_image_name).attr("height") && $(over_image_name).attr("width"))
		{
        	if (!$(id).is(":checked"))
            	$(this).attr("src", $(id).data("unchecked_image"));
		}
	};

	function _parentmouseleave()
	{
		$(this).children("input[type=checkbox]").each(function() {
			over_image_name=$(this).data("over_image_name");
			if ($(over_image_name).attr("height") && $(over_image_name).attr("width"))
			{
		        id="#"+$(this).attr("id");
	        	if (!$(id).is(":checked"))
	            	$(id+"_img").attr("src", $(this).data("unchecked_image"));
			}
		});
	};

// -----------------------------------------------------------------------------
};