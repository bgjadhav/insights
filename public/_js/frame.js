$(function() {
	var triangleFirst = true;
	$('#nav-sub').css('width',0);
	$('#nav-sub').addClass('loaded');

	function positionNav() {
		$('#nav-main li').each(function() {
			var top = $(this).position().top;
			var thisClass = $(this).attr('class').replace(' active', '');
			$('#nav-sub .' + thisClass + '-sub').css('top', top);
		});
	}
	positionNav();

	$('#nav-main li').click(function() {


		if ($(this).hasClass('toLink') === false) {

			var top = $(this).position().top;
			if(triangleFirst) {
				triangleFirst = false;
				$('#nav-sub .triangle').css('top', top + 40);
			}

			if($(this).hasClass('active')) {
				$('#nav-main li').removeClass('active');
				$('#nav-sub').stop(true,false).animate({
					width: 0
				},300,function() {
					$('#nav-sub').hide();
				});
				$('#content').stop(true,false).animate({
					marginLeft : 87
				}, {
					step : function() {
						$(window).resize();
					}
				});
				$('#nav-sub .triangle').animate({
					top : top + 40,
					left : -30
				}, 200);
				triangleFirst = true;
			} else {
				var width = 296;
				$('#nav-sub').stop(true,false).show().animate({
					width: width
				}, {
					step : function() {
						$(window).resize();
					}
				});
				var thisClass = $(this).attr('class');
				$('#nav-main li').not($(this)).removeClass('active');
				$(this).addClass('active');
				$('#nav-sub .sidebar-sub').hide();
				$('#nav-sub .' + thisClass + '-sub').show();
				$('#content').stop(true,false).animate({
					marginLeft : 87 + width
				});
				$('#nav-sub .triangle').animate({
					top : top + 40,
					left : 0
				}, 200);
			}
			positionNav();
		} else {
			$('#nav-main li').removeClass('active');
			$(this).addClass('focusC');
		}

	});

	$(window).resize(function() {
		positionNav();
	});

	$('#warning-top .close').click(function() {
		$('#warning-top .close').remove();
		$.post('/set_dialog', {
			type : 1
		}, function() {
			$('#warning-top').fadeOut();
			$('body').removeClass('warning');
		});
	});

	$('#floor_select').click(function() {
		$(this).find('div').slideToggle();
	});
});
