var productAdd = {

	options: {
		//section : $('#roadmap')
	},

	events: function() {

		var self = this;

		$("#AddRequest").click(function() {
			self.track('1');
		});

	},

	this_tab: function(){
		return 'requests';
	},

	track: function(success) {
		var data = {};
		var self = this;

		data.data = 'make_request';

		data.success = success;

		$.ajax({
			type: 'POST',
			url:insights_base_path+'/analytics/click/'+self.this_tab()+'/send-track-props',
			data: data,
			cache: false,
			success: function(json) {},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(XMLHttpRequest.responseText);
			}
		});

		return false;
	},

	init: function() {
		this.events();
	}

};


$(document).ready(function() {
	productAdd.init();
	$('#options').show();
});
