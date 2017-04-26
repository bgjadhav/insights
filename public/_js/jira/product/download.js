var productDownload = {

	options: {
		section : $('#roadmap'),
		continueDownload : false,
		filteredDownload : 'current'
	},

	events: function() {

		var self = this;

		$("#downloadData").click(function() {
			var tmpDownload = self.options.filteredDownload;
			self.options.filteredDownload = 'open';
			self.showOptionsDownload();
			self.track(1);
			self.options.filteredDownload = tmpDownload;


		});

		$('#close-export').click(function() {
			self.hideOptionsDownload();


		});

		$('#roadmap').on('click', '#cancelDownload', function() {
			self.changeStatusDownload(false);
			self.clearMesssage();


		});

		$('#roadmap').on('click', '#okMessage', function() {
			self.changeStatusDownload(false);
			self.clearMesssage();


		});

		$('#appliedFilters').on('click', function() {
			self.hideOptionsDownload();
			self.clearMesssage();
			self.download('filtered');


		});

		$('#fullRoadmap').on('click', function() {
			self.hideOptionsDownload();
			self.clearMesssage();
			self.download('current');


		});

		$(document).mouseup(function(e) {
			self.hideDownloadUp(e);


		});

		$(document).mousedown(function(e) {
			self.hideDownloadDown(e);


		});

	},

	hideDownloadDown: function(e) {
		var cover = $("#export-cover-box");
		var general = $("#downloadData");
		if (!cover.is(e.target) && !general.is(e.target) && cover.has(e.target).length === 0) {
			this.hideOptionsDownload();
		}


	},

	hideDownloadUp: function(e) {
		var container = $("#export-cover-box");
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			this.hideOptionsDownload();
		}


	},

	showOptionsDownload: function(data) {
		$("#export-top").css("display", "block");
		$("#export-cover-box").css("display", "block");


	},

	hideOptionsDownload: function() {
		$("#export-top").hide();
		$("#export-cover-box").css("display", "none");


	},

	changeStatusDownload: function(val) {
		this.options.continueDownload = val;


	},

	changeFilteredOption: function(filteredOption) {
		this.options.filteredDownload = filteredOption;


	},

	download: function(filteredOption) {

		var self = this;

		self.changeFilteredOption(filteredOption);
		self.changeStatusDownload(true);
		self.showWait();

		$.ajax({
			type: 'POST',
			url: insights_base_path+'/product/roadmap/download',
			data: self.getOptExport(self.options.filteredDownload)
		}).done(function(data) {
			if (data[0].success)
			{
				self.analizeStatus(data[0]);
			} else {
				self.changeStatusDownload(false);
				self.showError();
				self.track('0');
			}
		});


	},

	showWait: function(data) {

		this.showMessage({
			'title': 'Woah, Hold Your Horses...',
			'message': '<div id="messagePopUp">Exporting your data, in some cases this may take a few minutes...</div>',
			'extras': '<span id="cancelDownload">Cancel</span>',
			'img': '<img id="wait-blue" src="'+insights_base_path+'/_img/loading-blue.gif" />',
			'visibility': 'visible'
		});


	},

	getOptExport: function(filteredOption) {

		var filters = $("#roadmap").find('#filters');

		var data = {
			'download' : filteredOption,
			'idComponent' : filters.find('#components').val(),
			'idLabel' : filters.find('#labels').val(),
			'status' : filters.find('#statusT').val(),
			'search' : filters.find('#search').val(),
			'year' : filters.find('#yearT').val(),
			'geo' : filters.find('#geoT').val(),
			'idTarget' : filters.find('#targetMM').val(),
			'hideReleased' : filters.find('#hide_released').prop("checked")
		}
		return $.param(data);
	},

	showMessage: function(data) {

		var section = this.options.section;

		if (data['img'] != false) {
			section.find('.cover-box .img-wait').html(data['img']);
		}
		section.find('.cover-box h1').html(data['title']);
		section.find('.cover-box .cover-message').html(data['message']);
		section.find('.cover-box .cover-extra').html(data['extras']);
		section.find('.cover').css('visibility', data['visibility']);
		section.find('.cover-box').css('visibility', data['visibility']);


	},

	analizeStatus: function(json) {
		var self = this;

		if (json.status=='ready') {
			self.downloadFile(json);

		} else if (json.status == 'error') {
			self.showError();
			self.track('0');
			self.changeStatusDownload(false);

		} else {
			if (this.options.continueDownload == true) {
				self.askReady(json);

			} else {
				self.changeStatusDownload(true);
			}
		}


	},

	askReady: function(json) {
		var self = this;
		var type = 'askReady';

		setTimeout(function() {
			$.ajax({
				url: insights_base_path+'/product/roadmap/askReady/xls',
				data: json,
				dataType: "json",
				cache: false,
				type: 'POST',
				success: function(json) {
					self.analizeStatus(json);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					self.changeStatusDownload(false);
					self.showError();
					self.track('0');
				}
			})},
			5000
		);


	},

	showError:  function() {
		this.showMessage({
			'title': "Opps, wasn't expecting that....",
			'message': '<div id="messagePopUp">An error has occurred. Please try again in a few in minutes and if the problem still persists, contact <a href="mailto:product_operation@mediamath.com">product_operation@mediamath.com</a></div>',
			'extras': '<span id="okMessage">OK</span>',
			'img': false,
			'visibility': 'visible'
		});


	},

	clearMesssage: function(data) {
		this.showMessage({
			'title': '',
			'message': '',
			'extras': '',
			'img': '',
			'visibility': 'hidden'
		});


	},

	showMessage: function(data) {
		var section = $('#roadmap');

		if (data['img'] != false) {
			section.find('.cover-box .img-wait').html(data['img']);
		}

		section.find('.cover-box h1').html(data['title']);
		section.find('.cover-box .cover-message').html(data['message']);
		section.find('.cover-box .cover-extra').html(data['extras']);
		section.find('.cover').css('visibility', data['visibility']);
		section.find('.cover-box').css('visibility', data['visibility']);


	},

	downloadFile: function(data) {
		var self = this;
		var section = $('.export-form form');

		self.changeStatusDownload(false);

		section.empty();
		section.attr('action', insights_base_path+'/product/roadmap/downloadFile/xls');

		$.each(data, function(key, value) {
			section.prepend('<input type="text" name="'+key+'" value="'+value+'" />');
		});

		section.submit();
		self.track('1');
		self.clearMesssage();


	},

	this_tab: function() {
		if ($('#roadmap').find('#lroadmap').hasClass('activate')) {
			return 'roadmap';
		} else {
			return 'candidate';
		}


	},

	track: function(success) {
		var data = {};
		data.data = 'export_'+this.options.filteredDownload;
		data.success = success;
		var self = this;

		$.ajax({
			type: 'POST',
			url:insights_base_path+'/analytics/click/'+this.this_tab()+'/send-track-props',
			data: data,
			cache: false,
			type: 'POST',
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
	productDownload.init();
	$('#options').show();
});
