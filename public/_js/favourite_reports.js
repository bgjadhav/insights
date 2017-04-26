var Favourites = {
	events : function() {
		var self = this;
		$(".favourite-button").click(function() {
			if(!$(this).hasClass("working"))
			{
				$(this).addClass("working");
				//$(this).text("...");
				var favourite_title = $(this).data('favname');
				//alert(favourite_title);
				var thisObj = $(this);

				if($(this).hasClass("added"))
				{
					self.remove(favourite_title, thisObj);
				}
				else
				{
					$(this).addClass("added");
					self.add(favourite_title, thisObj);
				}

			}

		})
	},
	add	: function(fav_name, obj) {
			$.ajax({
					type: 'POST',
					url: insights_base_path+'/favourites/reports/addReport',
					data: 'fav_name='+fav_name,
				}).done(function(data){
					if(data.success)
					{
						//obj.text("---");
					}
					obj.removeClass("working");
				});

		},
	remove : function(fav_name, obj) {
			$.ajax({
					type: 'POST',
					url: insights_base_path+'/favourites/reports/removeReport',
					data: 'fav_name='+fav_name,
				}).done(function(data){
					if(data.success)
					{
						//obj.text("+++");
						obj.removeClass("added");
					}
					obj.removeClass("working");
				});

		},	init : function() {
			//alert(insights_path);
		this.events();
	}
};


$( document ).ready(function() {
	Favourites.init();
});
