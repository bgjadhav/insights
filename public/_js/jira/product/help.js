var productHelp = {

	options: {
		//section : $('#roadmap')
	},

	events: function() {

		var self = this;

		$("#help").click(function() {
			self.track('1');


		});

	},

	this_tab: function(){
		var self = this;

		if ($('#main').hasClass('requests')) {
			return 'requests';

		} else {
			return self.roadmapOrCandidatesList();
		}
	},

	roadmapOrCandidatesList: function()
	{
		if ($('#roadmap').find('#lroadmap').hasClass('activate')) {
			return 'roadmap';
		} else {
			return 'candidate';
		}

	},

	track: function(success) {
		var data = {};
		var self = this;

		data.data = 'help';

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


	},

	init: function() {
		this.events();


	}

};


$(document).ready(function() {
	productHelp.init();
	$('#options').show();
});
