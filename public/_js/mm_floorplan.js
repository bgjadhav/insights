// Variables storing data and settings for the tags
var data = [];
var settings = [];

// Attach Taggd stuff to the image
$(window).load(function() {
	$('.taggd').each(function(i, e) {
	var $e = $(e);
	$e.taggd(settings[i]);
	$e.taggd('items', data[i])
	});
});

// Tags data (coordinates and others)
var temp_data = [];
for (index = 0; index < people.length; ++index) {
    temp_data[index] = people[index].popup;
}

// Mostly styling for the tags
data.push(temp_data);

var timer;
function scroll() {
	timer = setTimeout(function() {
		clearTimeout(timer);
		// set max height of sidebar
		var height = $('.taggd-wrapper').height();
		var searchHeight = $('.search').outerHeight(true);
		$('.sidebar_ul').css('max-height', height - searchHeight);
	},50);
}
$(window).resize(function() {
	scroll();
});

 // Show modal on desk click
$(document).on('click','.taggd-item',function(){
	if ($(this).data( "target" )) {
		var name = $(this).data( "target" );
		$('section.modal').not($(name)).removeClass('visible');
		setTimeout(function() {
			$('section.modal').not($(name)).hide();
		},300);
		
		setTimeout(function() {
			$(name).addClass('visible');
			$(name).fadeIn("fast");
		},20);
		var src = $(name).find('.image img').data('src');
		if(src) {
			$(name).find('.image img').removeData('src');
			$(name).find('.image img').attr('src', src);
		}
	} else {

		window.location= "./people/edit/" + $(this).data( "user_id" );
	}
});

// Hide the modal when user clicks outside it
$(document).mouseup(function (e)
{
	// if a modal exists
	if ($(".visible")[0]){
	    var container = $(".visible");
	    if (!container.is(e.target) // if the target of the click isn't the container...
	        && container.has(e.target).length === 0) // ... nor a descendant of the container
	    {
	        container.fadeOut("fast");
	    }
	} else {
	    // Do something if class does not exist
	}
    
});


$( document ).ready(function() {
	
	$('body').on('mouseover', '.taggd-item', function() {
		var offset = $(this).position();
		var width = $(this).outerWidth();
		$(this).next('span.taggd-item-hover').css({
			top: offset.top -134,
			left: offset.left - ((138 - width) / 2)
		}).addClass('active');
	}).on('mouseleave', '.taggd-item', function() {
		$(this).next('span').removeClass('active');
	});
	
    $("#list_sidebar li a").hover(function () {
		var name = "#" + $(this).data( "sidebar-name" );
		$('[data-target=' + name + ']').toggleClass("active"); 
 	});

 	$("#list_sidebar li a").click(function (e) {
		e.preventDefault();
		var name = "#" + $(this).data( "sidebar-name" );
		$('section.modal').not($(name)).removeClass('visible');
		setTimeout(function() {
			$('section.modal').not($(name)).hide();
		},300);
		setTimeout(function() {
			$(name).addClass('visible');
			$(name).fadeIn("fast");
		},20);
		var src = $(name).find('.image img').data('src');
		if(src) {
			$(name).find('.image img').removeData('src');
			$(name).find('.image img').attr('src', src);
		}
 	});
	
	// This part handles the initial resize
	timer = setTimeout(function() {
		clearTimeout(timer);
		// set max height of sidebar
		var height = $('#floorplan').height();
		var searchHeight = $('.search').outerHeight(true);
		$('.sidebar_ul').css('max-height', height - searchHeight);
	},50);

	// sidebar search
	$('#list_sidebar').on('keyup', '.search', function() {
		var value = $(this).val();
		value = value.toLowerCase();
		$('#list_sidebar li a').each(function() {
			var name = $(this).data('sidebar-name');
			name = name.replace('_',' ');
			if(name.indexOf(value) > -1) {
				$(this).show();	
			} else {
				$(this).hide();	
			}
		});
		scroll();
	});

	$('.modal').on('click', '.close', function(e) {
		e.preventDefault();
		$(this).parent().fadeOut("fast");
		var thisModal = $(this).parent();
		thisModal.removeClass('visible');
		setTimeout(function() {
			thisModal.hide();
		},300);
	});
});
