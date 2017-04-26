(function ($) {
	var config = {
		offsety : -148,
		offsetx : 0,
		class : false
	}
	
	$.fn.fooltips = function (options) {
		options = $.extend({}, config, options);

		if($('#fooltips').length === 0) {
			$('body').append('<div id="fooltips" class="fooltips"><span class="icon"></span><p></p><div class="bottom"></div></div>');
		}

		var fooltip = $('#fooltips');
		
		return this.each(function () {
			var text = $(this).data('title');
			
			// on mouse over
			$(this).on('mouseover', function() {
				fooltip.removeClass().addClass('fooltips');
				if(options.class) {
					fooltip.addClass(options.class);
				}
				
				var width = fooltip.width();
				var height = fooltip.height();
				var top = $(this).offset().top + options.offsety;
				var left = $(this).offset().left - width + options.offsetx;
				if(left < 0) {
					left = $(this).offset().left + options.offsetx + $(this).width();
					fooltip.addClass('reverse');
				} else {
					fooltip.removeClass('reverse');
				}
				fooltip.find('p').text(text);
				fooltip.show();
				fooltip.css({
					top: top,
					left: left
				});
				fooltip.stop(true,false).animate({
					opacity: 1
				});
			});
			
			// on mouse out
			$(this).on('mouseout', function() {
				fooltip.removeClass('fooltip').stop(true,false).animate({
					opacity: 0
				}, function() {
					fooltip.hide();
				});
			});
		});
	};
	
}(jQuery));
