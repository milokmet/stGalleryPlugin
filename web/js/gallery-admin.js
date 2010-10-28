(function($){
	
$.fn.galleryAdmin = function(options) {
    var opts = $.extend({}, $.fn.galleryAdmin.defaults, options);
    return this.each(function(){
        return new $.stGalleryAdmin(this, opts);
    });
}

$.stGalleryAdmin = function (list, options) {
    
	this.list = list;
	this.options = options;
	var _stGalleryAdmin = this;
	
	$('li.picture_box', list).live('mouseover mouseout', function(event){
		if (!$(this).find('.hidden_controls').is(':visible')) {
		  if (event.type == 'mouseover') {
			$(this).addClass('hover');
		  } else {
			$(this).removeClass('hover');
		  }
		}
	});
	
	$('ul.picture_actions .edit', list).live('click', function(event){
		$(this).parents('li.picture_box').find('.controls').toggle();
	});
	
	$('ul.picture_actions .delete a', list).live('click', function(event){
		
		if ($.data(document.body, 'st_gallery_deleted') == null) {
			$.data(document.body, 'st_gallery_deleted', 0);
		}

		if (parseInt($.data(document.body, 'st_gallery_deleted')) > 3 || confirm(stGalleryAdminMessages.are_you_sure)) {
			var pt = $(this).parents('li.picture_box');
			$.get($(this).attr('href'), function(){
				pt.fadeOut(600, function(){
					$(this).remove();
				});
				
				$.data(document.body, 'st_gallery_deleted', parseInt($.data(document.body, 'st_gallery_deleted')) + 1);
			});
		}
		return false;
	});
	
    // updatovanie obrázku
	$('.controls input, .hidden_controls .input, .hidden_controls.select', list).live('change', function(){
		var data = {};
		var box = $(this).parents('.picture_box');
		$('input, select', box).each(function(){
			data[$(this).attr('name')] = $(this).val();
		});
		
		url = options.update.replace('--PICTURE--', data.id);
		
		// delete data.id;
		
		$.post(url, data, function(response){
			_stGalleryAdmin.loadPicture(response);
		});
	}); // /updatovanie obrázku
	
	var submitOptions = {
		_stGalleryAdmin: this,
		dataType: 'json',
		success: function (response, status) {
			if (typeof(response.error) !== 'undefined') {
				// TODO: zobrazovať korektne chyby
				alert(response.error);
				$('#st_gallery_upload_form input[name=files]').val('');
			}

			if (response['pictures'].length > 0) {
				for (i in response['pictures']) {
					$(list).append('<li class="picture_box unpublished" id="picture_box_'+response['pictures'][i]+'"></li>');
					_stGalleryAdmin.loadPicture(response['pictures'][i]);
				}
				$('#st_gallery_upload_form input[name=files]').val('');
			}
		}
	}
	
	
	$('#st_gallery_upload_form input[name=files]').change(function(){
		$('#st_gallery_upload_form').ajaxSubmit(submitOptions);
	});
	
	
	this.loadPicture = function(id) {
		$('#picture_box_'+id).addClass('loading');
		$('#picture_box_'+id).load(this.options.load.replace('--PICTURE--', id), function(){
			$(this).removeClass('loading');
		});
	}
	
	// save publish form
	$('#'+this.options.publish).submit(function(){
		var ids = [];
		$('.picture_box input[name=id]').each(function(){
			ids[ids.length] = $(this).val();
		});
		
		$('input[name=pictures]', this).val(ids.join(':'));
		
		return true;
	});
	
	// sortable images
	$('ul#st_gallery_pictures').sortable({ handle:'.move' });
	// / sortable images
	
	// fixed upload form
	var uploadFormTop = $('#st_gallery_upload_form').offset().top;
	var uploadFormWidth = $('#st_gallery_upload_form').css('width');
	function fixedUploadForm(top, width, y) {
		if (y >= top) {
			$('#st_gallery_upload_form').addClass('st_gallery_upload_form_fixed');
			$('#st_gallery_upload_form').css('width', width);
		} else {
			$('#st_gallery_upload_form').removeClass('st_gallery_upload_form_fixed');
		}
	}
	
	fixedUploadForm(uploadFormTop, uploadFormWidth, $(window).scrollTop());
	$(window).scroll(function (event){
		fixedUploadForm(uploadFormTop, uploadFormWidth, $(this).scrollTop());
	});
	
	// / fixed upload form
	
}; // $.stGalleryAdmin
	
$.fn.galleryAdmin.defaults = {};

})(jQuery);