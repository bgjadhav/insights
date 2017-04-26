var productShare = {

	options: {
		sharing : '',
		sent : false
	},

	events: function() {

		var self = this;

		$("#share-general").click(function() {

			if (self.options.sent == false ) {

				self.options.sharing = 'share_filtered';

				self.track('1');

				self.options.sharing = '';

				self.options.sent = true;
			}

		});

		$(document).mousedown(function (e) {

			if (self.options.sent == true && !$('[id^="share"]').is(e.target) && !$('#helper-share').is(e.target) && $('#share-cover-box').is(':visible')) {
				self.options.sent = false;
			}

		});

	},

	this_tab: function() {
		var self = this;

		if ($('#main').hasClass('requests')) {
			return 'requests';

		} else {
			return self.roadmapOrCandidatesList();
		}
	},

	roadmapOrCandidatesList: function() {
		if ($('#roadmap').find('#lroadmap').hasClass('activate')) {
			return 'roadmap';
		} else {
			return 'candidate';
		}

	},

	track: function(success) {
		var data = {};
		var self = this;

		data.data = self.options.sharing+'&tids='+self.tids();

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

	tids: function() {
		var tids = [];
		$("#roadmap").find('[id^="row_"]').each(function(){
			var tid = this.id;
			tids.push(tid.replace('row_', ''));
		});
		return tids.join(",");
	},

	init: function() {
		this.options.sent = false;
		this.events();
	}

};


$(document).ready(function() {
	productShare.init();
	$('#options').show();
});
