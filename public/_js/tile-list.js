var TileList = function() {
	var private = {
		myCount: 0
	}
	return {

		event : function() {
			var self = this;
			$(window).resize(function() {
				self.resizeTiles();
			});
		},

		resizeTiles : function() {
			var minH = 93;
			$('.tile-li').css('min-height', minH);
			var self = this;
			var list = $('#tile-list').children();

			$.each(list, function(idLi, li) {
				var min = $('#tile-li-'+idLi).css('min-height').replace('px','');
				if (minH < min) {
					minH = min;
				}
				var height = $('#tile-li-'+idLi).height()+40;
				if (height > minH) {
					minH = height;
				}
			});
			$('.tile-li').css('min-height', minH);
			return false;
		},

		init : function() {
			this.resizeTiles();
			this.event();
			$(window).focus(function(){
				$(window).resize();
			});
			$(window).blur(function(){
				$(window).resize();
			});
			$(window).bind(function(){
				$(window).resize();
			});
		}
	}
};
